<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Queue;
use App\Models\Reminder;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create()
    {
        $doctors = Doctor::active()->with('schedules')->get();
        return view('booking.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'doctor_id'    => 'required|exists:doctors,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'complaint'    => 'nullable|string',
        ]);

        $user     = Auth::user();
        $doctorId = $data['doctor_id'];
        $date     = $data['booking_date'];

        // Cek double booking aktif di hari yang sama
        $existing = $user->bookings()
            ->where('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($existing) {
            return back()->withErrors(['booking_date' => 'Anda sudah memiliki booking aktif di tanggal tersebut.'])->withInput();
        }

        // Cek kuota dokter
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $schedule  = DoctorSchedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (!$schedule) {
            return back()->withErrors(['booking_date' => 'Dokter tidak berpraktik pada hari tersebut.'])->withInput();
        }

        $currentCount = Booking::where('doctor_id', $doctorId)
            ->where('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->count();

        if ($currentCount >= $schedule->max_quota) {
            return back()->withErrors(['booking_date' => 'Kuota dokter untuk hari tersebut sudah penuh.'])->withInput();
        }

        // Hitung nomor antrian
        $queueNumber = $currentCount + 1;

        // Simpan booking
        $booking = Booking::create([
            'user_id'      => $user->id,
            'doctor_id'    => $doctorId,
            'booking_date' => $date,
            'booking_time' => $data['booking_time'],
            'complaint'    => $data['complaint'],
            'status'       => 'pending',
            'queue_number' => $queueNumber,
        ]);

        // Buat entri antrian
        Queue::create([
            'booking_id'   => $booking->id,
            'queue_number' => $queueNumber,
            'queue_date'   => $date,
            'status'       => 'waiting',
        ]);

        // Buat pengingat H-1 jam 08:00
        $remindAt = Carbon::parse($date)->subDay()->setTime(8, 0, 0);
        Reminder::create([
            'user_id'    => $user->id,
            'booking_id' => $booking->id,
            'remind_at'  => $remindAt,
            'channel'    => 'in_app',
        ]);

        return redirect('/booking/' . $booking->id)
            ->with('success', 'Booking berhasil dibuat! Nomor antrian Anda: A-' . str_pad($queueNumber, 3, '0', STR_PAD_LEFT));
    }

    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with('doctor')
            ->orderByDesc('booking_date')
            ->paginate(10);

        return view('booking.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['doctor', 'queue'])->findOrFail($id);
        abort_if($booking->user_id !== Auth::id(), 403);
        return view('booking.show', compact('booking'));
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        abort_if($booking->user_id !== Auth::id(), 403);

        if (!$booking->canBeCancelled()) {
            return back()->with('error', 'Booking tidak dapat dibatalkan (sudah melewati hari H atau sudah selesai).');
        }

        $booking->update(['status' => 'cancelled']);

        if ($booking->queue) {
            $booking->queue->update(['status' => 'skipped']);
        }

        return redirect('/booking/riwayat')->with('success', 'Booking berhasil dibatalkan.');
    }
}
