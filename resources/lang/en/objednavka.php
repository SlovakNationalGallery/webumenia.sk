<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Language Lines for objednavka.blade.php template
    |--------------------------------------------------------------------------
    */

    'title'         => 'Reproduction order',
    'order_alert'   => '<strong> Warning: </strong><br> If you wish a reproduction to be ready before Christmas, make sure to place your order <strong>before December 9th</strong>.',
    'order_content' => '<h2 class="bottom-space">Reproduction order</h2>
                        <p>If you are interested in both printed and digital reproductions, please make a <strong>separate order for each type</strong>.</p>
                        <p>If you wish to order different sizes for each print, please specify them in the <strong>Note</strong> field.</p>
                        <p>Currently, prints can only be retrieved in person, either in <a href="https://www.sng.sk/en/bratislava/visiting-us/how-to-find-us/ex-libris-bookshop" target="_blank" class="underline">Ex Libris bookstore</a> in Esterházy palace in Bratislava, or at the ticket office of the <a href="https://www.sng.sk/en/zvolen/visiting-us/how-to-find-us" target="_blank" class="underline">Zvolen Castle</a>.</p>
                        <p>You will receive an <strong>automatic e-mail with detailed information</strong> after placing an order.</p>
                        <p>For more information, please visit the <a href="https://www.webumenia.sk/en/reprodukcie" target="_blank" class="underline">reproductions page</a>.</p>',
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
    'form_format_for-print_a2'     => 'Printed reproduction A2 size:',
    'form_format_for-print_a1'     => 'Printed reproduction A1 size:',
    'form_format_for-poster_a1'    => 'Poster A1 size:',
    'form_piece'                   => 'pc',
    'form_format_standalone'       => 'only print',
    'form_format_with_mounting'    => 'with mounting',
    'form_format_with_mounting_and_framing' => 'with mounting and framing',
    'form_format_a4'               => 'A4 size (25 eur/piece)',
    'form_format_a3'               => 'A3+ size (35 eur/piece)',
    'form_format_for-download'     => 'For download:',
    'form_format_digital'          => 'digital reproduction',
    'form_purpose-alert'           => 'The copyright law prevents us from providing digital reproductions of <abbr title="less than 70 years has followed the death of the artist" data-toggle="tooltip">copyright protected artworks</abbr> for general private purposes (decoration, gift etc.). Slovak National Gallery will create a written contract, according to the information provided in the order. This contract will limit the use of the reproduction just for the purpose stated in the order.',
    'form_purpose-alert-print'     => 'If you are interested in artwork prints, you can order high-quality reproductions, which are printed and framed by Slovak National Gallery.',
    'form_purpose-alert-poster'    => 'In this format, we currently offer only the reproductions of works from the <a href="https://www.webumenia.sk/en/kolekcia/144" target="_blank" class="alert-link">SNG Posters collection</a>. If your chosen artwork is not in this collection, please select a different format. We are gradually expanding the collection of available posters.',
    'form_purpose-label'           => 'Purpose',
    'form_purpose_private'         => 'Private',
    'form_purpose_commercial'      => 'Commercial',
    'form_purpose_research'        => 'Research',
    'form_purpose_education'       => 'Education',
    'form_purpose_exhibition'      => 'Exhibition',
    'form_purpose-info'            => 'Purpose description',
    'form_frame'                   => 'Frame',
    'form_frame_help'              => 'show preview',
    'form_frame_black'             => 'Black',
    'form_frame_white'             => 'Light wood',
    'form_delivery-point'          => 'Pick up location',
    'form_delivery-point_exlibris' => 'Ex Libris Bookshop, SNG, Bratislava',
    'form_delivery-point_zvolen'   => 'Zvolen castle, Zvolen',
    'form_note'                    => 'Note (Comment)',
    'form_terms_and_conditions'    => 'I agree <a href="http://www.sng.sk/en/o-galerii/dokumenty/gdpr" class="underline" target="_blank">to provide my personal information</a> for the purpose of the reproduction order processing',
    'form_order'                   => 'Place order',

    'modal_frame_colors'           => 'Frame colors',
    'modal_frame_availability'     => 'Available in both A4 and A3 + formats.',
    'modal_frame_multiple'         => 'If you order multiple items, please specify the color of the frame for each item in the note (comment).',

    // in routes.php
    'message_add_order'            => 'The artwork :artwork_description has been added to your cart.',
    'message_remove_order'         => 'The artwork :artwork_description has been removed from your cart.',
);
