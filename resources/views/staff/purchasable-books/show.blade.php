@extends('layouts.staff')

@section('title', 'Chi tiết sách sản phẩm - Nhân viên')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Chi tiết sách sản phẩm</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ $book->ten_sach }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tác giả:</strong> {{ $book->tac_gia }}</p>
                            <p><strong>Nhà xuất bản:</strong> {{ $book->nha_xuat_ban ?? 'N/A' }}</p>
                            <p><strong>Năm xuất bản:</strong> {{ $book->nam_xuat_ban ?? 'N/A' }}</p>
                            <p><strong>ISBN:</strong> {{ $book->isbn ?? 'N/A' }}</p>
                            <p><strong>Định dạng:</strong> <span class="badge bg-info">{{ $book->dinh_dang }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Giá:</strong> <span class="badge bg-success">{{ number_format($book->gia, 0, ',', '.') }}₫</span></p>
                            <p><strong>Số lượng tồn kho:</strong> <span class="badge bg-primary">{{ $book->so_luong_ton ?? 0 }}</span></p>
                            <p><strong>Số lượng đã bán:</strong> <span class="badge bg-warning">{{ $book->so_luong_ban ?? 0 }}</span></p>
                            <p><strong>Lượt xem:</strong> {{ $book->so_luot_xem ?? 0 }}</p>
                            <p><strong>Trạng thái:</strong> 
                                @if($book->trang_thai === 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @else
                                    <span class="badge bg-secondary">Tạm dừng</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    @if($book->mo_ta)
                    <div class="mt-3">
                        <h6>Mô tả:</h6>
                        <p>{{ $book->mo_ta }}</p>
                    </div>
                    @endif

                    @if($book->so_trang)
                    <div class="mt-2">
                        <p><strong>Số trang:</strong> {{ $book->so_trang }} trang</p>
                    </div>
                    @endif

                    @if($book->ngon_ngu)
                    <div class="mt-2">
                        <p><strong>Ngôn ngữ:</strong> {{ $book->ngon_ngu }}</p>
                    </div>
                    @endif

                    @if($book->kich_thuoc_file)
                    <div class="mt-2">
                        <p><strong>Kích thước file:</strong> {{ $book->formatted_file_size }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Ảnh bìa</h5>
                </div>
                <div class="card-body text-center">
                    @if($book->hinh_anh)
                        <img src="{{ asset('storage/' . $book->hinh_anh) }}" 
                             class="img-fluid rounded shadow" 
                             style="max-height: 400px; object-fit: cover;"
                             alt="{{ $book->ten_sach }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                             style="height: 400px;">
                            <i class="fas fa-book fa-4x text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Thao tác</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('staff.purchasable-books.edit', $book->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Chỉnh sửa
                        </a>
                        <a href="{{ route('staff.purchasable-books.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

