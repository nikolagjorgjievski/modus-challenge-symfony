<?php

namespace App\Service;

use App\Entity\Vehicle;
use App\Service\Interfaces\NHTSAServiceInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;

class HttpNHTSAService implements NHTSAServiceInterface
{
    private $url = 'https://one.nhtsa.gov';

    /**
     * @inheritDoc
     */
    public function getVehicles($modelYear, $manufacturer, $model, $withRating)
    {
        $url = $this->url . '/webapi/api/SafetyRatings';
        $url .= '/modelyear/' . $modelYear;
        $url .= '/make/' . $manufacturer;
        $url .= '/model/' . $model;
        $url .= '?format=json';
        $response = $this->request('GET', $url);

        $vehicles = [];

        if (!isset($response['Results'])) {
            return $vehicles;
        }

        foreach ($response['Results'] as $responseVehicle) {
            $vehicle = new Vehicle();
            $vehicle->setVehicleId($responseVehicle['VehicleId']);
            $vehicle->setDescription($responseVehicle['VehicleDescription']);
            if ($withRating) {
                $rating = $this->getVehicleRating($responseVehicle['VehicleId']);
                $vehicle->setRating($rating);
            }
            $vehicles[] = $vehicle;
        }

        return $vehicles;
    }

    /**
     * @param int $vehicleId
     * @return string
     */
    private function getVehicleRating($vehicleId)
    {
        $url = $this->url . '/webapi/api/SafetyRatings';
        $url .= '/VehicleId/' . $vehicleId;
        $url .= '?format=json';

        $response = $this->request('GET', $url);

        if (!isset($response['Results'])
            || count($response['Results']) == 0
            || !isset($response['Results'][0]['OverallRating'])
        ) {
            return '';
        }

        return $response['Results'][0]['OverallRating'];
    }

    /**
     * @return array
     */
    private function request($method, $url)
    {
        $client = HttpClient::create();
        try {
            $response = $client->request($method, $url);
            $statusCode = $response->getStatusCode();

            if ($statusCode != 200) {
                return [];
            }

            $headers = $response->getHeaders();
            $contentType = $headers['content-type'][0];

            if (strpos($contentType, 'application/json') === false) {
                return [];
            }

            return $response->toArray();
        } catch (ExceptionInterface $e) {
            error_log($e->getMessage());
        }
        return [];

    }

}