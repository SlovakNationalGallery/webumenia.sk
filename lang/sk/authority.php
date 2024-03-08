<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Language Lines for autor.blade.php template
    |--------------------------------------------------------------------------
    */

    'artworks' => '{0}   <a href=":artworks_url"><strong>:artworks_count</strong></a> diel
                     |{1}   <a href=":artworks_url"><strong>:artworks_count</strong></a> dielo
                     |[2,4] <a href=":artworks_url"><strong>:artworks_count</strong></a> diela
                     |[5,*] <a href=":artworks_url"><strong>:artworks_count</strong></a> diel',
    'collections' => '{0}   v <strong>:collections_count</strong> kolekciách
                     |{1}   v <strong>:collections_count</strong> kolekcií
                     |[2,*] v <strong>:collections_count</strong> kolekciách',
    'views' => '{0}   <strong>:view_count</strong> videní
                     |{1}   <strong>:view_count</strong> videnie
                     |[2,4] <strong>:view_count</strong> videnia
                     |[5,*] <strong>:view_count</strong> videní',

    'tags' => 'tagy',
    'back-to-artists' => 'zoznam autorov a autoriek',
    'alternative_names' => 'príp.',
    'places' => 'pôsobenie',
    'external_links' => 'externé odkazy',
    'source_links' => 'použité zdroje',
    'relationships' => 'vzťahy',

    'artworks_by_artist' => '{male}diela autora|{female}diela autorky',

    'button_show-all-artworks' => '{0}   zobraziť <strong>0</strong> diel
                                  |{1}   zobraziť <strong>:artworks_count</strong> dielo
                                  |[2,4] zobraziť všetky <strong>:artworks_count</strong> diela
                                  |[5,*] zobraziť všetkých <strong>:artworks_count</strong> diel',

    'filter' => [
        'role' => 'rola',
        'nationality' => 'príslušnosť',
        'place' => 'miesto',
        'sex' => 'pohlavie',
        'title_generator' => [
            'first_letter' => 'začína sa na: :value',
            'role' => 'rola: :value',
            'nationality' => 'príslušnosť: :value',
            'place' => 'miesto: :value',
            'years' => 'v rokoch :from — :to',
        ],
        'sort_by' => 'podľa',
        'sorting' => [
            'items_with_images_count' => 'počtu diel s obrázkom',
            'name' => 'mena',
            'birth_year' => 'roku narodenia',
            'items_count' => 'počtu diel',
        ],
    ],

    'authors' => 'autori',
    'authors_found' => 'nájdení autori pre',
    'authors_counted' => 'autorov a autoriek',
    'authors_none' => 'momentálne žiadni autori',
    'roles_label' => 'role',
    'roles' => [
        'fotograf/photographer' => '{male}fotograf|{female}fotografka',
        'maliar/painter' => '{male}maliar|{female}maliarka',
        'keramikár/ceramicist' => '{male}keramikár|{female}keramikárka',
        'grafik/graphic artist' => '{male}grafik|{female}grafička',
        'ilustrátor/illustrator' => '{male}ilustrátor|{female}ilustrátorka',
        'tvorca inštalácií/installation artist' =>
            '{male}tvorca inštalácií|{female}tvorkyňa inštalácií',
        'sochár/sculptor' => '{male}sochár|{female}sochárka',
        'sklársky výtvarník/glass artist' =>
            '{male}sklársky výtvarník|{female}sklárska výtvarníčka',
        'hudobník/musician' => '{male}hudobník|{female}hudobníčka',
        'rezbár/carver' => '{male}rezbár|{female}rezbárka',
        'kresliar/draftsman' => '{male}kresliar|{female}kresliarka',
        'vydavateľ/publisher' => '{male}vydavateľ|{female}vydavateľka',
        'dizajnér/designer' => '{male}dizajnér|{female}dizajnérka',
        'multimediálny umelec/multimedia artist' =>
            '{male}multimediálny umelec|{female}multimediálna umelkyňa',
        'grafický dizajnér/graphic designer' =>
            '{male}grafický dizajnér|{female}grafická dizajnérka',
        'pedagóg/teacher' => '{male}pedagóg|{female}pedagogička',
        'kamenár/stone mason' => '{male}kamenár|{female}kamenárka',
        'historik umenia/art historian' => '{male}historik umenia|{female}historička umenia',
        'spisovateľ/writer' => '{male}spisovateľ|{female}spisovateľka',
        'priemyselný dizajnér/industrial designer' =>
            '{male}priemyselný dizajnér|{female}priemyselná dizajnérka',
        'krajinár/landscapist' => '{male}krajinár|{female}krajinárka',
        'žánrista/genre artist' => '{male}žánrista|{female}žánristk',
        'umelecký kováč/blacksmith artist' => '{male}umelecký kováč|{female}umelecká kováčka',
        'redaktor/editor' => '{male}redaktor|{female}redaktorka',
        'básnik/poet' => '{male}básnik|{female}poetka',
        'kurátor/curator' => '{male}kurátor|{female}kurátorka',
        'typograf/typographer' => '{male}typograf|{female}typografka',
        'kostýmový výtvarník/costume designer' =>
            '{male}kostýmový výtvarník|{female}kostýmová výtvarníčka',
        'scénograf/scenographer' => '{male}scénograf|{female}scénografka',
        'rytec/engraver' => '{male}rytec|{female}rytkyňa',
        'šperkár/jeweler' => '{male}šperkár|{female}šperkárka',
        'reštaurátor/restorer' => '{male}reštaurátor|{female}reštaurátorka',
        'tvorca objektov/object creator' => '{male}tvorca objektov|{female}tvorkyňa objektov',
        'videoumelec/video artist' => '{male}videoumelec|{female}videoumelkyňa',
        'maliar fresiek/fresco painter' => '{male}maliar fresiek|{female}maliarka fresiek',
        'karikaturista/caricaturist' => '{male}karikaturista|{female}karikaturistka',
        'animátor/animator' => '{male}animátor|{female}animátorka',
        'režisér/director' => '{male}režisér|{female}režisérka',
        'publicista/publicist' => '{male}publicista|{female}publicistka',
        'performér/performe' => '{male}performér|{female}performérka',
        'konceptuálny umelec/conceptual artist' =>
            '{male}konceptuálny umelec|{female}konceptuálna umelkyňa',
        'novomediálny umelec/new media artist' =>
            '{male}novomediálny umelec|{female}novomediálna umelkyňa',
        'filmár/film maker' => '{male}filmár|{female}filmárka',
        'tlačiar/printer' => '{male}tlačiar|{female}tlačiarka',
        'zberateľ/collector' => '{male}zberateľ|{female}zberateľka',
        'medailér/medallist' => '{male}medailér|{female}medailérka',
        'portrétista/portraitist' => '{male}portrétista|{female}portrétistka',
        'leptač/etcher' => '{male}leptač|{female}leptačka',
        'litograf/lithographer' => '{male}litograf|{female}litografka',
        'drevorytec/woodengraver' => '{male}drevorytec|{female}drevorytkyňa',
        'politik/politician' => '{male}politik|{female}politička',
        'medirytec/copperplate engraver' => '{male}medirytec|{female}medirytkyňa',
        'textilný výtvarník/textile artist' =>
            '{male}textilný výtvarník|{female}textilná výtvarníčka',
        'miniaturista/miniaturist' => '{male}miniaturista|{female}miniaturistka',
        'kovolejár - cinár/metal founder - pewterer' =>
            '{male}kovolejár - cinár|{female}kovolejárka - cinárka',
        'autor koláží/author of collages' => '{male}autor koláží|{female}autorka koláží',
        'bábkar/puppet actor' => '{male}bábkar|{female}bábkarka',
        'architekt/architect' => '{male}architekt|{female}architektka',
        'múzejník/museologist' => '{male}múzejník|{female}múzejníčka',
        'kartograf/cartographer' => '{male}kartograf|{female}kartografka',
        'zlatník/goldsmith' => '{male}zlatník|{female}zlatníčka',
        'iluminátor/illuminator' => '{male}iluminátor|{female}iluminátorka',
        'novinár/journalist' => '{male}novinár|{female}novinárka',
        'intermediálny umelec/intermedia artist' =>
            '{male}intermediálny umelec|{female}intermediálna umelkyňa',
        'historik/historian' => '{male}historik|{female}historička',
        'archeológ/archaeologist' => '{male}archeológ|{female}archeologička',
        'galvanograf/galvanographer' => '{male}galvanograf|{female}galvanografka',
        'scénický výtvarník/stage designer' =>
            '{male}scénický výtvarník|{female}scénická výtvarníčka',
        'dramaturg/dramaturge' => '{male}dramaturg|{female}dramaturgička',
        'reportér/reporter' => '{male}reportér|{female}reportérka',
        'scenárista/screenwriter' => '{male}scenárista|{female}scenáristka',
        'prekladateľ/translator' => '{male}prekladateľ|{female}prekladateľka',
        'kameraman/cameraman' => '{male}kameraman|{female}kameramanka',
        'akčný umelec/performance artist' => '{male}akčný umelec|{female}akčná umelkyňa',
        'výtvarný teoretik/art theorist' => '{male}výtvarný teoretik|{female}výtvarná teoretička',
        'etnograf/ethnographer' => '{male}etnograf|{female}etnografka',
        'muzeológ/museologist' => '{male}muzeológ|{female}muzeologička',
        'figuralista/figure painter' => '{male}figuralista|{female}figuralistka',
        'autor komiksov/comics author' => '{male}autor komiksov|{female}autorka komiksov',
        'tvorca poštových známok/post stamps designer' =>
            '{male}tvorca poštových známok|{female}tvorkyňa poštových známok',
        'počítačový grafik/computer graphic designer' =>
            '{male}počítačový grafik|{female}počítačová grafička',
        'urbanista/urbanist' => '{male}urbanista|{female}urbanistka',
        'odevný dizajnér/clothing designer' => '{male}odevný dizajnér|{female}odevná dizajnérka',
        'módny návrhár/fashion designer' => '{male}módny návrhár|{female}módna návrhárka',
        'staviteľ/builder' => '{male}staviteľ|{female}staviteľka',
        'pamiatkar/conservationist' => '{male}pamiatkar|{female}pamiatkarka',
        'historik architektúry/architectural historian' =>
            '{male}historik architektúry|{female}historička architektúry',
        'textilný návrhár/textile designer' => '{male}textilný návrhár|{female}textilná návrhárka',
        'monumentalista/monumentalist' => '{male}monumentalista|{female}monumentalistka',
        'čipkár/lace artist' => '{male}čipkár|{female}čipkárka',
        'divadelný a scénický výtvarník/theatre and stage designer' =>
            '{male}divadelný a scénický výtvarník|{female}divadelná a scénická výtvarníčka',
        'tvorca hračiek/designer of toys' => '{male}tvorca hračiek|{female}tvorkyňa hračiek',
        'umelecký knihár/bookbinding artist' => '{male}umelecký knihár|{female}umelecká knihárka',
        'hudobný vedec/music scientist' => '{male}hudobný vedec|{female}hudobná vedkyňa',
        'literát/philologist' => '{male}literát|{female}literátka',
        'herec/actor' => '{male}herec|{female}herečka',
        'keramik/ceramicist' => '{male}keramik|{female}keramička',
        'počítačový umelec/computer artist' => 'počítačový umelec',
        'majiteľ fotoateliéru/owner of a photographic studio' =>
            '{male}majiteľ fotoateliéru|{female}majiteľka fotoateliéru',
        'teológ/theologian' => 'teológ',
        'folklorista/folklorist' => '{male}folklorista|{female}folkloristka',
        'hodinár/watchmaker' => 'hodinár',
        'umelecký drotár/artistic tinker' => '{male}umelecký drotár|{female}umelecká drotárka',
        'horolezec/rock climber' => '{male}horolezec|{female}horolezkyňa',
        'stavebný inžinier' => 'stavebný inžinier',
        'streetartist' => 'streetartist',
    ],
    'sex' => [
        'male' => 'muž',
        'female' => 'žena',
    ],
];
