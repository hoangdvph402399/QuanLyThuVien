@extends('layouts.frontend')

@section('title', 'Trang chủ - Giao diện NXB')

@section('content')
<!-- HEADER (Giao diện NXB) -->
<div class="border-bottom bg-white">
    <div class="container py-2 d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('home') }}" class="text-decoration-none d-flex align-items-center gap-2">
                <img src="{{ asset('favicon.ico') }}" alt="logo" width="36" height="36">
                <div class="fw-bold text-uppercase nxb-logo-text-red">Nhà xuất bản</div>
                <div class="fw-bold text-uppercase nxb-logo-text-dark">Xây Dựng</div>
            </a>
        </div>
        <div class="d-flex align-items-center gap-4 flex-wrap">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-phone-alt nxb-phone-icon"></i>
                <div class="small">
                    <div class="text-muted small">Hotline khách lẻ:</div>
                    <a href="tel:0327888669" class="fw-semibold text-decoration-none nxb-phone-link">0327888669</a>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-phone-volume nxb-phone-icon"></i>
                <div class="small">
                    <div class="text-muted small">Hotline khách sỉ:</div>
                    <a href="tel:02439741791" class="fw-semibold text-decoration-none nxb-phone-link">02439741791 - 0327888669</a>
                </div>
            </div>
            <a href="{{ route('cart.index') }}" class="btn btn-sm position-relative nxb-cart-btn" data-cart-count-route="{{ route('cart.count') }}">
                <i class="fas fa-shopping-cart text-white"></i>
                <span class="text-white">Giỏ sách</span>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white nxb-cart-count-badge" id="cart-count">0</span>
            </a>
            @auth
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('orders.index') }}">Đơn hàng</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="px-3 py-1">
                                @csrf
                                <button class="btn btn-link p-0">Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i>Đăng nhập</a>
            @endauth
        </div>
    </div>
    <div class="bg-gold-1 py-2">
        <div class="container d-flex align-items-center gap-3 flex-wrap">
            <button class="btn nxb-search-btn text-white d-flex align-items-center gap-2">
                <i class="fas fa-bars"></i>
                <span class="fw-semibold text-uppercase">Danh mục sách</span>
            </button>
            <form action="{{ route('books.public') }}" method="GET" class="flex-grow-1 d-flex nxb-search-form">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control form-control-lg rounded-start-pill search-nxb-input" placeholder="Tìm sách, tác giả, sản phẩm mong muốn...">
                <button type="submit" class="btn btn-lg rounded-end-pill btn-danger px-4"><i class="fas fa-search text-white"></i></button>
            </form>
        </div>
    </div>
</div>

<div class="bg-light py-3">
    <div class="container-fluid px-0">
        <div class="row g-3 mx-0">
            <!-- Bên trái: Danh mục -->
            <div class="col-lg-3 px-3">
                <div class="nxb-sidebar">
                    <div class="nxb-sidebar-header">
                        <i class="fas fa-bars me-2"></i>
                        <span class="fw-bold text-uppercase">Danh mục sách</span>
                    </div>
                    <div class="nxb-sidebar-body">
                        @foreach($categories as $cat)
                        <a href="{{ route('books.public', ['category_id' => $cat->id]) }}" class="nxb-category-item">
                            <i class="fas fa-book me-2"></i>
                            <span>{{ $cat->ten_the_loai }}</span>
                            @if(isset($cat->books_count))
                                <i class="fas fa-chevron-right ms-auto"></i>
                            @endif
                        </a>
                        @endforeach
                        <a href="{{ route('categories.index') }}" class="nxb-category-item nxb-all-categories">
                            <i class="fas fa-bars me-2"></i>
                            <span>Tất cả danh mục</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Giữa: Hero + các phần -->
            <div class="col-lg-6 px-3">
                <!-- Banner hero -->
                <div id="heroCarousel" class="carousel slide mb-4 shadow-sm" data-bs-ride="carousel">
                    <div class="carousel-inner rounded-3">
                        <div class="carousel-item active bg-dark text-white nxb-hero-carousel-item nxb-hero-carousel-item-1">
                            <div class="p-4 p-md-5">
                                <h3 class="fw-bold mb-2">Giới thiệu sách mới</h3>
                                <p class="mb-3">Khám phá những tựa sách mới nhất trong tháng</p>
                                <a href="{{ route('books.public') }}" class="btn btn-sm btn-light">Xem ngay</a>
                            </div>
                        </div>
                        <div class="carousel-item nxb-hero-carousel-item nxb-hero-carousel-item-2">
                            <div class="p-4 p-md-5 text-white">
                                <h3 class="fw-bold mb-2">Đọc sách miễn phí</h3>
                                <p class="mb-3">Hàng trăm đầu sách để bạn bắt đầu</p>
                                <a href="{{ route('books.public') }}" class="btn btn-sm btn-light">Bắt đầu đọc</a>
                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Trước</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Sau</span>
                    </button>
                </div>

                <!-- Phần Sách nổi bật -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="fw-bold mb-0">Sách nổi bật</h2>
                        <a href="{{ route('books.public') }}" class="text-decoration-none">
                            Xem toàn bộ <span>→</span>
                        </a>
                    </div>
                    <div class="position-relative">
                        <div class="d-flex overflow-hidden" id="featured-books-carousel" style="scroll-behavior: smooth;">
                            @forelse($featured ?? [] as $book)
                                <div class="flex-shrink-0 me-3" style="width: 180px;">
                                    <div class="card h-100 shadow-sm border-0">
                                        <a href="{{ route('books.show', $book->id) }}" class="text-decoration-none">
                                            <div class="position-relative" style="height: 240px; overflow: hidden;">
                                                @if($book->hinh_anh && file_exists(public_path('storage/'.$book->hinh_anh)))
                                                    <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach }}" class="w-100 h-100" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                        <i class="fas fa-book fa-3x text-secondary"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body p-2">
                                                <h6 class="card-title mb-1 small fw-semibold text-dark" style="min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                    {{ Str::limit($book->ten_sach, 60) }}
                                                </h6>
                                                <p class="text-muted small mb-1">{{ Str::limit($book->tac_gia ?? 'N/A', 30) }}</p>
                                                <div class="mb-1">
                                                    <span class="text-warning">
                                                        @for($i = 0; $i < 5; $i++)
                                                            <i class="fas fa-star"></i>
                                                        @endfor
                                                    </span>
                                                </div>
                                                @if(isset($book->gia) && $book->gia > 0)
                                                    <p class="text-danger mb-0 small fw-bold">Chỉ từ {{ number_format($book->gia, 0, ',', '.') }}₫</p>
                                                @elseif(isset($book->gia_ban) && $book->gia_ban > 0)
                                                    <p class="text-danger mb-0 small fw-bold">Chỉ từ {{ number_format($book->gia_ban, 0, ',', '.') }}₫</p>
                                                @else
                                                    <p class="text-danger mb-0 small fw-bold">Liên hệ</p>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center w-100 py-4">
                                    <p class="text-muted">Chưa có sách nổi bật</p>
                                </div>
                            @endforelse
                        </div>
                        @if(isset($featured) && $featured->count() > 6)
                            <button class="featured-carousel-prev position-absolute top-50 start-0 translate-middle-y btn btn-light rounded-circle shadow" onclick="scrollFeaturedBooks(-1)" style="left: -20px; z-index: 10; width: 40px; height: 40px;">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="featured-carousel-next position-absolute top-50 end-0 translate-middle-y btn btn-light rounded-circle shadow" onclick="scrollFeaturedBooks(1)" style="right: -20px; z-index: 10; width: 40px; height: 40px;">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Phần Trân trọng phục vụ -->
                <div class="mb-4">
                    <h2 class="fw-bold mb-4 text-center">Trân trọng phục vụ</h2>
                    <div class="nxb-service-grid">
                        <!-- Tile 1: Bộ Xây dựng -->
                        <a href="{{ route('books.public') }}" class="nxb-service-item">
                            <div class="nxb-service-image">
                                <img src="{{ asset('storage/banners/service-1.jpg') }}" alt="Bộ Xây dựng" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 200%22%3E%3Crect width=%22300%22 height=%22200%22 fill=%22%23e3f2fd%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22 font-size=%2220%22 fill=%22%231976d2%22%3E🏢%3C/text%3E%3C/svg%3E';">
                            </div>
                            <div class="nxb-service-label">Bộ Xây dựng</div>
                        </a>

                        <!-- Tile 2: Viện nghiên cứu -->
                        <a href="{{ route('books.public') }}" class="nxb-service-item">
                            <div class="nxb-service-image">
                                <img src="{{ asset('storage/banners/service-2.jpg') }}" alt="Viện nghiên cứu" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 200%22%3E%3Crect width=%22300%22 height=%22200%22 fill=%22%23f3e5f5%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22 font-size=%2220%22 fill=%22%239c27b0%22%3E🔬%3C/text%3E%3C/svg%3E';">
                            </div>
                            <div class="nxb-service-label">Viện nghiên cứu</div>
                        </a>

                        <!-- Tile 3: Doanh nghiệp/ Tổ chức -->
                        <a href="{{ route('books.public') }}" class="nxb-service-item">
                            <div class="nxb-service-image">
                                <img src="{{ asset('storage/banners/service-3.jpg') }}" alt="Doanh nghiệp/ Tổ chức" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 200%22%3E%3Crect width=%22300%22 height=%22200%22 fill=%22%23fff3e0%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22 font-size=%2220%22 fill=%22%23f57c00%22%3E🏢%3C/text%3E%3C/svg%3E';">
                            </div>
                            <div class="nxb-service-label">Doanh nghiệp/ Tổ chức</div>
                        </a>

                        <!-- Tile 4: Nhà sách -->
                        <a href="{{ route('books.public') }}" class="nxb-service-item">
                            <div class="nxb-service-image">
                                <img src="{{ asset('storage/banners/service-4.jpg') }}" alt="Nhà sách" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 200%22%3E%3Crect width=%22300%22 height=%22200%22 fill=%22%23e8f5e9%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22 font-size=%2220%22 fill=%22%234caf50%22%3E📚%3C/text%3E%3C/svg%3E';">
                            </div>
                            <div class="nxb-service-label">Nhà sách</div>
                        </a>

                        <!-- Tile 5: Quản lý thư viện -->
                        <a href="{{ route('books.public') }}" class="nxb-service-item">
                            <div class="nxb-service-image">
                                <img src="{{ asset('storage/banners/service-5.jpg') }}" alt="Quản lý thư viện" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 200%22%3E%3Crect width=%22300%22 height=%22200%22 fill=%22%23fce4ec%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22 font-size=%2220%22 fill=%22%23c2185b%22%3E📖%3C/text%3E%3C/svg%3E';">
                            </div>
                            <div class="nxb-service-label">Quản lý thư viện</div>
                        </a>

                        <!-- Tile 6: Sinh viên -->
                        <a href="{{ route('books.public') }}" class="nxb-service-item">
                            <div class="nxb-service-image">
                                <img src="{{ asset('storage/banners/service-6.jpg') }}" alt="Sinh viên" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 200%22%3E%3Crect width=%22300%22 height=%22200%22 fill=%22%23e1f5fe%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22 font-size=%2220%22 fill=%22%230289d1%22%3E👨‍🎓%3C/text%3E%3C/svg%3E';">
                            </div>
                            <div class="nxb-service-label">Sinh viên</div>
                        </a>

                        <!-- Tile 7: Tác giả -->
                        <a href="{{ route('books.public') }}" class="nxb-service-item">
                            <div class="nxb-service-image">
                                <img src="{{ asset('storage/banners/service-7.jpg') }}" alt="Tác giả" onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 300 200%22%3E%3Crect width=%22300%22 height=%22200%22 fill=%22%23fff9c4%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dominant-baseline=%22middle%22 font-size=%2220%22 fill=%22%23f9a825%22%3E✍️%3C/text%3E%3C/svg%3E';">
                            </div>
                            <div class="nxb-service-label">Tác giả</div>
                        </a>
                    </div>
                </div>

                <!-- Phần Tin tức -->
                @if(isset($news) && $news->count() > 0)
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="fw-bold mb-0">Tin tức</h2>
                        <a href="#" class="text-decoration-none">
                            Xem toàn bộ <span>→</span>
                        </a>
                    </div>
                    <div class="row g-3">
                        <!-- Tin tức nổi bật bên trái -->
                        <div class="col-lg-8">
                            @if(isset($featuredNews) && $featuredNews)
                            <div class="card h-100 shadow-sm border-0">
                                <a href="{{ $featuredNews->link_url ?? '#' }}" class="text-decoration-none">
                                    <div class="position-relative" style="height: 400px; overflow: hidden;">
                                        @if($featuredNews->image && file_exists(public_path('storage/'.$featuredNews->image)))
                                            <img src="{{ asset('storage/'.$featuredNews->image) }}" alt="{{ $featuredNews->title }}" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                <i class="fas fa-newspaper fa-4x text-secondary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body p-3">
                                        <p class="text-muted small mb-2">{{ $featuredNews->published_date ? $featuredNews->published_date->format('d/m/Y') : '' }}</p>
                                        <h4 class="card-title fw-bold text-dark mb-2">{{ $featuredNews->title }}</h4>
                                        <p class="text-muted small mb-0">{{ Str::limit($featuredNews->description ?? '', 150) }}</p>
                                    </div>
                                </a>
                            </div>
                            @endif
                        </div>
                        <!-- 3 tin tức nhỏ bên phải -->
                        <div class="col-lg-4">
                            <div class="d-flex flex-column gap-3 h-100">
                                @foreach($otherNews ?? [] as $item)
                                <div class="card shadow-sm border-0 flex-grow-1">
                                    <a href="{{ $item->link_url ?? '#' }}" class="text-decoration-none">
                                        <div class="row g-0">
                                            <div class="col-4">
                                                <div class="position-relative" style="height: 100px; overflow: hidden;">
                                                    @if($item->image && file_exists(public_path('storage/'.$item->image)))
                                                        <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                                            <i class="fas fa-newspaper fa-2x text-secondary"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="card-body p-2">
                                                    <p class="text-muted small mb-1">{{ $item->published_date ? $item->published_date->format('d/m/Y') : '' }}</p>
                                                    <h6 class="card-title fw-semibold text-dark mb-1" style="font-size: 0.85rem; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit($item->title, 80) }}</h6>
                                                    <p class="text-muted small mb-0" style="font-size: 0.75rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ Str::limit($item->description ?? '', 60) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Phần Chủ đề -->
                @php
                    $categoriesTop = $categoriesTop ?? collect();
                    if ($categoriesTop instanceof \Illuminate\Support\Collection) {
                        $categoriesTop = $categoriesTop->values();
                    }
                @endphp
                @if($categoriesTop && $categoriesTop->count() > 0)
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="fw-bold mb-0">Chủ đề</h2>
                        <a href="{{ route('categories.index') }}" class="text-decoration-none">
                            Xem toàn bộ <span>→</span>
                        </a>
                    </div>
                    <div class="nxb-topics-grid">
                        @foreach($categoriesTop as $category)
                            <a href="{{ route('books.public', ['category_id' => $category->id]) }}" class="nxb-topic-item">
                                <div class="nxb-topic-count">{{ number_format($category->books_count ?? 0, 0, ',', '.') }}</div>
                                <div class="nxb-topic-name">{{ $category->ten_the_loai }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @else
                <!-- Debug: Kiểm tra dữ liệu -->
                @if(isset($categories) && $categories->count() > 0)
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="fw-bold mb-0">Chủ đề</h2>
                        <a href="{{ route('categories.index') }}" class="text-decoration-none">
                            Xem toàn bộ <span>→</span>
                        </a>
                    </div>
                    <div class="nxb-topics-grid">
                        @foreach($categories->sortByDesc('books_count')->take(12) as $category)
                            <a href="{{ route('books.public', ['category_id' => $category->id]) }}" class="nxb-topic-item">
                                <div class="nxb-topic-count">{{ number_format($category->books_count ?? 0, 0, ',', '.') }}</div>
                                <div class="nxb-topic-name">{{ $category->ten_the_loai }}</div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
                @endif

                <!-- Hàm helper hiển thị sách -->
                @php
                    $renderBooks = function($items) {
                        echo '<div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 g-3 mx-0">';
                        foreach ($items as $b) {
                            echo '<div class="col px-2">';
                            echo '<div class="card h-100 shadow-sm">';
                            echo '<a href="'.route('books.show', $b->id).'" class="text-decoration-none">';
                            if ($b->hinh_anh && file_exists(public_path('storage/'.$b->hinh_anh))) {
                                echo '<img class="card-img-top nxb-book-image" src="'.asset('storage/'.$b->hinh_anh).'" alt="">';
                            } else {
                                echo '<div class="bg-light d-flex align-items-center justify-content-center nxb-book-image-placeholder"><i class="fas fa-book fa-2x text-secondary"></i></div>';
                            }
                            echo '</a>';
                            echo '<div class="card-body p-2">';
                            echo '<div class="small fw-semibold text-dark">'.e(\Illuminate\Support\Str::limit($b->ten_sach, 60)).'</div>';
                            echo '<div class="text-muted small">'.e($b->tac_gia).'</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    };
                @endphp

            </div>

            <!-- Bên phải: Sidebar hướng dẫn -->
            <div class="col-lg-3 px-3">
                <div class="nxb-guide-sidebar">
                    <!-- Hướng dẫn MUA SÁCH -->
                    <div class="nxb-guide-card mb-3">
                        <div class="nxb-guide-card-header">
                            <div class="nxb-guide-icon">
                                <i class="fas fa-shopping-bag"></i>
                            </div>
                            <h5 class="nxb-guide-title">Hướng dẫn<br><strong>MUA SÁCH</strong></h5>
                        </div>
                        <div class="nxb-guide-illustration">
                            <i class="fas fa-book-reader fa-4x text-primary"></i>
                        </div>
                    </div>

                    <!-- Hướng dẫn ĐỌC EBOOK -->
                    <div class="nxb-guide-card">
                        <div class="nxb-guide-card-header">
                            <div class="nxb-guide-icon">
                                <i class="fas fa-tablet-alt"></i>
                            </div>
                            <h5 class="nxb-guide-title">Hướng dẫn<br><strong>ĐỌC EBOOK</strong></h5>
                        </div>
                        <div class="nxb-guide-illustration">
                            <i class="fas fa-tablet fa-4x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.footer')

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/publisher-home.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/publisher-home.js') }}"></script>
@endpush