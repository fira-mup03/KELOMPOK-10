<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'booking_id', 'remind_at', 'channel', 'is_sent',
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'is_sent'   => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function scopePending($query)
    {
        return $query->where('is_sent', false)->where('remind_at', '<=', now());
    }
}
