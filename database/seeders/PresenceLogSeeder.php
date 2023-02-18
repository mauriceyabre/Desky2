<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\PresenceLog;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PresenceLogSeeder extends Seeder
{
    public function run()
    {
        $attendances = Attendance::withoutEagerLoads()->get(['id', 'absence', 'created_at']);
        $presenceData = [];

        foreach ($attendances as $attendance) {
            $rand1 = rand(1, 60);
            $attendance_id = $attendance->id;
            $hasAbsence = !!$attendance->absence;
            $created_at = $attendance->created_at;

            if (!$hasAbsence) {
                if (rand(1,2) === 1) {
                    $durationTotal = rand(480, 600);
                    $firstEnd = $created_at->copy()->addMinutes(rand(240, 270));

                    $presenceData[] = [
                        'started_at' => $created_at,
                        'ended_at' => $firstEnd,
                        'attendance_id' => $attendance_id,
                    ];

                    $duration1 = $created_at->diffInMinutes($firstEnd);
                    $durationDiff = $durationTotal - $duration1;
                    $start2 = $firstEnd->copy()->addMinutes(rand(15, 90));

                    $presenceData[] = [
                        'started_at' => $start2,
                        'ended_at' => $start2->copy()->addMinutes($durationDiff),
                        'attendance_id' => $attendance_id,
                    ];
                } else {
                    $presenceData[] = [
                        'started_at' => $created_at,
                        'ended_at' => $created_at->copy()->addMinutes(rand(480, 600)),
                        'attendance_id' => $attendance_id,
                    ];
                }
            } else {
                if (rand(1, 2) === 1) {
                    if ($rand1 === 1) {
                        $started_at = $created_at;
                    } else {
                        $started_at = $created_at->copy()->addHours(4)->addMinutes(rand(5, 40));
                    }

                    $ended_at = $started_at->copy()->addMinutes(rand(240, 300));

                    $presenceData[] = [
                        'started_at' => $started_at,
                        'ended_at' => $ended_at,
                        'attendance_id' => $attendance_id,
                    ];
                }
            }
        }

        PresenceLog::insert($presenceData);

        // retrieve projects
        $allProjects = Project::withoutEagerLoads()->select(['id', 'started_at', 'completed_at', 'phase'])->get();

        // fetch attendances in chunks
        Attendance::withOnly('presenceLogs')->chunk(20, function($attendances) use ($allProjects) {
            $attendances = $attendances->where('presence_duration', '>', 0);
            $projects = $allProjects->whereBetween('started_at', [$attendances->min('date'), $attendances->max('date')]);

            foreach ($attendances as $attendance) {
                $duration = (int) $attendance->presenceDuration;

                    // round duration to nearest 30 minutes
                    $mod = $duration % 30;
                    $rounded = ($mod >= 20) ? $duration + (30 - $mod) : $duration - $mod;

                    $date = $attendance->date;

                    // filter projects that were active on the attendance date
                    $selectedProjects = $projects->where(function ($project) use ($date) {
                        if ($project['started_at']) {
                            $started_at = Carbon::parse($project['started_at']);
                            if ($project['phase'] === 'completed') {
                                return $date->isBetween($started_at, Carbon::parse($project['completed_at']));
                            } else {
                                return $date->isBetween($started_at, $started_at->copy()->addDays(15));
                            }
                        }
                        return false;
                    })->pluck('id');

                    if ($selectedProjects->isEmpty()) {
                        continue;
                    }

                    $i = 0;
                    $data = [];

                    while ($i < $rounded) {
                        $projectId = $selectedProjects->random();
                        $timeRand = rand(2, 16) * 30;
                        if (($i + $timeRand) < $rounded) {
                            // progetto a caso e gli dò la durata
                            $data[$projectId] = ['duration' => $timeRand];
                        } else {
                            $data[$projectId] = ['duration' => ($rounded - $i)];
                            break;
                        }
                        $i = $i + $timeRand;
                    }

                    $attendance->projects()->sync($data);
                }
        });
    }
}
