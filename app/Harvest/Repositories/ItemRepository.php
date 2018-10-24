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
        'creator_role' => './/dc:creator.role',
        'authorities' => [
            null => './/dc:creator[starts-with(.,"urn:")]',
            'id' => '.',
        ],
        'rights' => './/dc:rights',
        'description' => './/dc:description',
        'extent' => './/dcterms:extent',
        'provenance' => './/dcterms:provenance',
        'created' => './/dcterms:created',
        'contributor' => './/dcterms:contributor',
    ];
}