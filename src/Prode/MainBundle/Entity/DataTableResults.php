<?php

namespace Prode\MainBundle\Entity;

use Brown298\DataTablesBundle\Model\DataTable\AbstractQueryBuilderDataTable;
use Brown298\DataTablesBundle\Model\DataTable\QueryBuilderDataTableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class UserTable
 *
 * @package Prode\MainBundle\Entity
 */
class DataTableResults extends AbstractQueryBuilderDataTable implements QueryBuilderDataTableInterface {

    /**
     * @var array
     
    protected $columns = array(
            'u.username' => 'Usuario',
            'g.home' => '$team1',
            'f.draw' => 'Empate',
            'g.away' => '$team2',
            'f.date' => '$date',
        ); */

//    public function DataTableResults($team1, $team2, $date) {
//        $this->columns = array(
//            'u.username' => 'Usuario',
//            'g.home' => $team1,
//            'f.draw' => 'Empate',
//            'g.away' => $team2,
//            'f.date' => $date,
//        );
//        
//        parent::__construct();
//    }
    
        public function DataTableResults($columns) {
//        $this->columns = array(
//            'u.username' => 'Usuario',
//            'g.home' => $team1,
//            'f.draw' => 'Empate',
//            'g.away' => $team2,
//            'f.date' => $date,
//        );
//        
            
            parent::__construct($columns);
            
    }

    /**
     * getDataFormatter
     *
     * @return \Closure
     */
    public function getDataFormatter() {
        $renderer = $this->container->get('templating');
        return function($data) use ($renderer) {
                    $count = 0;
                    $results = array();

                    foreach ($data as $row) {
                        //var_dump($this->gameId);exit;

                        $home = '';
                        $away = '';
                        $draw = '';

                        if ($row['game']['home']['id'] == $row['result']['id']) {
                            $home = 'X';
                        } else if ($row['game']['away']['id'] == $row['result']['id']) {
                            $away = 'X';
                        } else {
                            $draw = 'X';
                        }

                        $results[$count][] = $row['user']['username'];
                        $results[$count][] = $home; //$row['game']['home']['name'];
                        $results[$count][] = $draw; //$row['game']['home']['name'];
                        $results[$count][] = $away; //$row['game']['away']['name'];
                        $results[$count][] = $row['date']->format('H:i:s m/d/Y');
                        $count += 1;
                    }

                    return $results;
                };
    }

    /**
     * getQueryBuilder
     *
     * @param Request $request
     *
     * @return null
     */
    public function getQueryBuilder(Request $request = null) {
        $userRepository = $this->em->getRepository('Prode\MainBundle\Entity\Forecast');
//        $qb = $userRepository->createQueryBuilder('forecast')
//                ->select('f', 'g', 'u')
//                ->from('Prode\MainBundle\Entity\Forecast', 'f')
//                ->join('f.game', 'g')
//                ->join('f.user', 'u')
//                ->where('f.game = :game')
//                ->setParameters(array('game' => 1));
//                $qb = $userRepository->createQueryBuilder('forecast')


        
        
        $session = new Session();
        $gameId = $session->get('game_result');
        
        //var_dump($gameId);exit;
        
        $qb = $userRepository->createQueryBuilder('f')
                ->select('f', 'u', 'r', 'g', 'h', 'a')
                ->where('f.game = :game')
                ->join('f.game', 'g')
                ->join('f.user', 'u')
                ->join('f.result', 'r')
                ->join('g.home', 'h')
                ->join('g.away', 'a')
                ->setParameter('game', $gameId);

//        $qb = $userRepository->createQueryBuilder('forecast')
//        ->select('f', 'g', 'a', 'h', 'r', 'u')
//                ->from('Prode\MainBundle\Entity\Forecast', 'f')
//                ->join('f.game', 'g')
//                ->join('g.home', 'h')
//                ->join('g.away', 'a')
//                ->join('f.result', 'r')
//                ->join('f.user', 'u')
//                ->where('f.game = :game')
//                ->setParameters(array('game' => 1));

        return $qb;
    }

}