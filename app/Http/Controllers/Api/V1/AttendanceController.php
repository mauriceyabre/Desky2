<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Date;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AttendanceController extends Controller {
    public function index(Request $request) {
        $request->validate([
            'user_id' => ['int', 'exists:users,id'],
            'date' => ['date']
        ]);

        $attendances = Attendance::query()
            ->with('presenceLogs')
            ->when($request->filled('user_id'), function ($query) use ($request) {
                $query->where('user_id', '=', $request->input('user_id'));
            })->when($request->filled('date'), function ($query) use ($request) {
                $date = Date::make($request->input('date'));
                $query->whereYear('date', $date->year)->whereMonth('date', $date->month);
            })
            ->get();

        return response()->json(['attendances' => $attendances], Response::HTTP_OK);
    }

    public function store(Request $request) {

    }

    public function show(Request $request, int $id) {
        $request->validate([
            'with' => ['array']
        ]);

        $query = Attendance::query()->whereId($id)->with('presenceLogs');

        if ($request->filled('with')) {
            if (in_array('projects', $request->input('with'))) {
                $query->with('projects');
            }
        }

        $attendance = $query->firstOrFail();

        return response()->json(['attendance' => $attendance], Response::HTTP_OK);
    }

    /**
     * @throws Throwable
     */
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

        if ($request->hasAny(['create', 'edit', 'delete', 'absence', 'projects'])) {

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
                        abort(Response::HTTP_BAD_REQUEST, 'Sembra che ci sia un problema con gli orari.1');
                    }

                    if ($presences->count() > 1) {
                        $currentIndex = $presences->search(function ($item) use ($presence) { return $item['id'] === $presence['id']; });
                        if ($currentIndex > 0) {
                            $prevAttendance = $presences->get($currentIndex - 1);
                            $prev_presence_end = Carbon::parse($prevAttendance->ended_at);
                            if ($start->lt($prev_presence_end)) {
                                abort(Response::HTTP_BAD_REQUEST, 'Sembra che ci sia un problema con gli orari.2');
                            }
                        }

                    }

                    $attendance->presenceLogs()->whereId($presence['id'])->update([
                        'started_at' => $start->toDateTimeString(),
                        'ended_at' => $end->toDateTimeString()
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
                        abort(Response::HTTP_BAD_REQUEST, 'Sembra che ci sia un problema con gli orari.3');
                    }

                    if ($index === 0) {
                        if ($presences->count() > 0) {
                            $prevAttendance = $presences->last();
                            $prev_presence_end = Carbon::parse($prevAttendance['ended_at']);
                            if ($start->lt($prev_presence_end)) {
                                abort(Response::HTTP_BAD_REQUEST, 'Sembra che ci sia un problema con gli orari.4');
                            }
                        }
                    } else {
                        $prevAttendance = collect($data)->last();
                        $prev_presence_end = Carbon::parse($prevAttendance['ended_at']);

                        if ($start->lt($prev_presence_end)) {
                            abort(Response::HTTP_BAD_REQUEST, 'Sembra che ci sia un problema con gli orari.5');
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
                    abort(Response::HTTP_BAD_REQUEST, 'Non è possibile registrare un permesso quando hai lavorato almeno 8 ore.');
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
                    abort(Response::HTTP_BAD_REQUEST, 'Le ore dedicate ai progetti sono superiori al totale delle ore lavorate.');
                }
            }

            return response()->json(['toast' => 'Presenza Aggiornata', 'attendance' => $attendance]);
        }
        return response();
    }

    public function destroy($id) {
    }
}
