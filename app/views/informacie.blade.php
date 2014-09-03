@extends('layouts.master')

@section('title')
@parent
| informácie o výstave
@stop

@section('content')

<section class="info ">
    <div class="info-body">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="intro-text text-center">
                        <h1>DVE KRAJINY</h1>
                        <h2>OBRAZ SLOVENSKA</h2>
                        <h3 class='bottom-space'>19. storočie <i class="icon-versus"></i> súčasnosť</h3>
                    </div>
                </div>
                <div class="col-md-6">

                    <!-- <p style="margin-top: 50px;">Výstava Slovenskej národnej galérie s názvom DVE KRAJINY. Obraz Slovenska: 19. storočie x súčasnosť predstavuje na jednej strane premeny žánru slovenskej krajiny v umení 19. storočia, na strane druhej ukazuje ako sa tejto témy zhostilo súčasné umenie (cca od 70. rokov 20. storočia po dnešok). Multižánrový a transhistorický projekt na bohatom materiáli (kresby, grafiky, fotografie, maľby, sochy a objekty, inštalácie, videa, akcie a performancie) ukazuje šírku a rozmanitosť zobrazenia slovenskej krajiny ako topograficky definovaného miesta, spracovanej a interpretovanej tak autormi 19. storočia, ako aj súčasnými tvorcami.</p> -->

                    <!-- <h3><i class="icon-versus"></i> </h3> -->

                    <p class="medium">Výstava <a href="http://www.sng.sk" target="_blank">Slovenskej národnej galérie</a> s názvom <span>DVE KRAJINY. Obraz Slovenska: 19. storočie × súčasnosť</span> sa nachádza na dvoch poschodiach Esterházyho paláca a obsahuje vyše 400 diel zo zbierok 35 galérií, múzeí, inštitúcií a od súkromných majiteľov. Je rozdelená do jedenástich sekcií, ktoré predstavujú premeny žánru slovenskej krajiny v umení 19. storočia (napr. témy <a href="{{ URL::to('sekcia/3') }}">putovania po krajine</a>, <a href="{{ URL::to('sekcia/4') }}">mestá v krajine</a>, <a href="{{ URL::to('sekcia/5') }}">hrady</a>, dunajské pohľady, krajinu regiónov, <a href="{{ URL::to('sekcia/8') }}">Tatry</a>, či <a href="{{ URL::to('sekcia/11') }}">vplyvy modernizácie</a>), komentované vstupmi – intervenciami – súčasného umenia. Vzniká tu typ akejsi výstavy vo výstave, ktorá okrem „poznávacieho“ a estetického aspektu (ako vyzeralo Slovensko kedysi a akými prostriedkami sa zobrazovalo) prináša aj aktuálny, do istej miery scudzujúci príbeh krajiny, jej zobrazenia či vernejšie vyjadrenia (a teda aj bytia) v súčasnosti. Kým jedna časť súčasných artefaktov zachováva „malebné“ a „romantické“ ladenie diel z 19. storočia, resp. sa s nimi polemicky, niekedy úsmevne, „pohráva“, iná časť sa k modernizovanej krajine stavia oveľa nemilosrdnejšie, nastavuje jej zrkadlo s ironickým, kritickým nadhľadom. Vyústenie príbehu výtvarného putovania po „krásnej zemi“ od Tatier k Dunaju (ako ju kedysi nazval Samo Chalupka), tak dostáva nečakanú, ale v každom prípade výstižnú a aktualizovanú pointu.</p>

                    <h3 class="text-center visible-xs visible-sm"><i class="icon-versus"></i></h3>
                </div>
                <div class="col-md-6">
                    <p class="lead">Webstránka <a href="{{ URL::to('/') }}">dvekrajiny.sng.sk</a> sprístupňuje vybrané diela z výstavy on-line a vo vysokom rozlíšení. Dodržuje rozdelenie do jedenástich výstavných sekcií, ale taktiež umožňuje jednotlivé diela vyhľadávať a triediť podľa popisných údajov či tematických tagov a objavovať ich na interaktívnej mape Slovenska. </p>
                    <h3 class="text-center"><i class="icon-versus"></i></h3>
                    <p class="lead">Počas trvania výstavy budú na stránke postupne pribúdať ďalšie diela, informácie a funkcionality, čím sa bude približovať budúcej podobe nového <a href="http://www.webumenia.sk" target="_blank">Webu umenia</a> (momentálne obsahujúceho viac než 17 tisíc zdigitalizovaných diel výtvarného umenia na Slovensku), ktorému slúži ako prototyp a ukazuje tak smerovanie jeho prebiehajúceho redizajnu.</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p><strong class="atribut">Termín:</strong> 3. júl - 19. október 2014</p>
                    <p><strong class="atribut">Koncepcia:</strong> Martin Čičo, Katarína Bajcurová</p>
                    <p><strong class="atribut">Kurátori:</strong> Lucia Almášiová, Katarína Beňová, Petra Hanáková, Aurel Hrabušický, Lucia G. Stachová</p>
                    <p><strong class="atribut">Miesto:</strong> Slovenská národná galéria, Esterházyho palác, 2. a 3. poschodie, Námestie  Ľ. Štúra 4, Bratislava</p>
                    
                    <p><strong class="atribut">Otváracie hodiny:</strong></p>

                    
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <td class="atribut">Pondelok:</td>
                            <td>zatvorené</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="atribut">Utorok:</td>
                            <td>10.00 – 18.00</td>
                        </tr>
                        <tr>
                            <td class="atribut">Streda:</td>
                            <td>10.00 – 18.00</td>
                        </tr>
                        <tr>
                            <td class="atribut">Štvrtok:</td>
                            <td>12.00 – 20.00</td>
                        </tr>
                        <tr>
                            <td class="atribut">Piatok:</td>
                            <td>10.00 – 18.00</td>
                        </tr>
                        <tr>
                            <td class="atribut">Sobota:</td>
                            <td>10.00 – 18.00</td>
                        </tr>
                        <tr>
                            <td class="atribut">Nedeľa:</td>
                            <td>10.00 – 18.00</td>
                        </tr>
                        </tbody>
                    </table>

                    <p><a href="http://www.sng.sk/sk/uvod/navsteva-sng/vstupne" taget="_blank">Vstup zdarma</a></p>
                    <h3 class="text-center visible-xs visible-sm"><i class="icon-versus"></i></h3>
                </div>
                <div class="col-md-6">
                    <p><strong class="atribut">Katalóg DVE KRAJINY</strong></p>
                    <p><strong class="atribut">Dizajn:</strong> Ján Šicko</p>
                    <p><strong class="atribut">Vydala:</strong> Slovenská národná galéria</p>
                    <p><strong class="atribut">Cena:</strong> 20 €</p>
                    <p>Dostupný v kníhkupectve <a href="https://goo.gl/maps/3Uf4S" target="_blank" class="underline">Ex Libris v priestoroch SNG na Námestí Ľ. Štúra 4</a> v Bratislave.</p>

                    <a href="http://www.sng.sk/sk/uvod/vystavy/aktualne/dve-krajiny/katalog" target="_blank"><img src="{{URL::to('/images/katalog.jpg')}}" class="img-responsive" alt="Katalóg DVE KRAJINY"></a>

                </div>

            </div>
            <hr>
            <div class="row">
                <!-- stahovanie -->
                <div class="col-md-6">
                    <h4>Sťahovanie obrázkov</h4>
                    <div class="text-center"><img src="{{ URL::asset('images/license/cc.svg') }}" class="img-responsive"  style="max-width: 250px; padding-bottom: 20px" alt="Creative Commons"></div>
                    <p>Digitálne reprodukcie diel SNG na tejto stránke sú sprístupnené pod licenciou <a class="underline" href="http://creativecommons.org/licenses/by-nc-sa/4.0/deed.cs" target="_blank">Creative Commons BY-NC-SA 4.0</a>. Môžete si ich voľne stiahnuť vo vysokom rozlíšení. Reprodukcie sa môžu ľubovoľne využívať na nekomerčné účely - kopírovať, zdieľať či upravovať. Pri ďalšom šírení obrázkov je potrebné použiť rovnakú licenciu <em>(CC BY-NC-SA)</em> a uviesť odkaz na webstránku <a class="underline" href="http://dvekrajiny.sng.sk">http://dvekrajiny.sng.sk</a> s citáciou diela (autor, názov, rok vzniku, vlastník diela).</p>
                    <p>Príklady využitia reprodukcií:</p>
                    <ul>
                        <li>tlač na nekomerčné účely (plagáty, pohľadnice alebo tričká)</li>
                        <li>vlastná tvorba (digitálna úprava reprodukcie, využitie jej časti pre animáciu alebo koláž)</li>
                        <li>vzdelávanie (vloženie obrázku na vlastnú webstránku, použitie na Wikipedii či ako súčasť prezentácie)</li>
                    </ul>    
                    <p><a class="underline" href="{{ URL::to('creative-commons') }}">Všetky voľne stiahnuteľné diela nájdete tu.</a></p>
                    
                </div>
                <h3 class="text-center visible-xs visible-sm"><i class="icon-versus"></i></h3>
                <!-- reprodukcie -->
                <div class="col-md-6">
                    <h4>Tlač reprodukcií</h4>
                    <p>K <a href="{{ URL::to('katalog?gallery=Slovenská%20národná%20galéria,%20SNG') }}">vybraným dielam zo zbierok SNG</a> ponúkame možnosť objednať si reprodukcie v archívnej kvalite na fineartových papieroch. Po výbere diel, vyplnení údajov a odoslaní objednávky vás bude kontaktovať pracovník SNG s podrobnejšími informáciami. </p>
                    <p class="bottom-space">Momentálne je možné vyzdvihnúť si diela len osobne v kníhkupectve <a href="https://goo.gl/maps/3Uf4S" target="_blank" class="underline">Ex Libris v priestoroch SNG na Námestí Ľ. Štúra 4</a> v Bratislave. </p>

                    <h3 class="text-center"><i class="icon-versus"></i></h3>

                    <div class="row" style="margin-top: 50px">
                        <div class="col-md-6">
                            <p style="margin-bottom: 20px"><strong class="atribut">Generálny partner:</strong></p>
                            <a href="http://www.seas.sk" target="_blank"><img src="{{ URL::to('/images/partneri/enel.png') }}" alt="enel"></a>
                        </div>
                        <div class="col-md-6">
                            <p style="margin-bottom: 20px"><strong class="atribut">Voľný vstup vďaka:</strong></p>
                            <a href="https://www.jtbanka.sk" target="_blank"><img src="{{ URL::to('/images/partneri/ja-a-ty.png') }}" alt="banka ja a ty"></a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>


@stop
