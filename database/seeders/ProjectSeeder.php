<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Project;
use App\Models\User;
use Carbon\CarbonImmutable;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use DB;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder {
    use DisableForeignKeys, TruncateTable;

    public function run(): void
    {
        $customersId = Customer::all()->pluck('id')->toArray();

        $data = [];

        for ($i = 0; $i < 500; $i++) {

            $allPhases = array_keys(Project::$PHASES);
            $users_id = User::all()->pluck('id');

            // Project Name
            $name = strtoupper(trim(fake()->randomElement([
                    'Render',
                    'Rendering',
                    'Video',
                    'Foto inserimento',
                    'Planimetrie',
                    'catalogo',
                    'modellazione'
                ]) . ' ' . fake()->randomElement(['interni', 'esterni', 'ambientati', 'panoramici', 'aerei']) . ' ' . fake()->randomElement([
                    'appartamento',
                    'villa',
                    'casa',
                    'palazzo',
                    'palazzi',
                    'prodotto',
                    'pergola',
                    'mobile'
                ])));

            // Creation Date
            $created_at = CarbonImmutable::create(fake()->dateTimeBetween('-24 months'));

            // Phase
            $selPhase = fake()->randomElement($allPhases);
            if (now()->toImmutable()->subMonths(3) > $created_at) {
                // Older than two months
                $selPhase = 'completed';
            }

            // Start Date
            $started_date = null;
            $started_at = null;
            $deadline = null;
            if ($selPhase != 'deal') {
                $start_future = $created_at->addDays(random_int(2, 30));
                $started_date = (($start_future)->gt(now()->toImmutable())) ? now()->toImmutable() : $start_future;
                $started_at = $started_date->toDateString();

                // Deadline
                $deadline = fake()->optional(0.9)->passthrough($started_date->addDays(fake()->randomElement([14, 21, 30, 45]))->toDateString());
            }


            // Deliver date + Completed Date
            $delivered_at = null;
            $completed_at = null;
            $deleted_at = null;
            $deliver_date = null;
            $complete_date = null;

            if ($selPhase === 'delivered' || $selPhase === 'completed') {
                $deliver_future = $started_date->addDays(random_int(5, 45));
                $deliver_date = $deliver_future->gt(now()->toImmutable()) ? now()->toImmutable() : $deliver_future;
                $delivered_at = $deliver_date->toDateString();

                if ($selPhase === 'completed') {
                    $complete_future = $deliver_date->addDays(random_int(5, 8));
                    $complete_date = $complete_future->gt(now()) ? now()->toImmutable() : $deliver_future;
                    $completed_at = $complete_date->toDateString();
                }
            }

            $delRandom = random_int(1, 10);
            if ($delRandom === 2) {
                $selPhase = fake()->randomElement($allPhases);

                switch ($selPhase) {
                    case 'deal':
                        $completed_at = null;
                        $delivered_at = null;
                        $started_at = null;
                        break;
                    case 'ongoing':
                    case 'approved':
                    case 'new':
                        $completed_at = null;
                        $delivered_at = null;
                        break;
                    case 'delivered':
                        $completed_at = null;
                }

                $maxDate = max($created_at?->timestamp, $started_date?->timestamp, $deliver_date?->timestamp, $complete_date?->timestamp);
                $deleted_at = CarbonImmutable::parse($maxDate)->addDays(5)->toDateTimeString();
            }

            // Quote
            $quote_amount = (fake()->randomNumber(1)) * 400;
            $quote = (int)fake()->optional(0.7)->passthrough($quote_amount);

            // Advance Payment
            $deposit_amount = $quote * 0.3;
            $deposit = ($selPhase === 'deal') ? null : (int)fake()->optional(0.8)->passthrough($deposit_amount);

            $data[] = [
                'name' => $name,
                'description' => fake()->optional()->realText(),
                'phase' => $selPhase,
                'index' => null,
                'quote' => $quote,
                'deposit' => $deposit,
                'deadline' => $deadline,
                'customer_id' => $customersId[array_rand($customersId)],
                'created_by' => fake()->randomElement($users_id),
                'started_at' => $started_at,
                'delivered_at' => $delivered_at,
                'completed_at' => $completed_at,
                'created_at' => $created_at->toDateTime(),
                'deleted_at' => $deleted_at
            ];
        }

        foreach (array_chunk($data, 100) as $chunk) {
            Project::insert($chunk);
        }

        $members = User::all()->pluck('id');
        $projects = Project::all()->pluck('id');
        $projectsCount = $projects->count() * 3;

        $pivotData = [];
        for ($i = 0; $i < $projectsCount; $i++) {
            $pivotData[] = [
                'user_id' => $members->random(),
                'project_id' => $projects->random()
            ];
        }

        DB::table('project_user')->insert(array_unique($pivotData, SORT_REGULAR));
    }
}
