@extends('layouts.master')

@section('title')
Informácie | 
@parent
@stop

@section('content')

<section class="info">
    <div class="container">
        <div class="row content-section">
            <div class="col-md-12 text-center">
                    <h1>Čo je web umenia?</h1>
            </div>
        </div>
        <div class="row bottom-space vertical-align">
            <div class="col-md-4">

                <p class="lead">Web umenia je <strong>on-line katalóg</strong> výtvarných diel zo zbierok <strong>slovenských galérií</strong> evidovaných v&nbsp;<a class="unstyled" href="http://www.sng.sk/sk/zbierky/centralny-katalog-diel" target="_blank">Centrálnej evidencii diel výtvarného umenia</a>. </p>

                <p>Nájdete tu základné informácie o dielach a ich autoroch, ale aj pôvodné články, videá a kolekcie.Údaje a digitálne reprodukcie sú preberané a pravidelne aktualizované zo systému CEDVU (Centrálna evidencia diel výtvarného umenia), kam ich vkladajú zamestnanci jednotlivých galérií.</p>

            </div>
            <div class="col-md-4 text-center">
                <img srcset="/images/galerie-na-mape.png 1x, /images/galerie-na-mape@2x.png 2x" src="" alt="Galérie na mape" class="img-responsive" style="margin: 20px auto 40px" />
            </div>
            <div class="col-md-4 text-center">
                <ul class="list-unstyled lead">
                    <li><a href="{!! URL::to('katalog?gallery=Slovenská národná galéria, SNG') !!}">Slovenská národná galéria</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Oravská galéria, OGD') !!}">Oravská galéria v Dolnom Kubíne</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Galéria umenia Ernesta Zmetáka, GNZ') !!}">Galéria umenia Ernesta Zmetáka v Nových Zámkoch</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Liptovská galéria Petra Michala Bohúňa, GPB') !!}">Liptovská galéria Petra Michala Bohúňa v&nbsp;Liptovskom Mikuláši</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Galéria mesta Bratislavy, GMB') !!}">Galéria mesta Bratislavy</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Galéria+Miloša+Alexandra+Bazovského, GBT') !!}">Galéria Miloša Alexandra Bazovského v Trenčíne</a></li>
                    <li><a href="{!! URL::to('katalog?gallery=Nitrianska+galéria, NGN') !!}">Nitrianska galéria</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="mozete">
    <div class="container">
        <div class="row content-section">
            <div class="col-md-12 text-center mid-grey">
                    <h2 class="inherit">U nás môžete</h2>
            </div>
        </div>
        <div class="row ">
            <!-- stahovanie -->
            <div class="col-md-4">
                <h4>Sťahovať voľné diela</h4>
                <ul class="fa-ul">
                    <li><span class="fa-li">&sect;</span>Digitálne reprodukcie autorskoprávne voľných diel SNG na tejto stránke sú sprístupnené ako <a href="https://creativecommons.org/publicdomain/zero/1.0/" target="_blank"><strong>verejné vlastníctvo (public domain)</strong></a>. Môžete si ich voľne stiahnuť <strong>vo vysokom rozlíšení</strong> a využívať na súkromné aj komerčné účely - kopírovať, zdieľať či upravovať. </li>

                    <li><span class="fa-li">&sect;</span>Pri ďalšom šírení <strong>prosíme uviesť</strong> uviesť meno autora, názov, inštitúciu a zdroj (<a href="http://www.webumenia.sk">webumenia.sk</a>) </li>

                    <li><span class="fa-li">&sect;</span>Ak plánujete využiť reprodukcie na <strong>komerčné účely</strong>, prosím informujte nás o vašich plánoch vopred, vieme vám poradiť.</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h4>Využívať voľné diela</h4>
                <ul class="fa-ul">
                    <li><i class="fa-li icon-arrow-right"></i><strong>tlač</strong> (plagáty, pohľadnice alebo tričká)</li>

                    <li><i class="fa-li icon-arrow-right"></i><strong>vlastná tvorba</strong> (digitálna úprava reprodukcie, využitie jej časti pre animáciu alebo koláž)</li>

                    <li><i class="fa-li icon-arrow-right"></i><strong>vzdelávanie</strong> (vloženie obrázku na vlastnú webstránku, použitie na Wikipedii či ako súčasť prezentácie)</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h4>Objednať si kvalitné reprodukcie</h4>
                <p>K vybraným dielam zo zbierok SNG ponúkame možnosť objednať si reprodukcie v archívnej kvalite na fineartových papieroch. Po výbere diela, vyplnení údajov a odoslaní objednávky vás bude kontaktovať pracovník SNG s podrobnejšími informáciami. Momentálne je možné vyzdvihnúť si diela len osobne v kníhkupectve <a href="http://www.sng.sk/sk/uvod/navsteva-sng/sluzby/knihkupectvo-ex-libris" target="blank" class="strong">Ex&nbsp;Libris v priestoroch SNG</a> na <a href="https://goo.gl/maps/k0sBz" target="_blank" class="strong">Námestí. Štúra 4 v Bratislave</a> alebo v pokladni <a href="http://www.sng.sk/sk/uvod/vysunute-pracoviska/zvolen" target="blank" class="strong">Zvolenského zámku</a> - <a href="https://goo.gl/maps/MPRy6Qdwm8s" target="_blank" target="blank" class="strong">Námestie SNP 594/1</a>.</p>
            </div>

        </div>
        <div class="row top-space">
            <div class="col-md-4 dib">
                <a href="{!! URL::to('katalog?is_free=' . '1') !!}" class="inherit lead pull-left no-border"><i class="icon-arrow-right"></i> &nbsp; voľné diela na stiahnutie</a>
            </div>
            <div class="col-md-4 dib"></div>
            <div class="col-md-4 dib">
                <a href="#" data-toggle="modal" data-target="#priceList" class="inherit lead pull-left no-border"><i class="icon-arrow-right"></i> &nbsp; cenník reprodukcií</a>
            </div>
        </div>

    </div>
</section>
<section class="mozete more-items">
    <div class="container">
        <div class="row top-space bottom-space">   
            <div class="col-xs-12 text-center">
                <h3>Vyberte si reprodukcie</h3>
            </div>  
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="artworks-preview">
                @foreach ($items as $item)
                    <a href="{!! $item->getUrl() !!}" class="no-border"><img data-lazy="{!! $item->getImagePath() !!}" class="img-responsive-width " alt="{!! $item->getTitleWithAuthors() !!} " title="{!! $item->getTitleWithAuthors() !!} " ></a>
                @endforeach
                </div>
            </div>
        </div>
        <div class="row content-section">
            <div class="col-sm-12 text-center">
                <a href="{!! url_to('katalog', ['gallery' => 'Slovenská národná galéria, SNG']) !!}" class="btn btn-default btn-outline sans" >zobraziť všetkých <strong>{!! App\Item::forReproduction()->count() !!}</strong>  <i class="fa fa-chevron-right "></i></a>
            </div>
        </div>
    </div>
</section>

<section class="mozete more-items">
    <div class="container">
        <div class="row content-section"><!-- top-space bottom-space -->
            <div class="col-md-12 text-center mid-grey">
                    <h2>Spojte sa s nami</h2>
            </div>
        </div>
        <div class="row bottom-space">
            <div class="col-md-4">
                <p class="lead">
                    Zverejňujeme výtvarné diela <br>zo zbierok <strong>slovenských galérií</strong>.
                </p>
                <p>
                    Podmienkou pre zverejnenie diel a ďalšich autorov na Web umenia je ich zastúpenie v zbierkach registrovaných galérií. Diela zo súkromných zbierok a iných zdrojov aktuálne nezverejňujeme. 
                </p>
            </div>
            <div class="col-md-4">
                <p class="lead">
                    Sme otvorení <strong>ďalším <br>formám&nbsp;spolupráce</strong>.
                </p>
                <p>
                    Sme však otvorení iným formám spolupráce, čoskoro zverejníme časť výtvarných diel ako otvorené dáta. Ak viete napríklad o niektorom z autorov viac informácií, alebo ste na jeho profile u nás nenašli fotografiu, budeme radi ak nám pomôžete pri ich doplnení. Napíšte nám na <a href="mailto:lab@sng.sk">lab@sng.sk</a>, spojíme sa s vami.
                </p>
            </div>  
            <div class="col-md-4">
                <p class="lead">
                    Web umenia realizuje <br>kolektív <strong><a href="http://lab.sng.sk/" target="_blank" class="strong">lab.SNG</a></strong>
                </p>
                <ul class="list-lab list-unstyled">
                    <li><strong>Michal Čudrnák</strong> vedúci lab.SNG, autor článkov</li>
                    <li><strong>Lukáš Štepanovský</strong> editor obsahu, autor článkov</li>
                    <li><strong>Igor Rjabinin</strong> vývojár</li>
                    <li><strong>Philo van Kemenade</strong> vývojár</li>
                    <li><strong>Braňo Matis</strong> grafický dizajnér</li>
                </ul>

            </div>  
        </div>
    </div>
</section>

<!-- Modal -->
<div tabindex="-1" class="modal fade" id="priceList" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h1>cenník reprodukcií</h1>
            </div>
            <div class="modal-body">
                
                <p>Ceny sú len orientačné</p>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th></th>
                            <th class="text-right">do formátu A4</th>
                            <th class="text-right">od A4 do A3+</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>samostatná reprodukcia</strong></td>
                            <td class="text-right">25 €</td>
                            <td class="text-right">35 €</td>
                        </tr>
                        <tr>
                            <td><strong><s>reprodukcia s paspartou</s></strong><sup class="text-danger">*</sup></td>
                            <td class="text-right">35 €</td>
                            <td class="text-right">50 €</td>
                        </tr>
                        <tr>
                            <td><strong><s>s paspartou a rámom</s></strong><sup class="text-danger">*</sup></td>
                            <td class="text-right">40 €</td>
                            <td class="text-right">60 €</td>
                        </tr>
                    </tbody>
                </table>

                <p>
                    <sup class="text-danger">*</sup> Z technických dôvodov dočasne pozastavené
                </p>

            </div>
            <div class="modal-footer">
                <div class="text-center"><button type="button" data-dismiss="modal" class="btn btn-default btn-outline sans">zavrieť</button></div>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascript')
{!! Html::script('js/slick.js') !!}
<script type="text/javascript">
    $(document).ready(function(){

        $('.artworks-preview').slick({
            dots: false,
            lazyLoad: 'progressive',
            infinite: false,
            speed: 300,
            slidesToShow: 1,
            slide: 'a',
            centerMode: false,
            variableWidth: true,
        });

    });
</script>

@stop
