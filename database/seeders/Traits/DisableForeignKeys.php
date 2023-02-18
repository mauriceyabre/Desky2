<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

trait DisableForeignKeys
{
    public function enableForeignKeys(): void
    {
        // DB::statement('SET FOREIGN_KEY_CHECK=1');
        DB::statement('PRAGMA foreign_keys = true');
    }

    public function disableForeignKeys(): void
    {
        // DB::statement('SET FOREIGN_KEY_CHECK=0');
        DB::statement('PRAGMA foreign_keys = false');
    }

}
