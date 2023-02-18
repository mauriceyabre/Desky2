<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

trait TruncateTable
{
    public function truncate($table): void
    {
        DB::table($table)->truncate();
    }
}