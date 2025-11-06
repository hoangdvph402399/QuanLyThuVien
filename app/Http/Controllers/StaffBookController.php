<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;

class StaffBookController extends Controller
{
    public function index()
    {
        $books = Book::with('category')->paginate(10);
        return view('staff.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('staff.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_sach' => 'required|string|max:255',
            'tac_gia' => 'required|string|max:255',
            'nam_xuat_ban' => 'required|integer|min:1900|max:' . date('Y'),
            'category_id' => 'required|exists:categories,id',
            'mo_ta' => 'nullable|string',
            'gia' => 'nullable|numeric|min:0',
            'dinh_dang' => 'nullable|string',
            'trang_thai' => 'required|in:active,inactive',
            'hinh_anh' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('hinh_anh')) {
            try {
                $file = $request->file('hinh_anh');
                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = storage_path('app/public/books');
                
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $file->move($destinationPath, $filename);
                $path = 'books/' . $filename;
            } catch (\Exception $e) {
                \Log::error('Upload error:', ['message' => $e->getMessage()]);
                return redirect()->back()->with('error', 'Lỗi khi upload ảnh: ' . $e->getMessage())->withInput();
            }
        }

        try {
            $book = Book::create([
                'ten_sach' => $request->ten_sach,
                'category_id' => $request->category_id,
                'tac_gia' => $request->tac_gia,
                'nam_xuat_ban' => $request->nam_xuat_ban,
                'hinh_anh' => $path,
                'mo_ta' => $request->mo_ta,
                'gia' => $request->gia ?? 0,
                'dinh_dang' => $request->dinh_dang ?? 'Sách giấy',
                'trang_thai' => $request->trang_thai,
                'danh_gia_trung_binh' => 0,
                'so_luong_ban' => 0,
                'so_luot_xem' => 0,
            ]);

            return redirect()->route('staff.books.index')
                ->with('success', 'Thêm sách thành công!');
        } catch (\Exception $e) {
            \Log::error('Book creation error:', ['message' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Lỗi khi tạo sách: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $book = Book::with(['category', 'borrows', 'reviews'])->findOrFail($id);
        return view('staff.books.show', compact('book'));
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();
        return view('staff.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        $request->validate([
            'ten_sach' => 'required|string|max:255',
            'tac_gia' => 'required|string|max:255',
            'nam_xuat_ban' => 'required|integer|min:1900|max:' . date('Y'),
            'category_id' => 'required|exists:categories,id',
            'mo_ta' => 'nullable|string',
        ]);

        $book->update($request->all());

        return redirect()->route('staff.books.index')
            ->with('success', 'Cập nhật sách thành công!');
    }
}

