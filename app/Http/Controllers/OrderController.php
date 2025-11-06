<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\PurchasableBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Hiển thị trang checkout
     */
    public function checkout()
    {
        $cart = $this->getCurrentCart();
        $cartItems = $cart->items()->with('purchasableBook')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        return view('orders.checkout', compact('cart', 'cartItems'));
    }

    /**
     * Xử lý đặt hàng
     */
    public function store(Request $request)
    {
        // Log để debug
        \Log::info('OrderController@store called', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
        ]);
        
        // Đảm bảo chỉ xử lý POST request
        // Nếu là GET request, redirect về trang index
        if (!$request->isMethod('POST')) {
            \Log::warning('OrderController@store called with wrong method - redirecting to index', [
                'method' => $request->method(),
                'expected' => 'POST',
                'url' => $request->fullUrl()
            ]);
            
            // Nếu là GET request, redirect về trang orders.index
            if ($request->isMethod('GET')) {
                return redirect()->route('orders.index');
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Method not allowed. Only POST requests are accepted.'
            ], 405);
        }
        
        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'customer_address' => 'nullable|string|max:500',
                'payment_method' => 'required|in:cash_on_delivery,bank_transfer',
                'notes' => 'nullable|string|max:1000',
            ], [
                'customer_name.required' => 'Vui lòng nhập họ và tên',
                'customer_email.required' => 'Vui lòng nhập email',
                'customer_email.email' => 'Email không hợp lệ',
                'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
                'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        }

        $cart = $this->getCurrentCart();
        $cartItems = $cart->items()->with('purchasableBook')->get();
        
        if ($cartItems->isEmpty()) {
            // Log để debug
            \Log::warning('OrderController@store: Cart is empty', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'user_id' => Auth::id(),
                'session_id' => Session::getId(),
            ]);
            
            // Nếu là AJAX request, trả JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Giỏ hàng của bạn đang trống',
                    'redirect_url' => route('cart.index')
                ], 400);
            }
            
            // Nếu không phải AJAX, redirect về trang giỏ hàng
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống. Vui lòng thêm sản phẩm vào giỏ hàng trước khi đặt hàng.');
        }

        // Kiểm tra số lượng tồn kho trước khi đặt hàng
        foreach ($cartItems as $item) {
            $book = $item->purchasableBook;
            if (!$book->isInStock() || $book->so_luong_ton < $item->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Sách '{$book->ten_sach}' không đủ hàng trong kho"
                ], 400);
            }
        }

        DB::beginTransaction();
        
        try {
            // Xác định payment_status dựa trên payment_method
            $paymentStatus = 'pending';
            if ($request->payment_method === 'cash_on_delivery') {
                // Với COD, payment_status sẽ là 'pending' cho đến khi giao hàng
                $paymentStatus = 'pending';
            } elseif ($request->payment_method === 'bank_transfer') {
                // Với chuyển khoản, payment_status cũng là 'pending' cho đến khi xác nhận
                $paymentStatus = 'pending';
            }
            
            // Tạo đơn hàng
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'session_id' => Session::getId(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'subtotal' => $cart->total_amount,
                'tax_amount' => 0, // Miễn phí thuế cho sách điện tử
                'shipping_amount' => 0, // Miễn phí vận chuyển cho sách điện tử
                'total_amount' => $cart->total_amount,
                'status' => 'pending',
                'payment_status' => $paymentStatus,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // Tạo order items và cập nhật số lượng tồn kho
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'purchasable_book_id' => $item->purchasable_book_id,
                    'book_title' => $item->purchasableBook->ten_sach,
                    'book_author' => $item->purchasableBook->tac_gia,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'total_price' => $item->total_price,
                ]);

                // Giảm số lượng tồn kho và tăng số lượng bán
                $item->purchasableBook->decreaseStock($item->quantity);
                $item->purchasableBook->incrementSales();
            }

            // Xóa giỏ hàng
            $cart->items()->delete();
            $cart->update(['total_amount' => 0, 'total_items' => 0]);

            DB::commit();
            
            // Log thành công
            \Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => $order->user_id
            ]);

            // Nếu là AJAX request, trả JSON với redirect_url
            // Nếu không phải AJAX, redirect trực tiếp
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đặt hàng thành công! Mã đơn hàng: ' . $order->order_number,
                    'order_number' => $order->order_number,
                    'redirect_url' => route('orders.index')
                ], 200)->header('Content-Type', 'application/json')
                  ->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0')
                  ->header('Pragma', 'no-cache')
                  ->header('Expires', '0');
            }
            
            // Redirect trực tiếp cho non-AJAX requests
            return redirect()->route('orders.index')
                ->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Log lỗi chi tiết
            \Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);
        
        // Kiểm tra quyền truy cập
        if (!$this->canAccessOrder($order)) {
            abort(403, 'Bạn không có quyền xem đơn hàng này');
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Hiển thị danh sách đơn hàng của user
     */
    public function index(Request $request)
    {
        // Log để debug
        \Log::info('OrderController@index called', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'is_api' => $request->is('api/*'),
            'expects_json' => $request->expectsJson(),
            'wants_json' => $request->wantsJson(),
            'accept' => $request->header('Accept'),
            'user_agent' => $request->header('User-Agent'),
        ]);
        
        // Đảm bảo chỉ xử lý GET request
        if (!$request->isMethod('GET')) {
            \Log::error('OrderController@index called with wrong method', [
                'method' => $request->method(),
                'expected' => 'GET'
            ]);
            abort(405, 'Method not allowed');
        }
        
        $query = Order::with('items');
        
        if (Auth::check()) {
            $query->forUser(Auth::id());
        } else {
            $query->forSession(Session::getId());
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Chỉ trả JSON nếu request từ API route (api/*)
        // Tất cả request từ web route (/orders) sẽ trả về HTML
        // KHÔNG kiểm tra expectsJson() để tránh browser cache header
        if ($request->is('api/*')) {
            \Log::info('Returning JSON for API request');
            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        }
        
        // Force trả về HTML view cho browser với header rõ ràng
        // Đảm bảo không bao giờ trả về JSON cho web route
        // Bỏ qua tất cả Accept headers và luôn trả về HTML
        \Log::info('Returning HTML view for web request', [
            'orders_count' => $orders->count(),
            'view_path' => 'orders.index'
        ]);
        
        // Force HTML response - không bao giờ trả JSON cho web route
        $response = response()->view('orders.index', compact('orders'));
        
        // Set headers để force HTML
        $response->header('Content-Type', 'text/html; charset=utf-8');
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');
        
        return $response;
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
     * Kiểm tra quyền truy cập đơn hàng
     */
    private function canAccessOrder($order)
    {
        if (Auth::check()) {
            return $order->user_id === Auth::id();
        } else {
            return $order->session_id === Session::getId();
        }
    }
}
