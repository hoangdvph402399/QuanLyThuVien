@extends('layouts.admin')

@section('title', 'Phiếu Nhập Kho - Admin')

@section('content')
<div class="admin-table">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="fas fa-file-invoice"></i> Phiếu Nhập Kho</h3>
        <a href="{{ route('admin.inventory.receipts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tạo Phiếu Nhập Mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Bộ lọc -->
    <form method="GET" action="{{ route('admin.inventory.receipts') }}" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ phê duyệt</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã phê duyệt</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="book_id" class="form-control">
                    <option value="">-- Tất cả sách --</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->ten_sach }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="Từ ngày">
            </div>
            <div class="col-md-2">
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="Đến ngày">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Lọc</button>
            </div>
        </div>
    </form>

    <!-- Bảng danh sách -->
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Số phiếu</th>
                    <th>Ngày nhập</th>
                    <th>Sách</th>
                    <th>Số lượng</th>
                    <th>Vị trí</th>
                    <th>Loại</th>
                    <th>Người nhập</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($receipts as $receipt)
                    <tr>
                        <td><strong>{{ $receipt->receipt_number }}</strong></td>
                        <td>{{ $receipt->receipt_date->format('d/m/Y') }}</td>
                        <td>{{ $receipt->book->ten_sach }}</td>
                        <td>{{ $receipt->quantity }}</td>
                        <td>{{ $receipt->storage_location }}</td>
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
                        <td>
                            <a href="{{ route('admin.inventory.receipts.show', $receipt->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            @if($receipt->status == 'pending' && auth()->user()->isAdmin())
                                <form action="{{ route('admin.inventory.receipts.approve', $receipt->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Duyệt
                                    </button>
                                </form>
                                <button type="button" class="btn btn-sm btn-danger" onclick="rejectReceipt({{ $receipt->id }})">
                                    <i class="fas fa-times"></i> Từ chối
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Không có phiếu nhập nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $receipts->links() }}
</div>

<!-- Modal từ chối phiếu -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Từ chối phiếu nhập</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Lý do từ chối:</label>
                        <textarea name="reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Từ chối</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function rejectReceipt(id) {
    document.getElementById('rejectForm').action = '{{ url("admin/inventory-receipts") }}/' + id + '/reject';
    $('#rejectModal').modal('show');
}
</script>
@endsection

