<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Queue;
use App\Models\Booking;
use Carbon\Carbon;

class QueueController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $today   = today();

        // Antrian aktif user (hari ini atau yang terdekat di masa depan)
        $myQueue = Queue::whereHas('booking', function ($q) use ($user, $today) {
            $q->where('user_id', $user->id)
              ->where('booking_date', '>=', $today)
              ->whereNotIn('status', ['cancelled', 'done']);
        })->with('booking.doctor')
          ->orderBy('queue_date', 'asc')
          ->first();

        // Antrian sedang dipanggil (in_progress) hari ini
        $currentQueue = Queue::where('queue_date', $today)
            ->where('status', 'in_progress')
            ->first();

        $estimasi = null;
        if ($myQueue && $currentQueue && $myQueue->status === 'waiting') {
            $diff     = $myQueue->queue_number - $currentQueue->queue_number;
            $estimasi = max(0, $diff) * 20; // menit
        }

        return view('queue.index', compact('myQueue', 'currentQueue', 'estimasi'));
    }

    public function status()
    {
        $today        = today();
        $currentQueue = Queue::where('queue_date', $today)
            ->where('status', 'in_progress')
            ->first();

        $waiting = Queue::where('queue_date', $today)
            ->where('status', 'waiting')
            ->count();

        return response()->json([
            'current_number'   => $currentQueue ? 'A-' . str_pad($currentQueue->queue_number, 3, '0', STR_PAD_LEFT) : null,
            'current_status'   => $currentQueue?->status,
            'waiting_count'    => $waiting,
            'last_updated'     => now()->format('H:i:s'),
        ]);
    }
}
