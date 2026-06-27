<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'doctor_id', 'booking_date', 'booking_time',
        'complaint', 'status', 'queue_number', 'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function queue()
    {
        return $this->hasOne(Queue::class);
    }

    public function reminder()
    {
        return $this->hasOne(Reminder::class);
    }

    public function treatmentHistory()
    {
        return $this->hasOne(TreatmentHistory::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopeNotCancelled($query)
    {
        return $query->where('status', '!=', 'cancelled');
    }

    public function canBeCancelled(): bool
    {
        return $this->booking_date > now()->toDateString()
            && !in_array($this->status, ['cancelled', 'done']);
    }
}
