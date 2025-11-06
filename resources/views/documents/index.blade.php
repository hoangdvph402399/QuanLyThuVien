@extends('layouts.frontend')

@section('title', 'Tin tức & Điểm sách')

@section('content')
<style>
    .documents-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }
    
    .page-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .page-title {
        font-size: 36px;
        font-weight: 700;
        color: #333;
        margin-bottom: 15px;
    }
    
    .page-subtitle {
        font-size: 16px;
        color: #666;
    }
    
    .search-box {
        max-width: 600px;
        margin: 0 auto 40px;
    }
    
    .search-form {
        display: flex;
        gap: 10px;
    }
    
    .search-input {
        flex: 1;
        padding: 12px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 15px;
    }
    
    .search-button {
        padding: 12px 30px;
        background: #2c5aa0;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .search-button:hover {
        background: #1e3a6f;
    }
    
    .featured-section {
        margin-bottom: 50px;
    }
    
    .featured-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }
    
    .featured-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s;
        text-decoration: none;
        color: inherit;
    }
    
    .featured-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    .featured-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .featured-content {
        padding: 20px;
    }
    
    .featured-title {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .featured-date {
        font-size: 13px;
        color: #999;
    }
    
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-bottom: 40px;
    }
    
    .document-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s;
        text-decoration: none;
        color: inherit;
    }
    
    .document-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    .document-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    
    .document-content {
        padding: 20px;
    }
    
    .document-title {
        font-size: 16px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .document-description {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .document-date {
        font-size: 13px;
        color: #999;
    }
    
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 40px;
    }
    
    @media (max-width: 992px) {
        .featured-grid,
        .documents-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 576px) {
        .featured-grid,
        .documents-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="documents-container">
    <div class="page-header">
        <h1 class="page-title">Tin tức & Điểm sách</h1>
        <p class="page-subtitle">Cập nhật thông tin mới nhất về sách và xuất bản</p>
    </div>
    
    <!-- Search Box -->
    <div class="search-box">
        <form action="{{ route('documents.index') }}" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Tìm kiếm tin tức..." value="{{ request('search') }}">
            <button type="submit" class="search-button">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </form>
    </div>
    
    <!-- Featured Documents -->
    @if($featuredDocuments->count() > 0 && !request('search'))
    <div class="featured-section">
        <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 25px; color: #333;">
            <i class="fas fa-star" style="color: #ffa500;"></i> Nổi bật
        </h2>
        <div class="featured-grid">
            @foreach($featuredDocuments as $doc)
                <a href="{{ route('documents.show', $doc->id) }}" class="featured-card">
                    @if($doc->image && file_exists(public_path('storage/'.$doc->image)))
                        <img src="{{ asset('storage/'.$doc->image) }}" alt="{{ $doc->title }}" class="featured-image">
                    @else
                        <div class="featured-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-newspaper" style="font-size: 48px; color: white;"></i>
                        </div>
                    @endif
                    <div class="featured-content">
                        <h3 class="featured-title">{{ $doc->title }}</h3>
                        <p class="featured-date">
                            <i class="fas fa-calendar"></i> {{ $doc->published_date ? $doc->published_date->format('d/m/Y') : 'Chưa cập nhật' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
    
    <!-- All Documents -->
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 25px; color: #333;">
        @if(request('search'))
            Kết quả tìm kiếm: "{{ request('search') }}"
        @else
            Tất cả tin tức
        @endif
    </h2>
    
    @if($documents->count() > 0)
        <div class="documents-grid">
            @foreach($documents as $document)
                <a href="{{ route('documents.show', $document->id) }}" class="document-card">
                    @if($document->image && file_exists(public_path('storage/'.$document->image)))
                        <img src="{{ asset('storage/'.$document->image) }}" alt="{{ $document->title }}" class="document-image">
                    @else
                        <div class="document-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-newspaper" style="font-size: 40px; color: white;"></i>
                        </div>
                    @endif
                    <div class="document-content">
                        <h3 class="document-title">{{ $document->title }}</h3>
                        <p class="document-description">{{ Str::limit($document->description, 150) }}</p>
                        <p class="document-date">
                            <i class="fas fa-calendar"></i> {{ $document->published_date ? $document->published_date->format('d/m/Y') : 'Chưa cập nhật' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $documents->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 8px;">
            <i class="fas fa-search" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
            <h3 style="color: #666; margin-bottom: 10px;">Không tìm thấy kết quả</h3>
            <p style="color: #999;">Vui lòng thử lại với từ khóa khác</p>
        </div>
    @endif
</div>
@endsection

