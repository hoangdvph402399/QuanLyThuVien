@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="container-fluid">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-4" style="font-size: 24px; font-weight: 600; color: #333;">Danh Sách Đơn Hàng</h2>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex gap-3 align-items-end">
                <div class="flex-grow-1">
                    <label for="search" class="form-label mb-2" style="font-weight: 500; color: #555;">Tìm kiếm đơn hàng</label>
                    <input type="text" 
                           id="search"
                           name="search" 
                           value="{{ request('search') }}" 
                           class="form-control" 
                           placeholder="Nhập mã đơn hàng, tên người nhận...">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" style="background-color: #0066cc; border-color: #0066cc; padding: 10px 24px; font-weight: 500;">
                        Tìm kiếm
                    </button>
                </div>
                @if(request()->hasAny(['search', 'status', 'payment_status', 'date_from', 'date_to']))
                <div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="card-body" style="padding: 0;">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="margin-bottom: 0;">
                                <thead style="background-color: #f8f9fa;">
                                    <tr>
                                        <th style="padding: 15px; font-weight: 600; color: #333; border-bottom: 2px solid #dee2e6;">STT</th>
                                        <th style="padding: 15px; font-weight: 600; color: #333; border-bottom: 2px solid #dee2e6;">Mã đơn hàng</th>
                                        <th style="padding: 15px; font-weight: 600; color: #333; border-bottom: 2px solid #dee2e6;">Tên người nhận</th>
                                        <th style="padding: 15px; font-weight: 600; color: #333; border-bottom: 2px solid #dee2e6;">Ngày đặt</th>
                                        <th style="padding: 15px; font-weight: 600; color: #333; border-bottom: 2px solid #dee2e6;">Phương thức thanh toán</th>
                                        <th style="padding: 15px; font-weight: 600; color: #333; border-bottom: 2px solid #dee2e6;">Trạng Thái đơn hàng</th>
                                        <th style="padding: 15px; font-weight: 600; color: #333; border-bottom: 2px solid #dee2e6;">Hành Động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $index => $order)
                                        <tr style="background-color: {{ $index % 2 == 0 ? '#ffffff' : '#f8f9fa' }}; border-bottom: 1px solid #dee2e6;">
                                            <td style="padding: 15px; color: #555;">{{ $orders->firstItem() + $index }}</td>
                                            <td style="padding: 15px; color: #333; font-weight: 500;">{{ $order->order_number }}</td>
                                            <td style="padding: 15px; color: #555;">{{ $order->customer_name }}</td>
                                            <td style="padding: 15px; color: #555;">{{ $order->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td style="padding: 15px; color: #555;">
                                                @if($order->payment_method == 'cash_on_delivery')
                                                    Thanh toán khi nhận hàng
                                                @elseif($order->payment_method == 'bank_transfer')
                                                    Chuyển khoản ngân hàng
                                                @else
                                                    {{ $order->payment_method }}
                                                @endif
                                            </td>
                                            <td style="padding: 15px;">
                                                @if($order->status === 'pending')
                                                    <span class="badge" style="background-color: #ffc107; color: #000; padding: 6px 12px; border-radius: 4px; font-weight: 500; font-size: 13px;">Chờ xác nhận</span>
                                                @elseif($order->status === 'processing')
                                                    <span class="badge" style="background-color: #0d6efd; color: #fff; padding: 6px 12px; border-radius: 4px; font-weight: 500; font-size: 13px;">Đã xác nhận</span>
                                                @elseif($order->status === 'completed')
                                                    <span class="badge" style="background-color: #198754; color: #fff; padding: 6px 12px; border-radius: 4px; font-weight: 500; font-size: 13px;">Giao hàng thành công</span>
                                                @elseif($order->status === 'cancelled')
                                                    <span class="badge" style="background-color: #6c757d; color: #fff; padding: 6px 12px; border-radius: 4px; font-weight: 500; font-size: 13px;">Đã hủy</span>
                                                @endif
                                            </td>
                                            <td style="padding: 15px;">
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                                       class="btn btn-sm" 
                                                       style="background-color: #17a2b8; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; text-decoration: none;"
                                                       title="Xem chi tiết">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                                       class="btn btn-sm" 
                                                       style="background-color: #17a2b8; color: #fff; border: none; padding: 6px 12px; border-radius: 4px; text-decoration: none;"
                                                       title="Chỉnh sửa">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="p-3" style="border-top: 1px solid #dee2e6;">
                            {{ $orders->appends(request()->query())->links('vendor.pagination.admin') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có đơn hàng nào</h5>
                            <p class="text-muted">Tất cả đơn hàng sẽ hiển thị ở đây.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table tbody tr:hover {
        background-color: #e9ecef !important;
    }
    
    .btn-sm:hover {
        opacity: 0.8;
        transform: translateY(-1px);
        transition: all 0.2s;
    }
</style>
@endsection
