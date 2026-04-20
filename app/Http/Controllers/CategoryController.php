<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:categories,nama',
        ]);

        $category = Category::create([
            'nama' => $request->nama
        ]);

        // Handle AJAX request from rak management
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'category' => $category,
                'message' => 'Kategori berhasil ditambahkan.'
            ]);
        }

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:categories,nama,' . $category->id,
        ]);

        $category->update([
            'nama' => $request->nama
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'category' => $category,
                'message' => 'Kategori berhasil diperbarui.'
            ]);
        }

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        // Check if category has books
        if ($category->books()->count() > 0) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Kategori tidak bisa dihapus karena masih memiliki buku!'
                ]);
            }
            return redirect()->route('categories.index')->with('error', 'Kategori tidak bisa dihapus karena masih memiliki buku!');
        }
        
        $category->delete();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Kategori berhasil dihapus.'
            ]);
        }
        
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}