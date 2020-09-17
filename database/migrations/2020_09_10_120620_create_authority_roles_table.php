<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorityRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authority_roles', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->text('type');
            $table->text('sk');
            $table->text('en');
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
                'sk' => $role[1],
                'en' => $role[2],
            ];
        }
        DB::table('authority_roles')->insert($authority_roles);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authority_roles');
    }
}
