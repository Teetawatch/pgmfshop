<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'status', 'subtotal', 'discount',
        'shipping_cost', 'total', 'coupon_code', 'payment_method',
        'payment_slip', 'slip_verified', 'slip_hash', 'slip_verification_data',
        'shipping_method', 'tracking_number', 'tracking_url',
        'shipping_address', 'status_history', 'notes', 'payment_deadline',
        'transfer_date', 'transfer_time', 'transfer_amount',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'shipping_address' => 'array',
        'status_history' => 'array',
        'slip_verification_data' => 'array',
        'payment_deadline' => 'datetime',
        'transfer_date' => 'date',
        'transfer_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function generateOrderNumber(): string
    {
        return DB::transaction(function () {
            $today = now()->format('Ymd');

            $sequence = DB::table('order_sequences')
                ->where('order_date', now()->toDateString())
                ->lockForUpdate()
                ->first();

            if ($sequence) {
                $nextSeq = $sequence->last_sequence + 1;
                DB::table('order_sequences')
                    ->where('id', $sequence->id)
                    ->update([
                        'last_sequence' => $nextSeq,
                        'updated_at' => now(),
                    ]);
            } else {
                $nextSeq = 1;
                DB::table('order_sequences')->insert([
                    'order_date' => now()->toDateString(),
                    'last_sequence' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return 'PGMF' . $today . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
        });
    }

    public function addStatusHistory(string $status, string $note = ''): void
    {
        $history = $this->status_history ?? [];
        $history[] = [
            'status' => $status,
            'note' => $note,
            'timestamp' => now()->toISOString(),
        ];
        $this->status_history = $history;
        $this->status = $status;
        $this->save();
    }
}
