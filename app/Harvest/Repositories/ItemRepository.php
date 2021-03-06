<?php

namespace App\Harvest\Repositories;

class ItemRepository extends AbstractRepository
{
    protected $xPathNamespaces = [
        'dc' => 'http://purl.org/dc/elements/1.1/',
        'dcterms' => 'http://purl.org/dc/terms/',
    ];

    protected $fieldMap = [
        'status' => './/ns:header/@status',
        'id' => './/ns:identifier',
        'identifier' => './/dc:identifier',
        'title_translated' => [
            null => './/dc:title.translated',
            'lang' => './@xml:lang',
            'title_translated' => '.',
        ],
        'type' => [
            null => './/dc:type',
            'lang' => './@xml:lang',
            'type' => '.',
        ],
        'format' => [
            null => './/dc:format',
            'lang' => './@xml:lang',
            'format' => '.',
        ],
        'format_medium' => [
            null => './/dc:format.medium',
            'lang' => './@xml:lang',
            'format_medium' => '.',
        ],
        'subject' => [
            null => './/dc:subject',
            'lang' => './@xml:lang',
            'subject' => '.',
        ],
        'title' => './/dc:title',
        'subject_place' => './/dc:subject.place',
        'relation_isPartOf' => './/dc:relation.isPartOf',
        'creator' => './/dc:creator',
        'authorities' => [
            null => './/dc:creator[starts-with(.,"urn:")]',
            'id' => '.',
            'role' => './following-sibling::dc:creator.role[1]',
        ],
        'rights' => './/dc:rights',
        'description' => './/dc:description',
        'extent' => './/dcterms:extent',
        'gallery' => './/dcterms:provenance[not(@type)]',
        'credit' => [
            null => './/dcterms:provenance[@type="former"]',
            'lang' => './@xml:lang',
            'credit' => '.',
        ],
        'created' => './/dcterms:created',
    ];
}