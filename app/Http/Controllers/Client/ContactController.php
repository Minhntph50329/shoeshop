<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Http\Requests\Client\Contact\StoreContactRequest;
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
    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();

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
