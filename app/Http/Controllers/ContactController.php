<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Mail\NewContactMessageMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|in:general,order,product,payment,other',
            'message' => 'required|string|min:10|max:2000',
        ], [
            'subject.required' => 'กรุณาเลือกหัวข้อ',
            'subject.in' => 'หัวข้อไม่ถูกต้อง',
            'message.required' => 'กรุณากรอกข้อความ',
            'message.min' => 'ข้อความต้องมีอย่างน้อย 10 ตัวอักษร',
            'message.max' => 'ข้อความต้องไม่เกิน 2,000 ตัวอักษร',
        ]);

        $contactMessage = ContactMessage::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        // Notify admin via email
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->queue(new NewContactMessageMail($contactMessage));
        }

        return redirect()->route('contact')->with('success', 'ส่งข้อความเรียบร้อยแล้ว ทีมงานจะตอบกลับภายใน 24 ชั่วโมง');
    }
}
