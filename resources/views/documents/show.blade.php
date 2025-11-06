@extends('layouts.frontend')

@section('title', $document->title)

@section('content')
<style>
    /* Phần layout mượn phong cách từ trang sách */
    .document-detail-container { max-width: 1300px; margin: 20px auto; padding: 0 20px; }
    .breadcrumb { color: #666; font-size: 0.9em; margin-bottom: 20px; }
    .breadcrumb a { color: #666; text-decoration: none; }
    .breadcrumb a:hover { color: #d9534f; }

    .content-wrapper { display: flex; gap: 20px; }
    .main-content { flex: 3; background: #fff; padding: 20px 30px; box-shadow: 0 0 10px rgba(0,0,0,0.05); border-radius: 6px; }
    .sidebar { flex: 1; padding-top: 10px; }

    .doc-summary { display: flex; gap: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
    .doc-cover { width: 200px; height: auto; flex-shrink: 0; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background: #f5f5f5; }
    .doc-info { flex: 1; }
    .doc-title { font-size: 1.5em; margin: 0 0 10px; }
    .doc-meta { color: #666; font-size: 0.9em; display: flex; gap: 15px; margin-bottom: 10px; }
    .doc-actions { margin-top: 15px; }
    .doc-link { display:inline-block; padding:10px 16px; background:#2c5aa0; color:#fff; text-decoration:none; border-radius:4px; }

    .document-description { font-size: 16px; line-height: 1.8; color: #444; }
    
    .document-sidebar {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }
    
    .sidebar-section {
        background: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .sidebar-title {
        font-size: 18px;
        font-weight: 700;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #2c5aa0;
    }
    
    .related-item {
        display: flex;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s;
    }
    
    .related-item:last-child {
        border-bottom: none;
    }
    
    .related-item:hover {
        background: #f8f9fa;
        padding-left: 10px;
    }
    
    .related-item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 6px;
        flex-shrink: 0;
    }
    
    .related-item-info {
        flex: 1;
    }
    
    .related-item-title {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .related-item-date {
        font-size: 12px;
        color: #999;
    }
    
    .book-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s;
    }
    
    .book-item:last-child {
        border-bottom: none;
    }
    
    .book-item:hover {
        background: #f8f9fa;
        padding-left: 8px;
    }
    
    .book-item-image {
        width: 60px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
        flex-shrink: 0;
    }
    
    .book-item-info {
        flex: 1;
    }
    
    .book-item-title {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .book-item-author {
        font-size: 12px;
        color: #666;
    }
    
    @media (max-width: 768px) {
        .document-content-wrapper {
            grid-template-columns: 1fr;
        }
        
        .document-main {
            padding: 20px;
        }
        
        .document-title {
            font-size: 24px;
        }
    }
</style>

<div class="document-detail-container">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Trang chủ</a> / <a href="{{ route('documents.index') }}">Tin tức</a> / <span>{{ Str::limit($document->title, 50) }}</span>
    </div>

    <div class="content-wrapper">
        @php
            // Fallback hero image from admin banners if document image is missing
            $docHeroImg = null;
            $bannerDir = public_path('storage/banners');
            $extensions = ['jpg','jpeg','png','webp'];
            if(!($document->image && file_exists(public_path('storage/'.$document->image))) && file_exists($bannerDir)) {
                foreach($extensions as $ext) {
                    $path = $bannerDir . '/news-featured.' . $ext;
                    if(file_exists($path)) { $docHeroImg = asset('storage/banners/news-featured.' . $ext); break; }
                }
            }
        @endphp
        <div class="main-content">
            <div class="doc-summary">
                @if($document->image && file_exists(public_path('storage/'.$document->image)))
                    <img class="doc-cover" src="{{ asset('storage/'.$document->image) }}" alt="{{ $document->title }}">
                @elseif($docHeroImg)
                    <img class="doc-cover" src="{{ $docHeroImg }}" alt="{{ $document->title }}">
                @else
                    <div class="doc-cover"></div>
                @endif
                <div class="doc-info">
                    <h1 class="doc-title">{{ $document->title }}</h1>
                    <div class="doc-meta">
                        <span><i class="fas fa-calendar"></i> {{ $document->published_date ? $document->published_date->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                        <span><i class="fas fa-clock"></i> {{ $document->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="doc-actions" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                        @if($document->link_url && $document->link_url !== '#' && $document->link_url !== 'javascript:void(0)')
                            <a href="{{ $document->link_url }}" class="doc-link" target="_blank">Xem chi tiết</a>
                        @endif
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" class="doc-link" style="background:#1877f2;">
                            Chia sẻ Facebook
                        </a>
                    </div>
                </div>
            </div>

            <div class="document-description" style="margin-top: 20px;">
                {!! nl2br(e($document->description)) !!}
            </div>

            @php
                // Fallback images for related grid
                $newsFallbacks = [];
                if(file_exists($bannerDir ?? public_path('storage/banners'))) {
                    for($i=1; $i<=3; $i++) {
                        foreach($extensions as $ext) {
                            $p = ($bannerDir ?? public_path('storage/banners')) . '/news-' . $i . '.' . $ext;
                            if(file_exists($p)) { $newsFallbacks[$i] = asset('storage/banners/news-' . $i . '.' . $ext); break; }
                        }
                    }
                }
            @endphp

            @if($relatedDocuments->count() > 0)
            <div class="book-section" style="margin-top:20px;">
                <div class="section-header"><h2 class="section-title">Tin tức liên quan</h2></div>
                <div class="books-grid">
                    @foreach($relatedDocuments->take(6) as $index => $rel)
                        <a href="{{ route('documents.show', $rel->id) }}" class="book-card-modern" style="text-decoration:none;">
                            <div class="book-image-container">
                                @if($rel->image && file_exists(public_path('storage/'.$rel->image)))
                                    <img src="{{ asset('storage/'.$rel->image) }}" alt="{{ $rel->title }}">
                                @elseif(isset($newsFallbacks[($index%3)+1]))
                                    <img src="{{ $newsFallbacks[($index%3)+1] }}" alt="{{ $rel->title }}">
                                @else
                                    <div class="news-placeholder" style="background:#f5f5f5;width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-newspaper" style="color:#bbb;font-size:28px;"></i>
                                    </div>
                                @endif
                                <div class="book-overlay"></div>
                            </div>
                            <div class="book-info-modern">
                                <div class="book-title-modern">{{ $rel->title }}</div>
                                <div class="book-author-modern">{{ $rel->published_date ? $rel->published_date->format('d/m/Y') : '' }}</div>
                                <div class="book-meta-modern">
                                    <div class="book-category-modern"><i class="fas fa-file-alt"></i> Bài viết</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            @php
                $moreDocs = \App\Models\Document::where('id','!=',$document->id)
                    ->orderBy('published_date','desc')->orderBy('created_at','desc')->limit(6)->get();
            @endphp
            @if($moreDocs->count() > 0)
            <div class="book-section" style="margin-top:20px;">
                <div class="section-header"><h2 class="section-title">Có thể bạn quan tâm</h2></div>
                <div class="books-grid">
                    @foreach($moreDocs as $index => $rel)
                        <a href="{{ route('documents.show', $rel->id) }}" class="book-card-modern" style="text-decoration:none;">
                            <div class="book-image-container">
                                @if($rel->image && file_exists(public_path('storage/'.$rel->image)))
                                    <img src="{{ asset('storage/'.$rel->image) }}" alt="{{ $rel->title }}">
                                @elseif(isset($newsFallbacks[($index%3)+1]))
                                    <img src="{{ $newsFallbacks[($index%3)+1] }}" alt="{{ $rel->title }}">
                                @else
                                    <div class="news-placeholder" style="background:#f5f5f5;width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-newspaper" style="color:#bbb;font-size:28px;"></i>
                                    </div>
                                @endif
                                <div class="book-overlay"></div>
                            </div>
                            <div class="book-info-modern">
                                <div class="book-title-modern">{{ $rel->title }}</div>
                                <div class="book-author-modern">{{ $rel->published_date ? $rel->published_date->format('d/m/Y') : '' }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="sidebar">
            <!-- Related Documents -->
            @if($relatedDocuments->count() > 0)
            <div class="sidebar-section">
                <h3 class="sidebar-title">Tin tức liên quan</h3>
                @foreach($relatedDocuments as $related)
                    <a href="{{ route('documents.show', $related->id) }}" class="related-item">
                        @if($related->image && file_exists(public_path('storage/'.$related->image)))
                            <img src="{{ asset('storage/'.$related->image) }}" alt="{{ $related->title }}" class="related-item-image">
                        @else
                            <div class="related-item-image" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-newspaper" style="font-size: 24px; color: #999;"></i>
                            </div>
                        @endif
                        <div class="related-item-info">
                            <div class="related-item-title">{{ $related->title }}</div>
                            <div class="related-item-date">{{ $related->published_date ? $related->published_date->format('d/m/Y') : '' }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
            @endif
            
            <!-- New Books -->
            @if($newBooks->count() > 0)
            <div class="sidebar-section">
                <h3 class="sidebar-title">Sách mới</h3>
                @foreach($newBooks as $book)
                    <a href="{{ route('books.show', $book->id) }}" class="book-item">
                        @if($book->hinh_anh && file_exists(public_path('storage/'.$book->hinh_anh)))
                            <img src="{{ asset('storage/'.$book->hinh_anh) }}" alt="{{ $book->ten_sach }}" class="book-item-image">
                        @else
                            <div class="book-item-image" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-book" style="font-size: 20px; color: #999;"></i>
                            </div>
                        @endif
                        <div class="book-item-info">
                            <div class="book-item-title">{{ $book->ten_sach }}</div>
                            <div class="book-item-author">{{ $book->tac_gia }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

