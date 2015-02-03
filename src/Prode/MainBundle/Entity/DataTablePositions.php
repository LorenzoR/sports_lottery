<?php

namespace Prode\MainBundle\Entity;

use Brown298\DataTablesBundle\Model\DataTable\AbstractQueryBuilderDataTable;
use Brown298\DataTablesBundle\Model\DataTable\QueryBuilderDataTableInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserTable
 *
 * @package Prode\MainBundle\Entity
 */
class DataTablePositions extends AbstractQueryBuilderDataTable implements QueryBuilderDataTableInterface {

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

                        $results[$count][] = $count + 1;
                        $results[$count][] = $row['user']['username'];
                        $results[$count][] = $row['firstRound'];
                        $results[$count][] = $row['roundOf16'];
                        $results[$count][] = $row['quarterfinals'];
                        $results[$count][] = $row['semifinals'];
                        $results[$count][] = $row['thirdPlace'];
                        $results[$count][] = $row['finals'];
                        $results[$count][] = $row['total'];
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
        $userRepository = $this->em->getRepository('Prode\MainBundle\Entity\Position');
        
        $qb = $userRepository->createQueryBuilder('p')
                ->select('p', 'u')
                ->join('p.user', 'u')
                ->addOrderBy('p.total', 'DESC')
                ->addOrderBy('u.username');

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