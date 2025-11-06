@extends('layouts.admin')

@section('title', 'Tạo Phiếu Nhập Kho - Admin')

@section('content')
<div class="admin-table">
    <h3><i class="fas fa-plus"></i> Tạo Phiếu Nhập Kho Mới</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.inventory.receipts.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Số phiếu</label>
                    <input type="text" class="form-control" value="{{ $receiptNumber }}" readonly>
                    <small class="form-text text-muted">Số phiếu được tạo tự động</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Ngày nhập <span class="text-danger">*</span></label>
                    <input type="date" name="receipt_date" class="form-control" value="{{ old('receipt_date', date('Y-m-d')) }}" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Sách <span class="text-danger">*</span></label>
                    <select name="book_id" class="form-control" required>
                        <option value="">-- Chọn sách --</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->ten_sach }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" min="1" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Vị trí lưu trữ <span class="text-danger">*</span></label>
                    <input type="text" name="storage_location" class="form-control" value="{{ old('storage_location') }}" placeholder="Ví dụ: Kệ A1, Tầng 2, Vị trí 3" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Loại lưu trữ <span class="text-danger">*</span></label>
                    <select name="storage_type" class="form-control" required>
                        <option value="Kho" {{ old('storage_type') == 'Kho' ? 'selected' : '' }}>Kho</option>
                        <option value="Trung bay" {{ old('storage_type') == 'Trung bay' ? 'selected' : '' }}>Trưng bày</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Giá mua đơn vị (VNĐ)</label>
                    <input type="number" name="unit_price" class="form-control" value="{{ old('unit_price') }}" min="0" step="0.01">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Nhà cung cấp</label>
                    <input type="text" name="supplier" class="form-control" value="{{ old('supplier') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Ghi chú</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.inventory.receipts') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Tạo Phiếu Nhập</button>
        </div>
    </form>
</div>
@endsection

