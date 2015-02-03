<?php

namespace Prode\MainBundle\Service;

use Doctrine\ORM\EntityManager;
use Prode\MainBundle\Entity\Team;

class UserService {

    private $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function getFirstUser() {
        $query = $this->em->createQueryBuilder();
        $query->select('u')
                ->from('Prode\MainBundle\Entity\User', 'u')
                ->where('u.isPlaying = :isPlaying')
                ->setParameters(array('isPlaying' => true));
        
        return $query->getQuery()->setMaxResults(1)->setFirstResult(0)->getResult();
    }
    
    public function getAllUsers() {
       return $this->em->getRepository('ProdeMainBundle:User')->findAll();
    }
    
    public function getPlayingUsers() {
        return $this->em->getRepository('ProdeMainBundle:User')->findAll();
    }
    
    public function getById($id) {
        return $this->em->getRepository('ProdeMainBundle:User')->find($id);
    }
    
}

?>