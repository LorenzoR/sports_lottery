<?php

namespace Prode\MainBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Prode\MainBundle\Entity\Position;

/**
 * @ORM\Entity
 * @ORM\Table(name="prode_user")
 */
class User extends BaseUser {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $dni;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity="Prode\MainBundle\Entity\Forecast", mappedBy="user", cascade={"persist"})
     * */
    private $forecasts;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPlaying;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $invitedBy;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $businessUnit;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $workPlace;

    /**
     * @ORM\OneToOne(targetEntity="Prode\MainBundle\Entity\Position", mappedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id")
     * */
    private $position;

    public function __construct() {
        parent::__construct();
        $this->isPlaying = true;
        $this->position = new Position();
        $this->position->setUser($this);
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDni() {
        return $this->dni;
    }

    public function setDni($dni) {
        $this->dni = $dni;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function getForecasts() {
        return $this->forecasts;
    }

    public function setForecasts($forecasts) {
        $this->forecasts = $forecasts;
    }

    public function getIsPlaying() {
        return $this->isPlaying;
    }

    public function setIsPlaying($isPlaying) {
        $this->isPlaying = $isPlaying;
    }

    public function getInvitedBy() {
        return $this->invitedBy;
    }

    public function setInvitedBy($invitedBy) {
        $this->invitedBy = $invitedBy;
    }

    public function getBusinessUnit() {
        return $this->businessUnit;
    }

    public function setBusinessUnit($businessUnit) {
        $this->businessUnit = $businessUnit;
    }

    public function getWorkPlace() {
        return $this->workPlace;
    }

    public function setWorkPlace($workPlace) {
        $this->workPlace = $workPlace;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position) {
        $this->position = $position;
    }

    public function __toString() {
        return $this->username;
    }

}

?>
