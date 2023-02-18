<?php


namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller {

    public function fetch(Request $request) {
        $request->validate([
            'user' => 'sometimes|exists:users,id',
            'date' => 'sometimes|date',
            'with' => 'sometimes|array',
            'with.*' => ['required_unless:with,null', 'string', Rule::in(['projects'])],
            'projects' => 'sometimes|array',
            'projects.*' => 'alpha_num|exists:projects,id|required_unless:projects,null'
        ]);

        if ($request->filled('date')) {
            $date = Carbon::parse($request->input('date'));
        }  else {
            $date = today();
        }

        $query = Attendance::query();

        if ($request->filled('user')) {
            $query = $query->whereRelation('user', 'id', '=', $request->input('user'));
        };


        if ($date) {
            $query = $query->whereYear('date', $date->year)
                ->whereMonth('date', $date->month);
        }

        if ($request->filled('with')) {
            $relations = $request->input('with');
            if (in_array('projects', $relations)) {
                $query = $query->with('projects', function ($query) {
                    $query->with('customer:id,name')->without(['members', 'creator']);
                });
            }
        }

        return $query->get();
    }

    public function store(Request $request) {

    }

    public function update(Request $request, $id = null) {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'user_id' => 'required|exists:users,id',
            'absence' => ['string', 'nullable', 'sometimes', Rule::in(['holidays', 'permit', 'sickness'])],
            'absence.action' => ['string', Rule::in(['create', 'edit', 'delete'])],
            'projects' => 'array|sometimes',
            'projects.*.id' => 'required|exists:projects,id',
            'projects.*.duration' => 'required|int|min:30|max:720'
        ]);

        if ($request->inertia() && $request->hasAny(['create', 'edit', 'delete', 'absence', 'projects'])) {

            if ($id) {
                $attendance = Attendance::findOrFail($id);
            } else {
                $attendance = Attendance::create([
                    'date' => $request->input('date'),
                    'user_id' => $request->input('user_id')
                ]);
            }

            $date = $attendance->date->format('Y-m-d');

            $toDeletes = $request->input('delete');
            $toEdits = $request->input('edit');
            $toCreates = $request->input('create');

            if (!empty($toDeletes)) {
                foreach ($toDeletes as $presence) {
                    $attendance->presenceLogs()->whereId($presence['id'])->delete();
                }
            }

            if (!empty($toEdits)) {
                if ($toDeletes) {
                    $attendance->refresh();
                }
                $presences = collect($attendance->presenceLogs);

                foreach ($toEdits as $presence) {
                    $start = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $presence['started_at']);
                    $end = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $presence['ended_at']);

                    if ($start->gt($end)) {
                        return back()->withErrors(['generic' => 'Sembra che ci sia un problema con gli orari.1']);
                    }

                    if ($presences->count() > 1) {
                        $currentIndex = $presences->search(function ($item) use ($presence) { return $item['id'] === $presence['id']; });
                        if ($currentIndex > 0) {
                            $prevAttendance = $presences->get($currentIndex - 1);
                            $prev_presence_end = Carbon::parse($prevAttendance->ended_at);
                            if ($start->lt($prev_presence_end)) {
                                return back()->withErrors(['generic' => 'Sembra che ci sia un problema con gli orari.2']);
                            }
                        }

                    }

                    $attendance->presenceLogs()->whereId($presence['id'])->update([
                        "started_at" => $start->toDateTimeString(),
                        "ended_at" => $end->toDateTimeString()
                    ]);
                }
            }

            if (!empty($toCreates)) {
                $data = [];

                if (!empty($toDeletes) || !empty($toEdits)) {
                    $attendance->refresh();
                }

                $presences = collect($attendance->presenceLogs);

                foreach ($toCreates as $index => $presence) {
                    $start = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $presence['started_at']);
                    $end = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $presence['ended_at']);

                    if ($start->gt($end)) {
                        return back()->withErrors(['generic' => 'Sembra che ci sia un problema con gli orari.3']);
                    }

                    if ($index === 0) {
                        if ($presences->count() > 0) {
                            $prevAttendance = $presences->last();
                            $prev_presence_end = Carbon::parse($prevAttendance['ended_at']);
                            if ($start->lt($prev_presence_end)) {
                                return back()->withErrors(['generic' => 'Sembra che ci sia un problema con gli orari.4']);
                            }
                        }
                    } else {
                        $prevAttendance = collect($data)->last();
                        $prev_presence_end = Carbon::parse($prevAttendance['ended_at']);

                        if ($start->lt($prev_presence_end)) {
                            return back()->withErrors(['generic' => 'Sembra che ci sia un problema con gli orari.5']);
                        }
                    }
                    $data[] = [
                        'started_at' => $start->toDateTimeString(),
                        'ended_at' => $end->toDateTimeString()
                    ];
                }

                $attendance->presenceLogs()->createMany($data);
            }

            if (!empty($toDeletes) || !empty($toEdits) || !empty($toCreates)) {
                $attendance->refresh();
            }

            if ($request->has('absence')) {
                if ($attendance->presenceDuration >= 480) {
                    return back()->withErrors(['generic' => 'Non è possibile registrare un permesso quando hai lavorato almeno 8 ore.']);
                }
                $attendance->updateOrFail(['absence' => $request->input('absence')]);
            }

            if ($request->has('projects')) {
                $projects = collect($request->input('projects'));
                $duration = $projects->sum('duration');

                if ($attendance->presenceDuration >= $duration) {
                    $toSyncProjects = [];

                    foreach ($request->input('projects') as $project) {
                        $toSyncProjects[$project['id']] = ['duration' => $project['duration']];
                    }
                    $attendance->projects()->sync($toSyncProjects);
                } else {
                    return back()->withErrors(['generic' => 'Le ore dedicate ai progetti sono superiori al totale delle ore lavorate.']);
                }
            }
        }

        return back()->with('toast', ['message' => 'Presenza Aggiornata']);
    }

    public function destroy(Attendance $attendance) {
    }
}
