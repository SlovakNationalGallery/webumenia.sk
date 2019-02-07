<?php

namespace App\Harvest\Harvesters;

use App\Authority;
use App\Harvest\Importers\AuthorityImporter;
use App\Harvest\Repositories\AuthorityRepository;
use App\Harvest\Result;
use App\SpiceHarvesterRecord;

class AuthorityHarvester extends AbstractHarvester
{
    public function __construct(AuthorityRepository $repository, AuthorityImporter $importer) {
        parent::__construct($repository, $importer);
    }

    public function harvestSingle(SpiceHarvesterRecord $record, Result $result, array $row = null) {
        if ($row === null) {
            $row = $this->repository->getRow($record);
        }

        // @todo responsibility of repository?
        $linkUrls = $this->parseLinkUrls($row);
        if ($linkUrls) {
            $row['links'] = [];
            foreach ($linkUrls as $linkUrl) {
                $row['links'][] = [
                    'url' => [$linkUrl]
                ];
            }
        }

        /** @var Authority $authority */
        $authority = parent::harvestSingle($record, $result, $row);

        $authority->index();
        return $authority;
    }

    // @todo could be moved to mapper if mapper'd have access to whole row
    protected function parseLinkUrls(array $row) {
        if (isset($row['biography'][0])) {
            preg_match_all('#https?://\S+#', $row['biography'][0], $matches);
            return $matches[0];
        }
    }
}