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
                        <h1>Web umenia</h1>
                        <h2>databáza výtvarných diel</h2>
                        <h3 class='bottom-space'>v zbierkach verejných galérií</h3>
                    </div>
                </div>
                <div class="col-md-6">

                    <p class="medium">Web umenia je on-line katalóg s informáciami a digitálnymi fotografiami výtvarných diel zo slovenských zbierkotvorných galérií, ktorý návštevníkom umožňuje ich jednoduché vyhľadávanie a prezeranie. Zapojeným galériám dáva možnosť otvoriť verejnosti svoje zbierky, z ktorých býva vystavovaná len malá časť diel a väčšina zostava skrytá v depozitároch. Momentálne sú zastúpené diela z fondov Slovenskej národnej galérie, časť diel z Oravskej galérie a reprezentatívny výber z Galérie mesta Bratislavy.</p>

                    <p class="medium">Základom Webu umenia sú popisné údaje o výtvarnom diele, jeho fotografie a používateľský obsah v podobe tagov. Údaje a fotografie výtvarných diel sú automaticky preberané a pravidelne aktualizované zo systému CEDVU (Centrálna evidencia diel výtvarného umenia), kam ich vkladajú zamestnanci jednotlivých galérií. Vo viacerých prípadoch nie sú záznamy úplné - niektoré údaje môžu chýbať, rovnako sa môžu vyskytnúť nepresnosti. Pred publikovaním je preto nutné si správnosť údajov overiť.</p>

                    <h3 class="text-center visible-xs visible-sm"><i class="icon-versus"></i></h3>
                </div>
                <div class="col-md-6">
                    <p class="lead">Na fotografie výtvarných diel a samotné diela sa vzťahujú autorské práva, pre informácie o možnostiach ich využitia je potrebné kontaktovať príslušné oddelenie galérie (Kontakt) a získať predchádzajúci súhlas autora diela, resp. jeho dediča či jeho práva zastupujúcej inštitúcie. Preberanie fotografií a ich zverejňovanie na iných stránkach je bez predošlého súhlasu galérie zakázané, odporúčame použiť odkazovanie na stránky s dielom, alebo vkladanie odkazu na fotografiu.</p>
                    <h3 class="text-center"><i class="icon-versus"></i></h3>
                    <p class="lead">Vytvoril a spravuje tím <a href="http://lab.sng.sk">lab.SNG</a></p>
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
&nbsp;

@stop
