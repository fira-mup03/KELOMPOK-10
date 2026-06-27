<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id', 'day_of_week', 'start_time', 'end_time', 'max_quota',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function getDayNameAttribute()
    {
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        return $days[$this->day_of_week] ?? 'Unknown';
    }

    /**
     * Generate 30-minute time slots between start_time and end_time
     */
    public function getTimeSlotsAttribute(): array
    {
        $slots = [];
        $start = strtotime($this->start_time);
        $end   = strtotime($this->end_time);
        while ($start < $end) {
            $slots[] = date('H:i', $start);
            $start += 30 * 60;
        }
        return $slots;
    }
}
