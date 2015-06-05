@extends('layouts.master')

@section('title')
@parent
| informácie o výstave
@stop

@section('content')

<section class="info">
    <div class="container">
        <div class="row content-section">
            <div class="col-md-12 text-center">
                    <h2>Čo je web umenia?</h2>
            </div>
        </div>
        <div class="row bottom-space">
            <div class="col-md-4">

                <p class="lead">Web umenia je <strong>on-line katalóg</strong> výtvarných diel zo zbierok <strong>slovenských galérií</strong>.</p>

                <p>Nájdete tu základné informácie o dielach a ich autoroch, ale aj pôvodné články, videá a kolekcie. Údaje a digitálne reprodukcie sú preberané a pravidelne aktualizované zo systému CEDVU (Centrálna evidencia diel výtvarného umenia), kam ich vkladajú zamestnanci jednotlivých galérií. Vo viacerých prípadoch nie sú záznamy úplné a niektoré údaje môžu chýba, rovnako sa môžu vyskytnúť nepresnosti. Pred publikovaním je preto nutné si správnosť údajov overiť.</p>
            </div>
            <div class="col-md-4 text-center">
                <ul class="list-unstyled lead">
                    <li><a href="{{ URL::to('katalog?gallery=Slovenská národná galéria, SNG') }}">Slovenská národná galéria</a></li>
                    <li><a href="{{ URL::to('katalog?gallery=Oravská galéria, OGD') }}">Oravská galéria Dolný Kubín</a></li>
                    <li><a href="{{ URL::to('katalog?gallery=Galéria umenia Ernesta Zmetáka, GNZ') }}">Galérie umenia Nové Zámky Ernesta Zmetáka</a></li>
                    <li><a href="{{ URL::to('katalog?gallery=Liptovská galéria Petra Michala Bohúňa, GPB') }}">Liptovská galéria Petra Michala Bohúňa</a></li>
                    <li><a href="{{ URL::to('katalog?gallery=Galéria mesta Bratislavy, GMB') }}">Galéria mesta Bratislavy</a></li>
                </ul>
            </div>
            <div class="col-md-4 text-center">
                <img src="/images/galerie-na-mape.png" alt="Galérie na mape" class="img-responsive" style="margin-top: 10px" />
            </div>
        </div>
    </div>
</section>
<section class="mozete">
    <div class="container">
        <div class="row content-section">
            <!-- stahovanie -->
            <div class="col-md-4">
                <h4>Sťahovať diela a využívať ich</h4>
                <ul>
                    <li>Digitálne reprodukcie diel SNG na tejto stránke sú sprístupnené ako verejné vlastníctvo (public domain). Môžete si ich voľne stiahnuť vo vysokom rozlíšení a využívať na súkromné aj komerné účely - kopírovať, zdieľať i upravovať. </li>

                    <li>Pri ďalšom šírení prosíme uviesť meno autora, názov, majiteľa diela a zdroj (webummenia.sk) </li>

                    <li>Ak plánujete využiť reprodukcie na komerčné účely, prosím informujte o vašich plánoch vopred, naši odborníci vám vedia poradiť.</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h4>napríklad</h4>
                <ul>
                    <li>tlač (plagáty, pohľadnice alebo tričká)</li>

                    <li>vlastná tvorba (digitálna úprava reprodukcie, využitie jej časti pre animáciu alebo koláž)</li>

                    <li>vzdelávanie (vloženie obrázku na vlastnú webstránku, použitie na Wikipedii i ako súhlas prezentácie)</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h4>Objednať si kvalitné reprodukcie</h4>
                <p>K vybraným dielam zo zbierok SNG ponúkame možnosť objednať si reprodukcie v archívnej kvalite na fineartových papieroch. Po výbere diel, vyplnení údajov a odoslaní objednávky vás bude kontaktovať pracovník SNG s podrobnejšími informáciami. Momentálne je možné vyzdvihnúť si diela len osobne v kníhkupectve Ex Libris v priestoroch SNG na Námestí. Štúra 4 v Bratislave.</p>
            </div>

        </div>

    </div>
</section>
&nbsp;

@stop
