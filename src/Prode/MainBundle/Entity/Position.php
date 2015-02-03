<?php

namespace Prode\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Prode\MainBundle\Entity\Position
 *
 * @ORM\Table(name="position")
 * @ORM\Entity
 */
class Position {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Prode\MainBundle\Entity\User", inversedBy="position")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $firstRound;

    /**
     * @ORM\Column(type="integer")
     */
    private $roundOf16;

    /**
     * @ORM\Column(type="integer")
     */
    private $quarterfinals;

    /**
     * @ORM\Column(type="integer")
     */
    private $semifinals;

    /**
     * @ORM\Column(type="integer")
     */
    private $thirdPlace;

    /**
     * @ORM\Column(type="integer")
     */
    private $finals;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $finalTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    function __construct() {
        $this->firstRound = 0;
        $this->roundOf16 = 0;
        $this->quarterfinals = 0;
        $this->semifinals = 0;
        $this->thirdPlace = 0;
        $this->finals = 0;
        $this->finalTime = 0;
        $this->total = 0;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function getFirstRound() {
        return $this->firstRound;
    }

    public function setFirstRound($firstRound) {
        $this->firstRound = $firstRound;
    }

    public function getRoundOf16() {
        return $this->roundOf16;
    }

    public function setRoundOf16($roundOf16) {
        $this->roundOf16 = $roundOf16;
    }

    public function getQuarterfinals() {
        return $this->quarterfinals;
    }

    public function setQuarterfinals($quarterfinals) {
        $this->quarterfinals = $quarterfinals;
    }

    public function getSemifinals() {
        return $this->semifinals;
    }

    public function setSemifinals($semifinals) {
        $this->semifinals = $semifinals;
    }

    public function getThirdPlace() {
        return $this->thirdPlace;
    }

    public function setThirdPlace($thirdPlace) {
        $this->thirdPlace = $thirdPlace;
    }

    public function getFinals() {
        return $this->finals;
    }

    public function setFinals($finals) {
        $this->finals = $finals;
    }
    
    public function getFinalTime() {
        return $this->finalTime;
    }

    public function setFinalTime($finalTime) {
        $this->finalTime = $finalTime;
    }

    public function getTotal() {
        return $this->total;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function __toString() {
        return $this->user. " " . $this->total;
    }

}

?>
