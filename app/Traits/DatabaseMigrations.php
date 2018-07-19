<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait DatabaseMigrations
{
    use \Illuminate\Foundation\Testing\DatabaseMigrations {
        \Illuminate\Foundation\Testing\DatabaseMigrations::runDatabaseMigrations as parentRunDatabaseMigrations;
    }

    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    public function runDatabaseMigrations()
    {
        $tables = DB::select('SHOW TABLES');

        if ($tables) {
            $droplist = [];
            $colname = 'Tables_in_' . env('DB_DATABASE');
            foreach ($tables as $table) {
                $droplist[] = $table->$colname;
            }
            $droplist = implode(',', $droplist);

            DB::beginTransaction();
            //turn off referential integrity
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            DB::statement("DROP TABLE $droplist");
            //turn referential integrity back on
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            DB::commit();
        }

        $this->parentRunDatabaseMigrations();

        array_pop($this->beforeApplicationDestroyedCallbacks);
    }
}
