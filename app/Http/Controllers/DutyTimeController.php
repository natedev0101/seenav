<?php

namespace App\Http\Controllers;

use App\Models\DutyTime;
use App\Models\DutyTimeClosed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Carbon\Carbon;

class DutyTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $closedDuties = DutyTimeClosed::where('user_id', $request->user()->id)
            ->orderBy('ended_at', 'desc')
            ->paginate(10);

        return view('duty_time.index', [
            'activeDuty' => $activeDuty,
            'closedDuties' => $closedDuties,
        ]);
    }

    /**
     * Store a new duty time record.
     */
    public function store(Request $request)
    {
        $dutyTime = DutyTime::create([
            'user_id' => $request->user()->id,
            'started_at' => now(),
        ]);

        return redirect()->route('duty-time.index');
    }

    /**
     * Update the specified duty time record.
     */
    public function update(Request $request, DutyTime $dutyTime)
    {
        if ($request->has('pause')) {
            $dutyTime->update([
                'is_paused' => true,
                'paused_at' => now(),
            ]);
        } elseif ($request->has('resume')) {
            $pauseDuration = Carbon::parse($dutyTime->paused_at)->diffInSeconds(now());
            
            $dutyTime->update([
                'is_paused' => false,
                'paused_at' => null,
                'total_pause_duration' => $dutyTime->total_pause_duration + $pauseDuration,
            ]);
        }

        return redirect()->route('duty-time.index');
    }

    /**
     * End the specified duty time and store it in closed duties.
     */
    public function destroy(Request $request, DutyTime $dutyTime)
    {
        $request->validate([
            'proof_image' => ['required', 'image', 'max:2048'], // max 2MB
        ]);

        $imagePath = $request->file('proof_image')->store('duty-proofs', 'public');
        
        $totalDuration = Carbon::parse($dutyTime->started_at)->diffInSeconds(now());

        DutyTimeClosed::create([
            'user_id' => $request->user()->id,
            'started_at' => $dutyTime->started_at,
            'ended_at' => now(),
            'total_duration' => $totalDuration,
            'total_pause_duration' => $dutyTime->total_pause_duration,
            'proof_image_path' => $imagePath,
        ]);

        $dutyTime->delete();

        return redirect()->route('duty-time.index');
    }
}
