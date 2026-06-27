<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TreatmentHistory;
use App\Models\Doctor;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $query = TreatmentHistory::with('doctor')
            ->where('user_id', Auth::id())
            ->orderByDesc('treatment_date');

        if ($request->filled('year')) {
            $query->whereYear('treatment_date', $request->year);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        $histories = $query->paginate(10);
        $doctors   = Doctor::active()->get();
        $years     = TreatmentHistory::where('user_id', Auth::id())
            ->selectRaw('YEAR(treatment_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('treatment.index', compact('histories', 'doctors', 'years'));
    }

    public function show($id)
    {
        $history = TreatmentHistory::with(['doctor', 'booking'])->findOrFail($id);
        abort_if($history->user_id !== Auth::id(), 403);

        $related = TreatmentHistory::where('user_id', Auth::id())
            ->where('id', '!=', $id)
            ->orderByDesc('treatment_date')
            ->limit(3)
            ->get();

        return view('treatment.show', compact('history', 'related'));
    }
}
