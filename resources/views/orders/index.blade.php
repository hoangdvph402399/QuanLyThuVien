<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Lịch sử mua hàng - Nhà Xuất Bản Xây Dựng</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <style>
        /* Styles cho bảng lịch sử mua hàng */
        .purchase-history-section {
            background-color: #fff;
            padding: 30px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .purchase-history-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
        }

        .purchase-history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .purchase-history-table thead {
            background-color: #f5f5f5;
        }

        .purchase-history-table th {
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #ddd;
            font-size: 14px;
        }

        .purchase-history-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #555;
        }

        .purchase-history-table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .order-code {
            font-weight: 600;
            color: #333;
        }

        .order-date {
            color: #666;
        }

        .order-amount {
            font-weight: 600;
            color: #d82329;
        }

        .status-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .status-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            cursor: default;
        }

        .status-btn.cancelled {
            background-color: #dc3545;
            color: #fff;
        }

        .status-btn.unpaid {
            background-color: #6c757d;
            color: #fff;
        }

        .status-btn.paid {
            background-color: #28a745;
            color: #fff;
        }

        .status-btn.processing {
            background-color: #17a2b8;
            color: #fff;
        }

        .view-btn {
            background-color: #28a745;
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .view-btn:hover {
            background-color: #218838;
            color: #fff;
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 14px;
            color: #777;
            margin-bottom: 30px;
        }

        .pagination-wrapper {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-top">
            <div class="logo-section">
                <img src="{{ asset('favicon.ico') }}" alt="Logo" class="logo-img">
                <div class="logo-text">
                    <span class="logo-part1">NHÀ XUẤT BẢN</span>
                    <span class="logo-part2">XÂY DỰNG</span>
                </div>
            </div>
            <div class="hotline-section">
                <div class="hotline-item">
                    <span class="hotline-label">Hotline khách lẻ:</span>
                    <a href="tel:0327888669" class="hotline-number">0327888669</a>
                </div>
                <div class="hotline-item">
                    <span class="hotline-label">Hotline khách sỉ:</span>
                    <a href="tel:02439741791" class="hotline-number">02439741791 - 0327888669</a>
                </div>
            </div>
            <div class="user-actions">
                <a href="{{ route('cart.index') }}" class="cart-link">
                    <span class="cart-icon">🛒</span>
                    <span>Giỏ sách</span>
                    <span class="cart-badge" id="cart-count">0</span>
                </a>
                @auth
                    <div class="user-menu-dropdown" style="position: relative;">
                        <a href="#" class="auth-link user-menu-toggle">
                            <span class="user-icon">👤</span>
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <div class="user-dropdown-menu">
                            <div class="dropdown-header" style="padding: 12px 15px; border-bottom: 1px solid #eee; font-weight: 600; color: #333;">
                                <span class="user-icon">👤</span>
                                {{ auth()->user()->name }}
                            </div>
                            <a href="{{ route('account.purchased-books') }}" class="dropdown-item">
                                <span>❤️</span> Sách đã mua
                            </a>
                            <a href="{{ route('account') }}" class="dropdown-item">
                                <span>👤</span> Thông tin tài khoản
                            </a>
                            <a href="{{ route('account.change-password') }}" class="dropdown-item">
                                <span>🔒</span> Đổi mật khẩu
                            </a>
                            <a href="{{ route('orders.index') }}" class="dropdown-item">
                                <span>⏰</span> Lịch sử mua hàng
                            </a>
                            <a href="#" class="dropdown-item">
                                <span>💳</span> Lịch sử nạp tiền
                            </a>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                            <div style="border-top: 1px solid #eee; margin-top: 5px;"></div>
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <span>📊</span> Dashboard
                            </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">
                                    <span>➡️</span> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                    <style>
                        .user-menu-dropdown {
                            position: relative;
                        }
                        .user-menu-dropdown .user-dropdown-menu {
                            display: none;
                            position: absolute;
                            top: calc(100% + 5px);
                            right: 0;
                            background: white;
                            border: 1px solid #ddd;
                            border-radius: 8px;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                            min-width: 220px;
                            z-index: 1000;
                            overflow: hidden;
                        }
                        .user-menu-dropdown:hover .user-dropdown-menu {
                            display: block;
                        }
                        .user-menu-dropdown .dropdown-item {
                            display: block;
                            padding: 10px 15px;
                            color: #333;
                            text-decoration: none;
                            border-bottom: 1px solid #eee;
                            transition: background-color 0.2s;
                            cursor: pointer;
                        }
                        .user-menu-dropdown .dropdown-item:hover {
                            background-color: #f5f5f5;
                        }
                        .user-menu-dropdown .dropdown-item.logout-btn {
                            border: none;
                            background: none;
                            width: 100%;
                            text-align: left;
                            color: #d32f2f;
                            border-top: 1px solid #eee;
                            margin-top: 5px;
                        }
                        .user-menu-dropdown .dropdown-item.logout-btn:hover {
                            background-color: #ffebee;
                        }
                        .user-menu-dropdown .dropdown-item span {
                            margin-right: 8px;
                        }
                    </style>
                @else
                    <a href="{{ route('login') }}" class="auth-link">Đăng nhập</a>
                @endauth
            </div>
        </div>
        <div class="header-nav">
            <div class="search-bar">
                <form action="{{ route('books.public') }}" method="GET" class="search-form">
                    <input type="text" name="keyword" placeholder="Tìm sách, tác giả, sản phẩm mong muốn..." value="{{ request('keyword') }}" class="search-input">
                    <button type="submit" class="search-button">🔍 Tìm kiếm</button>
                </form>
            </div>
        </div>
    </header>

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb-nav">
        <div class="breadcrumb-container">
            <a href="{{ route('home') }}" class="breadcrumb-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Lịch sử mua hàng</span>
        </div>
    </nav>

    <main class="account-container">
        <aside class="account-sidebar">
            <div class="user-profile">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="username">{{ auth()->user()->name }}</div>
            </div>
            <nav class="account-nav">
                <ul>
                    <li><a href="{{ route('account.purchased-books') }}"><span class="icon">📖</span> Sách đã mua</a></li>
                    <li><a href="{{ route('account') }}"><span class="icon">👤</span> Thông tin khách hàng</a></li>
                    <li><a href="{{ route('account.change-password') }}"><span class="icon">🔒</span> Đổi mật khẩu</a></li>
                    <li class="active"><a href="{{ route('orders.index') }}"><span class="icon">🛒</span> Lịch sử mua hàng</a></li>
                    <li><a href="#"><span class="icon">💳</span> Lịch sử nạp tiền</a></li>
                    <li><a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="icon">➡️</span> Đăng xuất</a></li>
                </ul>
            </nav>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </aside>

        <section class="account-content">
            <div class="coin-balance-section">
                <div class="coin-card">
                    <div>
                        <p class="coin-title">Số dư Book coin</p>
                        <p class="coin-value">0 coin</p>
                    </div>
                    <button class="coin-action-btn">Nạp thêm</button>
                </div>
                <div class="coin-card">
                    <div>
                        <p class="coin-title">Số dư Book coin khuyến mãi</p>
                        <p class="coin-value">0 coin</p>
                    </div>
                </div>
            </div>
            
            <div class="purchase-history-section">
                <h2 class="purchase-history-title">Lịch sử mua hàng</h2>
                
                @if($orders->count() > 0)
                <table class="purchase-history-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Số tiền</th>
                            <th>Phương thức thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Xử lý</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $index => $order)
                        <tr>
                            <td>{{ $orders->firstItem() + $index }}</td>
                            <td>
                                <span class="order-code">#{{ $order->order_number }}</span>
                            </td>
                            <td>
                                <span class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td>
                                <span class="order-amount">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                            </td>
                            <td>
                                @if($order->payment_method === 'cash_on_delivery')
                                    <span style="color: #28a745; font-weight: 500;">💳 Thanh toán khi nhận hàng</span>
                                @elseif($order->payment_method === 'bank_transfer')
                                    <span style="color: #17a2b8; font-weight: 500;">🏦 Chuyển khoản ngân hàng</span>
                                @else
                                    <span style="color: #6c757d;">Chưa xác định</span>
                                @endif
                            </td>
                            <td>
                                <div class="status-buttons">
                                    @if($order->status === 'cancelled')
                                        <span class="status-btn cancelled">Đã huỷ</span>
                                    @elseif($order->status === 'pending')
                                        <span class="status-btn" style="background-color: #ffc107; color: #000;">Chờ xử lý</span>
                                    @elseif($order->status === 'processing')
                                        <span class="status-btn processing">Đang xử lý</span>
                                    @elseif($order->status === 'shipped')
                                        <span class="status-btn" style="background-color: #17a2b8; color: #fff;">Đã giao hàng</span>
                                    @elseif($order->status === 'delivered')
                                        <span class="status-btn paid">Đã hoàn thành</span>
                                    @endif
                                    @if($order->payment_status === 'pending')
                                        <span class="status-btn unpaid">Chưa thanh toán</span>
                                    @elseif($order->payment_status === 'paid')
                                        <span class="status-btn paid">Đã thanh toán</span>
                                    @elseif($order->payment_status === 'failed')
                                        <span class="status-btn cancelled">Thanh toán thất bại</span>
                                    @elseif($order->payment_status === 'refunded')
                                        <span class="status-btn" style="background-color: #6c757d; color: #fff;">Đã hoàn tiền</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="view-btn">Xem</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Phân trang -->
                @if($orders->hasPages())
                <div class="pagination-wrapper">
                    {{ $orders->links() }}
                </div>
                @endif

                @else
                <div class="empty-state">
                    <div class="empty-icon">🛒</div>
                    <h4>Bạn chưa có đơn hàng nào</h4>
                    <p>Hãy bắt đầu mua sắm để tạo đơn hàng đầu tiên của bạn!</p>
                    <a href="{{ route('books.public') }}" class="btn-primary">
                        Mua sắm ngay
                    </a>
                </div>
                @endif
            </div>
        </section>
    </main>

    @include('components.footer')
</body>
</html>
