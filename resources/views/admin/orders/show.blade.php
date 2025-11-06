@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Quản lý đơn hàng</a></li>
            <li class="breadcrumb-item active">Chi tiết đơn hàng</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-receipt"></i> Chi tiết đơn hàng #{{ $order->order_number }}</h2>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

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

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shopping-bag"></i> Sản phẩm trong đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên sách</th>
                                    <th>Tác giả</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->book_title }}</strong>
                                        </td>
                                        <td>{{ $item->book_author }}</td>
                                        <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>
                                            <strong class="text-success">{{ number_format($item->total_price, 0, ',', '.') }} VNĐ</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Tạm tính:</strong></td>
                                    <td><strong>{{ number_format($order->subtotal, 0, ',', '.') }} VNĐ</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Phí vận chuyển:</strong></td>
                                    <td><strong class="text-success">{{ number_format($order->shipping_amount, 0, ',', '.') }} VNĐ</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Thuế:</strong></td>
                                    <td><strong class="text-success">{{ number_format($order->tax_amount, 0, ',', '.') }} VNĐ</strong></td>
                                </tr>
                                <tr class="table-primary">
                                    <td colspan="5" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td><strong class="text-primary fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Họ và tên</label>
                            <p class="mb-0"><strong>{{ $order->customer_name }}</strong></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="mb-0">{{ $order->customer_email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Số điện thoại</label>
                            <p class="mb-0">{{ $order->customer_phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Địa chỉ giao hàng</label>
                            <p class="mb-0">{{ $order->customer_address ?? 'N/A' }}</p>
                        </div>
                        @if($order->user)
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Tài khoản</label>
                            <p class="mb-0">
                                <a href="{{ route('admin.users.show', $order->user->id) }}" target="_blank">
                                    {{ $order->user->name }} ({{ $order->user->email }})
                                </a>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($order->notes)
            <!-- Notes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> Ghi chú</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Order Status & Actions -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog"></i> Quản lý đơn hàng</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Trạng thái đơn hàng</label>
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái thanh toán</label>
                            <select name="payment_status" class="form-select">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Mã đơn hàng</label>
                        <p class="mb-0"><strong>#{{ $order->order_number }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Ngày đặt hàng</label>
                        <p class="mb-0">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Phương thức thanh toán</label>
                        <p class="mb-0">
                            @if($order->payment_method == 'cash_on_delivery')
                                <span class="badge bg-info">Thanh toán khi nhận hàng</span>
                            @elseif($order->payment_method == 'bank_transfer')
                                <span class="badge bg-primary">Chuyển khoản ngân hàng</span>
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Trạng thái hiện tại</label>
                        <p class="mb-0">
                            @if($order->status === 'pending')
                                <span class="badge bg-warning">Chờ xử lý</span>
                            @elseif($order->status === 'processing')
                                <span class="badge bg-info">Đang xử lý</span>
                            @elseif($order->status === 'completed')
                                <span class="badge bg-success">Hoàn thành</span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge bg-danger">Đã hủy</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-muted">Thanh toán</label>
                        <p class="mb-0">
                            @if($order->payment_status === 'pending')
                                <span class="badge bg-secondary">Chưa thanh toán</span>
                            @elseif($order->payment_status === 'paid')
                                <span class="badge bg-success">Đã thanh toán</span>
                            @elseif($order->payment_status === 'refunded')
                                <span class="badge bg-warning">Đã hoàn tiền</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

