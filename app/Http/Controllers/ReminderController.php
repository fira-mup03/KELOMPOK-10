<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reminder;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = today();

        $upcoming = Reminder::with(['booking.doctor'])
            ->where('user_id', $user->id)
            ->whereHas('booking', function ($q) use ($today) {
                $q->where('booking_date', '>=', $today)
                  ->whereNotIn('status', ['cancelled', 'done']);
            })
            ->orderBy('remind_at')
            ->get();

        $past = Reminder::with(['booking.doctor'])
            ->where('user_id', $user->id)
            ->whereHas('booking', function ($q) use ($today) {
                $q->where(function ($inner) use ($today) {
                    $inner->where('booking_date', '<', $today)
                          ->orWhereIn('status', ['done', 'cancelled']);
                });
            })
            ->orderByDesc('remind_at')
            ->get();

        return view('reminder.index', compact('upcoming', 'past'));
    }
}
