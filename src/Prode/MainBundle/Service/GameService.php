<?php

namespace Prode\MainBundle\Service;

use Doctrine\ORM\EntityManager;
use Prode\MainBundle\Entity\Team;

class GameService {

    private $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }
    
    public function updateResult($game, $result) {
        $game->setResult($result);
        $this->em->flush();
    }

    public function getById($id) {
        return $this->em->find('ProdeMainBundle:Game', $id);
    }

    public function getAllGames() {
        return $this->em->getRepository('ProdeMainBundle:Game')->findAll();
    }

    public function getByRound($round) {
        $query = $this->em->createQueryBuilder();
        $query->select('g', 'a', 'h')
                ->from('Prode\MainBundle\Entity\Game', 'g')
                ->join('g.home', 'h')
                ->join('g.away', 'a')
                ->where('g.round = :round')
                ->setParameters(array('round' => $round))
                ->orderBy('g.date', 'ASC');

        return $query->getQuery()->getResult();
    }

}

?>