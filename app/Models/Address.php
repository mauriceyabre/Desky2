<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model {
    protected $fillable = [
        'street',
        'city',
        'postcode',
        'province',
        'state',
        'country_code',
        'addressable_type',
        'addressable_id'
    ];

    protected $hidden = [
        'addressable_type'
    ];

    public function addressable(): MorphTo {
        return $this->morphTo();
    }
}
