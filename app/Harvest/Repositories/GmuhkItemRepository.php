<?php

namespace App\Harvest\Repositories;

use App\Harvest\Repositories\AbstractRepository;

class GmuhkItemRepository extends AbstractRepository
{
    protected $xPathNamespaces = [
        'dc' => 'http://purl.org/dc/elements/1.1/',
        'dcterms' => 'http://purl.org/dc/terms/',
        'edm' => 'http://www.europeana.eu/schemas/edm/',
        'skos' => 'http://www.w3.org/2004/02/skos/core#',
        'rdf' => 'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
    ];

    protected $fieldMap = [
        'datestamp' => './/ns:datestamp',
        'id' => './/ns:identifier',
        'identifier' => './/dc:identifier[0]',
        'author' => './/edm:Agent[starts-with(@rdf:about, "#PredmetAutor:")]/skos:prefLabel',
        'title' => './/dc:title',
        'dating' => './/edm:TimeSpan[starts-with(@rdf:about, "#PredmetDatText:")]/skos:prefLabel',
        'date_earliest' => './/edm:TimeSpan[starts-with(@rdf:about, "#PredmetDatHodn:")]/edm:begin',
        'date_latest' => './/edm:TimeSpan[starts-with(@rdf:about, "#PredmetDatHodn:")]/edm:end',
        'technique' => './/edm:ProvidedCHO/dc:format[node()]',
        'medium' => './/dcterms:medium[node()]',
        'measurement' => './/dcterms:extent',
        'gallery' => './/edm:dataProvider',
        'image' => './/edm:isShownBy/@rdf:resource',
        'work_type' => './/ns:setSpec[last()]',
    ];
}