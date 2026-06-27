<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'booking_id', 'doctor_id', 'treatment_date',
        'diagnosis', 'treatment', 'prescription', 'next_visit',
    ];

    protected $casts = [
        'treatment_date' => 'date',
        'next_visit'     => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function getDiagnosisShortAttribute(): string
    {
        return strlen($this->diagnosis) > 50
            ? substr($this->diagnosis, 0, 50) . '...'
            : $this->diagnosis;
    }
}
