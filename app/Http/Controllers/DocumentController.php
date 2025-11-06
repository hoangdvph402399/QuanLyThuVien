<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Hiển thị trang chi tiết tài liệu/tin tức/điểm sách
     */
    public function show($id)
    {
        $document = Document::findOrFail($id);
        
        // Lấy các tài liệu liên quan (mới nhất, không bao gồm tài liệu hiện tại)
        $relatedDocuments = Document::where('id', '!=', $id)
            ->orderBy('published_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        
        // Lấy sách mới nhất để hiển thị sidebar
        $newBooks = Book::where(function($query) {
                $query->where('trang_thai', 'active')
                      ->orWhereNull('trang_thai');
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Lấy sách xem nhiều nhất
        $popularBooks = Book::where(function($query) {
                $query->where('trang_thai', 'active')
                      ->orWhereNull('trang_thai');
            })
            ->orderBy('so_luot_xem', 'desc')
            ->limit(5)
            ->get();
        
        return view('documents.show', compact('document', 'relatedDocuments', 'newBooks', 'popularBooks'));
    }
    
    /**
     * Hiển thị danh sách tất cả tài liệu/tin tức
     */
    public function index(Request $request)
    {
        $query = Document::orderBy('published_date', 'desc')
            ->orderBy('created_at', 'desc');
        
        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $documents = $query->paginate(12);
        
        // Lấy tài liệu nổi bật
        $featuredDocuments = Document::orderBy('published_date', 'desc')
            ->limit(3)
            ->get();
        
        return view('documents.index', compact('documents', 'featuredDocuments'));
    }
}

