<?php

namespace Database\Seeders;

use App\Models\Vat;
use Illuminate\Database\Seeder;

class VatSeeder extends Seeder {
    public function run() {
        $vats = [
            [
                'ref_id' => 2682847,
                'value' => 22,
                'description' => '22% Aliquota Ordinaria - D.P.R. 633/72'
            ],
            [
                'ref_id' => 2310514,
                'value' => 0,
                'description' => 'Non soggetta art. 1/54-89 L. 190/2014 e succ. modifiche/integrazioni - Regime Forfettario'
            ],
            [
                'ref_id' => 35,
                'value' => 0,
                'description' => 'Escluso Art.7 D.P.R 633/72 - Estero'
            ]
        ];
        Vat::insert($vats);
    }
}
