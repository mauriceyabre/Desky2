<?php

namespace App\Support\Classes;

use App\Models\Vat;

class FicClient {
    public int $id;
    public ?string $name;
    public ?string $type;
    public ?string $first_name;
    public ?string $last_name;
    public ?string $vat_number;
    public ?string $tax_code;
    public ?string $email;
    public ?string $phone;
    public ?string $bank_iban;
    public ?string $created_at;
    public ?string $updated_at;
    public ?string $bank_name;
    public ?string $bank_swift_code;
    public ?bool $e_invoice;
    public ?string $ei_code;
    public Vat|null $default_vat;
    public ?string $address_street;
    public ?string $address_postal_code;
    public ?string $address_city;
    public ?string $address_province;
    public ?string $country;

    public function __construct($client) {
        foreach ($client as $key => $value) {
            if (property_exists($this, $key)) {
                if ($key === 'default_vat' && $value !== null) {
                    $this->default_vat = new Vat(collect($value)->toArray());
                    continue;
                }
                $this->$key = $value;
            }
        }
    }
}
