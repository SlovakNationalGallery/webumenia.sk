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

    'form_title'                   => 'Diela objednávky',
    'form_name'                    => 'Meno',
    'form_address'                 => 'Adresa',
    'form_email'                   => 'E-mail',
    'form_phone'                   => 'Telefón',
    'form_format'                  => 'Formát',
    'form_format_for-print'        => 'tlačená reprodukcia:',
    'form_format_for-print_a4'     => 'tlačená reprodukcia do formátu A4:',
    'form_format_for-print_a3'     => 'tlačená reprodukcia do formátu A3+:',
    'form_piece'                   => 'ks',
    'form_format_standalone'       => 'samostatná reprodukcia',
    'form_format_with_mounting'    => 'reprodukcia s paspartou',
    'form_format_with_mounting_and_framing' => 's paspartou a rámom',
    'form_format_a4'               => 'do formátu A4 (24 €/ks)',
    'form_format_a3'               => 'do formátu A3+ (35 €/ks)',
    'form_format_for-download'     => 'na stiahnutie:',
    'form_format_digital'          => 'digitálna reprodukcia',
    'form_purpose-alert'           => 'Autorský zákon nám neumožňuje poskytovať digitálne reprodukcie <abbr title="neprešlo 70 rokov od smrti autora" data-toggle="tooltip">autorsky chránených diel</abbr> na všeobecné súkromné účely (napr. ako dekoráciu). Na základe Vami uvedených informácií vytvorí SNG písomný súhlas s využitím digitálnej reprodukcie iba na predmetný účel &ndash; je to legislatívna ochrana tak pre Vás ako aj pre nás.',
    'form_purpose-alert-print'     => 'V prípade záujmu o tlač výtvarných diel môžete využiť objednávku na tlačenú reprodukciu, kde výrobu a úpravu výtlačku zabezpečuje SNG.',
    'form_purpose-label'           => 'Účel',
    'form_purpose-info'            => 'Účel - podrobnejšie informácie',
    'form_frame'                   => 'Rám',
    'form_frame_help'              => 'zobrazit náhled',
    'form_frame_black'             => 'černý',
    'form_frame_white'             => 'světlé dřevo',
    'form_delivery-point'          => 'Miesto osobného odberu',
    'form_delivery-point_exlibris' => 'Kníhkupectvo Ex Libris v SNG',
    'form_delivery-point_zvolen'   => 'Zvolenský zámok',
    'form_note'                    => 'Poznámka',
    'form_terms_and_conditions'    => 'souhlasím se <a href="http://www.sng.sk/sk/o-galerii/dokumenty/gdpr" class="underline" target="_blank">zpracováním osobních údajů</a> pro účel vyřízení objednávky',
    'form_order'                   => 'Objednať',

    'modal_frame_colors'           => 'Barvy rámů',
    'modal_frame_availability'     => 'K dispozici v obou formátech A4 a A3 +.',
    'modal_frame_multiple'         => 'V případě, že objednáváte několik děl, prosíme uveďte barvu rámu k jednotlivým dílům do poznámky.',

    // in routes.php
    'message_add_order'            => 'Dielo :artwork_description bolo pridané do košíka.',
    'message_remove_order'         => 'Dielo :artwork_description bolo odstránené z košíka.',
);
