<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAuthorityTypeToAuthor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            $result = DB::table('authorities')
                ->where('type', 'person')
                ->update([
                    'type' => 'author',
                ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
            $result = DB::table('authorities')
                ->where('type', 'author')
                ->update([
                    'type' => 'person',
                ]);
        }
    }
}
