<?php 
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

        $collections = [
            [
                'name' => 'Krajina na obzore – najstaršie pohľady',
                'type' => 'sekcia',
                'text' => 'Príbeh estetického objavovania a doceňovania domácej krajiny v stredoeurópskom priestore začal vlastne až vtedy, keď rozvoj európskeho krajinárstva dosahoval zenit. Do 19. storočia bola u nás krajina nanajvýš súčasťou či pozadím iných tém, najčastejšie loveckých alebo bojových výjavov. Najosobitejšie sa tradícia zobrazenia domácej krajiny prejavila v pohľadoch na mestá – veduty. Tie sa pôvodne zameriavali výlučne na mestskú panorámu a okolitej krajine venovali len málo pozornosti. Na začiatku 19. storočia sa však už objavujú diela, kde autori mesto vnímajú už v kontexte krajiny, akoby sa pozreli ponad hradby a spozorovali ju „na obzore“. V tejto sekcii sa stretávame s najstaršími príkladmi zachytenia krajiny v médiách 19. storočia.',
                'order' => '1',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Povolanie krajinár',
                'type' => 'sekcia',
                'text' => 'V priebehu 19. storočia dosiahlo krajinárstvo i umelci krajinári spoločenské docenenie aj akademické potvrdenie. Dokladajú to tiež výstavy organizované napríklad viedenskou akadémiou a od roku 1840 aj peštianskym výtvarným salónom, kde sa popri tradičných žánroch portréte a historickej maľbe objavuje stále populárnejšia „domáca“ krajina. Táto sekcia predstavuje najvýznamnejších umelcov - krajinárov pracujúcich v jednotlivých médiách v priebehu celého storočia.',
                'order' => '2',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Putovanie',
                'type' => 'sekcia',
                'text' => 'Významnú, priam kľúčovú tému krajinárstva – putovanie – tematizuje sekcia na dvoch dôležitých príkladoch. Časovo a pre domáce rozvinutie žánru aj významovo prvým je cyklus viedenského akademického maliara/krajinára Josefa Fischera nazvaný Malebná cesta dolu Váhom (1818).
Druhým príkladom je ďalšie putovanie po rieke, tentoraz po Dunaji, ktorý bol príťažlivý z viacerých dôvodov. K prvým a najvýznamnejším cyklom patril album 264 Donau-Ansichten vydavateľa Adolfa Kunikeho, ktorého kľúčovými autormi bol Jakob Alt a Ludwig Erminy. Dunajský cyklus je viac vedutový ako krajinársky, i tu však možno pozorovať zmenu v ponímaní veduty. Väčšie mestá stvárňovali obvykle dva pohľady, ktoré akoby v dvojexpozícii hľadali „plastickú“ tvár danej lokality a týmto spôsobom vyjadrovali tiež pohyb po rieke.',
                'order' => '3',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Mesto v krajine',
                'type' => 'sekcia',
                'text' => 'Napriek tomu, že tradičná veduta stagnovala a v podstate sa už ďalej ani nevyvíjala, zobrazenie miest sa predsa len objavuje aj v 19. storočí. Vidíme tu však veľmi jasne posun, aký táto nová obdoba veduty oproti tradičnej (v prvej sekcii) zaznamenala. Nezobrazuje už typickú pohľadovú „fasádu“ či siluetu mesta, dokonca ani nemusí zachytávať jeho celkovú panorámu. Teraz už ide skôr o zachytenie situovania mesta v krajine a jeho okolí. Maliar sa nesnaží nájsť jeho najviac „informatívny“, ale skôr o krajinársky najpríťažlivejší záber. Obchádza mesto a hľadá jeho posadenie v krajine a prírodný rámec s charakteristikami malebnosti; to bolo to, čo sa od pekného pohľadu na mesto očakávalo. ',
                'order' => '4',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Historizujúca interpretácia krajiny – hrady, zámky, ruiny',
                'type' => 'sekcia',
                'text' => 'K najfrekventovanejším motívom krajinárstva patrili hrady. Stredoveké hrady, resp. ich pozostatky tvorili markantné body v krajine, ich vtedajší zväčša ruinózny stav tiež zodpovedal aktuálnemu romantickému nazeraniu a hľadaniu malebnosti. K obdivu k ruinám, pretrvávajúcemu z 18. storočia, sa však teraz pridával nový vzťah súčasníkov k minulosti, súvisiaci s mohutným prúdom historizmu v európskom myslením a kultúre. O popularite hradov svedčí vznik mnohých grafických cyklov a početných kópií ich významnejších zobrazení. K najznámejším patrili Devín, Oravský a Trenčiansky hrad, Strečno, Beckov a ďalšie hrady Považia. V maliarstve nachádzame aj prípady využitia motívu ako identifikácie portrétovanej osoby alebo aj osobného vzťahu umelca k danej lokalite (napr. Beckov u Ladislava Mednyánszkeho alebo hrad Slanec u Ľudovíta Csordáka).',
                'order' => '5',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Živá krajina – kulisa etnografických výskumov',
                'type' => 'sekcia',
                'text' => '19. storočie bolo dôležitým obdobím formovania sa európskych národov a v tejto spojitosti dochádzalo aj k výskumu ich špecifických znakov a vzájomných rozdielov. Za nositeľov osobitých vlastností etnika bol považovaný predovšetkým materinský jazyk a ľudové kultúrne prejavy, dnes súhrnne nazývané folklór. Ich podstatnou súčasťou, vizuálne určujúcou regionálne špecifiká, bol odev. Od začiatku storočia sa práve pre svoju príťažlivú rozmanitosť stal predmetom etnografických výskumov ale aj obrazových albumov. Figúra, v krajinárstve tradične používaná ako drobná štafáž, tu tak dostáva novú dôležitosť, pomer krajina : človek sa otáča v opačnom garde. Figúra narastá a krajina sa dostáva do úzadia, je tu prítomná len ako jeho doplnok, či „regionálna adresa“.  ',
                'order' => '6',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Krajina v službách idey vlasti a národa',
                'type' => 'sekcia',
                'text' => 'Jedným zo symbolov politickej geografie Habsburskej monarchie bol Dunaj pretekajúci jej veľkým územím. Jej snahu po jednote reprezentuje i názov dunajská, či podunajská monarchia. Tendencia identifikovať geografické, či historické objekty s vlasťou bola ale tiež súčasťou národnobuditeľských aktivít jej jednotlivých etník. Ako symboly teritória Slovákov sa ujali predovšetkým Devín a Kriváň, respektíve vymedzenie „od Tatier k Dunaju“. Boli však manifestované literárne a napriek množstvu zobrazení sa vo výtvarnom umení s národnouvedomovacími snahami väčšinou nespájali. Po potlačení revolúcie 1848/1849 pokračovala snaha o budovanie pozitívneho obrazu spoločnej vlasti. Okrem grafických cyklov bola slovenská časť Uhorska často zobrazovaná ako súčasť výzdoby oficiálnych priestorov v Budapešti alebo Egri. Zavŕšením tohto vlastivedného žánru bolo „popularizačné veľdielo“ Rakúsko-uhorská monarchia slovom a obrazom, kde nachádzame početné malebné pohľady z nášho územia, ale i prvé príklady industrializácie a technických stavieb.',
                'order' => '7',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Tatranská krajina',
                'type' => 'sekcia',
                'text' => 'Záujem o Tatry rástol postupne už od 17. storočia, ako krajinársku výzvu ich však bolo tiež treba „objaviť“. Prvé zobrazenia – panorámy, či horské scenérie vznikali v súvislosti s ich prírodovedným skúmaním. Najstaršie umelecké stvárnenia tatranských motívov pochádzajú z konca 18. storočia. Od začiatku 19. storočia sa téme Tatier príležitostne venovali najmä domáci, spišskí umelci, okolo polovice storočia sa začína záujem o tatranské prostredie zvyšovať. Centrom sa stali predovšetkým kúpele v Starom Smokovci, kde je doložený pobyt viacerých známych krajinárov. Ďalšími motívmi sú Hrebienok a Studenovodské vodopády, žiaden domáci umelec sa však zobrazovaniu Tatier nevenoval systematicky. Po dobudovaní košicko-bohumínskej železnice (1872) a výraznom zlepšení dostupnosti Tatier sa stali významnou turistickou destináciou. Klientela opätovne lákala maliarov, grafikov však v oveľa zásadnejšej miere nahradili fotografi.',
                'order' => '8',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Pod povrchom – krajina nepoznaná',
                'type' => 'sekcia',
                'text' => 'Kombináciu prírodovedného nazerania a analytickej vášne umelcov pre neprebádané kúty krajiny prinášajú pohľady na bizarné prírodné prostredia na povrchu, ale i tajomný svet jaskýň. Známy lekár a horolezec Edmund Téry spolu s amatérskym fotografom Martinom A. Patanťušom ako prví podrobnejšie fotograficky zdokumentovali oblasť Manínskej tiesňavy, Súľovských skál alebo Vršatca na Považí. Na ich výskum nadviazali ďalší fotografi (Andrej Kmeť, Alojz Baker, Július Francisci a iní.), ale ešte koncom 19. storočia Ľudovít Csordák mapoval bizarné prírodné prostredie Zádielskej doliny maliarskymi prostriedkami. Veľkému záujmu sa tešili dve novoobjavené jaskyne – Dobšinská ľadová (1870) a Belianska jaskyňa (1881). Vizuálne príťažlivú Dobšinskú ľadovú jaskyňu zachytávajú viaceré kresby a maľby, prvý album jej fotografií vznikol po roku 1887, keď bola ako vôbec prvá v Európe elektricky osvetlená.',
                'order' => '9',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Zdravie a odpočinok – kúpele',
                'type' => 'sekcia',
                'text' => 'Osobitnú tému v rámci modernizácie spoločnosti predstavujú kúpele, ktoré zaznamenávali vzrastajúcu popularitu. Najmä po polovici storočia vzniklo viacero grafických cyklov približujúcich kúpeľné budovy, dvorany, parky a ďalšie súčasti kúpeľov Sliač, Starý Smokovec a Piešťany. Slúžili na propagačné účely a aj v tejto oblasti iniciatívu postupne prebrali fotografi.',
                'order' => '10',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Krajina na ceste modernizácie',
                'type' => 'sekcia',
                'text' => 'Najprogresívnejšou témou bola v druhej polovici 19. storočia modernizácii krajiny. Využitie parného stroja v doprave prinieslo s výstavbou železníc a dopravno-inžinierskych stavieb mostov a tunelov aj zmenu tváre krajiny. Výstavby seredskej či bratislavskej trate smerom na Viedeň (1846) sú dokumentované grafikami, neskoršia košicko-bohumínska železnica (1869 – 1872) už aj fotografiami. Kontrastu medzi krajinou a modernými konštrukciami sa venoval Pavol Socháň, tento trend ale  nájdeme aj v dokumentárnych fotografiách prvých priemyselných podnikov.',
                'order' => '11',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        DB::table('collections')->insert($collections);

	}

}
