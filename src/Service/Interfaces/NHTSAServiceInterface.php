<?php


namespace App\Service\Interfaces;


interface NHTSAServiceInterface
{

    /**
     * @param string $modelYear
     * @param string $manufacturer
     * @param string $model
     * @param bool $withRating
     * @return array
     */
    public function getVehicles($modelYear, $manufacturer, $model, $withRating);

}