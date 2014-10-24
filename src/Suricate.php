<?php

namespace Kpacha\Suricate;

use Guzzle\Http\ClientInterface;

class Suricate
{

    const URL_PREFIX = '/v1/service';
    const STATUS_CODE_CREATED = 201;
    const STATUS_CODE_OK = 200;

    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function getAllNames()
    {
        $services = $this->executeGetMethod();
        $serviceNames = array();
        foreach ($services as $service) {
            $serviceNames[] = $service['name'];
        }
        return $serviceNames;
    }

    public function getAll($service)
    {
        return $this->executeGetMethod("/$service");
    }

    public function get($service, $id)
    {
        return $this->executeGetMethod("/$service/$id");
    }

    private function executeGetMethod($url = '')
    {
        $response = $this->send($this->client->get($this->getNormalizedUrl($url)));
        return json_decode($response->getBody(), true);
    }

    public function putService($service, $id, $node)
    {
        $request = $this->client->put(
                $this->getNormalizedUrl("/$service/$id"),
                array('Content-Type' => 'application/json'),
                json_encode($node)
        );
        $response = $this->send($request);
        return $response->getStatusCode() == self::STATUS_CODE_CREATED;
    }

    public function removeService($service, $id)
    {
        $response = $this->send($this->client->delete($this->getNormalizedUrl("/$service/$id")));
        return $response->getStatusCode() == self::STATUS_CODE_OK;
    }

    private function getNormalizedUrl($url)
    {
        return self::URL_PREFIX . $url;
    }

    private function send($request)
    {
        try {
            return $request->send();
        } catch (\Exception $e) {
            throw new SuricateException($e->getMessage());
        }
    }
}
