@php
/**
 * @var \Illuminate\Support\Collection $featured_books
 * @var \App\Models\Book $book
 * @var array $bannerImages
 */
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libhub - Thư viện Trực tuyến</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
</head>
<body>
    <header class="main-header">
        <div class="header-top">
            <div class="logo-section">
                <img src="{{ asset('favicon.ico') }}" alt="Logo" class="logo-img">
                <div class="logo-text">
                    <span class="logo-part1">THƯ VIỆN</span>
                    <span class="logo-part2">LIBHUB</span>
                </div>
            </div>
            <script>
                (function(){
                    function setupSlider(wrapperId){
                        var slider = document.getElementById(wrapperId);
                        if(!slider) return;
                        var slides = slider.querySelectorAll('.diem-sach-featured-slide');
                        if(!slides || slides.length <= 1) return;
                        var current = 0;
                        function show(i){
                            slides[current].classList.remove('active');
                            current = (i + slides.length) % slides.length;
                            slides[current].classList.add('active');
                        }
                        var prevBtn = document.querySelector('.diem-sach-nav-prev[data-target="'+wrapperId+'"]');
                        var nextBtn = document.querySelector('.diem-sach-nav-next[data-target="'+wrapperId+'"]');
                        if(prevBtn){ prevBtn.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); show(current-1); }); }
                        if(nextBtn){ nextBtn.addEventListener('click', function(e){ e.preventDefault(); e.stopPropagation(); show(current+1); }); }
                    }
                    document.addEventListener('DOMContentLoaded', function(){
                        setupSlider('diemSachSlider');
                    });
                })();
            </script>
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
    <main class="main-layout container">
        <div class="main-content">
            <div class="main-banner-section">
                <div class="banner-carousel-wrapper">
                    <div class="banner-carousel">
                        @php
                            // Ưu tiên banner từ admin, sau đó mới dùng sách nổi bật
                            $bannerImages = [];
                            $bannerDir = public_path('storage/banners');
                            $extensions = ['jpg', 'jpeg', 'png', 'webp'];
                            
                            // Tìm banner từ admin (banner1, banner2, banner3, banner4)
                            $adminBanners = [];
                            $bannerTitles = [
                                1 => 'MUA 1 NĂM TẶNG 1 TÚI CANVAS',
                                2 => 'ĐỌC SÁCH KHÔNG GIỚI HẠN',
                                3 => 'SÁCH NÓI MIỄN PHÍ',
                                4 => 'TRUYỆN TRANH HOT NHẤT'
                            ];
                            
                            for($i = 1; $i <= 4; $i++) {
                                $adminBanners[$i] = null;
                                if(file_exists($bannerDir)) {
                                    foreach($extensions as $ext) {
                                        $path = $bannerDir . '/banner' . $i . '.' . $ext;
                                        if(file_exists($path)) {
                                            $adminBanners[$i] = [
                                                'image' => asset('storage/banners/banner' . $i . '.' . $ext),
                                                'title' => $bannerTitles[$i] ?? 'Banner ' . $i,
                                                'link' => '#'
                                            ];
                                            break;
                                        }
                                    }
                                }
                            }
                            
                            // Thêm banner từ admin vào danh sách
                            foreach($adminBanners as $banner) {
                                if($banner) {
                                    $bannerImages[] = $banner;
                                }
                            }
                            
                            // Nếu chưa đủ 3 banner, bổ sung từ sách nổi bật
                            if(count($bannerImages) < 3 && isset($featured_books) && $featured_books->count() > 0) {
                                foreach($featured_books as $book) {
                                    if(count($bannerImages) >= 5) break; // Tối đa 5 banner
                                    if($book->hinh_anh && file_exists(public_path('storage/'.$book->hinh_anh))) {
                                        $bannerImages[] = [
                                            'image' => asset('storage/'.$book->hinh_anh),
                                            'title' => $book->ten_sach,
                                            'link' => route('books.show', $book->id)
                                        ];
                                    }
                                }
                            }
                            
                            // Thêm placeholder nếu không có đủ ảnh (tối thiểu 3)
                            while(count($bannerImages) < 3) {
                                $bannerImages[] = [
                                    'image' => null,
                                    'title' => 'Banner ' . (count($bannerImages) + 1),
                                    'link' => '#'
                                ];
                            }
                        @endphp
                        
                        @foreach($bannerImages as $index => $banner)
                            <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}">
                                <a href="{{ $banner['link'] }}" class="slide-link">
                                    @if($banner['image'])
                                        <img src="{{ $banner['image'] }}" alt="{{ $banner['title'] }}" class="slide-image">
                                    @else
                                        <div class="slide-placeholder">
                                            <div class="placeholder-content">
                                                <h2>{{ $banner['title'] }}</h2>
                                                <p>Khám phá thư viện sách đa dạng</p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="slide-overlay">
                                        <h3 class="slide-title">{{ $banner['title'] }}</h3>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Navigation Arrows -->
                    <button class="carousel-nav carousel-prev" onclick="changeSlide(-1)">
                        <span>‹</span>
                    </button>
                    <button class="carousel-nav carousel-next" onclick="changeSlide(1)">
                        <span>›</span>
                    </button>
                    
                    <!-- Dots Indicator -->
                    <div class="carousel-dots">
                        @foreach($bannerImages as $index => $banner)
                            <span class="dot {{ $index === 0 ? 'active' : '' }}" onclick="currentSlide({{ $index + 1 }})"></span>
                        @endforeach
                    </div>
                </div>
                
                <div class="right-panels">
                    @php
                        // Tìm ảnh cho 3 panel từ admin banner
                        $panelImages = [];
                        $bannerDir = public_path('storage/banners');
                        $extensions = ['jpg', 'jpeg', 'png', 'webp'];
                        
                        // Tìm ảnh cho panel 1, 2, 3
                        for($i = 1; $i <= 3; $i++) {
                            $panelImages[$i] = null;
                            
                            if(file_exists($bannerDir)) {
                                foreach($extensions as $ext) {
                                    $path = $bannerDir . '/panel' . $i . '.' . $ext;
                                    if(file_exists($path)) {
                                        $panelImages[$i] = asset('storage/banners/panel' . $i . '.' . $ext);
                                        break;
                                    }
                                }
                            }
                        }
                    @endphp
                    <div class="panel-card panel-download {{ $panelImages[1] ? 'has-image' : '' }}">
                        @if($panelImages[1])
                            <img src="{{ $panelImages[1] }}" alt="Tải xuống Danh mục SÁCH" class="panel-image">
                        @else
                            <div class="panel-icon">📥</div>
                        @endif
                        <h3>Tải xuống<br><strong>Danh mục SÁCH</strong></h3>
                    </div>
                    <div class="panel-card panel-procedure {{ $panelImages[2] ? 'has-image' : '' }}">
                        @if($panelImages[2])
                            <img src="{{ $panelImages[2] }}" alt="Thủ tục Hành chính" class="panel-image">
                        @else
                            <div class="panel-icon">📋</div>
                        @endif
                        <h3>Thủ tục Hành chính<br><strong>Ngành xây dựng</strong></h3>
                    </div>
                </div>
            </div>
            
            <div class="bottom-banner-section">
                @php
                    // Tìm ảnh cho cooperation banner và panel 3
                    $cooperationImage = null;
                    $panel3Image = null;
                    $bannerDir = public_path('storage/banners');
                    $extensions = ['jpg', 'jpeg', 'png', 'webp'];
                    
                    if(file_exists($bannerDir)) {
                        // Tìm ảnh cooperation
                        foreach($extensions as $ext) {
                            $path = $bannerDir . '/cooperation.' . $ext;
                            if(file_exists($path)) {
                                $cooperationImage = asset('storage/banners/cooperation.' . $ext);
                                break;
                            }
                        }
                        
                        // Tìm ảnh panel 3
                        foreach($extensions as $ext) {
                            $path = $bannerDir . '/panel3.' . $ext;
                            if(file_exists($path)) {
                                $panel3Image = asset('storage/banners/panel3.' . $ext);
                                break;
                            }
                        }
                    }
                @endphp
                <div class="cooperation-banner {{ $cooperationImage ? 'has-image' : '' }}">
                    @if($cooperationImage)
                        <img src="{{ $cooperationImage }}" alt="LIÊN KẾT - HỢP TÁC XUẤT BẢN" class="cooperation-image">
                    @endif
                    <div class="coop-content">
                        <div class="coop-text">
                            <h2>LIÊN KẾT - HỢP TÁC XUẤT BẢN</h2>
                            <p>Hiện thực hóa cuốn sách của bạn</p>
                            <p class="coop-hotline">HOTLINE: 0327.888.669</p>
                            <button class="coop-btn"><span>XEM CHI TIẾT</span></button>
                        </div>
                    </div>
                </div>
                <div class="panel-card panel-free {{ $panel3Image ? 'has-image' : '' }}">
                    @if($panel3Image)
                        <img src="{{ $panel3Image }}" alt="Đọc sách miễn phí" class="panel-image">
                    @else
                        <div class="panel-icon">📚</div>
                    @endif
                    <h3>FREE<br><strong>Đọc sách miễn phí</strong></h3>
                </div>
            </div>
            
            <!-- Phần Bảng Xếp Hạng -->
            <div class="book-section">
                <div class="section-header">
                    <h2 class="section-title">Bảng Xếp Hạng</h2>
                    <a href="{{ route('books.public', ['category_id' => null]) }}" class="view-all-link">
                        Xem toàn bộ <span>→</span>
                    </a>
                </div>
                <div class="book-carousel-wrapper">
                    <div class="book-list sach-list-container" id="sach-noi-carousel">
                        @forelse($top_books ?? [] as $book)
                            <div class="book-item">
                                <a href="{{ route('books.show', $book->id) }}" class="book-link">
                                    <div class="book-cover">
                                        @if(isset($book->hinh_anh) && !empty($book->hinh_anh) && file_exists(public_path('storage/'.$book->hinh_anh)))
                                            <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach ?? 'Sách' }}">
                                        @else
                                            <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="210" height="297" fill="#f0f0f0"></rect>
                                                <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                            </svg>
                                        @endif
                                    </div>
                                    <p class="book-title">{{ $book->ten_sach ?? 'Chưa có tên' }}</p>
                                    @if(isset($book->tac_gia) && !empty($book->tac_gia))
                                        <p class="book-author">{{ $book->tac_gia }}</p>
                                    @endif
                                    <div class="book-rating">
                                        <span class="stars">★★★★★</span>
                                    </div>
                                    @if(isset($book->gia) && $book->gia > 0)
                                        <p class="book-price">Chỉ từ {{ number_format($book->gia, 0, ',', '.') }}₫</p>
                                    @elseif(isset($book->gia_ban) && $book->gia_ban > 0)
                                        <p class="book-price">Chỉ từ {{ number_format($book->gia_ban, 0, ',', '.') }}₫</p>
                                    @else
                                        <p class="book-price">Chỉ từ 120.000₫</p>
                                    @endif
                                </a>
                            </div>
                        @empty
                            @if(isset($featured_books) && $featured_books->count() > 0)
                                @foreach($featured_books->take(10) as $book)
                                    <div class="book-item">
                                        <a href="{{ route('books.show', $book->id) }}" class="book-link">
                                            <div class="book-cover">
                                                @if(isset($book->hinh_anh) && !empty($book->hinh_anh) && file_exists(public_path('storage/'.$book->hinh_anh)))
                                                    <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach ?? 'Sách' }}">
                                                @else
                                                    <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="210" height="297" fill="#f0f0f0"></rect>
                                                        <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                                    </svg>
                                                @endif
                                            </div>
                                            <p class="book-title">{{ $book->ten_sach ?? 'Chưa có tên' }}</p>
                                            @if(isset($book->tac_gia) && !empty($book->tac_gia))
                                                <p class="book-author">{{ $book->tac_gia }}</p>
                                            @endif
                                            <div class="book-rating">
                                                <span class="stars">★★★★★</span>
                                            </div>
                                            @if(isset($book->gia) && $book->gia > 0)
                                                <p class="book-price">Chỉ từ {{ number_format($book->gia, 0, ',', '.') }}₫</p>
                                            @elseif(isset($book->gia_ban) && $book->gia_ban > 0)
                                                <p class="book-price">Chỉ từ {{ number_format($book->gia_ban, 0, ',', '.') }}₫</p>
                                            @else
                                                <p class="book-price">Chỉ từ 120.000₫</p>
                                            @endif
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        @endforelse
                    </div>
                    <button class="book-nav book-nav-prev" onclick="scrollCarousel('sach-noi-carousel', -1)">
                        <span>‹</span>
                    </button>
                    <button class="book-nav book-nav-next" onclick="scrollCarousel('sach-noi-carousel', 1)">
                        <span>›</span>
                    </button>
                </div>
            </div>
            
            <!-- Phần Nâng cấp tài khoản -->
            <div class="upgrade-section">
                <h2 class="section-title-upgrade">Nâng cấp tài khoản</h2>
                <div class="upgrade-cards">
                    <div class="upgrade-card vip-1" onclick="window.location.href='{{ route('login') }}'">
                        <div class="upgrade-illustration">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="white"/>
                            </svg>
                        </div>
                        <div class="upgrade-number">1</div>
                        <div class="upgrade-label">Tháng</div>
                        <div class="upgrade-price">39.000₫</div>
                    </div>
                    <div class="upgrade-card vip-2" onclick="window.location.href='{{ route('login') }}'">
                        <div class="upgrade-illustration">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="white"/>
                            </svg>
                        </div>
                        <div class="upgrade-number">2</div>
                        <div class="upgrade-label">Tháng</div>
                        <div class="upgrade-price">69.000₫</div>
                    </div>
                    <div class="upgrade-card vip-3" onclick="window.location.href='{{ route('login') }}'">
                        <div class="upgrade-illustration">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="white"/>
                            </svg>
                        </div>
                        <div class="upgrade-number">3</div>
                        <div class="upgrade-label">Tháng</div>
                        <div class="upgrade-price">99.000₫</div>
                    </div>
                    <div class="upgrade-card vip-6" onclick="window.location.href='{{ route('login') }}'">
                        <div class="upgrade-illustration">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="white"/>
                            </svg>
                        </div>
                        <div class="upgrade-number">6</div>
                        <div class="upgrade-label">Tháng</div>
                        <div class="upgrade-price">179.000₫</div>
                    </div>
                    <div class="upgrade-card vip-9" onclick="window.location.href='{{ route('login') }}'">
                        <div class="upgrade-illustration">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="white"/>
                            </svg>
                        </div>
                        <div class="upgrade-number">9</div>
                        <div class="upgrade-label">Tháng</div>
                        <div class="upgrade-price">239.000₫</div>
                    </div>
                    <div class="upgrade-card vip-12" onclick="window.location.href='{{ route('login') }}'">
                        <div class="upgrade-illustration">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="white"/>
                            </svg>
                        </div>
                        <div class="upgrade-number">12</div>
                        <div class="upgrade-label">Tháng</div>
                        <div class="upgrade-price">299.000₫</div>
                    </div>
                    <div class="upgrade-card vip-24" onclick="window.location.href='{{ route('login') }}'">
                        <div class="upgrade-illustration">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="white"/>
                            </svg>
                        </div>
                        <div class="upgrade-number">24</div>
                        <div class="upgrade-label">Tháng</div>
                        <div class="upgrade-price">499.000₫</div>
                    </div>
                </div>
            </div>
            
            <!-- Phần Sách mới và Sách mua nhiều nhất -->
            <div class="upgrade-bestbooks-section">
                <div class="left-column">
                    <!-- Phần Sách mới -->
                    <div class="book-section">
                        <div class="section-header">
                            <h2 class="section-title">Sách mới</h2>
                            <a href="{{ route('books.public', ['category_id' => null]) }}" class="view-all-link">
                                Xem toàn bộ <span>→</span>
                            </a>
                        </div>
                        <div class="book-carousel-wrapper">
                            <div class="book-list sach-list-container" id="sach-moi-carousel">
                                @forelse($new_books ?? [] as $book)
                                    <div class="book-item">
                                        <a href="{{ route('books.show', $book->id) }}" class="book-link">
                                            <div class="book-cover">
                                                @if(isset($book->hinh_anh) && !empty($book->hinh_anh) && file_exists(public_path('storage/'.$book->hinh_anh)))
                                                    <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach ?? 'Sách' }}">
                                                @else
                                                    <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="210" height="297" fill="#f0f0f0"></rect>
                                                        <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                                    </svg>
                                                @endif
                                            </div>
                                            <p class="book-title">{{ $book->ten_sach ?? 'Chưa có tên' }}</p>
                                            @if(isset($book->tac_gia) && !empty($book->tac_gia))
                                                <p class="book-author">{{ $book->tac_gia }}</p>
                                            @endif
                                            <div class="book-rating">
                                                <span class="stars">★★★★★</span>
                                            </div>
                                            @if(isset($book->gia) && $book->gia > 0)
                                                <p class="book-price">Chỉ từ {{ number_format($book->gia, 0, ',', '.') }}₫</p>
                                            @elseif(isset($book->so_luong_ban) && $book->so_luong_ban > 0)
                                                <p class="book-price">Đã bán: {{ $book->so_luong_ban }}</p>
                                            @endif
                                        </a>
                                    </div>
                                @empty
                                    @if(isset($featured_books) && $featured_books->count() > 0)
                                        @foreach($featured_books->take(10) as $book)
                                            <div class="book-item">
                                                <a href="{{ route('books.show', $book->id) }}" class="book-link">
                                                    <div class="book-cover">
                                                        @if(isset($book->hinh_anh) && !empty($book->hinh_anh) && file_exists(public_path('storage/'.$book->hinh_anh)))
                                                            <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach ?? 'Sách' }}">
                                                        @else
                                                            <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                                <rect width="210" height="297" fill="#f0f0f0"></rect>
                                                                <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <p class="book-title">{{ $book->ten_sach ?? 'Chưa có tên' }}</p>
                                                    @if(isset($book->tac_gia) && !empty($book->tac_gia))
                                                        <p class="book-author">{{ $book->tac_gia }}</p>
                                                    @endif
                                                    @if(isset($book->gia) && $book->gia > 0)
                                                        <p class="book-price">Chỉ từ {{ number_format($book->gia, 0, ',', '.') }}₫</p>
                                                    @elseif(isset($book->so_luong_ban) && $book->so_luong_ban > 0)
                                                        <p class="book-price">Đã bán: {{ $book->so_luong_ban }}</p>
                                                    @endif
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforelse
                            </div>
                        </div>
                    </div>
                    
                    <!-- Phần Có thể bạn thích -->
                    <div class="book-section">
                        <div class="section-header">
                            <h2 class="section-title">Có thể bạn thích</h2>
                            <a href="{{ route('books.public', ['category_id' => null]) }}" class="view-all-link">
                                Xem toàn bộ <span>→</span>
                            </a>
                        </div>
                        <div class="book-carousel-wrapper">
                            <div class="book-list sach-list-container" id="co-the-ban-thich-carousel">
                                @forelse($recommended_books ?? [] as $book)
                                    <div class="book-item">
                                        <a href="{{ route('books.show', $book->id) }}" class="book-link">
                                            <div class="book-cover">
                                                @if(isset($book->hinh_anh) && !empty($book->hinh_anh) && file_exists(public_path('storage/'.$book->hinh_anh)))
                                                    <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach ?? 'Sách' }}">
                                                @else
                                                    <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="210" height="297" fill="#f0f0f0"></rect>
                                                        <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                                    </svg>
                                                @endif
                                            </div>
                                            <p class="book-title">{{ $book->ten_sach ?? 'Chưa có tên' }}</p>
                                            @if(isset($book->tac_gia) && !empty($book->tac_gia))
                                                <p class="book-author">{{ $book->tac_gia }}</p>
                                            @endif
                                            <div class="book-rating">
                                                <span class="stars">★★★★★</span>
                                            </div>
                                            @if(isset($book->gia) && $book->gia > 0)
                                                <p class="book-price">Chỉ từ {{ number_format($book->gia, 0, ',', '.') }}₫</p>
                                            @elseif(isset($book->so_luong_ban) && $book->so_luong_ban > 0)
                                                <p class="book-price">Đã bán: {{ $book->so_luong_ban }}</p>
                                            @endif
                                        </a>
                                    </div>
                                @empty
                                    @if(isset($featured_books) && $featured_books->count() > 0)
                                        @foreach($featured_books->take(10) as $book)
                                            <div class="book-item">
                                                <a href="{{ route('books.show', $book->id) }}" class="book-link">
                                                    <div class="book-cover">
                                                        @if(isset($book->hinh_anh) && !empty($book->hinh_anh) && file_exists(public_path('storage/'.$book->hinh_anh)))
                                                            <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach ?? 'Sách' }}">
                                                        @else
                                                            <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                                <rect width="210" height="297" fill="#f0f0f0"></rect>
                                                                <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <p class="book-title">{{ $book->ten_sach ?? 'Chưa có tên' }}</p>
                                                    @if(isset($book->tac_gia) && !empty($book->tac_gia))
                                                        <p class="book-author">{{ $book->tac_gia }}</p>
                                                    @endif
                                                    @if(isset($book->gia) && $book->gia > 0)
                                                        <p class="book-price">Chỉ từ {{ number_format($book->gia, 0, ',', '.') }}₫</p>
                                                    @elseif(isset($book->so_luong_ban) && $book->so_luong_ban > 0)
                                                        <p class="book-price">Đã bán: {{ $book->so_luong_ban }}</p>
                                                    @endif
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforelse
                            </div>
                        </div>
                    </div>
                    
                    <!-- Phần Đề xuất -->
                    <div class="book-section">
                        <div class="section-header">
                            <h2 class="section-title">Đề xuất</h2>
                            <a href="{{ route('books.public', ['category_id' => null]) }}" class="view-all-link">
                                Xem toàn bộ <span>→</span>
                            </a>
                        </div>
                        <div class="book-carousel-wrapper">
                            <div class="book-list sach-list-container" id="de-xuat-carousel">
                                @forelse($suggested_books ?? [] as $book)
                                    <div class="book-item">
                                        <a href="{{ route('books.show', $book->id) }}" class="book-link">
                                            <div class="book-cover">
                                                @if(isset($book->hinh_anh) && !empty($book->hinh_anh) && file_exists(public_path('storage/'.$book->hinh_anh)))
                                                    <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach ?? 'Sách' }}">
                                                @else
                                                    <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="210" height="297" fill="#f0f0f0"></rect>
                                                        <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                                    </svg>
                                                @endif
                                            </div>
                                            <p class="book-title">{{ $book->ten_sach ?? 'Chưa có tên' }}</p>
                                            @if(isset($book->tac_gia) && !empty($book->tac_gia))
                                                <p class="book-author">{{ $book->tac_gia }}</p>
                                            @endif
                                            <div class="book-rating">
                                                <span class="stars">★★★★★</span>
                                            </div>
                                            @if(isset($book->gia) && $book->gia > 0)
                                                <p class="book-price">Chỉ từ {{ number_format($book->gia, 0, ',', '.') }}₫</p>
                                            @elseif(isset($book->so_luong_ban) && $book->so_luong_ban > 0)
                                                <p class="book-price">Đã bán: {{ $book->so_luong_ban }}</p>
                                            @endif
                                        </a>
                                    </div>
                                @empty
                                    @if(isset($featured_books) && $featured_books->count() > 0)
                                        @foreach($featured_books->take(6) as $book)
                                            <div class="book-item">
                                                <a href="{{ route('books.show', $book->id) }}" class="book-link">
                                                    <div class="book-cover">
                                                        @if(isset($book->hinh_anh) && !empty($book->hinh_anh) && file_exists(public_path('storage/'.$book->hinh_anh)))
                                                            <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach ?? 'Sách' }}">
                                                        @else
                                                            <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                                <rect width="210" height="297" fill="#f0f0f0"></rect>
                                                                <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <p class="book-title">{{ $book->ten_sach ?? 'Chưa có tên' }}</p>
                                                    @if(isset($book->tac_gia) && !empty($book->tac_gia))
                                                        <p class="book-author">{{ $book->tac_gia }}</p>
                                                    @endif
                                                    <div class="book-rating">
                                                        <span class="stars">★★★★★</span>
                                                    </div>
                                                    @if(isset($book->gia) && $book->gia > 0)
                                                        <p class="book-price">Chỉ từ {{ number_format($book->gia, 0, ',', '.') }}₫</p>
                                                    @elseif(isset($book->so_luong_ban) && $book->so_luong_ban > 0)
                                                        <p class="book-price">Đã bán: {{ $book->so_luong_ban }}</p>
                                                    @endif
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="right-column">
                    <!-- Phần Sách mua nhiều nhất và Sách xem nhiều nhất -->
                    <div class="bestbooks-container">
                        <!-- Phần Sách mua nhiều nhất -->
                        <div class="bestbooks-section">
                            <h2 class="section-title-bestbooks">Sách mua nhiều nhất</h2>
                            <div class="bestbooks-list">
                                @forelse($top_selling_books ?? [] as $index => $book)
                                    <div class="bestbook-item">
                                        <a href="{{ route('books.show', $book->id) }}" class="bestbook-link">
                                            <div class="bestbook-cover">
                                                @if(isset($book->hinh_anh) && $book->hinh_anh && file_exists(public_path('storage/'.$book->hinh_anh)))
                                                    <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach }}">
                                                @else
                                                    <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="210" height="297" fill="#f0f0f0"/>
                                                        <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="bestbook-info">
                                                <h3 class="bestbook-title">{{ Str::limit($book->ten_sach, 60) }}</h3>
                                                <p class="bestbook-purchases">{{ number_format($book->so_luong_ban ?? 0, 0, ',', '.') }} lượt mua</p>
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                    <div class="bestbook-item">
                                        <div class="bestbook-info">
                                            <p>Chưa có dữ liệu</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        
                        <!-- Phần Sách xem nhiều nhất -->
                        <div class="bestbooks-section">
                            <h2 class="section-title-bestbooks">Sách xem nhiều nhất</h2>
                            <div class="bestbooks-list">
                                @forelse($most_viewed_books ?? [] as $index => $book)
                                    <div class="bestbook-item">
                                        <a href="{{ route('books.show', $book->id) }}" class="bestbook-link">
                                            <div class="bestbook-cover">
                                                @if(isset($book->hinh_anh) && $book->hinh_anh && file_exists(public_path('storage/'.$book->hinh_anh)))
                                                    <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach }}">
                                                @else
                                                    <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="210" height="297" fill="#f0f0f0"/>
                                                        <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="bestbook-info">
                                                <h3 class="bestbook-title">{{ Str::limit($book->ten_sach, 60) }}</h3>
                                                <p class="bestbook-purchases">{{ number_format($book->so_luot_xem ?? 0, 0, ',', '.') }} lượt xem</p>
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                    <div class="bestbook-item">
                                        <div class="bestbook-info">
                                            <p>Chưa có dữ liệu</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Phần Sách nổi bật -->
            @if(isset($featured_books) && $featured_books->count() > 0)
            <div class="book-section">
                <div class="section-header">
                    <h2 class="section-title">Sách nổi bật</h2>
                    <a href="{{ route('books.public', ['category_id' => null]) }}" class="view-all-link">
                        Xem toàn bộ <span>→</span>
                    </a>
                </div>
                <div class="book-carousel-wrapper">
                    <div class="book-list sach-list-container" id="sach-noi-bat-carousel">
                        @foreach($featured_books->take(10) as $book)
                            <div class="book-item">
                                <a href="{{ route('books.show', $book->id) }}" class="book-link">
                                    <div class="book-cover">
                                        @if(isset($book->hinh_anh) && !empty($book->hinh_anh) && file_exists(public_path('storage/'.$book->hinh_anh)))
                                            <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach ?? 'Sách' }}">
                                        @else
                                            <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="210" height="297" fill="#f0f0f0"></rect>
                                                <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                            </svg>
                                        @endif
                                    </div>
                                    <p class="book-title">{{ $book->ten_sach ?? 'Chưa có tên' }}</p>
                                    @if(isset($book->tac_gia) && !empty($book->tac_gia))
                                        <p class="book-author">{{ $book->tac_gia }}</p>
                                    @endif
                                    <div class="book-rating">
                                        <span class="stars">★★★★★</span>
                                    </div>
                                    @if(isset($book->gia) && $book->gia > 0)
                                        <p class="book-price">Chỉ từ {{ number_format($book->gia, 0, ',', '.') }}₫</p>
                                    @elseif(isset($book->gia_ban) && $book->gia_ban > 0)
                                        <p class="book-price">Chỉ từ {{ number_format($book->gia_ban, 0, ',', '.') }}₫</p>
                                    @else
                                        <p class="book-price">Chỉ từ 120.000₫</p>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <button class="book-nav book-nav-prev" onclick="scrollCarousel('sach-noi-bat-carousel', -1)">
                        <span>‹</span>
                    </button>
                    <button class="book-nav book-nav-next" onclick="scrollCarousel('sach-noi-bat-carousel', 1)">
                        <span>›</span>
                    </button>
                </div>
            </div>
            @endif
            
            <!-- Phần Sách hay -->
            @if(isset($top_books) && $top_books->count() > 0)
            <div class="book-section">
                <div class="section-header">
                    <h2 class="section-title">Sách hay</h2>
                    <a href="{{ route('books.public', ['category_id' => null]) }}" class="view-all-link">
                        Xem toàn bộ <span>→</span>
                    </a>
                </div>
                <div class="book-carousel-wrapper">
                    <div class="book-list sach-list-container" id="sach-hay-carousel">
                        @foreach($top_books->take(6) as $book)
                            <div class="book-item">
                                <a href="{{ route('books.show', $book->id) }}" class="book-link">
                                    <div class="book-cover">
                                        @if(isset($book->hinh_anh) && !empty($book->hinh_anh) && file_exists(public_path('storage/'.$book->hinh_anh)))
                                            <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach ?? 'Sách' }}">
                                        @else
                                            <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="210" height="297" fill="#f0f0f0"></rect>
                                                <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                            </svg>
                                        @endif
                                    </div>
                                    <p class="book-title">{{ $book->ten_sach ?? 'Chưa có tên' }}</p>
                                    @if(isset($book->tac_gia) && !empty($book->tac_gia))
                                        <p class="book-author">{{ $book->tac_gia }}</p>
                                    @endif
                                    <div class="book-rating">
                                        <span class="stars">★★★★★</span>
                                    </div>
                                    @if(isset($book->gia) && $book->gia > 0)
                                        <p class="book-price">Chỉ từ {{ number_format($book->gia, 0, ',', '.') }}₫</p>
                                    @elseif(isset($book->gia_ban) && $book->gia_ban > 0)
                                        <p class="book-price">Chỉ từ {{ number_format($book->gia_ban, 0, ',', '.') }}₫</p>
                                    @else
                                        <p class="book-price">Chỉ từ 120.000₫</p>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <button class="book-nav book-nav-prev" onclick="scrollCarousel('sach-hay-carousel', -1)">
                        <span>‹</span>
                    </button>
                    <button class="book-nav book-nav-next" onclick="scrollCarousel('sach-hay-carousel', 1)">
                        <span>›</span>
                    </button>
                </div>
            </div>
            @endif
            
            <!-- Phần Tuyển tập hay nhất -->
            @if(isset($best_collections) && $best_collections->count() > 0)
            <div class="book-section best-collection-section">
                <div class="section-header">
                    <h2 class="section-title">Tuyển tập hay nhất</h2>
                    <a href="{{ route('books.public', ['category_id' => null]) }}" class="view-all-link">
                        Xem toàn bộ <span>→</span>
                    </a>
                </div>
                <div class="book-carousel-wrapper">
                    <div class="book-list sach-list-container" id="tuyen-tap-hay-nhat-carousel">
                        @foreach($best_collections->take(6) as $book)
                            <div class="book-item collection-item">
                                <a href="{{ route('books.show', $book->id) }}" class="book-link">
                                    <div class="book-cover">
                                        @if(isset($book->hinh_anh) && !empty($book->hinh_anh) && file_exists(public_path('storage/'.$book->hinh_anh)))
                                            <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach ?? 'Sách' }}">
                                        @else
                                            <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                <rect width="210" height="297" fill="#f0f0f0"></rect>
                                                <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="collection-item-content">
                                        <h3 class="collection-title">{{ $book->ten_sach ?? 'Chưa có tên' }}</h3>
                                        <p class="collection-description">{{ Str::limit($book->mo_ta ?? 'Đang cập nhật mô tả...', 100) }}</p>
                                        <div class="book-rating">
                                            <span class="stars">★★★★★</span>
                                        </div>
                                        <div class="collection-price">
                                            @php
                                                $gia_ban = $book->gia_ban ?? $book->gia ?? 0;
                                                $gia_goc = isset($book->gia_goc) && $book->gia_goc > $gia_ban ? $book->gia_goc : null;
                                            @endphp
                                            @if($gia_goc)
                                                <span class="collection-price-current">{{ number_format($gia_ban, 0, ',', '.') }}₫</span>
                                                <span class="collection-price-old">{{ number_format($gia_goc, 0, ',', '.') }}₫</span>
                                            @elseif($gia_ban > 0)
                                                <span class="collection-price-current">{{ number_format($gia_ban, 0, ',', '.') }}₫</span>
                                            @else
                                                <span class="collection-price-current">Liên hệ</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <button class="book-nav book-nav-prev collection-nav-prev" onclick="scrollCarousel('tuyen-tap-hay-nhat-carousel', -1)">
                        <span>‹</span>
                    </button>
                    <button class="book-nav book-nav-next collection-nav-next" onclick="scrollCarousel('tuyen-tap-hay-nhat-carousel', 1)">
                        <span>›</span>
                    </button>
                </div>
            </div>
            @endif
            
            <!-- Phần Chủ đề -->
            @if(isset($categoriesTop) && $categoriesTop->count() > 0)
            <div class="book-section">
                <div class="section-header">
                    <h2 class="section-title">Chủ đề</h2>
                    <a href="{{ route('categories.index') }}" class="view-all-link">
                        Xem toàn bộ <span>→</span>
                    </a>
                </div>
                <div class="topics-grid">
                    @foreach($categoriesTop as $category)
                        <a href="{{ route('books.public', ['category_id' => $category->id]) }}" class="topic-item">
                            <div class="topic-count">{{ number_format($category->books_count ?? 0, 0, ',', '.') }}</div>
                            <div class="topic-name">{{ $category->ten_the_loai }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Phần Điểm sách -->
            <div class="book-section diem-sach-section">
                <div class="section-header">
                    <h2 class="section-title">Điểm sách</h2>
                    <a href="{{ route('books.public', ['category_id' => null]) }}" class="view-all-link">
                        Xem toàn bộ <span>→</span>
                    </a>
                </div>
                @php
                    // Tìm ảnh cho diem sach banners từ admin
                    $diemSachImages = [];
                    $bannerDir = public_path('storage/banners');
                    $extensions = ['jpg', 'jpeg', 'png', 'webp'];
                    
                    // Tìm ảnh diem-sach-featured
                    $diemSachImages['featured'] = null;
                    if(file_exists($bannerDir)) {
                        foreach($extensions as $ext) {
                            $path = $bannerDir . '/diem-sach-featured.' . $ext;
                            if(file_exists($path)) {
                                $diemSachImages['featured'] = asset('storage/banners/diem-sach-featured.' . $ext);
                                break;
                            }
                        }
                    }
                    
                    // Tìm ảnh diem-sach-1, diem-sach-2, diem-sach-3
                    for($i = 1; $i <= 3; $i++) {
                        $diemSachImages[$i] = null;
                        if(file_exists($bannerDir)) {
                            foreach($extensions as $ext) {
                                $path = $bannerDir . '/diem-sach-' . $i . '.' . $ext;
                                if(file_exists($path)) {
                                    $diemSachImages[$i] = asset('storage/banners/diem-sach-' . $i . '.' . $ext);
                                    break;
                                }
                            }
                        }
                    }
                @endphp
                <div class="diem-sach-content">
                    <!-- Bên trái: Slider ảnh lớn với nút điều hướng -->
                    <div class="diem-sach-left">
                        @php
                            $slides = [];
                            // Ưu tiên sử dụng Documents (Văn bản luật) cho Điểm sách
                            if(isset($documents) && $documents->count() > 0) {
                                foreach($documents->take(4) as $idx => $doc) {
                                    $img = null;
                                    if($diemSachImages['featured'] && $idx === 0) {
                                        $img = $diemSachImages['featured'];
                                    } elseif(isset($diemSachImages[$idx]) && $diemSachImages[$idx]) {
                                        $img = $diemSachImages[$idx];
                                    } elseif(isset($doc->image) && !empty($doc->image) && file_exists(public_path('storage/'.$doc->image))) {
                                        $img = asset('storage/'.$doc->image);
                                    }

                                    $slides[] = [
                                        'link' => route('documents.show', $doc->id),
                                        'img' => $img,
                                        'title' => $doc->title,
                                        'date' => $doc->published_date ? $doc->published_date->format('d/m/Y') : ($doc->created_at ? $doc->created_at->format('d/m/Y') : 'N/A'),
                                        'desc' => $doc->description ?? 'Đang cập nhật mô tả...'
                                    ];
                                }
                            } else {
                                // Fallback: Nếu không có documents, dùng sách
                                if(isset($diem_sach_featured) && $diem_sach_featured) {
                                    $img = null;
                                    if($diemSachImages['featured']) {
                                        $img = $diemSachImages['featured'];
                                    } elseif(isset($diem_sach_featured->hinh_anh) && !empty($diem_sach_featured->hinh_anh) && file_exists(public_path('storage/'.$diem_sach_featured->hinh_anh))) {
                                        $img = asset('storage/'.$diem_sach_featured->hinh_anh);
                                    }
                                    $slides[] = [
                                        'link' => route('books.show', $diem_sach_featured->id),
                                        'img' => $img,
                                        'title' => $diem_sach_featured->ten_sach,
                                        'date' => $diem_sach_featured->created_at ? $diem_sach_featured->created_at->format('d/m/Y') : 'N/A',
                                        'desc' => $diem_sach_featured->mo_ta ?? 'Đang cập nhật mô tả...'
                                    ];
                                }

                                // Thêm tối đa 3 slide từ danh sách sách
                                $diemSachList = isset($diem_sach_list) ? $diem_sach_list->values() : collect();
                                for($i = 1; $i <= 3; $i++) {
                                    $book = $diemSachList->get($i - 1);
                                    $img = $diemSachImages[$i] ?? null;
                                    if(!$img && $book && isset($book->hinh_anh) && !empty($book->hinh_anh) && file_exists(public_path('storage/'.$book->hinh_anh))) {
                                        $img = asset('storage/'.$book->hinh_anh);
                                    }
                                    if($img || $book) {
                                        $slides[] = [
                                            'link' => $book ? route('books.show', $book->id) : 'javascript:void(0)',
                                            'img' => $img,
                                            'title' => $book->ten_sach ?? ('Điểm sách '.$i),
                                            'date' => $book && $book->created_at ? $book->created_at->format('d/m/Y') : 'N/A',
                                            'desc' => $book->mo_ta ?? 'Đang cập nhật mô tả...'
                                        ];
                                    }
                                }
                            }
                        @endphp
                        <div class="diem-sach-featured-wrapper">
                            <div class="diem-sach-featured-slider" id="diemSachSlider">
                                @foreach($slides as $idx => $s)
                                    <div class="diem-sach-featured-slide {{ $idx === 0 ? 'active' : '' }}">
                                        <a href="{{ $s['link'] }}" class="diem-sach-featured-link" @if($s['link']==='javascript:void(0)') style="cursor: default;" @endif>
                                            <div class="diem-sach-featured-cover">
                                                @if(!empty($s['img']))
                                                    <img src="{{ $s['img'] }}" alt="{{ $s['title'] }}">
                                                @else
                                                    <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="210" height="297" fill="#f0f0f0"/>
                                                        <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="diem-sach-featured-info">
                                                <p class="diem-sach-featured-date">{{ $s['date'] }}</p>
                                                <h3 class="diem-sach-featured-title">
                                                    <span class="diem-sach-title-icon">📄</span>
                                                    {{ $s['title'] }}
                                                </h3>
                                                <p class="diem-sach-featured-description">{{ Str::limit($s['desc'], 200) }}</p>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            @if(count($slides) > 1)
                                <button class="diem-sach-nav diem-sach-nav-prev" data-target="diemSachSlider" aria-label="Previous">‹</button>
                                <button class="diem-sach-nav diem-sach-nav-next" data-target="diemSachSlider" aria-label="Next">›</button>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Bên phải: Danh sách 3 item nhỏ -->
                    <div class="diem-sach-right">
                        <div class="diem-sach-list">
                            @php
                                // Ưu tiên documents, fallback sang sách
                                $rightItems = collect();
                                if(isset($documents) && $documents->count() > 0) {
                                    // Bỏ qua item đầu tiên (đã dùng cho slider), lấy 3 item tiếp theo
                                    $rightItems = $documents->skip(1)->take(3);
                                } else {
                                    $rightItems = isset($diem_sach_list) ? $diem_sach_list->values()->take(3) : collect();
                                }
                            @endphp
                            @for($i = 0; $i < 3; $i++)
                                @php
                                    $item = $rightItems->get($i);
                                    $isDocument = $item && isset($item->title); // Document có 'title', Book có 'ten_sach'
                                @endphp
                                <div class="diem-sach-item">
                                    @if($item)
                                        <a href="{{ $isDocument ? route('documents.show', $item->id) : route('books.show', $item->id) }}" class="diem-sach-item-link">
                                    @else
                                        <div class="diem-sach-item-link" style="cursor: default;">
                                    @endif
                                        <div class="diem-sach-item-cover">
                                            @php
                                                $imgKey = $i + 1;
                                                $hasAdminImage = isset($diemSachImages[$imgKey]) && $diemSachImages[$imgKey];
                                                $itemImage = null;
                                                if($item) {
                                                    if($isDocument && isset($item->image) && !empty($item->image)) {
                                                        $itemImage = asset('storage/'.$item->image);
                                                    } elseif(!$isDocument && isset($item->hinh_anh) && !empty($item->hinh_anh)) {
                                                        $itemImage = asset('storage/'.$item->hinh_anh);
                                                    }
                                                }
                                            @endphp
                                            @if($hasAdminImage)
                                                <img src="{{ $diemSachImages[$imgKey] }}" alt="{{ $isDocument ? ($item->title ?? 'Điểm sách') : ($item->ten_sach ?? 'Điểm sách') }}">
                                            @elseif($itemImage && file_exists(public_path('storage/'.($isDocument ? $item->image : $item->hinh_anh))))
                                                <img src="{{ $itemImage }}" alt="{{ $isDocument ? $item->title : $item->ten_sach }}">
                                            @else
                                                <svg viewBox="0 0 210 297" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="210" height="297" fill="#f0f0f0"/>
                                                    <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="16" fill="#999">📚</text>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="diem-sach-item-info">
                                            <div class="diem-sach-item-header">
                                                <span class="diem-sach-item-icon">📄</span>
                                                <h4 class="diem-sach-item-title">
                                                    {{ $item ? ($isDocument ? $item->title : $item->ten_sach) : ('Điểm sách ' . ($i + 1)) }}
                                                </h4>
                                            </div>
                                            <p class="diem-sach-item-description">
                                                {{ $item ? Str::limit($isDocument ? ($item->description ?? 'Đang cập nhật...') : ($item->mo_ta ?? 'Đang cập nhật...'), 100) : 'Đang cập nhật mô tả...' }}
                                            </p>
                                            <p class="diem-sach-item-date">
                                                @if($item)
                                                    {{ $isDocument ? ($item->published_date ? $item->published_date->format('d/m/Y') : ($item->created_at ? $item->created_at->format('d/m/Y') : 'N/A')) : ($item->created_at ? $item->created_at->format('d/m/Y') : 'N/A') }}
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </div>
                                    @if($item)
                                        </a>
                                    @else
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Phần Tin tức -->
            <div class="book-section news-section">
                <div class="section-header">
                    <h2 class="section-title">Tin tức</h2>
                    <a href="#" class="view-all-link" onclick="event.preventDefault(); alert('Trang tin tức đang được phát triển');">
                        Xem toàn bộ <span>→</span>
                    </a>
                </div>
                @php
                    // Tìm ảnh cho news banners từ admin
                    $newsImages = [];
                    $bannerDir = public_path('storage/banners');
                    $extensions = ['jpg', 'jpeg', 'png', 'webp'];
                    
                    // Tìm ảnh news-featured
                    $newsImages['featured'] = null;
                    if(file_exists($bannerDir)) {
                        foreach($extensions as $ext) {
                            $path = $bannerDir . '/news-featured.' . $ext;
                            if(file_exists($path)) {
                                $newsImages['featured'] = asset('storage/banners/news-featured.' . $ext);
                                break;
                            }
                        }
                    }
                    
                    // Tìm ảnh news-1, news-2, news-3
                    for($i = 1; $i <= 3; $i++) {
                        $newsImages[$i] = null;
                        if(file_exists($bannerDir)) {
                            foreach($extensions as $ext) {
                                $path = $bannerDir . '/news-' . $i . '.' . $ext;
                                if(file_exists($path)) {
                                    $newsImages[$i] = asset('storage/banners/news-' . $i . '.' . $ext);
                                    break;
                                }
                            }
                        }
                    }
                @endphp
                @php
                    // Tạo slides cho tin tức
                    $newsSlides = [];

                    // Slide 1: tin tức nổi bật
                    if(isset($featuredNews) && $featuredNews) {
                        $img = null;
                        if($newsImages['featured']) {
                            $img = $newsImages['featured'];
                        } elseif(isset($featuredNews->image) && !empty($featuredNews->image) && file_exists(public_path('storage/'.$featuredNews->image))) {
                            $img = asset('storage/'.$featuredNews->image);
                        }
                        $newsSlides[] = [
                            'link' => route('documents.show', $featuredNews->id),
                            'img' => $img,
                            'title' => $featuredNews->title,
                            'date' => $featuredNews->published_date ? $featuredNews->published_date->format('d/m/Y') : '',
                            'desc' => $featuredNews->description ?? '',
                            'id' => $featuredNews->id
                        ];
                    } elseif($newsImages['featured']) {
                        $newsSlides[] = [
                            'link' => '#',
                            'img' => $newsImages['featured'],
                            'title' => 'Tin tức nổi bật',
                            'date' => '',
                            'desc' => 'Đang cập nhật...',
                            'id' => null
                        ];
                    }

                    // Thêm tối đa 3 slide từ danh sách bên phải
                    $otherNewsList = isset($otherNews) ? $otherNews->values() : collect();
                    for($i = 1; $i <= 3; $i++) {
                        $item = $otherNewsList->get($i - 1);
                        $img = $newsImages[$i] ?? null;
                        if(!$img && $item && isset($item->image) && !empty($item->image) && file_exists(public_path('storage/'.$item->image))) {
                            $img = asset('storage/'.$item->image);
                        }
                        if($img || $item) {
                            $newsSlides[] = [
                                'link' => $item ? route('documents.show', $item->id) : '#',
                                'img' => $img,
                                'title' => $item->title ?? ('Tin tức '.$i),
                                'date' => $item && $item->published_date ? (is_string($item->published_date) ? \Carbon\Carbon::parse($item->published_date)->format('d/m/Y') : $item->published_date->format('d/m/Y')) : '',
                                'desc' => $item->description ?? 'Đang cập nhật...',
                                'id' => $item->id ?? null
                            ];
                        }
                    }
                @endphp

                <div class="news-content">
                    <!-- Tin tức nổi bật bên trái với slider -->
                    <div class="news-featured">
                        <div class="news-featured-wrapper">
                            <div class="news-featured-slider" id="newsSlider">
                                @foreach($newsSlides as $idx => $slide)
                                    <div class="news-featured-slide {{ $idx === 0 ? 'active' : '' }}">
                                        <a href="{{ $slide['link'] }}" class="news-featured-link">
                                            <div class="news-featured-image">
                                                @if(!empty($slide['img']))
                                                    <img src="{{ $slide['img'] }}" alt="{{ $slide['title'] }}">
                                                @else
                                                    <svg viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="400" height="300" fill="#f0f0f0"/>
                                                        <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="40" fill="#999">📰</text>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div class="news-featured-info">
                                                <p class="news-date">{{ $slide['date'] }}</p>
                                                <h3 class="news-title">{{ $slide['title'] }}</h3>
                                                <p class="news-description">{{ Str::limit($slide['desc'], 150) }}</p>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            @if(count($newsSlides) > 1)
                                <button class="news-nav news-nav-prev" aria-label="Previous">‹</button>
                                <button class="news-nav news-nav-next" aria-label="Next">›</button>
                            @endif
                        </div>
                    </div>
                    <!-- 3 tin tức nhỏ bên phải -->
                    <div class="news-list">
                        @php
                            // Luôn hiển thị đủ 3 tin tức nhỏ
                            $otherNewsList = isset($otherNews) ? $otherNews->values() : collect();
                        @endphp
                        @for($i = 1; $i <= 3; $i++)
                            @php
                                $item = $otherNewsList->get($i - 1);
                            @endphp
                            <div class="news-item">
                                @if($item)
                                    <a href="{{ route('documents.show', $item->id) }}" class="news-item-link">
                                @else
                                    <div class="news-item-link" style="cursor: default;">
                                @endif
                                    <div class="news-item-image">
                                        @if(isset($newsImages[$i]) && $newsImages[$i])
                                            <img src="{{ $newsImages[$i] }}" alt="{{ $item->title ?? 'Tin tức ' . $i }}">
                                        @elseif($item && $item->image && file_exists(public_path('storage/'.$item->image)))
                                            <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title ?? 'Tin tức ' . $i }}">
                                        @else
                                            <div class="news-placeholder-small">
                                                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="100" height="100" fill="#f0f0f0"/>
                                                    <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="30" fill="#999">📰</text>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="news-item-info">
                                        @if($item)
                                            <p class="news-date-small">{{ $item->published_date ? (is_string($item->published_date) ? \Carbon\Carbon::parse($item->published_date)->format('d/m/Y') : $item->published_date->format('d/m/Y')) : '' }}</p>
                                            <h4 class="news-title-small">{{ Str::limit($item->title ?? 'Tin tức ' . $i, 80) }}</h4>
                                            <p class="news-description-small">{{ Str::limit(($item->description ?? 'Đang cập nhật mô tả...'), 80) }}</p>
                                        @else
                                            <p class="news-date-small"></p>
                                            <h4 class="news-title-small">Chưa có tin tức</h4>
                                            <p class="news-description-small"></p>
                                        @endif
                                    </div>
                                @if($item)
                                    </a>
                                @else
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
            
            <!-- Phần Trân trọng phục vụ -->
            <div class="book-section service-section">
                <h2 class="section-title text-center" style="margin-bottom: 30px;">Trân trọng phục vụ</h2>
                @php
                    // Tìm ảnh cho service banners từ admin
                    $serviceImages = [];
                    $bannerDir = public_path('storage/banners');
                    $extensions = ['jpg', 'jpeg', 'png', 'webp'];
                    $serviceConfigs = [
                        1 => 'Bộ Xây dựng',
                        2 => 'Viện nghiên cứu',
                        3 => 'Doanh nghiệp/ Tổ chức',
                        4 => 'Nhà sách',
                        5 => 'Quản lý thư viện',
                        6 => 'Sinh viên',
                        7 => 'Tác giả'
                    ];
                    
                    foreach($serviceConfigs as $i => $title) {
                        $serviceImages[$i] = null;
                        if(file_exists($bannerDir)) {
                            foreach($extensions as $ext) {
                                $path = $bannerDir . '/service-' . $i . '.' . $ext;
                                if(file_exists($path)) {
                                    $serviceImages[$i] = asset('storage/banners/service-' . $i . '.' . $ext);
                                    break;
                                }
                            }
                        }
                    }
                @endphp
                <div class="service-grid">
                    <!-- Tile 1: Bộ Xây dựng -->
                    <a href="{{ route('books.public') }}" class="service-item">
                        <div class="service-image">
                            @if($serviceImages[1])
                                <img src="{{ $serviceImages[1] }}" alt="Bộ Xây dựng">
                            @else
                                <svg viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="300" height="200" fill="#e3f2fd"/>
                                    <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="20" fill="#1976d2">🏢</text>
                                </svg>
                            @endif
                        </div>
                        <div class="service-label">Bộ Xây dựng</div>
                    </a>

                    <!-- Tile 2: Viện nghiên cứu -->
                    <a href="{{ route('books.public') }}" class="service-item">
                        <div class="service-image">
                            @if($serviceImages[2])
                                <img src="{{ $serviceImages[2] }}" alt="Viện nghiên cứu">
                            @else
                                <svg viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="300" height="200" fill="#f3e5f5"/>
                                    <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="20" fill="#9c27b0">🔬</text>
                                </svg>
                            @endif
                        </div>
                        <div class="service-label">Viện nghiên cứu</div>
                    </a>

                    <!-- Tile 3: Doanh nghiệp/ Tổ chức -->
                    <a href="{{ route('books.public') }}" class="service-item">
                        <div class="service-image">
                            @if($serviceImages[3])
                                <img src="{{ $serviceImages[3] }}" alt="Doanh nghiệp/ Tổ chức">
                            @else
                                <svg viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="300" height="200" fill="#fff3e0"/>
                                    <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="20" fill="#f57c00">🏢</text>
                                </svg>
                            @endif
                        </div>
                        <div class="service-label">Doanh nghiệp/ Tổ chức</div>
                    </a>

                    <!-- Tile 4: Nhà sách -->
                    <a href="{{ route('books.public') }}" class="service-item">
                        <div class="service-image">
                            @if($serviceImages[4])
                                <img src="{{ $serviceImages[4] }}" alt="Nhà sách">
                            @else
                                <svg viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="300" height="200" fill="#e8f5e9"/>
                                    <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="20" fill="#4caf50">📚</text>
                                </svg>
                            @endif
                        </div>
                        <div class="service-label">Nhà sách</div>
                    </a>

                    <!-- Tile 5: Quản lý thư viện -->
                    <a href="{{ route('books.public') }}" class="service-item">
                        <div class="service-image">
                            @if($serviceImages[5])
                                <img src="{{ $serviceImages[5] }}" alt="Quản lý thư viện">
                            @else
                                <svg viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="300" height="200" fill="#fce4ec"/>
                                    <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="20" fill="#c2185b">📖</text>
                                </svg>
                            @endif
                        </div>
                        <div class="service-label">Quản lý thư viện</div>
                    </a>

                    <!-- Tile 6: Sinh viên -->
                    <a href="{{ route('books.public') }}" class="service-item">
                        <div class="service-image">
                            @if($serviceImages[6])
                                <img src="{{ $serviceImages[6] }}" alt="Sinh viên">
                            @else
                                <svg viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="300" height="200" fill="#e1f5fe"/>
                                    <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="20" fill="#0289d1">👨‍🎓</text>
                                </svg>
                            @endif
                        </div>
                        <div class="service-label">Sinh viên</div>
                    </a>

                    <!-- Tile 7: Tác giả -->
                    <a href="{{ route('books.public') }}" class="service-item">
                        <div class="service-image">
                            @if($serviceImages[7])
                                <img src="{{ $serviceImages[7] }}" alt="Tác giả">
                            @else
                                <svg viewBox="0 0 300 200" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="300" height="200" fill="#fff9c4"/>
                                    <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="20" fill="#f9a825">✍️</text>
                                </svg>
                            @endif
                        </div>
                        <div class="service-label">Tác giả</div>
                    </a>
                </div>
            </div>
            
            <!-- Phần Tác giả -->
            <div class="book-section author-section">
                <div class="section-header">
                    <h2 class="section-title">Tác giả</h2>
                    <a href="#" class="view-all-link" onclick="event.preventDefault(); alert('Trang danh sách tác giả đang được phát triển');">
                        Xem toàn bộ <span>→</span>
                    </a>
                </div>
                @php
                    // Tìm ảnh cho author banners từ admin
                    $authorImages = [];
                    $bannerDir = public_path('storage/banners');
                    $extensions = ['jpg', 'jpeg', 'png', 'webp'];
                    
                    for($i = 1; $i <= 5; $i++) {
                        $authorImages[$i] = null;
                        if(file_exists($bannerDir)) {
                            foreach($extensions as $ext) {
                                $path = $bannerDir . '/author-' . $i . '.' . $ext;
                                if(file_exists($path)) {
                                    $authorImages[$i] = asset('storage/banners/author-' . $i . '.' . $ext);
                                    break;
                                }
                            }
                        }
                    }
                @endphp
                <div class="authors-list">
                    @forelse($authors ?? [] as $index => $author)
                        <div class="author-item">
                            <a href="#" class="author-link" onclick="event.preventDefault(); alert('Trang chi tiết tác giả đang được phát triển');">
                                <div class="author-avatar">
                                    @if(isset($author->hinh_anh) && !empty($author->hinh_anh) && file_exists(public_path('storage/'.$author->hinh_anh)))
                                        <img src="{{ asset('storage/'.$author->hinh_anh) }}" alt="{{ $author->ten_tac_gia }}" class="author-image">
                                    @elseif(isset($authorImages[$index + 1]) && $authorImages[$index + 1])
                                        <img src="{{ $authorImages[$index + 1] }}" alt="{{ $author->ten_tac_gia ?? 'Tác giả' }}" class="author-image">
                                    @else
                                        <div class="author-placeholder">
                                            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="50" cy="50" r="50" fill="#e0e0e0"/>
                                                <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="40" fill="#999">👤</text>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <p class="author-name">{{ $author->ten_tac_gia ?? 'Tác giả' }}</p>
                            </a>
                        </div>
                    @empty
                        @for($i = 1; $i <= 5; $i++)
                            <div class="author-item">
                                <div class="author-placeholder-wrapper">
                                    <div class="author-avatar">
                                        @if(isset($authorImages[$i]) && $authorImages[$i])
                                            <img src="{{ $authorImages[$i] }}" alt="Tác giả {{ $i }}" class="author-image">
                                        @else
                                            <div class="author-placeholder">
                                                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="50" cy="50" r="50" fill="#e0e0e0"/>
                                                    <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="40" fill="#999">👤</text>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="author-name">Chưa có tác giả</p>
                                </div>
                            </div>
                        @endfor
                    @endforelse
                </div>
            </div>
            
            <!-- Phần Liên hệ - Hợp tác -->
            <div class="book-section contact-section">
                <div class="section-header">
                    <h2 class="section-title">Liên hệ - Hợp tác</h2>
                </div>
                @php
                    // Tìm ảnh cho contact banners từ admin
                    $contactImages = [];
                    $bannerDir = public_path('storage/banners');
                    $extensions = ['jpg', 'jpeg', 'png', 'webp'];
                    $contactConfigs = [
                        1 => 'Liên kết xuất bản',
                        2 => 'Báo giá sách sỉ',
                        3 => 'Dịch vụ in',
                        4 => 'Liên hệ hỗ trợ'
                    ];
                    
                    foreach($contactConfigs as $i => $title) {
                        $contactImages[$i] = null;
                        if(file_exists($bannerDir)) {
                            foreach($extensions as $ext) {
                                $path = $bannerDir . '/contact-' . $i . '.' . $ext;
                                if(file_exists($path)) {
                                    $contactImages[$i] = asset('storage/banners/contact-' . $i . '.' . $ext);
                                    break;
                                }
                            }
                        }
                    }
                @endphp
                <div class="contact-list">
                    <div class="contact-item">
                        <a href="#" class="contact-link" onclick="event.preventDefault(); alert('Trang liên kết xuất bản đang được phát triển');">
                            <div class="contact-image-wrapper">
                                <div class="contact-image">
                                    @if($contactImages[1])
                                        <img src="{{ $contactImages[1] }}" alt="Liên kết xuất bản">
                                    @else
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="200" height="200" fill="#f5f5f5"/>
                                            <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="40" fill="#999">📝</text>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <p class="contact-label">Liên kết xuất bản</p>
                        </a>
                    </div>
                    
                    <div class="contact-item">
                        <a href="#" class="contact-link" onclick="event.preventDefault(); alert('Trang báo giá sách sỉ đang được phát triển');">
                            <div class="contact-image-wrapper">
                                <div class="contact-image">
                                    @if($contactImages[2])
                                        <img src="{{ $contactImages[2] }}" alt="Báo giá sách sỉ">
                                    @else
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="200" height="200" fill="#f5f5f5"/>
                                            <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="40" fill="#999">💰</text>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <p class="contact-label">Báo giá sách sỉ</p>
                        </a>
                    </div>
                    
                    <div class="contact-item">
                        <a href="#" class="contact-link" onclick="event.preventDefault(); alert('Trang dịch vụ in đang được phát triển');">
                            <div class="contact-image-wrapper">
                                <div class="contact-image">
                                    @if($contactImages[3])
                                        <img src="{{ $contactImages[3] }}" alt="Dịch vụ in">
                                    @else
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="200" height="200" fill="#f5f5f5"/>
                                            <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="40" fill="#999">🖨️</text>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <p class="contact-label">Dịch vụ in</p>
                        </a>
                    </div>
                    
                    <div class="contact-item">
                        <a href="#" class="contact-link" onclick="event.preventDefault(); alert('Trang liên hệ hỗ trợ đang được phát triển');">
                            <div class="contact-image-wrapper">
                                <div class="contact-image">
                                    @if($contactImages[4])
                                        <img src="{{ $contactImages[4] }}" alt="Liên hệ hỗ trợ">
                                    @else
                                        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                                            <rect width="200" height="200" fill="#f5f5f5"/>
                                            <text x="50%" y="50%" text-anchor="middle" dominant-baseline="middle" font-size="40" fill="#999">📞</text>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <p class="contact-label">Liên hệ hỗ trợ</p>
                        </a>
                    </div>
                </div>
            </div>
        
        </div>
    </main>
        
    <script>
        // Tự động scroll về đầu trang khi reload
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        
        // Scroll về đầu trang khi trang load
        window.addEventListener('load', () => {
            window.scrollTo(0, 0);
        });
        
        // Scroll về đầu trang khi DOM ready (đảm bảo scroll ngay cả khi load chậm)
        document.addEventListener('DOMContentLoaded', () => {
            window.scrollTo(0, 0);
            
            // Khởi tạo carousel slides sau khi DOM đã sẵn sàng
            let currentSlideIndex = 0;
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.dot');
            let totalSlides = slides.length;
            
            function showSlide(index) {
                // Ẩn tất cả slides
                slides.forEach(slide => slide.classList.remove('active'));
                if(dots.length > 0) {
                    dots.forEach(dot => dot.classList.remove('active'));
                }
                
                // Đảm bảo index trong phạm vi hợp lệ
                if (index >= totalSlides) {
                    currentSlideIndex = 0;
                } else if (index < 0) {
                    currentSlideIndex = totalSlides - 1;
                } else {
                    currentSlideIndex = index;
                }
                
                // Hiển thị slide hiện tại
                if(slides[currentSlideIndex]) {
                    slides[currentSlideIndex].classList.add('active');
                }
                if(dots[currentSlideIndex]) {
                    dots[currentSlideIndex].classList.add('active');
                }
            }
            
            function changeSlide(direction) {
                showSlide(currentSlideIndex + direction);
            }
            
            function currentSlide(index) {
                showSlide(index - 1);
            }
            
            // Tự động chuyển slide mỗi 5 giây
            if(totalSlides > 1) {
                setInterval(() => {
                    changeSlide(1);
                }, 5000);
            }
            
            // Khởi tạo slide đầu tiên
            if(totalSlides > 0) {
                showSlide(0);
            }
            
            // Hiển thị/ẩn nút navigation khi hover
            const bookCarouselWrappers = document.querySelectorAll('.book-carousel-wrapper');
            bookCarouselWrappers.forEach(wrapper => {
                const navButtons = wrapper.querySelectorAll('.book-nav');
                wrapper.addEventListener('mouseenter', () => {
                    navButtons.forEach(btn => {
                        btn.style.opacity = '1';
                        btn.style.pointerEvents = 'all';
                    });
                });
                wrapper.addEventListener('mouseleave', () => {
                    navButtons.forEach(btn => {
                        btn.style.opacity = '0';
                        btn.style.pointerEvents = 'none';
                    });
                });
            });

            // Điểm sách slider functionality
            const diemSachSlider = document.getElementById('diemSachSlider');
            console.log('Điểm sách slider element:', diemSachSlider);

            if (diemSachSlider) {
                let currentDiemSachIndex = 0;
                const diemSachSlides = diemSachSlider.querySelectorAll('.diem-sach-featured-slide');
                const totalDiemSachSlides = diemSachSlides.length;

                console.log('Total Điểm sách slides:', totalDiemSachSlides);
                console.log('Slides:', diemSachSlides);

                function showDiemSachSlide(index) {
                    console.log('Showing slide:', index);
                    // Ẩn tất cả slides
                    diemSachSlides.forEach(slide => slide.classList.remove('active'));

                    // Đảm bảo index trong phạm vi hợp lệ
                    if (index >= totalDiemSachSlides) {
                        currentDiemSachIndex = 0;
                    } else if (index < 0) {
                        currentDiemSachIndex = totalDiemSachSlides - 1;
                    } else {
                        currentDiemSachIndex = index;
                    }

                    // Hiển thị slide hiện tại
                    if (diemSachSlides[currentDiemSachIndex]) {
                        diemSachSlides[currentDiemSachIndex].classList.add('active');
                        console.log('Active slide:', currentDiemSachIndex);
                    }
                }

                function changeDiemSachSlide(direction) {
                    console.log('Changing slide, direction:', direction);
                    showDiemSachSlide(currentDiemSachIndex + direction);
                }

                // Gắn sự kiện cho nút navigation
                const prevBtn = document.querySelector('.diem-sach-nav-prev');
                const nextBtn = document.querySelector('.diem-sach-nav-next');

                console.log('Prev button:', prevBtn);
                console.log('Next button:', nextBtn);

                if (prevBtn) {
                    prevBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        console.log('Prev button clicked');
                        changeDiemSachSlide(-1);
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        console.log('Next button clicked');
                        changeDiemSachSlide(1);
                    });
                }

                // Tự động chuyển slide mỗi 5 giây
                if (totalDiemSachSlides > 1) {
                    setInterval(() => {
                        changeDiemSachSlide(1);
                    }, 5000);
                }

                // Khởi tạo slide đầu tiên
                if (totalDiemSachSlides > 0) {
                    showDiemSachSlide(0);
                }
            } else {
                console.log('Điểm sách slider not found!');
            }

            // Tin tức slider functionality
            const newsSlider = document.getElementById('newsSlider');
            console.log('Tin tức slider element:', newsSlider);

            if (newsSlider) {
                let currentNewsIndex = 0;
                const newsSlides = newsSlider.querySelectorAll('.news-featured-slide');
                const totalNewsSlides = newsSlides.length;

                console.log('Total Tin tức slides:', totalNewsSlides);
                console.log('Slides:', newsSlides);

                function showNewsSlide(index) {
                    console.log('Showing news slide:', index);
                    // Ẩn tất cả slides
                    newsSlides.forEach(slide => slide.classList.remove('active'));

                    // Đảm bảo index trong phạm vi hợp lệ
                    if (index >= totalNewsSlides) {
                        currentNewsIndex = 0;
                    } else if (index < 0) {
                        currentNewsIndex = totalNewsSlides - 1;
                    } else {
                        currentNewsIndex = index;
                    }

                    // Hiển thị slide hiện tại
                    if (newsSlides[currentNewsIndex]) {
                        newsSlides[currentNewsIndex].classList.add('active');
                        console.log('Active news slide:', currentNewsIndex);
                    }
                }

                function changeNewsSlide(direction) {
                    console.log('Changing news slide, direction:', direction);
                    showNewsSlide(currentNewsIndex + direction);
                }

                // Gắn sự kiện cho nút navigation
                const prevBtn = document.querySelector('.news-nav-prev');
                const nextBtn = document.querySelector('.news-nav-next');

                console.log('News Prev button:', prevBtn);
                console.log('News Next button:', nextBtn);

                if (prevBtn) {
                    prevBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        console.log('News Prev button clicked');
                        changeNewsSlide(-1);
                    });
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        console.log('News Next button clicked');
                        changeNewsSlide(1);
                    });
                }

                // Tự động chuyển slide mỗi 5 giây
                if (totalNewsSlides > 1) {
                    setInterval(() => {
                        changeNewsSlide(1);
                    }, 5000);
                }

                // Khởi tạo slide đầu tiên
                if (totalNewsSlides > 0) {
                    showNewsSlide(0);
                }
            } else {
                console.log('Tin tức slider not found!');
            }
        });
        
        // Function scroll carousel cho phần Bảng Xếp Hạng (để global để có thể gọi từ HTML)
        function scrollCarousel(carouselId, direction) {
            const carousel = document.getElementById(carouselId);
            if (!carousel) return;
            
            const scrollAmount = 220; // Khoảng cách scroll (210px width + 10px gap)
            const currentScroll = carousel.scrollLeft;
            const targetScroll = currentScroll + (scrollAmount * direction);
            
            carousel.scrollTo({
                left: targetScroll,
                behavior: 'smooth'
            });
        }
        
    </script>
    
    @include('components.footer')
</body>
</html>

