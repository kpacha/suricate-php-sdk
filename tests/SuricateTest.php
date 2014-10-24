<?php

namespace Kpacha\Suricate;

class SuricateTest extends \PHPUnit_Framework_TestCase
{

    const CLIENT_CLASS = 'Guzzle\Http\Client';

    private static $serviceNode = array(
        'name' => 'test',
        'id' => 'ca2fff8e-d756-480c-b59e-8297ff886240',
        'address' => '10.20.30.40',
        'port' => 1234,
        'registrationTimeUTC' => 1325129459728,
        'serviceType' => 'STATIC',
        'payload' => 'supu'
    );

    public function testListServiceNames()
    {
        $services = array('service1', 'service2');
        $returnedValue = array(array('name' => 'service1'), array('name' => 'service2'));

        $suricate = new Suricate($this->getMockedClient('/v1/service', $returnedValue));
        $this->assertEquals($services, $suricate->getAllNames());
    }

    public function testListServiceNodes()
    {
        $nodes = array(self::$serviceNode, self::$serviceNode);

        $suricate = new Suricate($this->getMockedClient('/v1/service/test', $nodes));
        $this->assertEquals($nodes, $suricate->getAll('test'));
    }

    public function testGetServiceNode()
    {
        $suricate = new Suricate(
                        $this->getMockedClient(
                                '/v1/service/test/ca2fff8e-d756-480c-b59e-8297ff886240', self::$serviceNode
                        )
        );
        $this->assertEquals(self::$serviceNode, $suricate->get('test', 'ca2fff8e-d756-480c-b59e-8297ff886240'));
    }

    public function testPutService()
    {
        $response = $this->getMock('Response', array('getStatusCode'));
        $response->expects($this->once())->method('getStatusCode')
                ->will($this->returnValue(201));

        $request = $this->getMock('Request', array('send'));
        $request->expects($this->once())->method('send')->will($this->returnValue($response));

        $client = $this->getMock(self::CLIENT_CLASS);
        $client->expects($this->once())->method('put')
                ->with(
                        '/v1/service/test/ca2fff8e-d756-480c-b59e-8297ff886240',
                        array('Content-Type' => 'application/json'), json_encode(self::$serviceNode)
                )
                ->will($this->returnValue($request));

        $suricate = new Suricate($client);
        $this->assertTrue($suricate->putService('test', 'ca2fff8e-d756-480c-b59e-8297ff886240', self::$serviceNode));
    }

    public function testRemoveService()
    {
        $response = $this->getMock('Response', array('getStatusCode'));
        $response->expects($this->once())->method('getStatusCode')
                ->will($this->returnValue(200));

        $request = $this->getMock('Request', array('send'));
        $request->expects($this->once())->method('send')->will($this->returnValue($response));

        $client = $this->getMock(self::CLIENT_CLASS);
        $client->expects($this->once())->method('delete')
                ->with('/v1/service/test/ca2fff8e-d756-480c-b59e-8297ff886240')
                ->will($this->returnValue($request));

        $suricate = new Suricate($client);
        $this->assertTrue($suricate->removeService('test', 'ca2fff8e-d756-480c-b59e-8297ff886240'));
    }

    /**
     * @expectedException Kpacha\Suricate\SuricateException
     */
    public function testSuricateExceptionIsThrowedIdSomethingGoesWrong()
    {
        $request = $this->getMock('Request', array('send'));
        $request->expects($this->once())->method('send')->will($this->throwException(new \Exception('master caution!')));

        $client = $this->getMock(self::CLIENT_CLASS);
        $client->expects($this->once())->method('get')->will($this->returnValue($request));

        $suricate = new Suricate($client);

        $suricate->get('test', 'ca2fff8e-d756-480c-b59e-8297ff886240');
    }

    private function getMockedClient($requestUrl, $requestReturnedValue)
    {
        $client = $this->getMock(self::CLIENT_CLASS);
        $client->expects($this->once())->method('get')->with($requestUrl)
                ->will($this->returnValue($this->getMockedRequest($requestReturnedValue)));
        return $client;
    }

    private function getMockedRequest($returnedValue)
    {
        $response = $this->getMock('Response', array('getBody'));
        $response->expects($this->once())->method('getBody')
                ->will($this->returnValue(json_encode($returnedValue)));

        $request = $this->getMock('Request', array('send'));
        $request->expects($this->once())->method('send')->will($this->returnValue($response));
        return $request;
    }

}

