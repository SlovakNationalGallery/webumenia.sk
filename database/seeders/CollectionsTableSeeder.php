<?php

namespace Database\Seeders;

use App\Collection;
use App\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class CollectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $collections = [
            [
                'name' => 'Krajina na obzore – najstaršie pohľady',
                'type' => 'sekcia',
                'text' =>
                    'Príbeh estetického objavovania a doceňovania domácej krajiny v stredoeurópskom priestore začal vlastne až vtedy, keď rozvoj európskeho krajinárstva dosahoval zenit. Do 19. storočia bola u nás krajina nanajvýš súčasťou či pozadím iných tém, najčastejšie loveckých alebo bojových výjavov. Najosobitejšie sa tradícia zobrazenia domácej krajiny prejavila v pohľadoch na mestá – veduty. Tie sa pôvodne zameriavali výlučne na mestskú panorámu a okolitej krajine venovali len málo pozornosti. Na začiatku 19. storočia sa však už objavujú diela, kde autori mesto vnímajú už v kontexte krajiny, akoby sa pozreli ponad hradby a spozorovali ju „na obzore“. V tejto sekcii sa stretávame s najstaršími príkladmi zachytenia krajiny v médiách 19. storočia.',
            ],
            [
                'name' => 'Povolanie krajinár',
                'type' => 'sekcia',
                'published_at' => Date::now(),
                'text' =>
                    'V priebehu 19. storočia dosiahlo krajinárstvo i umelci krajinári spoločenské docenenie aj akademické potvrdenie. Dokladajú to tiež výstavy organizované napríklad viedenskou akadémiou a od roku 1840 aj peštianskym výtvarným salónom, kde sa popri tradičných žánroch portréte a historickej maľbe objavuje stále populárnejšia „domáca“ krajina. Táto sekcia predstavuje najvýznamnejších umelcov - krajinárov pracujúcich v jednotlivých médiách v priebehu celého storočia.',
            ],
            [
                'name' => 'Putovanie',
                'type' => 'sekcia',
                'text' => 'Významnú, priam kľúčovú tému krajinárstva – putovanie – tematizuje sekcia na dvoch dôležitých príkladoch. Časovo a pre domáce rozvinutie žánru aj významovo prvým je cyklus viedenského akademického maliara/krajinára Josefa Fischera nazvaný Malebná cesta dolu Váhom (1818).
Druhým príkladom je ďalšie putovanie po rieke, tentoraz po Dunaji, ktorý bol príťažlivý z viacerých dôvodov. K prvým a najvýznamnejším cyklom patril album 264 Donau-Ansichten vydavateľa Adolfa Kunikeho, ktorého kľúčovými autormi bol Jakob Alt a Ludwig Erminy. Dunajský cyklus je viac vedutový ako krajinársky, i tu však možno pozorovať zmenu v ponímaní veduty. Väčšie mestá stvárňovali obvykle dva pohľady, ktoré akoby v dvojexpozícii hľadali „plastickú“ tvár danej lokality a týmto spôsobom vyjadrovali tiež pohyb po rieke.',
            ],
        ];

        foreach ($collections as $collection) {
            $items = factory(Item::class, 5)->create();
            Collection::factory()
                ->create($collection)
                ->items()
                ->saveMany($items);
        }
    }
}
