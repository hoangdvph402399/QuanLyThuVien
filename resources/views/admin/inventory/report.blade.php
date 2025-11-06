@extends('layouts.admin')

@section('title', 'Báo Cáo Tổng Hợp Kho - Admin')

@section('content')
<div class="admin-table">
    <h3><i class="fas fa-chart-bar"></i> Báo Cáo Tổng Hợp Kho</h3>

    <!-- Thống kê tổng quan -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Tổng sách trong kho</h5>
                    <h2>{{ $stats['total_books_in_stock'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Tổng sách trưng bày</h5>
                    <h2>{{ $stats['total_books_on_display'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Có sẵn trong kho</h5>
                    <h2>{{ $stats['available_in_stock'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Có sẵn trưng bày</h5>
                    <h2>{{ $stats['available_on_display'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <h5 class="card-title">Đang mượn từ kho</h5>
                    <h2>{{ $stats['borrowed_from_stock'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h5 class="card-title">Đang mượn từ trưng bày</h5>
                    <h2>{{ $stats['borrowed_from_display'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Tổng phiếu nhập</h5>
                    <h2>{{ $stats['total_receipts'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Phiếu chờ phê duyệt</h5>
                    <h2>{{ $stats['pending_receipts'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-store"></i> Phân bổ trưng bày đang hoạt động</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-center">{{ $stats['active_displays'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Phiếu nhập gần đây -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-file-invoice"></i> Phiếu Nhập Gần Đây</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Số phiếu</th>
                                    <th>Ngày nhập</th>
                                    <th>Sách</th>
                                    <th>Số lượng</th>
                                    <th>Loại</th>
                                    <th>Người nhập</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_receipts'] as $receipt)
                                    <tr>
                                        <td><strong>{{ $receipt->receipt_number }}</strong></td>
                                        <td>{{ $receipt->receipt_date->format('d/m/Y') }}</td>
                                        <td>{{ $receipt->book->ten_sach }}</td>
                                        <td>{{ $receipt->quantity }}</td>
                                        <td>
                                            @if($receipt->storage_type == 'Kho')
                                                <span class="badge badge-info">Kho</span>
                                            @else
                                                <span class="badge badge-warning">Trưng bày</span>
                                            @endif
                                        </td>
                                        <td>{{ $receipt->receiver->name }}</td>
                                        <td>
                                            @if($receipt->status == 'pending')
                                                <span class="badge badge-warning">Chờ phê duyệt</span>
                                            @elseif($receipt->status == 'approved')
                                                <span class="badge badge-success">Đã phê duyệt</span>
                                            @else
                                                <span class="badge badge-danger">Từ chối</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Chưa có phiếu nhập nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phân bổ trưng bày gần đây -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-store"></i> Phân Bổ Trưng Bày Gần Đây</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Sách</th>
                                    <th>Khu vực trưng bày</th>
                                    <th>Số lượng trưng bày</th>
                                    <th>Số lượng trong kho</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Người phân bổ</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_displays'] as $display)
                                    <tr>
                                        <td>{{ $display->book->ten_sach }}</td>
                                        <td>{{ $display->display_area }}</td>
                                        <td><strong>{{ $display->quantity_on_display }}</strong></td>
                                        <td>{{ $display->quantity_in_stock }}</td>
                                        <td>{{ $display->display_start_date ? $display->display_start_date->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $display->allocator->name }}</td>
                                        <td>
                                            @if($display->isActive())
                                                <span class="badge badge-success">Đang hoạt động</span>
                                            @else
                                                <span class="badge badge-secondary">Đã kết thúc</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Chưa có phân bổ trưng bày nào</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

