<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Http\Requests\Admin\Contact\ReplyContactRequest;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of contacts.
     */
    public function index(Request $request)
    {
        $query = Contact::latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by keyword
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%")
                  ->orWhere('message', 'like', "%{$keyword}%");
            });
        }

        $contacts = $query->paginate(10);

        return view('admin.contacts.index', compact('contacts'));
    }

    /**
     * Reply to a contact message.
     */
    public function reply(ReplyContactRequest $request, $id)
    {
        $contact = Contact::findOrFail($id);

        $validated = $request->validated();

        $contact->update([
            'reply_message' => $request->reply_message,
            'replied_at'    => now(),
            'status'        => 'replied',
        ]);

        return back()->with('success', 'Đã phản hồi liên hệ thành công!');
    }

    /**
     * Remove the specified contact.
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return back()->with('success', 'Đã xóa liên hệ thành công!');
    }
}
