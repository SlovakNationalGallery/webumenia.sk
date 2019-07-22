<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Language Lines for objednavka.blade.php template
    |--------------------------------------------------------------------------
    */

    'title'         => 'objednávka',
    'order_alert'   => '<strong> Upozornění: </strong> <br> Tištěné reprodukce, které mají být provedeny do Vánočních svátků, je možné objednat do <strong>10. prosince</strong>.<br> Objednávky vytvořené po tomto termínu budou vyhotoveny <strong>až po svátcích</strong>.',
    'order_content' => '<h2 class="bottom-space">Objednávka</h2>
                        <p>Chcete-li si objednat nejen tištěné, ale i digitální reprodukce, udělejte prosím <strong>zvlášť objednávku pro každý typ</strong>.</p>
                        <p>Přejete-li si různé rozměry pro jednotlivé tištěné reprodukce, uveďte je prosím v položce <strong>Poznámka</strong>.</p>
                        <p>Momentálně je možné vyzvednout reprodukce pouze osobně v <a href="https://www.sng.sk/sk/bratislava/navsteva/kde-nas-najdete/knihkupectvo-ex-libris" target="_blank" class="underline">knihkupectví Ex Libris</a> v prostorách SNG na Náměstí Ľ. Štúra 4 v Bratislavě nebo v pokladně <a href="https://www.sng.sk/sk/zvolen/navsteva/kde-nas-najdete" target="_blank" class="underline">Zvolenského zámku – Námestie SNP 594/1</a>.</p>
                        <p>Po odeslání objednávky Vám pošleme <strong>potvrzující e-mail s podrobnějšími informacemi</strong>.</p>
                        <p>Všechny důležité informace naleznete na stránce <a href="https://www.webumenia.sk/cs/reprodukcie" target="_blank" class="underline">reprodukce</a>.</p>',
    'order_none'    => 'Nemáte v košíku žiadne diela',
    'order_remove'  => 'odstrániť',
    'order_warning' => 'Toto dielo momentálne nemáme zdigitalizované v dostatočnej kvalite, vybavenie objednávky preto môže trvať dlhšie ako zvyčajne.',

    'form_title'                   => 'Díla objednávky',
    'form_name'                    => 'Jméno',
    'form_address'                 => 'Adresa',
    'form_email'                   => 'E-mail',
    'form_phone'                   => 'Telefon',
    'form_format'                  => 'Formát',
    'form_format_for-print'        => 'tištěná reprodukce:',
    'form_format_for-print_a4'     => 'tištěná reprodukce do formátu A4:',
    'form_format_for-print_a3'     => 'tištěná reprodukce do formátu A3+:',
    'form_format_for-print_a2'     => 'tištěná reprodukce do formátu A2:',
    'form_format_for-print_a1'     => 'tištěná reprodukce do formátu A1:',
    'form_piece'                   => 'ks',
    'form_format_standalone'       => 'samostatná reprodukce',
    'form_format_with_mounting'    => 'reprodukce s paspartou',
    'form_format_with_mounting_and_framing' => 's paspartou a rámem',
    'form_format_a4'               => 'do formátu A4',
    'form_format_a3'               => 'do formátu A3+',
    'form_format_for-download'     => 'ke stažení:',
    'form_format_digital'          => 'digitální reprodukce',
    'form_purpose-alert'           => 'Autorský zákon nám neumožňuje poskytovat digitální reprodukce <abbr title="neuplynulo 70 let od smrti autora" data-toggle="tooltip">autorsky chráněných děl</abbr> na všeobecné soukromé účely (např. jako dekoraci). Na základě Vámi uvedených informací vytvoří SNG písemný souhlas s využitím digitální reprodukce pouze na předmětný účel &ndash; je to legislativní ochrana tak pro Vás i pro nás.',
    'form_purpose-alert-print'     => 'V případě zájmu o tisk výtvarných děl můžete využít objednávku na tištěnou reprodukci, kde výrobu a úpravu výtisku zajišťuje SNG.',
    'form_purpose-label'           => 'Účel',
    'form_purpose-info'            => 'Účel - podrobnější informace',
    'form_frame'                   => 'Rám',
    'form_frame_help'              => 'zobrazit náhled',
    'form_frame_black'             => 'černý',
    'form_frame_white'             => 'světlé dřevo',
    'form_delivery-point'          => 'Místo osobního odběru',
    'form_delivery-point_exlibris' => 'Knihkupectví ex libris v SNG',
    'form_delivery-point_zvolen'   => 'Zvolenský zámek',
    'form_note'                    => 'Poznámka',
    'form_terms_and_conditions'    => 'souhlasím se <a href="http://www.sng.sk/sk/o-galerii/dokumenty/gdpr" class="underline" target="_blank">zpracováním osobních údajů</a> pro účel vyřízení objednávky',
    'form_order'                   => 'Objednat',

    'modal_frame_colors'           => 'Barvy rámů',
    'modal_frame_availability'     => 'K dispozici v obou formátech A4 a A3 +.',
    'modal_frame_multiple'         => 'V případě, že objednáváte několik děl, prosíme uveďte barvu rámu k jednotlivým dílům do poznámky.',

    // in routes.php
    'message_add_order'            => 'Dílo :artwork_description bylo přidáno do košíku.',
    'message_remove_order'         => 'Dílo :artwork_description bylo odstraněno z košíku.',
);
