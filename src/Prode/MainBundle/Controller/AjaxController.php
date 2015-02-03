<?php

namespace Prode\MainBundle\Controller;

use Prode\MainBundle\Entity\DataTableResults;
use Prode\MainBundle\Entity\DataTablePositions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AjaxController extends Controller {

    public function getGameForecastsAction($gameId, $firstResult = 0, $maxResults = 20) {

        $forecastService = $this->get('forecast_service');

        $count = $forecastService->countForecastByGame($gameId);

        $forecasts = $forecastService->getForecastByGame($gameId, $firstResult, $maxResults);

        $forecastJson = array();

        foreach ($forecasts AS $aForecast) {
            $newForecast['username'] = $aForecast->getUser()->getUsername();
            $newForecast['date'] = $aForecast->getDate()->format('Y-m-d H:i:s');

            $newForecast['home'] = '';
            $newForecast['away'] = '';
            $newForecast['draw'] = '';

            if ($aForecast->isHome()) {
                $newForecast['home'] = 'X';
            } else if ($aForecast->isAway()) {
                $newForecast['away'] = 'X';
            } else if ($aForecast->isDraw()) {
                $newForecast['draw'] = 'X';
            }

            $forecastJson[] = json_encode($newForecast);
        }

        $response = array('code' => 100, 'success' => true, 'count' => $count, 'forecasts' => $forecastJson);

        return new Response(json_encode($response));
    }

    public function dataTablesAction2() {

        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * Easy set variables
         */

// DB table to use
        $table = 'datatables_demo';

// Table's primary key
        $primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case object
// parameter names
        $columns = array(
            array('db' => 'first_name', 'dt' => 'first_name'),
            array('db' => 'last_name', 'dt' => 'last_name'),
            array('db' => 'position', 'dt' => 'position'),
            array('db' => 'office', 'dt' => 'office'),
            array(
                'db' => 'start_date',
                'dt' => 'start_date',
                'formatter' => function( $d, $row ) {
                    return date('jS M y', strtotime($d));
                }
            ),
            array(
                'db' => 'salary',
                'dt' => 'salary',
                'formatter' => function( $d, $row ) {
                    return '$' . number_format($d);
                }
            )
        );

// SQL server connection information
        $sql_details = array(
            'user' => '',
            'pass' => '',
            'db' => '',
            'host' => ''
        );


        /*         * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
         * If you just want to use the basic configuration for DataTables with PHP
         * server-side, there is no need to edit below this line.
         */

        require( 'ssp.class.php' );

        return new Response(json_encode(
                        SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
        ));
    }

    public function dataTablesAction($tableName = 'resultados') {
        $request = $this->getRequest();
        $em = $this->get('doctrine.orm.entity_manager');

        // process the data table
        $dataTableA = new DataTableResults();
        $dataTableA->setEm($em);
        $dataTableA->setContainer($this->container);
        if ($tableName == 'resultados' && $response = $dataTableA->ProcessRequest($request)) {
            return $response;
        }

//        $dataTableB = new UserTableB();
//        $dataTableB->setEm($em);
//        $dataTableB->setContainer($this->container);
//        if ($tableName == 'dataTableB' && $response = $dataTableB->ProcessRequest($request)) {
//            return $response;
//        }

        // display html
        //return array(
            
            //'columnsB' => $dataTableB->getColumns(),
        //);
        
        return $this->render('ProdeMainBundle:Default:dataTables.html.twig', 
                array('columnsA' => $dataTableA->getColumns()));
    }
    
    public function dataTablesPositionsAction($tableName = 'positions') {
        $request = $this->getRequest();
        $em = $this->get('doctrine.orm.entity_manager');

        // process the data table
        $dataTableA = new DataTablePositions();
        $dataTableA->setEm($em);
        $dataTableA->setContainer($this->container);
        if ($tableName == 'positions' && $response = $dataTableA->ProcessRequest($request)) {
            return $response;
        }

//        $dataTableB = new UserTableB();
//        $dataTableB->setEm($em);
//        $dataTableB->setContainer($this->container);
//        if ($tableName == 'dataTableB' && $response = $dataTableB->ProcessRequest($request)) {
//            return $response;
//        }

        // display html
        //return array(
            
            //'columnsB' => $dataTableB->getColumns(),
        //);
        
        return $this->render('ProdeMainBundle:Default:positions.html.twig', 
                array('columnsA' => $dataTableA->getColumns()));
    }

}

?>
