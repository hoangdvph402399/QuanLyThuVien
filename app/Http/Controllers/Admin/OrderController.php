<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Tìm kiếm theo mã đơn hàng, tên khách hàng, email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo trạng thái thanh toán
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Lọc theo ngày
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Thống kê
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'unpaid' => Order::where('payment_status', 'pending')->count(),
            'paid' => Order::where('payment_status', 'paid')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.purchasableBook'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'nullable|in:pending,processing,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,refunded',
        ]);

        $order = Order::findOrFail($id);

        if ($request->filled('status')) {
            $order->status = $request->status;
        }

        if ($request->filled('payment_status')) {
            $order->payment_status = $request->payment_status;
        }

        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}

