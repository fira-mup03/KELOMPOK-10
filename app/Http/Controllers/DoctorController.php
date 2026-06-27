<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

class DoctorController extends Controller
{
    public function schedules($id)
    {
        $doctor = Doctor::with('schedules')->findOrFail($id);

        $schedules = $doctor->schedules->map(function ($schedule) {
            return [
                'day_of_week' => $schedule->day_of_week,
                'start_time'  => $schedule->start_time,
                'end_time'    => $schedule->end_time,
                'max_quota'   => $schedule->max_quota,
                'time_slots'  => $schedule->time_slots,
            ];
        });

        return response()->json([
            'doctor'    => [
                'id'             => $doctor->id,
                'name'           => $doctor->name,
                'specialization' => $doctor->specialization,
            ],
            'schedules' => $schedules,
        ]);
    }
}
