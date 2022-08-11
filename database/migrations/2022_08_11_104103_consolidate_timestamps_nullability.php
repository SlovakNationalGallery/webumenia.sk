<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private $tables = [
        'articles',
        'authorities',
        'collections',
        'element_sets',
        'elements',
        'featured_pieces',
        'items',
        'links',
        'nationalities',
        'orders',
        'sketchbooks',
        'spice_harvester_harvests',
        'spice_harvester_records',
        'users',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $table) {
            DB::statement("
                ALTER TABLE $table
                MODIFY COLUMN created_at timestamp NULL,
                MODIFY COLUMN updated_at timestamp NULL;
            ");
        }

        DB::unprepared("
            UPDATE spice_harvester_records SET datestamp = '1000-01-01 00:00:00' WHERE datestamp = 0;
            ALTER TABLE spice_harvester_records MODIFY COLUMN datestamp datetime NULL;
            UPDATE spice_harvester_records SET datestamp = NULL WHERE datestamp = '1000-01-01 00:00:00';
        ");
    }

    /**
     * Reverse the migrations.
     *
     * Note, you'll have to disable strict mode (in config/database.php)
     * in order to run this rollback.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("
            UPDATE spice_harvester_records SET datestamp = '0000-00-00 00:00:00' WHERE datestamp IS NULL;
            ALTER TABLE spice_harvester_records MODIFY COLUMN datestamp datetime NOT NULL;
        ");

        foreach ($this->tables as $table) {
            DB::statement("
                ALTER TABLE $table
                MODIFY COLUMN created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
                MODIFY COLUMN updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00';
            ");
        }
    }
};
