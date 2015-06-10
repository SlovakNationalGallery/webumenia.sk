<?php 
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
                // 'author' => '',
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

        ];

        DB::table('articles')->insert($articles);

	}

}
