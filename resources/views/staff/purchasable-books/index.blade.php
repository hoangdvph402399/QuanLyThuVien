@extends('layouts.staff')

@section('title', 'Quản lý sách sản phẩm - Nhân viên')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <h4 class="page-title">Quản lý sách sản phẩm</h4>
                <a href="{{ route('staff.purchasable-books.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm sách sản phẩm mới
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
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Danh sách sách sản phẩm</h5>
                </div>
                <div class="card-body">
                    @if($books->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ảnh</th>
                                        <th>Tên sách</th>
                                        <th>Tác giả</th>
                                        <th>Nhà xuất bản</th>
                                        <th>Giá</th>
                                        <th>Định dạng</th>
                                        <th>Số lượng tồn</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($books as $book)
                                    <tr>
                                        <td>{{ $book->id }}</td>
                                        <td>
                                            @if($book->hinh_anh)
                                                <img src="{{ asset('storage/' . $book->hinh_anh) }}" 
                                                     width="50" height="70" 
                                                     style="object-fit: cover; border-radius: 4px;"
                                                     alt="{{ $book->ten_sach }}">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 70px; border-radius: 4px;">
                                                    <i class="fas fa-book text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $book->ten_sach }}</strong>
                                        </td>
                                        <td>{{ $book->tac_gia }}</td>
                                        <td>{{ $book->nha_xuat_ban ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ number_format($book->gia, 0, ',', '.') }}₫</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $book->dinh_dang }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $book->so_luong_ton ?? 0 }}</span>
                                        </td>
                                        <td>
                                            @if($book->trang_thai === 'active')
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Tạm dừng</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('staff.purchasable-books.show', $book->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('staff.purchasable-books.edit', $book->id) }}" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $books->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có sách sản phẩm nào</h5>
                            <p class="text-muted">Hãy thêm sách sản phẩm đầu tiên.</p>
                            <a href="{{ route('staff.purchasable-books.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Thêm sách sản phẩm đầu tiên
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

