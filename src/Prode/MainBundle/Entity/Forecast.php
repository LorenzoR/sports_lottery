<?php

namespace Prode\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Prode\MainBundle\Entity\Forecast
 *
 * @ORM\Table(name="forecast")
 * @ORM\Entity
 */
class Forecast {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="Prode\MainBundle\Entity\Game", inversedBy="forecasts")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     * */
    private $game;
    
    /**
     * @ORM\ManyToOne(targetEntity="Prode\MainBundle\Entity\User", inversedBy="forecasts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Prode\MainBundle\Entity\Team")
     * @ORM\JoinColumn(name="result", referencedColumnName="id")
     * */
    private $result;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct($game, $user, $result) {
        $this->game = $game;
        $this->user = $user;
        $this->result = $result;
        $this->date = new \DateTime("now");
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getGame() {
        return $this->game;
    }

    public function setGame($game) {
        $this->game = $game;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getResult() {
        return $this->result;
    }

    public function setResult($result) {
        $this->result = $result;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }
    
    public function isCorrect() {
        return $this->result->getId() == $this->game->getResult()->getId();
    }
    
    public function isHome() {
        return $this->result->getId() == $this->game->getHome()->getId();
    }
    
    public function isAway() {
        return $this->result->getId() == $this->game->getAway()->getId();
    }
    
    public function isDraw() {
        return $this->result->getId() === 0;
    }
    
    public function __toString() {
        return "Forecast: ".$this->id.". ".$this->game.", Usuario:".$this->user;
    }

}

?>
