<?php

use Illuminate\Database\Seeder;

class CollectionsTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

        DB::table('collections')->truncate();

        $now = date("Y-m-d H:i:s");

        $collection_id = 1;

        $collections = [
            [
                'id' => $collection_id,
                'name' => '65 - otevřený depozitář',
                'type' => 'sekcia',
                'text' => '
                Sbírku Památníku národního písemnictví tvoří na 7 milionů sbírkových předmětů různého charakteru. Jedná se tedy o jednu z nejvýznamnějších v České republice. Najdeme zde rukopisy, různé verze divadelních her, portréty, dopisy, rukopisné záznamy, autografy, samizdaty, fotografie, obrazy, grafiky, ex libris, plastiky, první vydání knih, knihovny významných osobností, ale i předměty osobní povahy, jako jsou například dýmka, hrací karty, psací stroje nebo nábytek.<br>
                A z tohoto množství k 65. roku založení instituce vybrali odborní pracovníci ze sbírek 65 unikátů. Jedná se o výjimečné předměty a kulturní symboly, pod kterými jsou skryta jména jako Božena Němcová, Karel Hynek Mácha, Karel Havlíček Borovský, Jan Neruda, Jaroslav Hašek, Karel Čapek, Jaroslav Seifert. Najdeme zde i další osobnosti patřící již do současnosti - Vladimíra Holana, Bohumila Hrabala, Josefa Hiršala, Bohumilu Grégrovou, Ludvíka Vaculíka <br>
                a Václava Havla. Z výtvarných umělců to jsou Josef Váchal, Jakub Schikaneder, František Bílek, Vojtěch Preissig, František Kobliha, Karel Teige, Toyen, Zdeněk Burian, Jiří Kolář <br>
                a Vladimír Boudník. Dále jsou to výjimečné knižní tituly - autorské knihy, bibliofilie nebo vzácné tisky, jako jsou například Žlutický kancionál, Postyla od Josefa Váchala, ale i kniha související s novými formami knižní kultury - bibliofilie Karel Hynek Mácha od Zdeňka Sýkory typograficky upravená Zdeňkem Zieglerem a oceněná v soutěži Nejkrásnější české knihy roku.<br>
                Tento výběr zároveň představuje pohled do depozitáře Památníku národního písemnictví <br>
                a tak si díky webové prezentaci projektu 65 můžete tyto předměty prohlédnout včetně jejich příběhu a dokumentace. Přejeme vám zajímavou procházku tímto mimořádným depozitářem.<br>
                Zdeněk Freisleben<br>
                ředitel PNP
',
                'order' => '1',
                'created_at' => $now,
                'updated_at' => $now
            ],
        ];

        DB::table('collections')->insert($collections);

        $items = \App\Item::all();

        foreach ($items as $item) {
            $item->collections()->attach($collection_id);
        }

	}

}
