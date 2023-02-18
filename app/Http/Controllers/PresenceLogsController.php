<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\PresenceLog;
use Auth;
use Illuminate\Http\Request;

class PresenceLogsController extends Controller
{
    public function start(Request $request)
    {
        $attendance = Attendance::with('latestPresenceLog')->whereUserId(Auth::id())
            ->firstOrCreate([
                'user_id' => Auth::id(),
                'date' => date('Y-m-d')
            ],[
                'status' => AttendanceStatus::NEW,
                'date' => today()->toDateString(),
                'user_id' => Auth::id()
            ]);

        if (!!$attendance->latestPresenceLog && $attendance->latestPresenceLog->is_active) {
            return back()->with('toast', ['type' => 'danger', 'message' => 'C\'è già una sessione attiva']);
        } else {
            $presence = PresenceLog::create([
                'started_at' => now()->format('Y-m-d H:i:s'),
                'attendance_id' => $attendance->id,
            ]);
            return back()->with('toast', ['type' => 'success', 'message' => 'Sessione avviata alle: ' . $presence->started_at->toTimeString('minute')]);
        }
    }

    public function update(Request $request, PresenceLog $presence)
    {
    }

    public function stop(PresenceLog $presence_log)
    {
        $presence_log->ended_at = now();
        $presence_log->saveOrFail();

        return back()->with('toast', ['type' => 'success', 'message' => 'Sessione Terminata']);
    }

    public function destroy(PresenceLog $presence_log)
    {
        $presence_log->deleteOrFail();

        return back()->with('toast', ['type' => 'success', 'message' => 'Sessione Eliminata']);
    }
}
