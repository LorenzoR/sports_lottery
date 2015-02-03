<?php

namespace Prode\MainBundle\Service;

use Doctrine\ORM\EntityManager;
use Prode\MainBundle\Entity\Forecast;

class ForecastService {

    private $em;
    private $tournament = 'Brasil 2014';

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function insertForecast(Forecast $forecast) {
        $this->em->persist($forecast);
        $this->em->flush();
    }

    public function updateForecast($forecast, $newResult) {
        $forecast->setResult($newResult);
        $forecast->setDate(new \DateTime("now"));
        $this->em->flush();
    }

    public function getForecastByUserAndGame($user, $game) {
        $query = $this->em->createQueryBuilder();
        $query->select('f', 'g', 'a', 'h')
                ->from('Prode\MainBundle\Entity\Forecast', 'f')
                ->join('f.game', 'g')
                ->join('g.home', 'h')
                ->join('g.away', 'a')
                ->where('f.user = :user')
                ->andWhere('f.game = :game')
                ->andWhere('g.tournament = :tournament')
                ->setParameters(array('game' => $game, 'user' => $user, 'tournament' => $this->tournament));

        return $query->getQuery()->getOneOrNullResult();
        
    }
    
    public function countForecastByGame($gameId) {
        $query = $this->em->createQueryBuilder();
        $query->select('count(f.id)')
                ->from('Prode\MainBundle\Entity\Forecast', 'f')
                ->join('f.game', 'g')
                ->where('g.id = :gameId')
                ->andWhere('g.tournament = :tournament')
                ->setParameters(array('gameId' => $gameId, 'tournament' => $this->tournament));

        return $query->getQuery()->getSingleScalarResult();
    }
    
    public function getForecastByGame($gameId, $firstResult=0, $maxResults=50) {
        $query = $this->em->createQueryBuilder();
        $query->select('f', 'g', 'u')
                ->from('Prode\MainBundle\Entity\Forecast', 'f')
                ->join('f.game', 'g')
                ->join('f.user', 'u')
                ->where('f.game = :game')
                ->setParameters(array('game' => $gameId));
        
//                ->createQuery('SELECT f
//                                FROM ProdeMainBundle:Forecast f
//                                WHERE f.game = :game')
//                ->setParameter('game', $gameId);

        return $query->getQuery()->setMaxResults($maxResults)->setFirstResult($firstResult)->getResult();
    }

    public function getForecastsByRound($round, $firstResult = 0, $maxResults = 50) {
        $query = $this->em->createQueryBuilder();
        $query->select('f', 'g', 'h', 'a', 'u')
                ->from('Prode\MainBundle\Entity\Forecast', 'f')
                ->join('f.game', 'g')
                ->join('g.home', 'h')
                ->join('g.away', 'a')
                ->join('f.user', 'u')
                ->where('g.round = :round')
                ->andWhere('g.tournament = :tournament')
                ->andWhere('u.isPlaying = :isPlaying')
                ->setParameters(array('round' => $round, 
                                        'tournament' => $this->tournament,
                                        'isPlaying' => true))
                ->addOrderBy('g.id', 'ASC')
                ->addOrderBy('u.username', 'ASC');;

        return $query->getQuery()->setMaxResults($maxResults)->setFirstResult($firstResult)->getResult();
    }

    public function getForecastByUserAndRound($user, $round) {
        $query = $this->em->createQueryBuilder();
        $query->select('g', 'f', 'h', 'a')
                ->from('Prode\MainBundle\Entity\Game', 'g')
                ->leftJoin('g.forecasts', 'f', 'WITH', 'f.user = :user_id')
                ->join('g.home', 'h')
                ->join('g.away', 'a')
                ->where('g.round = :round')
                ->andWhere('g.tournament = :tournament')
                ->setParameters(array('user_id' => $user, 'round' => $round, 'tournament' => $this->tournament))
                ->orderBy('g.date', 'ASC');

        return $query->getQuery()->getResult();
    }

    public function getForecastsLeft($user, $round) {

        $query = $this->em->createQueryBuilder();
        $query->select('count(g.id)')
                ->from('Prode\MainBundle\Entity\Game', 'g')
                ->where('g.round = :round')
                ->andWhere('g.tournament = :tournament')
                ->setParameters(array('round' => $round, 'tournament' => $this->tournament));

        $gamesQty = $query->getQuery()->getSingleScalarResult();

        $query = $this->em->createQueryBuilder();
        $query->select('count(f.result)')
                ->from('Prode\MainBundle\Entity\Game', 'g')
                ->join('g.forecasts', 'f', 'WITH', 'f.user = :user')
                ->where('g.round = :round')
                ->andWhere('g.tournament = :tournament')
                ->setParameters(array('user' => $user, 'round' => $round, 'tournament' => $this->tournament));

        $resultsLeft = $query->getQuery()->getSingleScalarResult();

        return array('gamesQty' => $gamesQty, 'resultsLeft' => $resultsLeft);
    }

    public function getPoints() {

        $query = $this->em->createQueryBuilder();
        $query->select('f', 'g', 'u')
                ->from('Prode\MainBundle\Entity\Forecast', 'f')
                ->join('f.game', 'g')
                ->join('f.user', 'u')
                ->where('g.result = f.result')
                ->andWhere('g.tournament = :tournament')
                ->setParameters(array('tournament' => $this->tournament));

        return $query->getQuery()->getResult();
    }

//    public function getJobPost($jobPost) {
//       return $this->em->getRepository('ClasificadosJobBundle:JobPost')->find($jobPost);
//    }
//
//    public function getJobPostsByCategory($category) {
//        $dql = "SELECT j 
//                    FROM ClasificadosJobBundle:JobPost j 
//                    WHERE j.category = :category";
//
//        $query = $this->em->createQuery($dql);
//        $query->setParameter('category', $category);
//
//        return $query;
//    }
//
//    public function getJobPostsByCriteria($category, $province, $priceRange) {
//
//        $countQuery = 'SELECT COUNT(j) ';
//        $selectQuery = 'SELECT j ';
//        $where = array();
//        $parameters = array();
//
//        if ($province != null) {
//            $from = 'FROM ClasificadosJobBundle:JobPost j, ClasificadosUserBundle:Location l';
//            $where[] = 'j.location = l.id';
//            $where[] = 'l.province = :province';
//            $parameters['province'] = $province;
//        } else {
//            $from = 'FROM ClasificadosJobBundle:JobPost j';
//        }
//
//        if ($category != null) {
//            $where[] = 'j.category = :category';
//            $parameters['category'] = $category;
//        }
//
//        if ($priceRange != null) {
//            list($minPrice, $maxPrice) = preg_split('[a]', $priceRange);
//            $where[] = 'j.price BETWEEN :min_price AND :max_price';
//            $parameters['min_price'] = $minPrice;
//            $parameters['max_price'] = $maxPrice;
//        }
//
//        $countQuery .= $from;
//        $selectQuery .= $from;
//
//        $first = true;
//
//        foreach ($where AS $value) {
//            if ($first) {
//                $first = false;
//                $countQuery .= ' WHERE ' . $value;
//                $selectQuery .= ' WHERE ' . $value;
//            } else {
//                $countQuery .= ' AND ' . $value;
//                $selectQuery .= ' AND ' . $value;
//            }
//        }
//
//        $count = $this->em
//                ->createQuery($countQuery)
//                ->setParameters($parameters)
//                ->getSingleScalarResult();
//
//        $select = $this->em
//                ->createQuery($selectQuery)
//                ->setParameters($parameters)
//                ->setHint('knp_paginator.count', $count);
//        
//        return $select;
//        
//    }
//
//    public function getJobProvinces($category) {
//
//        $query = $this->em
//                ->createQuery('SELECT j
//                                FROM ClasificadosJobBundle:JobPost j, ClasificadosUserBundle:Location l
//                                WHERE j.location = l.id
//                                AND j.category = :category')
//                ->setParameter('category', $category);
//
//        return $query->getResult();
//    }
}

?>