<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicBookController extends Controller
{
    public function show($id)
    {
        $book = Book::with([
            'category',
            'publisher',
            'reviews' => function($query) {
                $query->with('user')->orderBy('created_at', 'desc');
            },
            'reviews.comments.user',
            'inventories'
        ])->findOrFail($id);

        // Tăng lượt xem
        $book->increment('so_luot_xem');

        // Lấy sách liên quan (cùng thể loại)
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('trang_thai', 'active')
            ->with('category')
            ->limit(6)
            ->get();

        // Lấy sách mua nhiều nhất (top selling)
        $top_selling_books = Book::where('trang_thai', 'active')
            ->where('id', '!=', $book->id)
            ->orderBy('so_luong_ban', 'desc')
            ->orderBy('so_luot_xem', 'desc')
            ->limit(5)
            ->get();

        // Lấy sách xem nhiều nhất (most viewed)
        $most_viewed_books = Book::where('trang_thai', 'active')
            ->where('id', '!=', $book->id)
            ->orderBy('so_luot_xem', 'desc')
            ->limit(5)
            ->get();

        // Lấy sách cùng chủ đề (cùng category)
        $same_topic_books = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('trang_thai', 'active')
            ->orderBy('so_luong_ban', 'desc')
            ->orderBy('so_luot_xem', 'desc')
            ->limit(12)
            ->get();
        
        // Nếu không đủ sách cùng chủ đề, lấy thêm sách nổi bật khác
        if ($same_topic_books->count() < 12) {
            $remaining = 12 - $same_topic_books->count();
            $additional_books = Book::where('id', '!=', $book->id)
                ->where('trang_thai', 'active')
                ->whereNotIn('id', $same_topic_books->pluck('id'))
                ->orderBy('so_luong_ban', 'desc')
                ->orderBy('so_luot_xem', 'desc')
                ->limit($remaining)
                ->get();
            
            $same_topic_books = $same_topic_books->merge($additional_books);
        }

        // Lấy thông tin tác giả
        $author = null;
        if ($book->tac_gia) {
            $author = \App\Models\Author::where('ten_tac_gia', 'like', '%' . $book->tac_gia . '%')->first();
        }

        // Thống kê sách
        $stats = [
            'total_reviews' => $book->reviews()->count(),
            'average_rating' => $book->reviews()->avg('rating') ?? 0,
            'total_copies' => $book->inventories()->count(),
            'available_copies' => $book->inventories()->where('status', 'Co san')->count(),
            'borrowed_copies' => $book->inventories()->where('status', 'Dang muon')->count(),
        ];

        // Kiểm tra user hiện tại có yêu thích sách này không
        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = $book->favorites()->where('user_id', auth()->id())->exists();
        }

        // Kiểm tra user hiện tại có đánh giá sách này không
        $userReview = null;
        if (auth()->check()) {
            $userReview = $book->reviews()->where('user_id', auth()->id())->first();
        }

        return view('books.show', compact(
            'book', 
            'relatedBooks', 
            'stats', 
            'isFavorited', 
            'userReview',
            'top_selling_books',
            'most_viewed_books',
            'same_topic_books',
            'author'
        ));
    }

    public function index(Request $request)
    {
        $query = Book::with('category');

        // Lọc theo thể loại (nếu có)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Tìm kiếm theo tên sách hoặc tác giả
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('ten_sach', 'like', "%{$keyword}%")
                  ->orWhere('tac_gia', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo giá
        if ($request->filled('price_min')) {
            $query->where('gia_ban', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('gia_ban', '<=', $request->price_max);
        }

        // Sắp xếp
        $sort = $request->get('sort', 'new');
        switch ($sort) {
            case 'price-asc':
                $query->orderBy('gia_ban', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('gia_ban', 'desc');
                break;
            case 'new':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Lấy danh sách sách sau khi lọc với phân trang
        $books = $query->paginate(12)->appends($request->query());

        // Lấy danh sách thể loại để hiển thị dropdown
        $categories = Category::all();

        return view('books.public', compact('books', 'categories'));
    }
}
