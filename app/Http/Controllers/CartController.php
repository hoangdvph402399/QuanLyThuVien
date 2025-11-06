<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PurchasableBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $cart = $this->getCurrentCart();
        $cartItems = $cart->items()->with('purchasableBook')->get();
        
        return view('cart.index', compact('cart', 'cartItems'));
    }

    /**
     * Thêm sách vào giỏ hàng
     */
    public function add(Request $request)
    {
        $request->validate([
            'book_id' => 'required',
            'paper_quantity' => 'nullable|integer|min:1|max:10',
            'ebook_duration' => 'nullable|integer|min:1|max:12'
        ]);

        $cart = $this->getCurrentCart();
        $paperQuantity = $request->paper_quantity ?? 0;
        $ebookDuration = $request->ebook_duration ?? 0;
        
        // Kiểm tra ít nhất một loại được chọn
        if ($paperQuantity == 0 && $ebookDuration == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng chọn ít nhất một sản phẩm!'
            ], 400);
        }

        try {
            // Xử lý sách giấy
            if ($paperQuantity > 0) {
                $purchasableBook = $this->getOrCreatePurchasableBook($request->book_id, 'paper');
                
                if (!$purchasableBook->isInStock()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sách giấy này đã hết hàng'
                    ], 400);
                }
                
                if ($purchasableBook->so_luong_ton < $paperQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Sách giấy chỉ còn {$purchasableBook->so_luong_ton} bản trong kho"
                    ], 400);
                }
                
                CartItem::addOrUpdate($cart->id, $purchasableBook->id, $paperQuantity);
            }
            
            // Xử lý ebook
            if ($ebookDuration > 0) {
                $purchasableBook = $this->getOrCreatePurchasableBook($request->book_id, 'ebook');
                
                if (!$purchasableBook->isInStock()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sách điện tử này đã hết hàng'
                    ], 400);
                }
                
                // Với ebook, quantity = duration (tháng)
                CartItem::addOrUpdate($cart->id, $purchasableBook->id, $ebookDuration);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sách vào giỏ hàng',
                'cart_count' => $cart->fresh()->total_items
            ]);
        } catch (\Exception $e) {
            Log::error('Cart add error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thêm sách vào giỏ hàng: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Lấy hoặc tạo PurchasableBook từ Book
     */
    private function getOrCreatePurchasableBook($bookId, $type = 'paper')
    {
        // Kiểm tra xem book_id có phải là PurchasableBook không
        $purchasableBook = PurchasableBook::find($bookId);
        if ($purchasableBook) {
            return $purchasableBook;
        }
        
        // Nếu không phải, tìm Book và tạo PurchasableBook tương ứng
        $book = Book::findOrFail($bookId);
        
        // Tạo identifier cho từng loại (paper hoặc ebook)
        $identifier = $type === 'ebook' ? 'ebook_' . $book->id : 'paper_' . $book->id;
        
        // Tìm PurchasableBook đã tồn tại với cùng identifier (dựa trên tên sách và loại)
        $purchasableBook = PurchasableBook::where('ten_sach', $book->ten_sach)
            ->where('dinh_dang', $type === 'ebook' ? 'PDF' : 'PAPER')
            ->first();
        
        if ($purchasableBook) {
            return $purchasableBook;
        }
        
        // Tạo mới PurchasableBook
        $price = $type === 'ebook' ? ($book->gia ?? 111000) * 0.21 : ($book->gia ?? 111000);
        
        // Load publisher nếu có
        $book->load('publisher');
        
        $purchasableBook = PurchasableBook::create([
            'ten_sach' => $book->ten_sach,
            'tac_gia' => $book->tac_gia ?? 'Chưa cập nhật',
            'mo_ta' => $book->mo_ta,
            'hinh_anh' => $book->hinh_anh,
            'gia' => $price,
            'nha_xuat_ban' => $book->publisher ? $book->publisher->ten_nha_xuat_ban : null,
            'nam_xuat_ban' => $book->nam_xuat_ban,
            'isbn' => $book->isbn ?? null,
            'so_trang' => $book->so_trang ?? null,
            'ngon_ngu' => 'Tiếng Việt',
            'dinh_dang' => $type === 'ebook' ? 'PDF' : 'PAPER',
            'kich_thuoc_file' => null,
            'trang_thai' => 'active',
            'so_luong_ton' => $type === 'ebook' ? 999 : ($book->available_copies ?? 1),
            'so_luong_ban' => 0,
            'danh_gia_trung_binh' => 0,
            'so_luot_xem' => 0,
        ]);
        
        return $purchasableBook;
    }

    /**
     * Cập nhật số lượng sách trong giỏ hàng
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $cartItem = CartItem::findOrFail($id);
        
        // Kiểm tra quyền sở hữu
        if (!$this->canAccessCart($cartItem->cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền truy cập'
            ], 403);
        }

        // Kiểm tra số lượng tồn kho
        $book = $cartItem->purchasableBook;
        if (!$book->isInStock()) {
            return response()->json([
                'success' => false,
                'message' => 'Sách này đã hết hàng'
            ], 400);
        }
        
        if ($book->so_luong_ton < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => "Chỉ còn {$book->so_luong_ton} bản trong kho"
            ], 400);
        }

        try {
            $cartItem->updateQuantity($request->quantity);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật số lượng',
                'cart_count' => $cartItem->cart->fresh()->total_items,
                'total_price' => number_format($cartItem->fresh()->total_price, 0, ',', '.') . ' VNĐ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật'
            ], 500);
        }
    }

    /**
     * Xóa sách khỏi giỏ hàng
     */
    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);
        
        // Kiểm tra quyền sở hữu
        if (!$this->canAccessCart($cartItem->cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền truy cập'
            ], 403);
        }

        try {
            $cart = $cartItem->cart;
            $cartItem->delete();
            $cart->recalculateTotals();
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sách khỏi giỏ hàng',
                'cart_count' => $cart->fresh()->total_items
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa'
            ], 500);
        }
    }

    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        $cart = $this->getCurrentCart();
        
        try {
            $cart->items()->delete();
            $cart->update(['total_amount' => 0, 'total_items' => 0]);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa toàn bộ giỏ hàng'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa giỏ hàng'
            ], 500);
        }
    }

    /**
     * Lấy số lượng sách trong giỏ hàng (AJAX)
     */
    public function count()
    {
        $cart = $this->getCurrentCart();
        
        return response()->json([
            'count' => $cart->total_items
        ]);
    }

    /**
     * Lấy giỏ hàng hiện tại
     */
    private function getCurrentCart()
    {
        if (Auth::check()) {
            return Cart::getOrCreateForUser(Auth::id());
        } else {
            $sessionId = Session::getId();
            return Cart::getOrCreateForSession($sessionId);
        }
    }

    /**
     * Kiểm tra quyền truy cập giỏ hàng
     */
    private function canAccessCart($cart)
    {
        if (Auth::check()) {
            return $cart->user_id === Auth::id();
        } else {
            return $cart->session_id === Session::getId();
        }
    }

    /**
     * Chuyển giỏ hàng từ session sang user khi đăng nhập
     */
    public function transferToUser($userId)
    {
        $sessionId = Session::getId();
        $sessionCart = Cart::forSession($sessionId)->active()->first();
        
        if ($sessionCart && !$sessionCart->isEmpty()) {
            $userCart = Cart::getOrCreateForUser($userId);
            
            // Chuyển các item từ session cart sang user cart
            foreach ($sessionCart->items as $item) {
                CartItem::addOrUpdate($userCart->id, $item->purchasable_book_id, $item->quantity);
            }
            
            // Xóa session cart
            $sessionCart->items()->delete();
            $sessionCart->delete();
        }
    }
}