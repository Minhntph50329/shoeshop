<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Trang tin tức Client (Danh sách bài viết từ Database)
     */
    public function index(Request $request)
    {
        $query = BlogPost::where('is_active', '!=', 0)->with(['category', 'author']);

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('category')) {
            $catParam = $request->category;
            $query->whereHas('category', function ($q) use ($catParam) {
                if (is_numeric($catParam)) {
                    $q->where('id', $catParam);
                } else {
                    $q->where('name', 'like', "%{$catParam}%");
                }
            });
        }

        $posts = $query->latest()->paginate(9)->withQueryString();

        $categories = BlogCategory::where('is_active', '!=', 0)
            ->withCount(['posts' => function ($q) {
                $q->where('is_active', '!=', 0);
            }])
            ->get();

        $recentPosts = BlogPost::where('is_active', '!=', 0)->latest()->take(5)->get();

        return view('client.blog.index', compact('posts', 'categories', 'recentPosts'));
    }

    /**
     * Trang chi tiết bài viết & Bình luận
     */
    public function show($slug)
    {
        $post = BlogPost::where('is_active', '!=', 0)
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug)
                  ->orWhere('id', $slug);
            })
            ->with(['category', 'author'])
            ->firstOrFail();

        // Tăng lượt xem bài viết (1 lần mỗi session)
        $sessionKey = 'viewed_blog_' . $post->id;
        if (!session()->has($sessionKey)) {
            $post->increment('views');
            session()->put($sessionKey, true);
        }

        // Lấy bình luận cấp 1 (parent_id = null) đang active
        $comments = BlogComment::where('post_id', $post->id)
            ->whereNull('parent_id')
            ->where('is_active', '!=', 0)
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();

        $categories = BlogCategory::where('is_active', '!=', 0)
            ->withCount(['posts' => function ($q) {
                $q->where('is_active', '!=', 0);
            }])
            ->get();

        $recentPosts = BlogPost::where('is_active', '!=', 0)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(5)
            ->get();

        return view('client.blog.show', compact('post', 'comments', 'categories', 'recentPosts'));
    }

    /**
     * Gửi bình luận bài viết (Lưu vào bảng blog_comments với user_name & user_email)
     */
    public function storeComment(Request $request, $postId)
    {
        $post = BlogPost::where('is_active', true)->findOrFail($postId);

        if (!$post->alow_comments) {
            return back()->with('error', 'Bài viết này hiện đã tắt tính năng bình luận.');
        }

        $rules = [
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id',
        ];

        if (!Auth::check()) {
            $rules['user_name'] = 'required|string|max:255';
            $rules['user_email'] = 'required|email|max:255';
        }

        $validated = $request->validate($rules, [
            'content.required' => 'Vui lòng nhập nội dung bình luận.',
            'user_name.required' => 'Vui lòng nhập họ tên của bạn.',
            'user_email.required' => 'Vui lòng nhập email hợp lệ.',
        ]);

        BlogComment::create([
            'post_id' => $post->id,
            'user_id' => Auth::check() ? Auth::id() : null,
            'parent_id' => $request->parent_id,
            'user_name' => Auth::check() ? Auth::user()->fullname : $request->user_name,
            'user_email' => Auth::check() ? Auth::user()->email : $request->user_email,
            'content' => $request->content,
            'is_active' => true,
        ]);

        return back()->with('success', 'Gửi bình luận thành công!');
    }
}
