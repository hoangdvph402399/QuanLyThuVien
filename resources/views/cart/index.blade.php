@extends('layouts.app')

@section('title', 'Giỏ hàng - Nhà xuất bản Xây dựng')

@section('styles')
<style>
    /* Override layout CSS cho trang cart - Độ ưu tiên cao nhất */
    body .container-fluid {
        padding: 20px !important;
        background-color: #fff !important;
        max-width: 100% !important;
    }
    
    /* Biến và thiết lập cơ bản */
    :root {
        --primary-color: #e51d2e; /* Màu đỏ chủ đạo từ logo/sách mới */
        --accent-orange: #ff6600; /* Màu cam cho nút mua hàng */
        --text-dark: #333;
        --text-light: #666;
        --border-color: #ddd;
        --bg-light: #f7f7f7;
    }

    /* Override tất cả các style từ layout cho cart */
    .cart-container,
    .cart-container * {
        box-sizing: border-box;
    }

    /* Reset các style từ layout */
    .cart-container .btn,
    .cart-container button {
        border-radius: 4px !important;
        font-size: inherit !important;
        letter-spacing: normal !important;
        box-shadow: none !important;
        transform: none !important;
        width: auto !important;
        margin: 0 !important;
    }

    .cart-container input[type="text"],
    .cart-container input[type="checkbox"] {
        border-radius: 0 !important;
        box-shadow: none !important;
    }

    .cart-container .cart-table,
    .cart-container .summary-box {
        box-shadow: none !important;
        border-radius: 4px !important;
    }

    /* Scope CSS cho trang cart */
    body .cart-container {
        font-family: Arial, sans-serif !important;
        color: var(--text-dark) !important;
    }

    .cart-container a {
        text-decoration: none;
        color: var(--text-light);
    }

    /* --- Bố cục chính --- */
    .cart-container {
        max-width: 1200px !important;
        margin: 20px auto !important;
        padding: 0 20px !important;
        background-color: #fff !important;
    }

    .breadcrumbs {
        font-size: 14px;
        color: var(--text-light);
        margin-bottom: 20px;
    }

    .breadcrumbs a {
        color: var(--text-light);
        transition: color 0.2s;
    }

    .breadcrumbs a:hover {
        color: var(--primary-color);
    }

    .cart-content {
        display: flex !important;
        gap: 30px;
    }

    /* --- Cột Trái: Danh sách Sản phẩm --- */
    .cart-left {
        flex: 3 !important; /* Chiếm khoảng 60% - 70% chiều rộng */
        width: auto !important;
    }

    .cart-left h2 {
        font-size: 20px !important;
        margin: 0 !important;
        display: inline-block !important;
        font-weight: bold !important;
    }

    .btn-mua-them {
        color: var(--accent-orange);
        font-weight: bold;
        float: right;
        margin-top: 5px;
    }

    /* Bảng Sản phẩm */
    .cart-table {
        margin-top: 15px !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 4px !important;
        background-color: #fff !important;
        width: 100% !important;
    }

    .cart-header-row, .cart-item-row {
        display: flex !important;
        align-items: center;
        padding: 15px 10px;
        border-bottom: 1px solid var(--border-color);
        font-size: 14px;
    }

    .cart-header-row {
        background-color: var(--bg-light);
        font-weight: bold;
    }

    .cart-item-row:last-child {
        border-bottom: none;
    }

    /* Định nghĩa các cột */
    .col-product-select { width: 5% !important; text-align: center !important; flex-shrink: 0 !important; }
    .col-product-name { width: 45% !important; flex-shrink: 0 !important; }
    .col-product-quantity { width: 20% !important; text-align: center !important; flex-shrink: 0 !important; }
    .col-product-total { width: 20% !important; text-align: right !important; flex-shrink: 0 !important; }
    .col-product-delete { width: 10% !important; text-align: center !important; flex-shrink: 0 !important; }

    /* Chi tiết Sản phẩm */
    .item-detail {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .item-cover {
        width: 60px;
        height: 80px;
        flex-shrink: 0;
        overflow: hidden;
        background-color: #f0f0f0; /* Giả lập bìa sách */
    }

    .item-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-title {
        font-weight: bold;
        margin: 0 0 5px 0;
        color: var(--text-dark);
    }

    .item-author, .item-price-mobile {
        margin: 0;
        font-size: 12px;
        color: var(--text-light);
    }
    .item-price-mobile {
        display: none; /* Ẩn trên desktop */
    }

    /* Điều chỉnh số lượng */
    .quantity-control {
        display: inline-flex !important;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        overflow: hidden;
    }

    .qty-btn {
        background: #fff !important;
        border: none !important;
        padding: 5px 10px !important;
        cursor: pointer;
        font-weight: bold !important;
        transition: background-color 0.2s;
        border-radius: 0 !important;
        font-size: 14px !important;
        width: auto !important;
        margin: 0 !important;
    }

    .qty-btn:hover:not(:disabled) {
        background-color: var(--bg-light);
    }

    .qty-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .quantity-control input {
        width: 30px;
        text-align: center;
        border: none;
        border-left: 1px solid var(--border-color);
        border-right: 1px solid var(--border-color);
        padding: 5px 0;
    }

    .total-price {
        font-weight: bold;
        color: var(--accent-orange);
    }

    .delete-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        color: var(--text-light);
        transition: color 0.2s;
    }

    .delete-btn:hover {
        color: var(--primary-color);
    }

    /* --- Cột Phải: Tổng kết --- */
    .cart-right {
        flex: 2 !important; /* Chiếm khoảng 30% - 40% chiều rộng */
        width: auto !important;
    }

    .summary-box {
        border: 1px solid var(--border-color) !important;
        padding: 20px !important;
        border-radius: 4px !important;
        background-color: #fff !important;
        box-shadow: none !important;
    }

    .summary-row {
        display: flex !important;
        justify-content: space-between !important;
        margin-bottom: 10px !important;
        font-size: 16px !important;
        align-items: center !important;
    }

    .summary-row.grand-total {
        font-size: 18px;
        font-weight: bold;
        margin-top: 15px;
    }

    .price-value {
        font-weight: bold;
    }

    .price-value.total-red {
        color: var(--accent-orange);
    }

    hr {
        border: 0;
        border-top: 1px dashed var(--border-color);
        margin: 15px 0;
    }

    /* Mã giảm giá */
    .promo-input {
        display: flex !important;
        margin-bottom: 20px !important;
        width: 100% !important;
    }

    .promo-input input {
        flex-grow: 1;
        padding: 10px;
        border: 1px solid var(--border-color);
        border-right: none;
        border-radius: 4px 0 0 4px;
    }

    .btn-apply {
        background-color: #4CAF50 !important; /* Màu xanh lá cây */
        color: white !important;
        padding: 10px 15px !important;
        border: none !important;
        border-radius: 0 4px 4px 0 !important;
        cursor: pointer;
        font-weight: bold !important;
        transition: background-color 0.3s;
        font-size: 14px !important;
        width: auto !important;
        margin: 0 !important;
        box-shadow: none !important;
        transform: none !important;
    }

    .btn-apply:hover {
        background-color: #45a049;
    }

    .btn-mua-hang {
        width: 100% !important;
        background-color: var(--accent-orange) !important;
        color: white !important;
        padding: 15px 0 !important;
        border: none !important;
        border-radius: 4px !important;
        font-size: 18px !important;
        font-weight: bold !important;
        cursor: pointer;
        margin-top: 10px;
        text-decoration: none !important;
        transition: background-color 0.3s;
        display: block !important;
        text-align: center !important;
    }

    .btn-mua-hang:hover {
        background-color: #e55a00 !important;
        color: white !important;
    }

    .btn-mua-hang i {
        margin-right: 8px;
    }

    .btn-apply:hover {
        background-color: #45a049;
    }

    .commitment-text {
        font-size: 12px;
        text-align: center;
        color: var(--text-light);
        margin-top: 15px;
    }

    /* --- Giỏ hàng trống --- */
    .empty-cart-container {
        text-align: center;
        padding: 60px 20px;
        background-color: #fff;
    }

    .empty-cart-icon {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 20px;
        display: block;
    }

    .empty-cart-container h3 {
        font-size: 24px;
        font-weight: bold;
        color: var(--text-dark);
        margin: 0 0 15px 0;
    }

    .empty-cart-container p {
        font-size: 16px;
        color: var(--text-light);
        margin: 0 0 30px 0;
    }

    .empty-cart-container .btn-mua-hang {
        display: inline-block;
        margin-top: 20px;
        padding: 12px 30px;
        width: auto;
    }

    /* --- Responsive (Tối ưu cho mobile) --- */
    @media (max-width: 768px) {
        .cart-content {
            flex-direction: column; /* Chuyển sang bố cục cột */
        }

        .cart-left, .cart-right {
            flex: auto;
        }

        .cart-header-row {
            display: none; /* Ẩn tiêu đề bảng trên mobile */
        }

        .cart-item-row {
            flex-wrap: wrap;
            border-bottom: 1px dashed var(--border-color);
        }
        
        .col-product-select { width: 10%; }
        .col-product-name { width: 70%; }
        .col-product-delete { width: 20%; text-align: right; }

        /* Di chuyển số lượng và thành tiền xuống dòng mới */
        .col-product-quantity { order: 4; width: 40%; text-align: left; margin-top: 10px;}
        .col-product-total { order: 3; width: 60%; text-align: right; margin-top: 10px;}

        .item-detail {
            width: 100%;
            margin-left: -10px; /* Căn chỉnh lại */
        }
        
        .item-price-mobile {
            display: block; /* Hiển thị đơn giá mobile */
        }
    }
</style>
@endsection

@section('content')
<div class="cart-container">
    <div class="breadcrumbs">
        <a href="{{ route('home') }}">Trang chủ</a> / Giỏ hàng
    </div>

    @if($cartItems->count() > 0)
    <div class="cart-content">
        <div class="cart-left">
            <h2>GIỎ SÁCH CỦA BẠN</h2>
            <a href="{{ route('books.public') }}" class="btn-mua-them">Mua thêm ></a>

            <div class="cart-table">
                <div class="cart-header-row">
                    <div class="col-product-select">
                        <input type="checkbox" checked>
                    </div>
                    <div class="col-product-name">Tất cả ( {{ $cart->total_items }} sản phẩm )</div>
                    <div class="col-product-quantity">Số lượng</div>
                    <div class="col-product-total">Thành tiền</div>
                    <div class="col-product-delete">Xóa</div>
                </div>

                @foreach($cartItems as $item)
                <div class="cart-item-row" data-item-id="{{ $item->id }}">
                    <div class="col-product-select">
                        <input type="checkbox" checked>
                    </div>
                    <div class="col-product-name item-detail">
                        <div class="item-cover">
                            @php
                                $imagePath = $item->purchasableBook->hinh_anh;
                                $imageUrl = null;
                                
                                if ($imagePath) {
                                    // Kiểm tra xem đường dẫn có chứa 'storage/' chưa
                                    if (strpos($imagePath, 'storage/') === 0) {
                                        $fullPath = public_path($imagePath);
                                    } else {
                                        $fullPath = public_path('storage/' . $imagePath);
                                    }
                                    
                                    if (file_exists($fullPath)) {
                                        $imageUrl = asset('storage/' . ltrim(str_replace('storage/', '', $imagePath), '/'));
                                    }
                                }
                            @endphp
                            
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="{{ $item->purchasableBook->ten_sach }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; background-color: #f0f0f0; color: #999; font-size: 10px; text-align: center; padding: 5px;">
                                    {{ substr($item->purchasableBook->ten_sach, 0, 15) }}
                                </div>
                            @else
                                <div style="display: flex; width: 100%; height: 100%; align-items: center; justify-content: center; background-color: #f0f0f0; color: #999; font-size: 10px; text-align: center; padding: 5px;">
                                    {{ substr($item->purchasableBook->ten_sach, 0, 15) }}
                                </div>
                            @endif
                        </div>
                        <div class="item-info">
                            <p class="item-title">{{ $item->purchasableBook->ten_sach }}</p>
                            <p class="item-author">{{ $item->purchasableBook->tac_gia }}</p>
                            <p class="item-price-mobile">Đơn giá: <strong>{{ number_format($item->price, 0, ',', '.') }}₫</strong></p>
                        </div>
                    </div>
                    <div class="col-product-quantity">
                        <div class="quantity-control">
                            <button class="qty-btn" data-action="decrease" data-item-id="{{ $item->id }}" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                            <input type="text" value="{{ $item->quantity }}" readonly data-item-id="{{ $item->id }}">
                            <button class="qty-btn" data-action="increase" data-item-id="{{ $item->id }}" {{ $item->quantity >= 10 ? 'disabled' : '' }}>+</button>
                        </div>
                    </div>
                    <div class="col-product-total">
                        <span class="total-price" id="total-{{ $item->id }}">{{ number_format($item->total_price, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="col-product-delete">
                        <button class="delete-btn remove-item-btn" data-item-id="{{ $item->id }}">🗑️</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="cart-right">
            <div class="summary-box">
                <div class="summary-row">
                    <span>Tổng tiền:</span>
                    <span class="price-value" id="subtotal">{{ number_format($cart->total_amount, 0, ',', '.') }}₫</span>
                </div>
                
                <div class="summary-row promo-code">
                    <span>Giảm giá SP:</span>
                    <span class="price-value">-0₫</span>
                </div>
                <div class="promo-input">
                    <input type="text" placeholder="Nhập mã giảm giá">
                    <button class="btn-apply">Áp dụng</button>
                </div>
                <hr>
                <div class="summary-row subtotal">
                    <span>Tạm tính:</span>
                    <span class="price-value" id="subtotal-value">{{ number_format($cart->total_amount, 0, ',', '.') }}₫</span>
                </div>
                <div class="summary-row discount">
                    <span>Giảm giá đơn:</span>
                    <span class="price-value">-0₫</span>
                </div>
                
                <hr>
                
                <div class="summary-row grand-total">
                    <span>Thanh toán:</span>
                    <span class="price-value total-red" id="total-amount">{{ number_format($cart->total_amount, 0, ',', '.') }}₫</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn-mua-hang" style="display: block; text-align: center; color: white; text-decoration: none;">
                    <i class="fas fa-shopping-cart"></i> Mua hàng
                </a>
                <p class="commitment-text">
                    Bằng việc tiến hành đặt mua hàng, bạn đồng ý với điều khoản của <strong>Nhà Xuất Bản Xây Dựng</strong>.
                </p>
            </div>
        </div>
    </div>
    @else
    <!-- Giỏ hàng trống -->
    <div class="empty-cart-container">
        <i class="fas fa-shopping-cart empty-cart-icon"></i>
        <h3>Giỏ hàng của bạn đang trống</h3>
        <p>Hãy thêm một số sách vào giỏ hàng để bắt đầu mua sắm!</p>
        <a href="{{ route('books.public') }}" class="btn-mua-hang">Mua sắm ngay</a>
    </div>
    @endif
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sách này khỏi giỏ hàng?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chỉ khởi tạo modal nếu element tồn tại (khi có sản phẩm trong giỏ)
    const confirmDeleteModalElement = document.getElementById('confirmDeleteModal');
    let confirmDeleteModal = null;
    let itemToDelete = null;
    
    if (confirmDeleteModalElement) {
        confirmDeleteModal = new bootstrap.Modal(confirmDeleteModalElement);
    }

    // Xử lý nút tăng/giảm số lượng (chỉ khi có sản phẩm)
    document.querySelectorAll('.qty-btn').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const itemId = this.dataset.itemId;
            const input = document.querySelector(`input[data-item-id="${itemId}"]`);
            
            if (!input) return;
            
            let quantity = parseInt(input.value);

            if (action === 'increase' && quantity < 10) {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            }

            input.value = quantity;
            updateQuantity(itemId, quantity);
        });
    });

    // Xử lý xóa item (chỉ khi có modal)
    document.querySelectorAll('.remove-item-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (!confirmDeleteModal) return;
            
            itemToDelete = this.dataset.itemId;
            confirmDeleteModal.show();
        });
    });

    // Xác nhận xóa item (chỉ khi có element)
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (itemToDelete && confirmDeleteModal) {
                removeItem(itemToDelete);
                confirmDeleteModal.hide();
            }
        });
    }

    // Hàm cập nhật số lượng
    function updateQuantity(itemId, quantity) {
        fetch(`/cart/update/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật thành tiền
                const totalElement = document.getElementById(`total-${itemId}`);
                if (totalElement) {
                    totalElement.textContent = data.total_price;
                }

                // Reload trang để cập nhật tổng tiền
                setTimeout(() => {
                    location.reload();
                }, 300);
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật số lượng');
        });
    }

    // Hàm xóa item
    function removeItem(itemId) {
        fetch(`/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa sản phẩm');
        });
    }
});
</script>
@endsection
