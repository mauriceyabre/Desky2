<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vat extends Model {
    protected $fillable = [
        'id',
        'ref_id',
        'description',
        'value'
    ];

    protected $casts = [
        'id' => 'int',
        'ref_id' => 'int',
        'description' => 'string',
        'value' => 'int'
    ];

    public $timestamps = false;
}
