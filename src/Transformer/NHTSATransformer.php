<?php

namespace App\Transformer;

use App\Entity\Vehicle;

class NHTSATransformer
{

    /**
     * @param array $vehicles
     * @param bool $withRating
     * @return array
     */
    public function transform($vehicles, $withRating)
    {
        if (empty($vehicles)) {
            return [
                'Count' => 0,
                'Results' => []
            ];
        }

        $response = [];
        $response['Count'] = count($vehicles);
        $response['Results'] = [];

        /** @var Vehicle $vehicle */
        foreach ($vehicles as $vehicle) {
            $vehicleResult = [];
            if ($withRating) {
                $vehicleResult['CrashRating'] = $vehicle->getRating();
            }
            $vehicleResult = array_merge($vehicleResult, [
                'Description' => $vehicle->getDescription(),
                'VehicleId' => $vehicle->getVehicleId()
            ]);
            $response['Results'][] = $vehicleResult;
        }

        return $response;

    }

}