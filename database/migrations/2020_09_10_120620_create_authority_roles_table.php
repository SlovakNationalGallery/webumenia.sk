<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class CreateAuthorityRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('authority_roles');
        Schema::dropIfExists('authority_role_translations');
        Schema::create('authority_roles', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->text('type');
        });

        Schema::create('authority_role_translations', function (Blueprint $table) {
            $table->text('type');
            $table->string('locale')->index();
            $table->text('role')->default('');
        });

        $roles = [
            ["author",                       "autor",                           "author"],
            ["author.after",                 "autor predlohy",                  "after"],
            ["author.atelier",               "ateliér",                         "atelier"],
            ["author.circle",                "okruh autora",                    "circle of"],
            ["author.copyist",               "kopista",                         "copyist of"],
            ["author.draft",                 "autor návrhu",                    "draft by"],
            ["author.drawer",                "kresliar",                        "draftsman"],
            ["author.engraver",              "rytec",                           "engraver"],
            ["author.epigone",               "napodobňovateľ",                  "epigone of"],
            ["author.follower",              "nasledovník",                     "follower of"],
            ["author.former",                "pôvodné určenie",                 "formerly attributed to"],
            ["author.graphic",               "grafik",                          "graphic artist"],
            ["author.modifier",              "autor úpravy záznamu",            "modified by"],
            ["author.office",                "štúdio",                          "office of"],
            ["author.original",              "autor origináu",                  "original by"],
            ["author.printer",               "tlačiar",                         "printer"],
            ["author.probably",              "pravdepodobne",                   "probably by"],
            ["author.probablyAfter",         "pravdepodobný autor predlohy",    "probably after"],
            ["author.probablyCircle",        "pravdepodobne okruh autora",      "probably circle of"],
            ["author.probablyDrawer",        "pravdepodobne kresliar",          "probable draftsman"],
            ["author.probablyEngraver",      "pravdepodobne rytec",             "probable engraver"],
            ["author.probablyPrinter",       "pravdepodobne tlačiar",           "probable printer"],
            ["author.probablyWorkshop",      "pravdepodobne dielňa autora",     "probably workshop of"],
            ["author.producer",              "výrobca",                         "producer"],
            ["author.publisher",             "vydavateľ",                       "publisher"],
            ["author.restorer",              "reštaurátor",                     "restorer"],
            ["author.workshop",              "dielňa autora",                   "workshop of"],
            ["author.concept",               "autor konceptu",                  "concept by"],
            ["author.photograph",            "autor fotografie",                "photographer"],
        ];
        foreach ($roles as $role) {
            $authority_roles[] = [
                'type' => $role[1] . '/' . $role[2],
            ];
        }
        DB::table('authority_roles')->insert($authority_roles);

        foreach ($roles as $role) {
                $role_translations[] = [
                    'type'      => $role[1] . '/' . $role[2],
                    'locale'    => 'sk',
                    'role'      => $role[1]
                ];
                $role_translations[] = [
                    'type'      => $role[1] . '/' . $role[2],
                    'locale'    => 'en',
                    'role'      => $role[2]
                ];
                $role_translations[] = [
                    'type'      => $role[1] . '/' . $role[2],
                    'locale'    => 'cs',
                    'role'      => $role[1]
                ];
        }
        DB::table('authority_role_translations')->insert($role_translations);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authority_roles');
        Schema::dropIfExists('authority_role_translations');
    }
}
