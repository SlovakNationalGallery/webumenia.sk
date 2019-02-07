<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Language Lines for objednavka.blade.php template
    |--------------------------------------------------------------------------
    */

    'title'         => 'Reproduction order',
    'order_alert'   => '<strong> Warning: </strong><br> If you wish a reproduction to be ready before Christmas, make sure to place your order <strong>before December 10th</strong>.',
    'order_content' => '<h2 class="bottom-space">Reproduction order</h2>
                        <p class="lead">We offer high quality printed or digital reproductions of selected artworks from the collections of the Slovak National Gallery.</p>
                        <p>After selecting the artworks you want, please select either printed or digital format. If you want both printed and digital reproductions, please create a separate order for each. You will recieve an e-mail with more detailed information after placing the order. </p>
                        <p>Printed reproductions can only be picked up at one of our pick up locations: <a href="http://www.sng.sk/en/bratislava/visiting-us/how-to-find-us/ex-libris-bookshop" target="_blank" class="underline">Ex Libris Bookshop in the Esterházy Palace, Námestie Ľudovíta Štúra 4, Bratislava</a>  or at the cash desk in <a href="http://www.sng.sk/en/zvolen/visiting-us/how-to-find-us" target="_blank" class="underline">Castle Zvolen, SNP Square 594/1, Zvolen</a>.</p>
                        <p>We provide digital reproductions of <abbr title="less than 70 years has followed the death of the artist" data-toggle="tooltip">copyright protected artworks</abbr> only for special purposes (educational, scientific, promotional, private), which can be specified in the order form below.</p>',
    'order_none'    => 'Your cart is empty',
    'order_remove'  => 'Remove',
    'order_warning' => 'We do not have this artwork digitized in a sufficient quality, printing the reproduction may take more time than usual.',

    'form_title'                   => 'Artworks in the order',
    'form_name'                    => 'Name',
    'form_address'                 => 'Address',
    'form_email'                   => 'E-mail',
    'form_phone'                   => 'Telephone number',
    'form_format'                  => 'Format',
    'form_format_for-print'        => 'Printed reproduction:',
    'form_format_for-print_a4'     => 'Printed reproduction A4 size:',
    'form_format_for-print_a3'     => 'Printed reproduction A3+ size:',
    'form_piece'                   => 'pc',
    'form_format_standalone'       => 'only print',
    'form_format_with_mounting'    => 'with mounting',
    'form_format_with_mounting_and_framing' => 'with mounting and framing',
    'form_format_a4'               => 'A4 size (25 eur/piece)',
    'form_format_a3'               => 'A3+ size (35 eur/piece)',
    'form_format_for-download'     => 'For download:',
    'form_format_digital'          => 'digital reproduction',
    'form_purpose-alert'           => 'The copyright law prevents us from providing digital reproductions of <abbr title="less than 70 years has followed the death of the artist" data-toggle="tooltip">copyright protected artworks</abbr> for general private purposes (decoration, gift etc.). Slovak National Gallery will create a written contract, according to the information provided in the order. This contract will limit the use of the reproduction just for the purpose stated in the order.',
    'form_purpose-label'           => 'Purpose',
    'form_purpose-info'            => 'Purpose description',
    'form_delivery-point'          => 'Pick up location',
    'form_delivery-point_exlibris' => 'Ex Libris Bookshop, SNG, Bratislava',
    'form_delivery-point_zvolen'   => 'Zvolen castle, Zvolen',
    'form_note'                    => 'Note (Comment)',
    'form_terms_and_conditions'    => 'I agree <a href="http://www.sng.sk/en/o-galerii/dokumenty/gdpr" class="underline" target="_blank">to provide my personal information</a> for the purpose of the reproduction order processing',
    'form_order'                   => 'Place order',

    // in routes.php
    'message_add_order'            => 'The artwork :artwork_description has been added to your cart.',
    'message_remove_order'         => 'The artwork :artwork_description has been removed from your cart.',
);
