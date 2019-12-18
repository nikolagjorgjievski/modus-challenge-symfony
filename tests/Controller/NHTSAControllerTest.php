<?php
namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NHTSAControllerTest extends WebTestCase
{
    /**
     * @dataProvider vehiclesUrlProvider
     */
    public function testVehicles($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('Count', $content);
        $this->assertGreaterThan(0, $content['Count']);

        $this->assertArrayHasKey('Results', $content);
        $this->assertGreaterThan(0, count($content['Results']));

        foreach ($content['Results'] as $vehicle) {
            $this->assertArrayHasKey('Description', $vehicle);
            $this->assertArrayHasKey('VehicleId', $vehicle);
        }
    }

    public function vehiclesUrlProvider()
    {
        return [
            ['/vehicles/2015/Audi/A3'],
            ['/vehicles/2015/Toyota/Yaris']
        ];
    }

    /**
     * @dataProvider invalidVehiclesUrlProvider
     */
    public function testInvalidVehicles($url)
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('Count', $content);
        $this->assertEquals(0, $content['Count']);

        $this->assertArrayHasKey('Results', $content);
        $this->assertEquals(0, count($content['Results']));
    }

    public function invalidVehiclesUrlProvider()
    {
        return [
            ['/vehicles/2015/Ford/Crown Victoria'],
            ['/vehicles/undefined/Ford/Fusion']
        ];
    }

    /**
     * @dataProvider vehiclesPostParamsProvider
     */
    public function testVehiclesPost($params)
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/vehicles',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($params)
        );

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('Count', $content);
        $this->assertGreaterThan(0, $content['Count']);

        $this->assertArrayHasKey('Results', $content);
        $this->assertGreaterThan(0, count($content['Results']));

        foreach ($content['Results'] as $vehicle) {
            $this->assertArrayHasKey('Description', $vehicle);
            $this->assertArrayHasKey('VehicleId', $vehicle);
        }
    }

    public function vehiclesPostParamsProvider()
    {
        return [
            [
                [
                    'modelYear' => 2015,
                    'manufacturer' => 'Audi',
                    'model' => 'A3'
                ]
            ], [
                [
                    'modelYear' => 2015,
                    'manufacturer' => 'Toyota',
                    'model' => 'Yaris'
                ]
            ],
        ];
    }

    /**
     * @dataProvider invalidVehiclesPostParamsProvider
     */
    public function testInvalidVehiclesPost($params)
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/vehicles',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($params)
        );

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('Count', $content);
        $this->assertEquals(0, $content['Count']);

        $this->assertArrayHasKey('Results', $content);
        $this->assertEquals(0, count($content['Results']));
    }

    public function invalidVehiclesPostParamsProvider()
    {
        return [
            [
                [
                    'manufacturer' => 'Honda',
                    'model' => 'Accord'
                ]
            ]
        ];
    }

    /**
     * @dataProvider vehiclesUrlProvider
     */
    public function testVehiclesWithRating($url)
    {
        $url .= '?withRating=true';

        $client = static::createClient();
        $client->request('GET', $url);

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('Count', $content);
        $this->assertGreaterThan(0, $content['Count']);

        $this->assertArrayHasKey('Results', $content);
        $this->assertGreaterThan(0, count($content['Results']));

        foreach ($content['Results'] as $vehicle) {
            $this->assertArrayHasKey('CrashRating', $vehicle);
            $this->assertArrayHasKey('Description', $vehicle);
            $this->assertArrayHasKey('VehicleId', $vehicle);
        }
    }
    /**
     * @dataProvider vehiclesWithInvalidRatingParamProvider
     */
    public function testVehiclesWithInvalidRatingParam($param)
    {
        $url = '/vehicles/2015/Audi/A3?withRating=' . $param;

        $client = static::createClient();
        $client->request('GET', $url);

        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('Count', $content);
        $this->assertGreaterThan(0, $content['Count']);

        $this->assertArrayHasKey('Results', $content);
        $this->assertGreaterThan(0, count($content['Results']));

        foreach ($content['Results'] as $vehicle) {
            $this->assertArrayNotHasKey('CrashRating', $vehicle);
            $this->assertArrayHasKey('Description', $vehicle);
            $this->assertArrayHasKey('VehicleId', $vehicle);
        }
    }

    public function vehiclesWithInvalidRatingParamProvider()
    {
        return [
            ['false'],
            ['bananas']
        ];
    }
}