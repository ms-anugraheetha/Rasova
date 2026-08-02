<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormSubmitted;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:5000',
        ]);

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        Mail::to('rasovadelights@gmail.com')->send(new ContactFormSubmitted(
            senderName: $validated['name'],
            senderEmail: $validated['email'],
            messageBody: $validated['message'],
            subjectLine: $validated['subject'] ?? null,
        ));

        return back()->with('status', "Thank you for contacting Rasova! We've received your message and will get back to you as soon as possible.");
    }
}