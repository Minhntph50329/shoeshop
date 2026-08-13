<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product', 'images', 'adminReplies.user'])
            ->whereNull('review_id') // main reviews only
            ->latest();

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter by keyword (user name, product name, review text)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('review_text', 'like', "%{$keyword}%")
                  ->orWhereHas('user', function ($uq) use ($keyword) {
                      $uq->where('fullname', 'like', "%{$keyword}%")
                         ->orWhere('email', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('product', function ($pq) use ($keyword) {
                      $pq->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        $reviews = $query->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Reply to a review.
     */
    public function reply(Request $request, $id)
    {
        $parentReview = Review::findOrFail($id);

        $request->validate([
            'reply_text' => 'required|string|min:5|max:1000',
        ], [
            'reply_text.required' => 'Vui lòng nhập nội dung trả lời.',
            'reply_text.min' => 'Nội dung trả lời phải có ít nhất 5 ký tự.',
        ]);

        // Create reply
        Review::create([
            'product_id'    => $parentReview->product_id,
            'user_id'       => auth()->id(),
            'review_id'     => $parentReview->id,
            'review_text'   => $request->reply_text,
            'is_active'     => true,
        ]);

        // Update parent
        $parentReview->update(['has_replies' => true]);

        return back()->with('success', 'Đã gửi câu trả lời đánh giá.');
    }

    /**
     * Toggle the active status of a review.
     */
    public function toggleActive($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_active' => !$review->is_active]);

        $status = $review->is_active ? 'hiển thị' : 'ẩn';
        return back()->with('success', "Đã thay đổi trạng thái đánh giá thành: {$status}.");
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return back()->with('success', 'Đã xóa đánh giá thành công.');
    }
}
