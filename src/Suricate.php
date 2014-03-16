<?php

namespace Kpacha\Suricate;

use Guzzle\Http\Client;

class Suricate
{

    const URL_PREFIX = '/v1/service';
    const STATUS_CODE_CREATED = 201;
    const STATUS_CODE_OK = 200;

    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getAllNames()
    {
        $services = $this->executeGetMethod(self::URL_PREFIX);
        $serviceNames = array();
        foreach ($services as $service) {
            $serviceNames[] = $service['name'];
        }
        return $serviceNames;
    }

    public function getAll($service)
    {
        return $this->executeGetMethod(self::URL_PREFIX . "/$service");
    }

    public function get($service, $id)
    {
        return $this->executeGetMethod(self::URL_PREFIX . "/$service/$id");
    }

    private function executeGetMethod($url)
    {
        $request = $this->client->get($url);
        $response = $request->send();
        return json_decode($response->getBody(), true);
    }

    public function putService($service, $id, $node)
    {
        $request = $this->client->put(
                self::URL_PREFIX . "/$service/$id", array('Content-Type' => 'application/json'), json_encode($node)
        );
        $response = $request->send();
        return $response->getStatusCode() == self::STATUS_CODE_CREATED;
    }

    public function removeService($service, $id)
    {
        $request = $this->client->delete(self::URL_PREFIX . "/$service/$id");
        $response = $request->send();
        return $response->getStatusCode() == self::STATUS_CODE_OK;
    }

}
