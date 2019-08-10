<?php

namespace App\Harvest\Factories;

use App\SpiceHarvesterHarvest;
use GuzzleHttp\Client as GuzzleClient;
use Phpoaipmh\Client;
use Phpoaipmh\ClientInterface;
use Phpoaipmh\Endpoint;
use Phpoaipmh\EndpointInterface;
use Phpoaipmh\HttpAdapter\GuzzleAdapter;
use Phpoaipmh\HttpAdapter\HttpAdapterInterface;

class EndpointFactory
{
    /**
     * @param SpiceHarvesterHarvest $harvest
     * @return EndpointInterface
     */
    public function createEndpoint(SpiceHarvesterHarvest $harvest) {
        $client = $this->createClient($harvest);
        return new Endpoint($client);
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     * @return ClientInterface
     */
    public function createClient(SpiceHarvesterHarvest $harvest) {
        $adapter = $this->createHttpAdapter($harvest);
        return new Client($harvest->base_url, $adapter);
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     * @return HttpAdapterInterface
     */
    public function createHttpAdapter(SpiceHarvesterHarvest $harvest) {
        $guzzleClient = $this->createGuzzleClient($harvest);
        return $guzzleClient ? new GuzzleAdapter($guzzleClient) : null;
    }

    /**
     * @param SpiceHarvesterHarvest $harvest
     * @return GuzzleClient
     */
    public function createGuzzleClient(SpiceHarvesterHarvest $harvest) {
        if ($harvest->username && $harvest->password) {
            return new GuzzleClient(['auth' => [$harvest->username, $harvest->password]]);
        }
    }
}