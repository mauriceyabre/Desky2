<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory {
    protected $model = Customer::class;

    public function definition() : array {
        // TYPE
        $type = $this->faker->optional(0.3, 'company')->randomElement(array_keys(Customer::$TYPES));

        // COUNTRY
        $country = $this->faker->optional(0.05, 'IT')->passthrough('CH');

        // PROVINCE
        $province = $this->faker->stateAbbr();

        // ADDRESS
        $address = [
            'street' => $this->faker->streetAddress(),
            'postal_code' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'province' => $province
        ];
        if ($country !== 'IT') $address = null;

        // VAT + PEC
        if ($type !== 'private') {
            $vat = $this->faker->optional()->vatId();
            $pec = $this->faker->optional()->companyEmail();
        } else {
            $vat = null;
            $pec = null;
        }

        // TAXCODE
        if($type === 'company') {
            $taxCode = $vat;
        }else {
            $taxCode = $this->faker->optional(0.25)->taxId();
        }

        // SDI
        if($country === 'IT') {
            if ($type !== 'private') {
                $sdi = $this->faker->optional(0.6)->regexify('[A-Z][0-9][A-Z]{4}[0-9]');
            } else {
                $sdi = '0000000';
            }
        } else {
            $sdi = 'XXXXXXX';
        }

        // SOCIAL LINKS
        $social_links = [
            'facebook' => $this->faker->optional(0.2)->url(),
            'instagram' => $this->faker->optional(0.2)->url(),
            'linkedin' => $this->faker->optional(0.2)->url(),
        ];

        // ADMINS ID
        $createdBy = $this->faker->randomElement([1,2,4]);

        return [
            'type' => $type,
            'name' => $this->faker->company(),
            'address' => $address,
            'country_code' => $country,
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'vat' => $vat,
            'tax_code' => $taxCode,
            'pec' => $pec,
            'sdi' => $sdi,
            'description' => $this->faker->optional(0.1)->realText(),
            'industry' => $this->faker->optional(0.4)->randomElement(['Agenzia Immobiliare', 'Architetto', 'Ingegnere', 'Studio di Progettazione']),
            'website' => $this->faker->optional(0.25)->url(),
            'social_links' => $social_links,
            'created_by' => $createdBy
        ];
    }
}
