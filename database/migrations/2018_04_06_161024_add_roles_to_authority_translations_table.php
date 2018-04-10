<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRolesToAuthorityTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authority_translations', function (Blueprint $table) {
            $table->text('roles')->nullable();
        });

        $default_locale = config('app.locale');

        $authority_roles = DB::select('
            SELECT authority_id, GROUP_CONCAT(CONCAT(\'"\', role, \'"\') SEPARATOR \', \') as roles
            FROM authority_roles GROUP BY authority_id
            ');

        foreach ($authority_roles as $ar) {
            $roles = '[' . $ar->roles . ']';
            $result = DB::table('authority_translations')
                ->where('authority_id', $ar->authority_id)
                ->where('locale', $default_locale)
                ->update(['roles' => $roles]);
        }

        Schema::drop('authority_roles');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::create('authority_roles', function ($table) {
            $table->increments('id');
            $table->string('authority_id');
            $table->boolean('prefered');
            $table->string('role');
        });


        Schema::table('authority_translations', function (Blueprint $table) {
            $table->dropColumn('roles');
        });
    }
}
