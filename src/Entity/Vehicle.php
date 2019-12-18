<?php

namespace App\Entity;

class Vehicle
{
    private $vehicleId;
    private $description;
    private $rating;

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Vehicle
     */
    public function setDescription(string $description): Vehicle
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int
     */
    public function getVehicleId()
    {
        return $this->vehicleId;
    }

    /**
     * @param int $vehicleId
     * @return Vehicle
     */
    public function setVehicleId(int $vehicleId): Vehicle
    {
        $this->vehicleId = $vehicleId;
        return $this;
    }

    /**
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param $rating
     * @return Vehicle
     */
    public function setRating($rating): Vehicle
    {
        $this->rating = $rating;
        return $this;
    }

}