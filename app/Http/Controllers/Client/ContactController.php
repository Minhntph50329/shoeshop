<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Show the contact form.
     */
    public function index()
    {
        return view('client.contact.index');
    }

    /**
     * Store a contact message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:20',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'name.required'    => 'Vui lòng nhập họ tên của bạn.',
            'email.required'   => 'Vui lòng nhập địa chỉ email.',
            'email.email'      => 'Địa chỉ email không đúng định dạng.',
            'message.required' => 'Vui lòng nhập nội dung liên hệ.',
            'message.min'      => 'Nội dung liên hệ phải có ít nhất 10 ký tự.',
        ]);

        Contact::create([
            'user_id' => Auth::id(),
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'message' => $request->message,
            'status'  => 'pending',
        ]);

        return back()->with('success', 'Lời nhắn của bạn đã được gửi đi thành công! Chúng tôi sẽ phản hồi sớm nhất.');
    }

    /**
     * Display the authenticated user's contact message history.
     */
    public function myContacts()
    {
        $contacts = Contact::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('client.contact.my-contacts', compact('contacts'));
    }
}
