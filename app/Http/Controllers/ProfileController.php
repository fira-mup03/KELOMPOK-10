<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\TreatmentHistory;
use App\Models\Booking;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // Dashboard stats
        $totalBookings      = $user->bookings()->count();
        $lastVisit          = $user->treatmentHistories()->latest('treatment_date')->first();
        $nextBooking        = $user->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('booking_date', '>=', today())
            ->orderBy('booking_date')
            ->first();

        // Health dashboard metrics
        $thisYearVisits     = $user->treatmentHistories()
            ->whereYear('treatment_date', now()->year)
            ->count();

        $treatments         = $user->treatmentHistories()->orderBy('treatment_date')->get();
        $avgInterval        = null;
        if ($treatments->count() >= 2) {
            $totalDays = 0;
            for ($i = 1; $i < $treatments->count(); $i++) {
                $totalDays += $treatments[$i]->treatment_date->diffInDays($treatments[$i - 1]->treatment_date);
            }
            $avgInterval = round($totalDays / ($treatments->count() - 1));
        }

        $distinctTreatments = $user->treatmentHistories()
            ->selectRaw('COUNT(DISTINCT treatment) as count')
            ->value('count');

        $latestDiagnosis    = $lastVisit?->diagnosis;
        $healthStatus       = $this->calculateHealthStatus($latestDiagnosis);

        return view('profile.index', compact(
            'user', 'totalBookings', 'lastVisit', 'nextBooking',
            'thisYearVisits', 'avgInterval', 'distinctTreatments', 'healthStatus'
        ));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|in:L,P',
            'address'       => 'nullable|string',
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return redirect('/profile')->with('success', 'Profil berhasil diperbarui!');
    }

    private function calculateHealthStatus(?string $diagnosis): string
    {
        if (!$diagnosis) return 'Belum Ada Data';
        $diagnosis = strtolower($diagnosis);
        if (str_contains($diagnosis, 'sehat') || str_contains($diagnosis, 'normal')) {
            return 'Baik';
        }
        if (str_contains($diagnosis, 'gigi berlubang') || str_contains($diagnosis, 'karies')) {
            return 'Perlu Perawatan';
        }
        return 'Perlu Perhatian';
    }
}
