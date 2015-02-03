<?php

namespace Prode\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Prode\MainBundle\Entity\Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity
 */
class Game {
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Prode\MainBundle\Entity\Team")
     * @ORM\JoinColumn(name="home", referencedColumnName="id")
     * */
    private $home;
    
    /**
     * @ORM\ManyToOne(targetEntity="Prode\MainBundle\Entity\Team")
     * @ORM\JoinColumn(name="away", referencedColumnName="id")
     * */
    private $away;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $date;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    private $tournament;
    
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $round;
    
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $stage;
    
    /**
     * @ORM\ManyToOne(targetEntity="Prode\MainBundle\Entity\Team")
     * @ORM\JoinColumn(name="result", referencedColumnName="id")
     * */
    private $result;
    
    /**
     * @ORM\OneToMany(targetEntity="Prode\MainBundle\Entity\Forecast", mappedBy="game", cascade={"persist"})
     **/
    private $forecasts;
    
    public function __construct()
    {
        $this->forecasts = new ArrayCollection();
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getHome() {
        return $this->home;
    }

    public function setHome($home) {
        $this->home = $home;
    }

    public function getAway() {
        return $this->away;
    }

    public function setAway($away) {
        $this->away = $away;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getTournament() {
        return $this->tournament;
    }

    public function setTournament($tournament) {
        $this->tournament = $tournament;
    }

    public function getRound() {
        return $this->round;
    }

    public function setRound($round) {
        $this->round = $round;
    }

    public function getResult() {
        return $this->result;
    }

    public function setResult($result) {
        $this->result = $result;
    }

    public function getForecasts() {
        return $this->forecasts;
    }

    public function setForecasts($forecasts) {
        $this->forecasts = $forecasts;
    }
    
    public function getStage() {
        return $this->stage;
    }

    public function setStage($stage) {
        $this->stage = $stage;
    }
    
    public function hasStarted() {
        
        $now = new \DateTime("now");
        $diff = strtotime($this->date->format('Y-m-d H:i:s')) - strtotime($now->format('Y-m-d H:i:s'));
        $diff_in_hrs = $diff/3600;
        
        return $diff_in_hrs <= 4;
    }
    
    public function __toString() {
        return "Game ID: ".$this->id.". ".$this->home." vs. ".$this->away;
    }

}

?>
