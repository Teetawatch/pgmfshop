<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    const STATUS_NEW = 'new';
    const STATUS_READ = 'read';
    const STATUS_REPLIED = 'replied';
    const STATUS_CLOSED = 'closed';

    const SUBJECTS = [
        'general' => 'สอบถามทั่วไป',
        'order' => 'สถานะคำสั่งซื้อ',
        'product' => 'สอบถามสินค้า',
        'payment' => 'การชำระเงิน',
        'other' => 'อื่นๆ',
    ];

    const STATUS_LABELS = [
        'new' => 'ใหม่',
        'read' => 'อ่านแล้ว',
        'replied' => 'ตอบกลับแล้ว',
        'closed' => 'ปิดแล้ว',
    ];

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
        'admin_reply',
        'replied_at',
        'replied_by',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function repliedByUser()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function getSubjectLabelAttribute(): string
    {
        return self::SUBJECTS[$this->subject] ?? $this->subject;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function markAsRead(): void
    {
        if ($this->status === self::STATUS_NEW) {
            $this->update(['status' => self::STATUS_READ]);
        }
    }

    public function reply(string $replyText, int $adminId): void
    {
        $this->update([
            'admin_reply' => $replyText,
            'status' => self::STATUS_REPLIED,
            'replied_at' => now(),
            'replied_by' => $adminId,
        ]);
    }
}
