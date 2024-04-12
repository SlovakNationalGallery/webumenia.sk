<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('can_administer')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_publish')->default(false);
            $table->boolean('can_import')->default(false);
        });

        DB::table('users')
            ->eachById(function ($user) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'can_administer' => $user->role === 'admin',
                        'can_edit' => $user->role === 'editor',
                        'can_import' => $user->role === 'importer',
                    ]);
            });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['can_administer', 'can_edit', 'can_publish', 'can_import']);
        });
    }
};
