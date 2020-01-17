<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpgradeJobsTable extends Migration
{
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            if (!DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
                $table->dropIndex('jobs_queue_reserved_reserved_at_index');
            }
            $table->dropColumn('reserved');
            $table->index(['queue', 'reserved_at']);
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->longText('exception')->nullable()->after('payload');
        });
    }

    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->tinyInteger('reserved')->nullable()->unsigned();
            $table->index(['queue', 'reserved', 'reserved_at']);
            if (!DB::connection() instanceof \Illuminate\Database\SQLiteConnection) {
                $table->dropIndex('jobs_queue_reserved_at_index');
            }
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->dropColumn('exception');
        });
    }
}
