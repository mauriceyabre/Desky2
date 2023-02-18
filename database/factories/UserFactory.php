<?php

    namespace Database\Factories;

    use App\Models\User;
    use Carbon\Carbon;
    use Exception;
    use Illuminate\Database\Eloquent\Factories\Factory;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Str;

    class UserFactory extends Factory {
        protected $model = User::class;

        /**
         * @throws Exception
         */
        public function definition(): array {

            $firstname = strtolower($this->faker->firstName());
            $lastname = strtolower($this->faker->lastName());

            $created_at = $this->faker->dateTimeBetween('-1 year', '-6 days');

            return [
                'role_key' => 'designer',
                'first_name' => $firstname,
                'last_name' => $lastname,
                'email' => $this->faker->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'phone' => fake()->optional(90)->passthrough('3' . random_int(100000000, 999999999)),
                'birthday' => $this->faker->optional()->passthrough(fake()->dateTimeBetween('-35 years', '-20 years')->format('Y-m-d')),
                'description' => $this->faker->realText(),
                'remember_token' => Str::random(10),
                'iban' => fake()->bankAccountNumber(),
                'tax_id' => fake()->taxId(),
                'vat_id' => fake()->vat(),
                'last_login' => now()->subDays(rand(1,7))->subHours(rand(1,12)),
                'created_at' => $created_at,
                'updated_at' => Carbon::create($created_at)->addDay(),
                'hire_date' => now()->subMonths(rand(12,28))->format('Y-m-d')
            ];
        }

        public function unverified(): Factory {
            return $this->state(function () {
                return [
                    'email_verified_at' => null,
                ];
            });
        }
    }
