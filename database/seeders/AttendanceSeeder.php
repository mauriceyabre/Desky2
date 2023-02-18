<?php

namespace Database\Seeders;

use App\Enums\AbsenceCause;
use App\Models\Attendance;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder {
    public function run() {
        $members = User::all();
        $now = CarbonImmutable::today();

        $festivo = [
            '2021-01-01',
            '2021-01-06',
            '2021-04-18',
            '2021-04-25',
            '2021-05-01',
            '2021-06-02',
            '2021-08-15',
            '2021-11-01',
            '2021-12-08',
            '2021-12-25',
            '2021-12-26',
            '2022-01-01',
            '2022-01-06',
            '2022-04-18',
            '2022-04-25',
            '2022-05-01',
            '2022-06-02',
            '2022-08-15',
            '2022-11-01',
            '2022-12-08',
            '2022-12-25',
            '2022-12-26',
            '2023-01-01',
            '2023-01-06',
            '2023-04-10',
            '2023-04-25',
            '2023-05-01',
            '2023-06-02',
            '2023-08-15',
            '2023-11-01',
            '2023-12-08',
            '2023-12-25',
            '2023-12-26',
        ];

        foreach ($members as $member) {
            $data = [];
            $id = $member->id;

            for ($i = 1; $i < 365; $i++) {
                $date = $now->subDays($i);
                $time = $date->setTime(9, rand(15, 45))->toDateTimeString();

                $isHoliday = in_array($date->format('Y-m-d'), $festivo);

                if ($date->isWeekend() || $isHoliday) {
                    continue;
                }

                // ABSENCES COMPUTATION
                $absence = null;
                if (rand(1, 15) === 1) {
                    $absence = fake()->randomElement(AbsenceCause::values());
                }

                $data[] = [
                    'date' => $date->format('Y-m-d'),
                    'user_id' => $id,
                    'absence' => $absence,
                    'created_at' => $time,
                    'updated_at' => $time,
                ];
            }

            Attendance::insert($data);
        }
    }
}
