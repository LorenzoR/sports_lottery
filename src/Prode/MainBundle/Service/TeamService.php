<?php

namespace Prode\MainBundle\Service;

use Doctrine\ORM\EntityManager;
use Prode\MainBundle\Entity\Team;

class TeamService {

    private $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function getById($id) {
       return $this->em->find('ProdeMainBundle:Team', $id);
    }
    
}

?>