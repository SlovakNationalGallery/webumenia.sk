<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDuplicationsInAuthorityItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TABLE authority_item_temp SELECT authority_id, item_id, MAX(role) as role FROM authority_item GROUP BY authority_id, item_id');
        Schema::rename('authority_item', 'authority_item_junk');
        Schema::rename('authority_item_temp', 'authority_item');
        Schema::table('authority_item', function (Blueprint $table) {
            $table->primary(['authority_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authority_item', function (Blueprint $table) {
            $table->dropPrimary(['authority_id', 'item_id']);
        });
        Schema::dropIfExists('authority_item_junk');
    }
}
