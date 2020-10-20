<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \App\Authority;

class AddFrontpageImageToAuthorities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('authorities', function (Blueprint $table) {
            $table->string('frontpage_image')->nullable();
        });

        foreach (Authority::all() as $authority) {
            $old_path = '/images/khb/';
            $name = "thumb-".str_slug($authority->formatedName, '-').".jpg";
            if (file_exists(public_path($old_path.$name))) {
                rename(public_path($old_path.$name), public_path(Authority::FRONTPAGE_IMG_DIR.$name));
                $authority->frontpage_image = $name;
                $authority->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('authorities', function (Blueprint $table) {
            $table->dropColumn('frontpage_image');
        });

        foreach (Authority::all() as $authority) {
            $old_path = '/images/khb/';
            $name = "thumb-".str_slug($authority->formatedName, '-').".jpg";
            if (file_exists(public_path(Authority::FRONTPAGE_IMG_DIR.$name))) {
                rename(public_path(Authority::FRONTPAGE_IMG_DIR.$name), public_path($old_path.$name));
            }
        }
    }
}
