
<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Language Lines for objednavka.blade.php template
    |--------------------------------------------------------------------------
    */

    'title'         => 'objednávka',
    'order_alert'   => '<strong>Upozornenie:</strong><br>Tlačené reprodukcie, ktoré majú byť vyhotovené do vianočných sviatkov, je možné objednať do <strong>10. decembra</strong>.<br> Objednávky vytvorené po tomto termíne budú vyhotovené <strong>až po sviatkoch</strong>.',
    'order_content' => '<h2 class="bottom-space">Objednávka</h2>
                        <p>Ak máte záujem o tlačené aj digitálne reprodukcie, vytvorte prosím <strong>zvlášť objednávku</strong> pre každý typ.</p>
                        <p>Ak si prajete rôzne rozmery pre jednotlivé tlačené reprodukcie, uveďte ich prosím v položke <strong>Poznámka</strong>.</p>
                        <p>Tlačené reprodukcie je momentálne možné vyzdvihnúť len osobne v&nbsp;kníhkupectve <a href="https://goo.gl/maps/3Uf4S" target="_blank" class="underline">Ex Libris v priestoroch SNG na Námestí Ľ. Štúra 4 v Bratislave</a>  alebo v pokladni <a href="https://goo.gl/maps/MPRy6Qdwm8s" target="_blank" class="underline">Zvolenského zámku - Námestie SNP 594/1</a>. </p>
                        <p>Po odoslaní objednávky Vám pošleme <strong>potvrdzujúci e-mail</strong> s podrobnejšími informáciami o objednávke.</p>
                        <p>Všetky dôležité informácie nájdete na stránke <a href="https://www.webumenia.sk/reprodukcie" target="_blank" class="underline">reprodukcie</a>.</p>',
    'order_none'    => 'Nemáte v košíku žiadne diela',
    'order_remove'  => 'odstrániť',
    'order_warning' => 'Toto dielo momentálne nemáme zdigitalizované v dostatočnej kvalite, preto nieje možné objednať si tlačenú reprodukciu.',

    'form_title'                   => 'Diela objednávky',
    'form_name'                    => 'Meno',
    'form_address'                 => 'Adresa',
    'form_email'                   => 'E-mail',
    'form_phone'                   => 'Telefón',
    'form_format'                  => 'Formát',
    'form_format_for-print'        => 'tlačená reprodukcia :',
    'form_format_for-print_a4'     => 'tlačená reprodukcia do formátu A4:',
    'form_format_for-print_a3'     => 'tlačená reprodukcia do formátu A3+:',
    'form_format_for-print_a2'     => 'tlačená reprodukcia do formátu A2:',
    'form_format_for-print_a1'     => 'tlačená reprodukcia do formátu A1:',
    'form_format_for-poster_a1'    => 'poster (art plagát) do formátu A1:',
    'form_piece'                   => 'ks',
    'form_format_standalone'       => 'samostatná reprodukcia',
    'form_format_with_mounting'    => 'reprodukcia s paspartou',
    'form_format_with_mounting_and_framing' => 's paspartou a rámom',
    'form_format_a3'               => 'samostatná reprodukcia (35 €/ks)',
    'form_format_for-download'     => 'na stiahnutie:',
    'form_format_digital'          => 'digitálna reprodukcia',
    'form_purpose-alert'           => 'Autorský zákon nám neumožňuje poskytovať digitálne reprodukcie <abbr title="neprešlo 70 rokov od smrti autora" data-toggle="tooltip">autorsky chránených diel</abbr> na všeobecné súkromné účely (napr. ako dekoráciu). Na základe Vami uvedených informácií vytvorí SNG písomný súhlas s využitím digitálnej reprodukcie iba na predmetný účel &ndash; je to legislatívna ochrana tak pre Vás ako aj pre nás.',
    'form_purpose-alert-print'     => 'V prípade záujmu o tlač výtvarných diel môžete využiť objednávku na tlačenú reprodukciu, kde výrobu a úpravu výtlačku zabezpečuje SNG.',
    'form_purpose-alert-poster'    => 'V tomto formáte ponúkame momentálne iba reprodukcie diel z kolekcie <a href="https://www.webumenia.sk/kolekcia/144" target="_blank" class="alert-link">Plagáty SNG</a>. Ak vami zvolené dielo nie je v tejto kolekcii, zvoľte prosím iný formát. Ponuku plagátov postupne rozširujeme.',
    'form_purpose-label'           => 'Účel',
    'form_purpose-info'            => 'Účel - podrobnejšie informácie',
    'form_frame'                   => 'Rám',
    'form_frame_help'              => 'zobraziť náhľad',
    'form_frame_black'             => 'čierny',
    'form_frame_white'             => 'svetlé drevo',
    'form_delivery-point'          => 'Miesto osobného odberu',
    'form_delivery-point_exlibris' => 'Kníhkupectvo Ex Libris v SNG',
    'form_delivery-point_zvolen'   => 'Zvolenský zámok',
    'form_note'                    => 'Poznámka',
    'form_terms_and_conditions'    => 'súhlasím so <a href="http://www.sng.sk/sk/o-galerii/dokumenty/gdpr" class="underline" target="_blank">spracovaním osobných údajov</a> na účel vybavenia objednávky',
    'form_order'                   => 'Objednať',

    'modal_frame_colors'           => 'Farby rámov',
    'modal_frame_availability'     => 'K dispozícií v oboch formátoch A4 a A3+.',
    'modal_frame_multiple'         => 'V prípade, že objednávate viacero diel, prosíme uveďte farbu rámu k jednotlivým dielam do poznámky.',

    // in routes.php
    'message_add_order'            => 'Dielo :artwork_description bolo pridané do košíka.',
    'message_remove_order'         => 'Dielo :artwork_description bolo odstránené z košíka.',
);
