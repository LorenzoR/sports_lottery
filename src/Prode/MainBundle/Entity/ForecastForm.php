<?php

namespace Prode\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class ForecastForm {

    private $user;
    private $forecasts;

    function __construct() {
        $this->forecasts = new ArrayCollection();
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getForecasts() {
        return $this->forecasts;
    }

    public function setForecasts($forecasts) {
        $this->forecasts = $forecasts;
    }
    
    public function addForecast($forecast) {
        $this->forecasts->add($forecast);
    }

}
?>
