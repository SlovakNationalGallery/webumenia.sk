<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

        DB::table('articles')->truncate();
        
        $now = date("Y-m-d H:i:s");

        $articles = [
            [
                'category_id' => 3,
                'author' => 'lab.SNG',
                'slug' => 'z-webu-umenia-vybera-eva-kasakova',
                'title' => 'Eva Kašáková',
                'main_image' => 'nfrs89fokfs.jpg',
                'title_color' => '#fff',
                'title_shadow' => '#777',
                'summary' => 'Eva Kašáková je grafická dizajnérka a absolventka Katedry vizuálnej komunikácie VŠVU. V jej portfóliu nájdeme umelecké publikácie pre nezávislé vydavateľstvo La Piscina z Tenerife, alebo vizuálnu identitu festivalu DIY vydávania <a href="http://www.self-x.sk" target="_blank">SELF</a>. Jej <a href="http://malydizajnblog.sk/" target="_blank">Malý dizajn blog</a>  je jedným z mála tuzemských online zdrojov, ktorý neponúka recyklované aktuálne trendy, ale pôvodné texty a kritický pohľad na dianie u nás. Na potulkách Webom umenia a históriou úžitkovej grafiky nás Eva zavedie do Košíc.',
                'content' => '<p>Biele miesta histórie vizuálnej kultúry na Slovensku sa v poslednej dobe začali odkrývať vďaka publikáciám ako <a href="https://www.typotheque.com/blog/forgotten_history" target="_blank">Modernosť tradície</a>, alebo iniciatívam okolo <a href="http://www.sdc.sk/?muzeum-dizajnu-aktuality" target="_blank">Múzea dizajnu</a>. Za prispenia zberateľov-aktivistov vzniká v bratislavských Hurbanových kasárňach zbierka, ktorá zachytáva vývoj nielen grafického, ale aj priemyselného a komunikačného dizajnu. </p>

                    <p>Malú sondu do histórie propagačnej grafiky na Slovensku ponúka aj zbierka plagátu a grafického dizajnu Slovenskej národnej galérie. Jej podstatnú časť tvoria galerijné a muzeálne plagáty, medzi ktorými majú najstaršie datovanie plagáty Východoslovenského múzea v Košiciach. Boli vytvorené v <a href="/katalog?search=&author=&work_type=%C3%BA%C5%BEitkov%C3%A9+umenie&tag=&gallery=&topic=&technique=litografia&has_image=1&year-range=600%2C2015">košickej litografickej dielni Hermés</a>
                     a zaujmú hneď na prvý pohľad. Majú výrazný grafický rukopis, jednofarebnú tlač a písmo ručne kreslené do kompozície plagátu. </p>

                    <p>Autori týchto plagátov boli zamestnancami podniku a zostávali zväčša neznámi. Predlohy na plagáty získavali buď priamo z diel vystavujúcich umelcov alebo z vlastných kresieb . Môžeme povedať, že boli <a href="http://www.cassovia.sk/litografia/lito2.php3" target="_blank">doboví grafickí dizajnéri</a>. Ich plagáty dokazujú dôležitú polohu Košíc na mape výtvarného umenia vo vtedajšom Československu. Keďže tu medzi dvoma svetovými vojnami pôsobili viacerí avantgardní výtvarníci, v prehľade plagátov neprekvapí výstava dánskeho grafického umenia z roku 1926, alebo výstava diel maďarského priekopníka moderného reklamného plagátu <a href="/autor/1001191">Alexandra Bortnyika</a></p>

                    <p><a href="/katalog?search=&author=&work_type=&tag=&gallery=&topic=&technique=litografia&has_image=1&year-range=600%2C2015">Litografia</a> dnes napriek pretrvávajúcemu záujmu o sieťotlač a ďalšie klasické techniky (napríklad tlač kovovými literami z výšky) uniká pozornosti dizajnérov. Čiastočne sa to dá pochopiť jej absolútnou nekompatibilitou s digitálnym procesom tvorby - jediný spôsob ako urobiť transfer na kameň (okrem voľnej kresby rukou) je prostredníctvom kopírovacieho papiera. No napriek tomu má ešte litografia šancu uplatniť sa. Práve kresba rukou a s ňou spojená voľnosť pri tvorbe písma, či typografických plagátov, môže byť vynikajúca zámienka na workshop pre študentov. Stačí len pohľadať u niekoho v ateliéri zaprášený litografický kameň.</p>',
                'promote' => true,
                'publish' => true,
                'published_date' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => 1,
                'author' => 'lab.SNG',
                'slug' => 'pribehy-umenia-slavin',
                'title' => 'Slavín',
                'main_image' => 'pribehy_slavin.jpg',
                'title_color' => '#fff',
                'title_shadow' => '#777',
                'summary' => 'Pri príležitosti spustenia nového Webu umenia sme pripravili sériu krátkych dokumentov Príbehy umenia. Osobným pohľadom autorov, ich rodín alebo ľudí, ktorí sú na nich zachytení, priblížime výtvarné diela a zároveň ukážeme jednu z možností spracovania zdigitalizovaných umeleckých diel. Pilotný diel je venovaný Slavínu, soche vojaka na pylóne a jeho autorovi <a href="/autor/11013">Alexandrovi Trizuljakovi</a>',
                'content' => '<p>Prvá časť série dokumentov Príbehy umenia o Slavíne bola premiérována počas sprievodného podujatia 5.júna v Berlinke SNG. Autorom dokumentu je skupina mladých tvorcov <a href="http://www.moire.sk/" target="_blank">Moiré</a>, ktorí si zvolili formát pozostávajúci z rozhovorov (kunsthistorička Alexandra Kusá, syn sochára Klement Trizuljak, manželka <a href="/autor/11013">Eva Trizuljaková</a>, záberov na exteriéry, interiéry (ateliér sochára), archívnych materiálov (Slovenský filmový ústav a rodinný archív rodiny Alexandra Trizuljaka) a zdigitalizovaných diel zo zbierok SNG. Širší záber diel súvisiacich s pamätníkom a sochou vojaka nájdete v našej kolekcii - súťažné návrhy a fotografie z výstavby Slavína, umelecké fotografie a sochárske návrhy.</p>
                    <iframe src="https://player.vimeo.com/video/130346650?color=66ccf4&title=0&byline=0&portrait=0" width="100%" height="350" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    <p>Z rozhovorov s aktérmi dokumentu, ale aj vnukom Alexandra Trizuljaka Šymonom Klimanom, teoretikom architektúry Petrom Szalayom spracovala Barbora Kalinová jednu z relácií Túlačka_FM.</p>
                    <iframe width="100%" height="350" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/209350335&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>
                    <p></p>
                    ',
                'promote' => true,
                'publish' => true,
                'published_date' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => 2,
                'author' => 'lab.SNG',
                'slug' => 'dielo-v-detaile-antonin-prochazka-jar',
                'title' => 'Antonín Procházka - Jar (1929)',
                'main_image' => 'prochazka-jar.jpg',
                'title_color' => '#fff',
                'title_shadow' => '#777',
                'summary' => 'V sérii článkov Dielo v detaile budeme predstavovať umenie z "makro pohľadu". Vďaka skenom a fotografiám umeleckých diel sa môžeme zamerať na štruktúru ich povrchu, alebo detaily, ktoré vystúpia až pri „nazoomovaní.“ Technológia však bude len prostriedkom, cieľom bude hlbšie pochopenie súvislostí, alebo umocnenie zážitku pri prehliadaní diela. Pre prvý diel sme vybrali maľbu od českého autora, ktorú vo výklade predstaví kurátor SNG Aurel Hrabušický.',
                'content' => '<p>V zbierkach slovenských galérií nájdeme od <a href="http://www.webumenia.sk/autor/8137">Antonína Procházku</a> okrem jednej grafiky a faksimílie len dve maľby. Jednou z nich je <a href="http://www.webumenia.sk/dielo/SVK:SNG.O_5780">Jar</a>, ktorá patrí už do obdobia neoklasickej fázy Procházkovej tvorby. V tejto fáze sa zameriaval na mestský spôsob života, ktorého príkladom je dáma na obraze v typickom dobovom odeve. Na jeho detailoch si dal Procházka veľmi záležať - elegantná šatka, vzdušné šaty a lakovky.</p>
                    <iframe src="https://player.vimeo.com/video/127050241?color=66ccf4&title=0&byline=0&portrait=0" width="100%" height="350" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                    <p></p>
                    ',
                'promote' => true,
                'publish' => true,
                'published_date' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'category_id' => null,
                'author' => 'lab.SNG',
                'slug' => 'spustili-sme-novy-web-umenia',
                'title' => 'Spustili sme nový Web umenia!',
                'main_image' => 'psicek-a-macicka-sa-tesia.jpg',
                'title_color' => '#fff',
                'title_shadow' => '#777',
                'summary' => 'Výtvarné diela zo zbierok slovenských galérií nájdete odteraz na Webe umenia v novej podobe, s novými funkciami a dizajnom.',
                'content' => '
<p>Podstatnou zmenou je prehliadanie diel v pôvodnej veľkosti - u mnohých diel zo Slovenskej národnej galérie, Oravskej galérie, Liptovskej galérie, Galérie umenia v Nových Zámkoch a Galérie mesta Bratislavy môžete obdivovať ich detaily, či už ide o ťahy štetcom alebo výjavy v pozadí. Medzi prvými tak  uvidíte časť z výsledkov digitalizácie diel výtvarného umenia, realizovanej vďaka národnému projektu Digitálna galéria (Operačný program Informatizácia spoločnosti), ktorého prijímateľom je Slovenská národná galéria.</p>
<p>Autori majú na novom Webe umenia svoj vlastný profil, ktorý vás prevedie prepojeniami medzi študentmi a učiteľmi, spolupracovníkmi a partnermi. Ak vás zaujíma, ktorí autori sa narodili alebo pôsobili vo vašom meste, môžete si ich vyhľadať vďaka jednoduchému filtrovaniu. Výraznými zmenami prešlo aj vyhľadávanie. Navigáciu na stránke vám uľahčí automatické dopĺňanie mena autora a názvu diela, širšie možnosti ponúka aj dopĺňanie synoným a rôznych tvarov vyhľadávaných slov – ak dáte hľadať „šašo“, nájdete aj diela, ktoré majú v názve klaun alebo komediant.</p>
<p>Digitálne reprodukcie autorskoprávne voľných diel si môžete stiahnuť v pôvodnej veľkosti, umiestniť na vlastnú webstránku, vytlačiť, alebo digitálne upraviť.  Ak si si chcete dať svoje obľúbené dielo vytlačiť v profesionálnej kvalite, stačí vyplniť objednávku a počkať si na výrobu a doručenie do predajne Ex-Libris. Mobilná generácia ocení responzívnu verziu webu, ktorá sa automaticky prispôsobuje veľkosti tabletu alebo mobilného telefónu.</p>
<p>Na Webe umenia budú postupne pribúdať zbierky ďalších galérií a nové funkcie, rovnako ako pôvodný obsah, ktorým priblížime rozmanitosť a kvalitu zbierok výtvarného umenia čo najširšiemu okruhu záujemcov. Už čoskoro tak budete môcť vyhľadávať podľa farieb, prehliadať si umenie podľa miest na mape, alebo si ukladať obľúbené diela do vlastného profilu.    </p>            
                    ',
				'promote' => true,
				'publish' => true,
				'published_date' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ],

        ];

        DB::table('articles')->insert($articles);

	}

}
