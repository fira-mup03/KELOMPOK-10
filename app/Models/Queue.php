<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'queue_number', 'queue_date', 'status', 'called_at', 'done_at',
    ];

    protected $casts = [
        'queue_date' => 'date',
        'called_at'  => 'datetime',
        'done_at'    => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function getFormattedNumberAttribute(): string
    {
        return 'A-' . str_pad($this->queue_number, 3, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'waiting'     => 'Menunggu',
            'in_progress' => 'Dipanggil',
            'done'        => 'Selesai',
            'skipped'     => 'Dilewati',
            default       => 'Unknown',
        };
    }
}
