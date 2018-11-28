<?php

namespace App\Harvest\Repositories;

use App\Harvest\Factories\EndpointFactory;
use App\SpiceHarvesterHarvest;
use App\SpiceHarvesterRecord;

abstract class AbstractRepository
{
    /** @var EndpointFactory */
    protected $endpointFactory;

    /** @var array */
    protected $xPathNamespaces = [];

    /** @var array */
    protected $fieldMap = [];

    /**
     * @param EndpointFactory $endpointFactory
     */
    public function __construct(EndpointFactory $endpointFactory) {
        $this->endpointFactory = $endpointFactory;

        $this->fieldMap += [
            null => '.',
            'datestamp' => './/ns:header/ns:datestamp',
        ];

        $this->xPathNamespaces += [
            'ns' => 'http://www.openarchives.org/OAI/2.0/',
        ];
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     * @param \DateTime $from
     * @param \DateTime $to
     * @return \Generator|array[]
     */
    public function getRows(SpiceHarvesterHarvest $harvest, \DateTime $from = null, \DateTime $to = null, &$total = null) {
        $endpoint = $this->endpointFactory->createEndpoint($harvest);
        $records = $endpoint->listRecords($harvest->metadata_prefix, $from, $to, $harvest->set_spec);

        $total = $records->getTotalRecordCount();

        foreach ($records as $record) {
            $row = $this->getDataRecursively($record, $this->fieldMap);
            yield $row[0];
        }
    }

    /**
     * @param SpiceHarvesterRecord $record
     * @return array
     */
    public function getRow(SpiceHarvesterRecord $harvesterRecord) {
        $endpoint = $this->endpointFactory->createEndpoint($harvesterRecord->harvest);
        $record = $endpoint->getRecord($harvesterRecord->item_id, $harvesterRecord->harvest->metadata_prefix);

        $row = $this->getDataRecursively($record, $this->fieldMap);
        return $row[0];
    }

    /**
     * @param \SimpleXMLElement $element
     * @param array|string $xpaths
     * @return array
     */
    protected function getDataRecursively(\SimpleXMLElement $element, $xpaths) {
        $this->registerXPathNamespaces($element);

        if (is_string($xpaths)) {
            $data = $element->xpath($xpaths);
            return array_map('strval', $data);
        }

        $nodes = $element->xpath($xpaths[null]);
        unset($xpaths[null]);

        $data = [];
        foreach ($nodes as $node) {
            $datum = [];
            foreach ($xpaths as $name => $xpath) {
                $datum[$name] = $this->getDataRecursively($node, $xpath);
            }
            $data[] = $datum;
        }

        return $data;
    }

    /**
     * @param \SimpleXMLElement $element
     */
    protected function registerXPathNamespaces(\SimpleXMLElement $element) {
        foreach ($this->xPathNamespaces as $prefix => $namespace) {
            $element->registerXPathNamespace($prefix, $namespace);
        }
    }
}