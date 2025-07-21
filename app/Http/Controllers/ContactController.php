<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submitContactForm(Request $request)
    {
        // ✅ 1. Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // ✅ 2. Save contact message in MySQL
        $contact = Contact::create($validated);

        // ✅ 3. Send email
        $this->sendContactEmail($validated);

        return response()->json([
            'message' => 'Contact message saved & email sent successfully!',
            'data' => $contact
        ]);
    }

    private function sendContactEmail($data)
    {
        $receiver = config('mail.receiver'); // We'll set this in .env
        Mail::send([], [], function ($message) use ($data, $receiver) {
            $message->from(config('mail.from.address'), 'Contact Form');
            $message->to($receiver);
            $message->subject("Contact Inquiry: {$data['subject']}");

            $message->setBody("
                <div style='font-family:Segoe UI, Tahoma; padding:20px; background:#f9f9f9; border-radius:10px; max-width:600px; margin:auto; border:1px solid #eee;'>
                    <h2>📩 New Contact Inquiry</h2>
                    <p><strong>Name:</strong> {$data['name']}</p>
                    <p><strong>Email:</strong> {$data['email']}</p>
                    <p><strong>Subject:</strong> {$data['subject']}</p>
                    <p><strong>Message:</strong><br>{$data['message']}</p>
                </div>
            ", 'text/html');
        });
    }
}