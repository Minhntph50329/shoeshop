<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Danh sách bài viết Tin tức
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['category', 'author']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $posts = $query->latest()->paginate(10)->withQueryString();
        $categories = BlogCategory::where('is_active', true)->get();

        return view('admin.news.index', compact('posts', 'categories'));
    }

    /**
     * Form tạo bài viết mới
     */
    public function create()
    {
        $categories = BlogCategory::where('is_active', true)->get();
        return view('admin.news.create', compact('categories'));
    }

    /**
     * Lưu bài viết mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:blog_categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'required|boolean',
            'alow_comments' => 'nullable|boolean',
        ], [
            'title.required' => 'Vui lòng nhập tiêu đề bài viết.',
            'content.required' => 'Vui lòng nhập nội dung bài viết.',
        ]);

        $validated['slug'] = Str::slug($request->title) . '-' . time();
        $validated['author_id'] = Auth::id();
        $validated['alow_comments'] = $request->boolean('alow_comments');

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('news', 'public');
            $validated['thumbnail'] = 'storage/' . $path;
        }

        BlogPost::create($validated);

        return redirect()->route('admin.news.index')->with('success', 'Tạo bài viết mới thành công!');
    }

    /**
     * Form sửa bài viết
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        $categories = BlogCategory::where('is_active', true)->get();
        return view('admin.news.edit', compact('post', 'categories'));
    }

    /**
     * Cập nhật bài viết
     */
    public function update(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:blog_categories,id',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'required|boolean',
            'alow_comments' => 'nullable|boolean',
        ]);

        $validated['alow_comments'] = $request->boolean('alow_comments');

        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail && str_contains($post->thumbnail, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $post->thumbnail));
            }
            $path = $request->file('thumbnail')->store('news', 'public');
            $validated['thumbnail'] = 'storage/' . $path;
        }

        $post->update($validated);

        return redirect()->route('admin.news.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    /**
     * Xóa mềm bài viết
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.news.index')->with('success', 'Đã chuyển bài viết vào thùng rác!');
    }

    /**
     * Thùng rác bài viết
     */
    public function trash()
    {
        $trashed = BlogPost::onlyTrashed()->with(['category', 'author'])->latest('deleted_at')->paginate(10);
        return view('admin.news.trash', compact('trashed'));
    }

    /**
     * Khôi phục bài viết
     */
    public function restore($id)
    {
        $post = BlogPost::onlyTrashed()->findOrFail($id);
        $post->restore();

        return redirect()->route('admin.news.trash')->with('success', 'Đã khôi phục bài viết!');
    }

    /**
     * Xóa vĩnh viễn bài viết
     */
    public function forceDelete($id)
    {
        $post = BlogPost::onlyTrashed()->findOrFail($id);

        if ($post->thumbnail && str_contains($post->thumbnail, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $post->thumbnail));
        }

        $post->forceDelete();

        return redirect()->route('admin.news.trash')->with('success', 'Đã xóa vĩnh viễn bài viết!');
    }

    /*
    |--------------------------------------------------------------------------
    | TRANG CON ADMIN 1: QUẢN LÝ DANH MỤC BÀI VIẾT (/admin/news/categories)
    |--------------------------------------------------------------------------
    */

    public function categories()
    {
        $categories = BlogCategory::withCount('posts')->latest()->paginate(10);
        return view('admin.news.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
        ]);

        BlogCategory::create([
            'name' => $request->name,
            'is_active' => true,
        ]);

        return back()->with('success', 'Thêm danh mục tin tức thành công!');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'is_active' => $request->is_active,
        ]);

        return back()->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroyCategory($id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Đã xóa danh mục tin tức.');
    }

    /*
    |--------------------------------------------------------------------------
    | TRANG CON ADMIN 2: QUẢN LÝ BÌNH LUẬN BÀI VIẾT (/admin/news/comments)
    | Bảng blog_comments: (id, post_id, user_id, user_name, user_email, content, parent_id, is_active, created_at, updated_at)
    |--------------------------------------------------------------------------
    */

    public function comments(Request $request)
    {
        $query = BlogComment::with(['post', 'user', 'parent']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('user_email', 'like', "%{$search}%");
            });
        }

        $comments = $query->latest()->paginate(10)->withQueryString();

        return view('admin.news.comments', compact('comments'));
    }

    public function replyComment(Request $request, $id)
    {
        $parentComment = BlogComment::findOrFail($id);

        $request->validate([
            'reply_content' => 'required|string|max:1000',
        ], [
            'reply_content.required' => 'Vui lòng nhập nội dung trả lời.',
        ]);

        BlogComment::create([
            'post_id' => $parentComment->post_id,
            'user_id' => Auth::id(),
            'parent_id' => $parentComment->id,
            'user_name' => Auth::user()->fullname ?? 'Admin Veloce',
            'user_email' => Auth::user()->email ?? 'admin@veloce.com',
            'content' => $request->reply_content,
            'is_active' => true,
        ]);

        return back()->with('success', 'Trả lời bình luận thành công!');
    }

    public function toggleCommentStatus($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->is_active = !$comment->is_active;
        $comment->save();

        $statusText = $comment->is_active ? 'Hiển thị' : 'Ẩn';
        return back()->with('success', "Đã thay đổi trạng thái bình luận sang: {$statusText}.");
    }

    public function destroyComment($id)
    {
        $comment = BlogComment::findOrFail($id);
        $comment->delete();

        return back()->with('success', 'Đã xóa bình luận thành công.');
    }
}
