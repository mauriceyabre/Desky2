<?php

namespace Database\Seeders;

use App\Enums\CustomerCategory;
use App\Enums\CustomerType;
use App\Models\Customer;
use App\Models\User;
use App\Support\Classes\FicClient;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder {
    public function run() {

        $data = [];

        $customerTypes = CustomerType::values();
        $adminIds = User::admins()->pluck('id');

        $clientsFile = file_get_contents(resource_path() . '/js/Helpers/Data/Clients.json');
        $clients = collect(json_decode($clientsFile))['data'];

        foreach ($clients as $client) {
            $client = new FicClient($client);

            // Name
            $name = $client->name;
            if (!empty($client->first_name . $client->last_name)) {
                $name = rtrim($client->first_name . ' ' . $client->last_name);
            }

            // Vat Number
            $vat_number = $client->vat_number;
            if (collect($data)->contains('vat_number', $vat_number)) {
                $vat_number = null;
            }

            // Tax Code
            $tax_code = $client->tax_code;
            if (collect($data)->contains('tax_code', $tax_code)) {
                $tax_code = null;
            }

            // Email
            $email = !empty($client->email) ? json_encode($client->email) : fake()->optional()->companyEmail();
            if ($email && collect($data)->contains('email', $email)) {
                $email = null;
            }

            // Social Links
            $numberOfLinks = rand(1,4);
            $links = [];
            $socials = ['facebook', 'instagram', 'tiktok', 'behance'];
            for ($i = 0; $i < $numberOfLinks; $i++) {
                $links[$socials[$i]] = fake()->url();
            }
            $links = collect($links);

            // Default Vat Entity
            $default_vat = $client->default_vat;
            $default_vat_entity = !empty($default_vat) ? json_encode($default_vat->toJson()) : null;

            // Default Vat Id
            if (!empty($default_vat_entity)) {
                $default_vat_id = null;
            } else {
                $default_vat_id = 1;
            }

            $data[] = [
                'ref_id' => $client->id,
                'name' => $name,
                'type' => $client->type ?? collect($customerTypes)->random(),
                'vat_number' => $vat_number,
                'tax_code' => $tax_code,
                'email' => $email,
                'pec' => fake()->optional()->companyEmail(),
                'phone' => empty($client->phone) ? fake()->optional()->phoneNumber() : $client->phone,
                'description' => fake()->optional()->realText(),
                'sdi_code' => $client->ei_code,
                'e_invoice' => $client->e_invoice,
                'default_vat_id' => $default_vat_id,
                'default_vat_entity' => $default_vat_entity,
                'bank_name' => empty($client->bank_name) ? null : $client->bank_name,
                'bank_iban' => empty($client->bank_iban) ? null : $client->bank_iban,
                'bank_swift' => empty($client->bank_swift_code) ? null : $client->bank_swift_code,
                'category' => fake()->optional()->randomElement(CustomerCategory::values()),
                'website' => fake()->optional()->url(),
                'social_links' => fake()->optional()->passthrough(json_encode($links?->toJson())),
                'created_by' => $adminIds->random(),
                'created_at' => $client->created_at,
                'updated_at' => $client->updated_at
            ];
        }

        foreach (array_chunk($data, 50) as $chunk) {
            Customer::insert($chunk);
        }

        $customers = Customer::all();
        $customers->each(function ($customer){
            $address = [
                'street' => fake()->streetAddress(),
                'postcode' => fake()->postcode(),
                'city' => fake()->city(),
                'country_code' => 'IT',
                'state' => null,
                'province' => fake()->stateAbbr()
            ];
            $customer->address()->create($address);
        });
    }
}
