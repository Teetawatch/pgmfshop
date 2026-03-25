<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactManageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $messages = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
            'read' => ContactMessage::where('status', 'read')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
            'closed' => ContactMessage::where('status', 'closed')->count(),
        ];

        return view('admin.contact-messages.index', compact('messages', 'stats'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $contactMessage->load(['user', 'repliedByUser']);
        $contactMessage->markAsRead();

        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'admin_reply' => 'required|string|min:5|max:5000',
        ], [
            'admin_reply.required' => 'กรุณากรอกข้อความตอบกลับ',
            'admin_reply.min' => 'ข้อความตอบกลับต้องมีอย่างน้อย 5 ตัวอักษร',
            'admin_reply.max' => 'ข้อความตอบกลับต้องไม่เกิน 5,000 ตัวอักษร',
        ]);

        $contactMessage->reply($validated['admin_reply'], auth()->id());

        // Send reply email to customer
        Mail::to($contactMessage->user->email)->queue(new ContactReplyMail($contactMessage));

        return redirect()->route('admin.contact-messages.show', $contactMessage)
            ->with('success', 'ตอบกลับข้อความเรียบร้อยแล้ว');
    }

    public function close(ContactMessage $contactMessage)
    {
        $contactMessage->update(['status' => ContactMessage::STATUS_CLOSED]);

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'ปิดข้อความเรียบร้อยแล้ว');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'ลบข้อความเรียบร้อยแล้ว');
    }
}
