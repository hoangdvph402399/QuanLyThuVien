<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\InventoryReceipt;
use App\Models\DisplayAllocation;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inventory::with(['book', 'creator']);

        // Lọc theo sách
        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo tình trạng
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Lọc theo vị trí
        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        // Tìm kiếm theo mã vạch
        if ($request->filled('barcode')) {
            $query->where('barcode', 'like', "%{$request->barcode}%");
        }

        // Tìm kiếm theo tên sách
        if ($request->filled('book_title')) {
            $query->whereHas('book', function($bookQuery) use ($request) {
                $bookQuery->where('ten_sach', 'like', "%{$request->book_title}%");
            });
        }

        $inventories = $query->orderBy('created_at', 'desc')->paginate(20);
        $books = Book::all();

        return view('admin.inventory.index', compact('inventories', 'books'));
    }

    public function create()
    {
        $books = Book::all();
        return view('admin.inventory.create', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'barcode' => 'nullable|string|max:100|unique:inventories',
            'location' => 'required|string|max:100',
            'condition' => 'required|in:Moi,Tot,Trung binh,Cu,Hong',
            'status' => 'required|in:Co san,Dang muon,Mat,Hong,Thanh ly',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        // Tạo mã vạch tự động nếu không có
        $barcode = $request->barcode;
        if (!$barcode) {
            $barcode = 'BK' . str_pad(Inventory::count() + 1, 6, '0', STR_PAD_LEFT);
        }

        $inventory = Inventory::create([
            'book_id' => $request->book_id,
            'barcode' => $barcode,
            'location' => $request->location,
            'condition' => $request->condition,
            'status' => $request->status,
            'purchase_price' => $request->purchase_price,
            'purchase_date' => $request->purchase_date,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]);

        // Tạo transaction nhập kho
        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'type' => 'Nhap kho',
            'quantity' => 1,
            'to_location' => $request->location,
            'condition_after' => $request->condition,
            'status_after' => $request->status,
            'reason' => 'Nhập kho mới',
            'notes' => $request->notes,
            'performed_by' => Auth::id(),
        ]);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Sách đã được thêm vào kho thành công!');
    }

    public function show($id)
    {
        $inventory = Inventory::with(['book', 'creator', 'transactions.performer'])
            ->findOrFail($id);

        return view('admin.inventory.show', compact('inventory'));
    }

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $books = Book::all();

        return view('admin.inventory.edit', compact('inventory', 'books'));
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $request->validate([
            'location' => 'required|string|max:100',
            'condition' => 'required|in:Moi,Tot,Trung binh,Cu,Hong',
            'status' => 'required|in:Co san,Dang muon,Mat,Hong,Thanh ly',
            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldLocation = $inventory->location;
        $oldCondition = $inventory->condition;
        $oldStatus = $inventory->status;

        $inventory->update([
            'location' => $request->location,
            'condition' => $request->condition,
            'status' => $request->status,
            'purchase_price' => $request->purchase_price,
            'purchase_date' => $request->purchase_date,
            'notes' => $request->notes,
        ]);

        // Tạo transaction nếu có thay đổi
        if ($oldLocation !== $request->location || 
            $oldCondition !== $request->condition || 
            $oldStatus !== $request->status) {
            
            InventoryTransaction::create([
                'inventory_id' => $inventory->id,
                'type' => 'Kiem ke',
                'quantity' => 1,
                'from_location' => $oldLocation,
                'to_location' => $request->location,
                'condition_before' => $oldCondition,
                'condition_after' => $request->condition,
                'status_before' => $oldStatus,
                'status_after' => $request->status,
                'reason' => 'Cập nhật thông tin',
                'notes' => $request->notes,
                'performed_by' => Auth::id(),
            ]);
        }

        return redirect()->route('admin.inventory.show', $inventory->id)
            ->with('success', 'Thông tin sách đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $inventory = Inventory::findOrFail($id);

        // Tạo transaction thanh lý
        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'type' => 'Thanh ly',
            'quantity' => 1,
            'from_location' => $inventory->location,
            'condition_before' => $inventory->condition,
            'status_before' => $inventory->status,
            'status_after' => 'Thanh ly',
            'reason' => 'Xóa khỏi hệ thống',
            'performed_by' => Auth::id(),
        ]);

        $inventory->delete();

        return back()->with('success', 'Sách đã được xóa khỏi kho thành công!');
    }

    public function transfer(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $request->validate([
            'to_location' => 'required|string|max:100',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldLocation = $inventory->location;

        $inventory->update([
            'location' => $request->to_location,
        ]);

        // Tạo transaction chuyển kho
        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'type' => 'Chuyen kho',
            'quantity' => 1,
            'from_location' => $oldLocation,
            'to_location' => $request->to_location,
            'condition_before' => $inventory->condition,
            'condition_after' => $inventory->condition,
            'status_before' => $inventory->status,
            'status_after' => $inventory->status,
            'reason' => $request->reason,
            'notes' => $request->notes,
            'performed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Sách đã được chuyển kho thành công!');
    }

    public function repair(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);

        $request->validate([
            'condition_after' => 'required|in:Moi,Tot,Trung binh,Cu,Hong',
            'reason' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldCondition = $inventory->condition;

        $inventory->update([
            'condition' => $request->condition_after,
        ]);

        // Tạo transaction sửa chữa
        InventoryTransaction::create([
            'inventory_id' => $inventory->id,
            'type' => 'Sua chua',
            'quantity' => 1,
            'condition_before' => $oldCondition,
            'condition_after' => $request->condition_after,
            'status_before' => $inventory->status,
            'status_after' => $inventory->status,
            'reason' => $request->reason,
            'notes' => $request->notes,
            'performed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Sách đã được sửa chữa thành công!');
    }

    public function transactions(Request $request)
    {
        $query = InventoryTransaction::with(['inventory.book', 'performer']);

        // Lọc theo loại giao dịch
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Lọc theo khoảng thời gian
        if ($request->filled('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('created_at', '<=', $request->to_date);
        }

        // Lọc theo người thực hiện
        if ($request->filled('performer_id')) {
            $query->where('performed_by', $request->performer_id);
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);
        $users = \App\Models\User::all();

        return view('admin.inventory.transactions', compact('transactions', 'users'));
    }

    public function dashboard()
    {
        $stats = [
            'total_books' => Inventory::count(),
            'available_books' => Inventory::where('status', 'Co san')->count(),
            'borrowed_books' => Inventory::where('status', 'Dang muon')->count(),
            'damaged_books' => Inventory::where('condition', 'Hong')->count(),
            'lost_books' => Inventory::where('status', 'Mat')->count(),
            'recent_transactions' => InventoryTransaction::with(['inventory.book', 'performer'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'transactions_by_type' => InventoryTransaction::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
        ];

        return view('admin.inventory.dashboard', compact('stats'));
    }

    public function scanBarcode(Request $request)
    {
        $barcode = $request->get('barcode');
        
        if (!$barcode) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mã vạch không được để trống'
            ], 400);
        }

        $inventory = Inventory::with(['book', 'creator'])
            ->where('barcode', $barcode)
            ->first();

        if (!$inventory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy sách với mã vạch này'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $inventory
        ]);
    }

    /**
     * Hiển thị form nhập kho
     */
    public function createReceipt()
    {
        $books = Book::all();
        $receiptNumber = InventoryReceipt::generateReceiptNumber();
        return view('admin.inventory.create-receipt', compact('books', 'receiptNumber'));
    }

    /**
     * Lưu phiếu nhập kho
     */
    public function storeReceipt(Request $request)
    {
        $request->validate([
            'receipt_date' => 'required|date',
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
            'storage_location' => 'required|string|max:100',
            'storage_type' => 'required|in:Kho,Trung bay',
            'unit_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Tính tổng giá
            $unitPrice = $request->unit_price ?? 0;
            $totalPrice = $unitPrice * $request->quantity;

            // Tạo số phiếu
            $receiptNumber = InventoryReceipt::generateReceiptNumber();

            // Tạo phiếu nhập
            $receipt = InventoryReceipt::create([
                'receipt_number' => $receiptNumber,
                'receipt_date' => $request->receipt_date,
                'book_id' => $request->book_id,
                'quantity' => $request->quantity,
                'storage_location' => $request->storage_location,
                'storage_type' => $request->storage_type,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'supplier' => $request->supplier,
                'received_by' => Auth::id(),
                'status' => 'pending', // Có thể cần phê duyệt
                'notes' => $request->notes,
            ]);

            // Tạo các bản copy sách trong kho
            for ($i = 0; $i < $request->quantity; $i++) {
                $barcode = 'BK' . str_pad(Inventory::count() + $i + 1, 6, '0', STR_PAD_LEFT);
                
                $inventory = Inventory::create([
                    'book_id' => $request->book_id,
                    'barcode' => $barcode,
                    'location' => $request->storage_location,
                    'condition' => 'Moi',
                    'status' => 'Co san',
                    'purchase_price' => $unitPrice,
                    'purchase_date' => $request->receipt_date,
                    'storage_type' => $request->storage_type,
                    'receipt_id' => $receipt->id,
                    'created_by' => Auth::id(),
                ]);

                // Tạo transaction nhập kho
                InventoryTransaction::create([
                    'inventory_id' => $inventory->id,
                    'type' => 'Nhap kho',
                    'quantity' => 1,
                    'to_location' => $request->storage_location,
                    'condition_after' => 'Moi',
                    'status_after' => 'Co san',
                    'reason' => 'Nhập kho theo phiếu: ' . $receipt->receipt_number,
                    'notes' => $request->notes,
                    'performed_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.inventory.receipts')
                ->with('success', 'Phiếu nhập kho đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Danh sách phiếu nhập kho
     */
    public function receipts(Request $request)
    {
        $query = InventoryReceipt::with(['book', 'receiver', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->where('receipt_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->where('receipt_date', '<=', $request->to_date);
        }

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        $receipts = $query->orderBy('created_at', 'desc')->paginate(20);
        $books = Book::all();

        return view('admin.inventory.receipts', compact('receipts', 'books'));
    }

    /**
     * Chi tiết phiếu nhập kho
     */
    public function showReceipt($id)
    {
        $receipt = InventoryReceipt::with(['book', 'receiver', 'approver', 'inventories'])
            ->findOrFail($id);

        return view('admin.inventory.show-receipt', compact('receipt'));
    }

    /**
     * Phê duyệt phiếu nhập kho
     */
    public function approveReceipt($id)
    {
        $receipt = InventoryReceipt::findOrFail($id);

        if ($receipt->status !== 'pending') {
            return back()->with('error', 'Phiếu nhập này đã được xử lý!');
        }

        $receipt->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Phiếu nhập kho đã được phê duyệt!');
    }

    /**
     * Từ chối phiếu nhập kho
     */
    public function rejectReceipt(Request $request, $id)
    {
        $receipt = InventoryReceipt::findOrFail($id);

        if ($receipt->status !== 'pending') {
            return back()->with('error', 'Phiếu nhập này đã được xử lý!');
        }

        $receipt->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'notes' => ($receipt->notes ?? '') . "\nLý do từ chối: " . ($request->reason ?? ''),
        ]);

        return back()->with('success', 'Phiếu nhập kho đã bị từ chối!');
    }

    /**
     * Hiển thị form xuất kho để trưng bày
     */
    public function createDisplayAllocation()
    {
        $books = Book::all();
        return view('admin.inventory.create-display-allocation', compact('books'));
    }

    /**
     * Lưu phân bổ trưng bày
     */
    public function storeDisplayAllocation(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity_on_display' => 'required|integer|min:1',
            'display_area' => 'required|string|max:100',
            'display_start_date' => 'required|date',
            'display_end_date' => 'nullable|date|after:display_start_date',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Kiểm tra số lượng sách có sẵn trong kho
            $availableInStock = Inventory::where('book_id', $request->book_id)
                ->where('storage_type', 'Kho')
                ->where('status', 'Co san')
                ->count();

            if ($availableInStock < $request->quantity_on_display) {
                return back()->withInput()->with('error', 
                    'Số lượng sách trong kho không đủ! Chỉ còn ' . $availableInStock . ' cuốn.');
            }

            // Lấy các sách từ kho để chuyển ra trưng bày
            $inventories = Inventory::where('book_id', $request->book_id)
                ->where('storage_type', 'Kho')
                ->where('status', 'Co san')
                ->limit($request->quantity_on_display)
                ->get();

            // Cập nhật số lượng còn lại trong kho
            $remainingInStock = $availableInStock - $request->quantity_on_display;

            // Tạo phân bổ trưng bày
            $allocation = DisplayAllocation::create([
                'book_id' => $request->book_id,
                'quantity_on_display' => $request->quantity_on_display,
                'quantity_in_stock' => $remainingInStock,
                'display_area' => $request->display_area,
                'display_start_date' => $request->display_start_date,
                'display_end_date' => $request->display_end_date,
                'allocated_by' => Auth::id(),
                'notes' => $request->notes,
            ]);

            // Chuyển các sách từ kho sang trưng bày
            foreach ($inventories as $inventory) {
                $oldLocation = $inventory->location;
                
                $inventory->update([
                    'storage_type' => 'Trung bay',
                    'location' => $request->display_area,
                ]);

                // Tạo transaction xuất kho
                InventoryTransaction::create([
                    'inventory_id' => $inventory->id,
                    'type' => 'Xuat kho',
                    'quantity' => 1,
                    'from_location' => $oldLocation,
                    'to_location' => $request->display_area,
                    'condition_before' => $inventory->condition,
                    'condition_after' => $inventory->condition,
                    'status_before' => $inventory->status,
                    'status_after' => $inventory->status,
                    'reason' => 'Xuất kho để trưng bày',
                    'notes' => $request->notes,
                    'performed_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.inventory.display-allocations')
                ->with('success', 'Phân bổ trưng bày đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Danh sách phân bổ trưng bày
     */
    public function displayAllocations(Request $request)
    {
        $query = DisplayAllocation::with(['book', 'allocator']);

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        if ($request->filled('display_area')) {
            $query->where('display_area', 'like', "%{$request->display_area}%");
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } else {
                $query->where('display_end_date', '<', now()->toDateString());
            }
        }

        $allocations = $query->orderBy('created_at', 'desc')->paginate(20);
        $books = Book::all();

        return view('admin.inventory.display-allocations', compact('allocations', 'books'));
    }

    /**
     * Thu hồi sách từ trưng bày về kho
     */
    public function returnFromDisplay(Request $request, $id)
    {
        $allocation = DisplayAllocation::findOrFail($id);

        $request->validate([
            'return_location' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Lấy các sách đang trưng bày
            $inventories = Inventory::where('book_id', $allocation->book_id)
                ->where('storage_type', 'Trung bay')
                ->where('location', $allocation->display_area)
                ->where('status', 'Co san')
                ->limit($allocation->quantity_on_display)
                ->get();

            // Chuyển các sách về kho
            foreach ($inventories as $inventory) {
                $oldLocation = $inventory->location;

                $inventory->update([
                    'storage_type' => 'Kho',
                    'location' => $request->return_location,
                ]);

                // Tạo transaction nhập lại kho
                InventoryTransaction::create([
                    'inventory_id' => $inventory->id,
                    'type' => 'Nhap kho',
                    'quantity' => 1,
                    'from_location' => $oldLocation,
                    'to_location' => $request->return_location,
                    'condition_before' => $inventory->condition,
                    'condition_after' => $inventory->condition,
                    'status_before' => $inventory->status,
                    'status_after' => $inventory->status,
                    'reason' => 'Thu hồi từ trưng bày về kho',
                    'notes' => $request->notes,
                    'performed_by' => Auth::id(),
                ]);
            }

            // Cập nhật phân bổ
            $allocation->update([
                'quantity_on_display' => 0,
                'quantity_in_stock' => Inventory::where('book_id', $allocation->book_id)
                    ->where('storage_type', 'Kho')
                    ->where('status', 'Co san')
                    ->count(),
                'display_end_date' => now()->toDateString(),
            ]);

            DB::commit();

            return back()->with('success', 'Sách đã được thu hồi về kho thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Báo cáo tổng hợp kho
     */
    public function report()
    {
        $stats = [
            'total_books_in_stock' => Inventory::inStock()->count(),
            'total_books_on_display' => Inventory::onDisplay()->count(),
            'available_in_stock' => Inventory::inStock()->where('status', 'Co san')->count(),
            'available_on_display' => Inventory::onDisplay()->where('status', 'Co san')->count(),
            'borrowed_from_stock' => Inventory::inStock()->where('status', 'Dang muon')->count(),
            'borrowed_from_display' => Inventory::onDisplay()->where('status', 'Dang muon')->count(),
            'total_receipts' => InventoryReceipt::count(),
            'pending_receipts' => InventoryReceipt::pending()->count(),
            'active_displays' => DisplayAllocation::active()->count(),
            'recent_receipts' => InventoryReceipt::with(['book', 'receiver'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'recent_displays' => DisplayAllocation::with(['book', 'allocator'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
        ];

        return view('admin.inventory.report', compact('stats'));
    }

    /**
     * Đồng bộ hóa tất cả sản phẩm từ kho lên trang chủ
     * Đảm bảo tất cả sách trong kho đều có trang_thai = 'active' để hiển thị trên trang chủ
     */
    public function syncToHomepage(Request $request)
    {
        try {
            DB::beginTransaction();

            // Lấy tất cả các book_id từ inventories (cả kho và trưng bày)
            $bookIds = Inventory::select('book_id')
                ->distinct()
                ->pluck('book_id')
                ->toArray();

            // Đếm số lượng sách cần đồng bộ
            $totalBooks = count($bookIds);
            $updatedCount = 0;
            $alreadyActiveCount = 0;

            // Cập nhật tất cả sách trong kho để có trang_thai = 'active'
            foreach ($bookIds as $bookId) {
                $book = Book::find($bookId);
                if ($book) {
                    // Chỉ cập nhật nếu sách chưa active
                    if ($book->trang_thai !== 'active') {
                        $book->update(['trang_thai' => 'active']);
                        $updatedCount++;
                    } else {
                        $alreadyActiveCount++;
                    }
                }
            }

            DB::commit();

            $message = "Đồng bộ hóa thành công! ";
            $message .= "Tổng số sách trong kho: {$totalBooks}. ";
            $message .= "Đã cập nhật: {$updatedCount} sách. ";
            $message .= "Đã active sẵn: {$alreadyActiveCount} sách.";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'total_books' => $totalBooks,
                        'updated' => $updatedCount,
                        'already_active' => $alreadyActiveCount,
                    ]
                ]);
            }

            return redirect()->route('admin.inventory.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'Có lỗi xảy ra khi đồng bộ hóa: ' . $e->getMessage();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }

            return back()->with('error', $errorMessage);
        }
    }
}