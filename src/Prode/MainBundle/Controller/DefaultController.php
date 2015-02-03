<?php

namespace Prode\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Prode\MainBundle\Entity\Forecast;
use Prode\MainBundle\Entity\DataTableResults;
use Prode\MainBundle\Entity\DataTablePositions;
use Prode\MainBundle\Entity\Task;
use Prode\MainBundle\Entity\ForecastForm;
use Prode\MainBundle\Entity\Tag;
use Prode\MainBundle\Form\Type\TaskType;
use Prode\MainBundle\Form\Type\NewForecastFormType;
use Prode\MainBundle\Form\Type\NewForecastCollectionFormType;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('ProdeMainBundle:Default:index.html.twig');
    }

    public function gameAction($round, Request $request) {
        $user = $this->get('security.context')->getToken()->getUser();

        if ($user == null) {
            return $this->render('ProdeMainBundle:Default:index.html.twig');
        }

        $forecastService = $this->get('forecast_service');
        $teamService = $this->get('team_service');
        $gameService = $this->get('game_service');

        if ($request != null && $request->getMethod() == "POST") {

            $newForecasts = $request->get('games');

            foreach ($newForecasts AS $gameId => $aNewForecast) {

                $game = $gameService->getById($gameId);

                if (!$game->hasStarted()) {

                    $forecast = $forecastService->getForecastByUserAndGame($user, $game);

                    $team = $teamService->getById($aNewForecast);

                    if ($forecast != null) {


                        if ($team === null) {
                            
                        } else {

                            $forecastService->updateForecast($forecast, $team);
                        }
                    } else {
                        $newForecast = new Forecast($game, $user, $team);
                        $forecastService->insertForecast($newForecast);
                    }
                }
            }
        }

        $games = $forecastService->getForecastByUserAndRound($user, $round);

        $resultTdClass = array();
        $checked = array();
        $rowStyle = array();

        foreach ($games AS $aGame) {

            $forecasts = $aGame->getForecasts();

            $resultTdClass[$aGame->getId()]['correct'] = array();
            $resultTdClass[$aGame->getId()]['correct']['home'] = "";
            $resultTdClass[$aGame->getId()]['correct']['away'] = "";
            $resultTdClass[$aGame->getId()]['correct']['draw'] = "";

            $checked[$aGame->getId()] = array();
            $checked[$aGame->getId()]['home'] = "";
            $checked[$aGame->getId()]['away'] = "";
            $checked[$aGame->getId()]['draw'] = "";

            $rowStyle[$aGame->getId()] = array();
            $rowStyle[$aGame->getId()]['home'] = "";
            $rowStyle[$aGame->getId()]['away'] = "";
            $rowStyle[$aGame->getId()]['draw'] = "";

            if (count($forecasts) > 0) {

                if (!is_null($aGame->getResult())) {
                    if ($forecasts[0]->getResult()->getId() == $aGame->getResult()->getId()) {
                        if ($forecasts[0]->getResult()->getId() == $aGame->getHome()->getId()) {
                            $resultTdClass[$aGame->getId()]['correct']['home'] = "correctResult";
                        } else if ($forecasts[0]->getResult()->getId() == $aGame->getAway()->getId()) {
                            $resultTdClass[$aGame->getId()]['correct']['away'] = "correctResult";
                        } else {
                            $resultTdClass[$aGame->getId()]['correct']['draw'] = "correctResult";
                        }
                    } else {
                        if ($forecasts[0]->getResult()->getId() == $aGame->getHome()->getId()) {
                            $resultTdClass[$aGame->getId()]['correct']['home'] = "incorrectResult";
                        } else if ($forecasts[0]->getResult()->getId() == $aGame->getAway()->getId()) {
                            $resultTdClass[$aGame->getId()]['correct']['away'] = "incorrectResult";
                        } else {
                            $resultTdClass[$aGame->getId()]['correct']['draw'] = "incorrectResult";
                        }
                    }
                }

                if ($forecasts[0]->getResult()->getId() == $aGame->getHome()->getId()) {
                    $checked[$aGame->getId()]['home'] = "checked=\"checked\"";
                    $rowStyle[$aGame->getId()]['home'] = "font-weight: bold;";
                } else if ($forecasts[0]->getResult()->getId() == $aGame->getAway()->getId()) {
                    $checked[$aGame->getId()]['away'] = "checked=\"checked\"";
                    $rowStyle[$aGame->getId()]['away'] = "font-weight: bold;";
                } else {
                    $checked[$aGame->getId()]['draw'] = "checked=\"checked\"";
                    $rowStyle[$aGame->getId()]['draw'] = "font-weight: bold;";
                }
            }
        }

        $forecastsLeftArray = $forecastService->getForecastsLeft($user, $round);

        $forecastsLeft = $forecastsLeftArray['gamesQty'] - $forecastsLeftArray['resultsLeft'];

        if ( $round == 'A' || $round == 'B' || $round == 'C' || $round == 'D' || $round == 'E' ||
                $round == 'F' || $round == 'G' || $round == 'H') {
            $roundTxt = 'Grupo '.$round;
        }
        else {
            if ( $round == 1) {
                $roundTxt = 'Final';
            }
            else if ( $round == 3) {
                $roundTxt = 'Tercer Puesto';
            }
            else if ( $round == 2) {
                $roundTxt = 'Semifinal';
            }
            else if ( $round == 4) {
                $roundTxt = 'Cuartos de Final';
            }
            else if ( $round == 8) {
                $roundTxt = 'Octavos de Final';
            }
        }
        
        return $this->render('ProdeMainBundle:Default:games.html.twig', array('round' => $round,
                    'roundTxt' => $roundTxt,
                    'games' => $games,
                    'checked' => $checked,
                    'forecastsLeft' => $forecastsLeft,
                    'resultTdClass' => $resultTdClass,
                    'rowStyle' => $rowStyle));
    }
    
    public function resultAction($gameId, $tableName = 'resultados') {
        
        $request = $this->getRequest();
        $em = $this->get('doctrine.orm.entity_manager');

        $gameService = $this->get('game_service');
        
        $game = $gameService->getById($gameId);
        
        $session = new Session();        
        $session->set('game_result', $game->getId());
        
        $columns = array(
            'u.username' => 'Usuario',
            'g.home' => $game->getHome()->getName(),
            'f.draw' => 'Empate',
            'g.away' => $game->getAway()->getName(),
            'f.date' => 'Fecha',
        );
        
        // process the data table
        $dataTableA = new DataTableResults($columns);
        $dataTableA->setEm($em);
        $dataTableA->setContainer($this->container);
        if ($tableName == 'resultados' && $response = $dataTableA->ProcessRequest($request)) {
            return $response;
        }
        
        $gameTitle = $game->getHome()->getName().' vs '.$game->getAway()->getName();
        
        $round = $game->getRound();
        
        if ( $round == 'A' || $round == 'B' || $round == 'C' || $round == 'D' || $round == 'E' ||
                $round == 'F' || $round == 'G' || $round == 'H') {
            $roundTxt = 'Grupo '.$round;
        }
        else {
            if ( $round == 1) {
                $roundTxt = 'Final';
            }
            else if ( $round == 3) {
                $roundTxt = 'Tercer Puesto';
            }
            else if ( $round == 2) {
                $roundTxt = 'Semifinal';
            }
            else if ( $round == 4) {
                $roundTxt = 'Cuartos de Final';
            }
            else if ( $round == 8) {
                $roundTxt = 'Octavos de Final';
            }
        }
        
        $hasFinished = $game->getResult() != null;
        
        if ( $hasFinished ) {
            if ( $game->getResult()->getId() == $game->getHome()->getId() ) {
                $resultIndex = 1;
            }
            else if ( $game->getResult()->getId() == $game->getAway()->getId() ) {
                $resultIndex = 3;
            }
            else {
                $resultIndex = 2;
            }
        } else {
            $resultIndex = null;
        }
        
        return $this->render('ProdeMainBundle:Default:result.html.twig', 
                array('columnsA' => $dataTableA->getColumns(), 
                    'round' => $round,
                    'roundTxt' => $roundTxt,
                    'gameTitle' => $gameTitle,
                    'hasFinished' => $hasFinished,
                    'resultIndex' => $resultIndex));
    }
        
//        $user = $this->get('security.context')->getToken()->getUser();
//
//        if ($user == null) {
//            return $this->render('ProdeMainBundle:Default:index.html.twig');
//        }
//
//        $teamService = $this->get('team_service');
//        $gameService = $this->get('game_service');
//
//        $games = $gameService->getByRound($round);
//
//        return $this->render('ProdeMainBundle:Default:result.html.twig', array('round' => $round,
//                    'games' => $games ));
//    }

    public function resultsAction($round) {
//        if ($loginService->activeSession() && $loginService->getActiveUser()->level == 2 && $loginService->getActiveUser()->userId == 1) {
//            $games = $gameService->getAllForecast(Constants::$tournament, $round);
//        } else {
//            $games = $gameService->getForecast(Constants::$tournament, $round);
//        }


        $gameService = $this->get('game_service');

        $games = $gameService->getByRound($round);

        $resp = array();

        foreach ($games AS $aGame) {
            if ($aGame->hasStarted()) {
                $resp[] = $aGame;
            }
        }

        return $this->render('ProdeMainBundle:Default:results.html.twig', array('games' => $resp));

        $forecastService = $this->get('forecast_service');
        //$userService = $this->get('user_service');

        $forecasts = $forecastService->getForecastsByRound($round);
        //$users = $userService->getAllUsers();

        $resp = array();

        foreach ($forecasts AS $aForecast) {
            if ($aForecast->getGame()->hasStarted()) {
                $gameId = $aForecast->getGame()->getId();

                if (!isset($resp[$gameId])) {
                    $resp[$gameId]['home'] = $aForecast->getGame()->getHome()->getName();
                    $resp[$gameId]['away'] = $aForecast->getGame()->getAway()->getName();
                    $resp[$gameId]['date'] = $aForecast->getGame()->getDate();
                    $resp[$gameId]['users'] = array();
                }

                $forecast['userId'] = $aForecast->getUser()->getId();
                $forecast['username'] = $aForecast->getUser()->getUsername();
                $forecast['home'] = '';
                $forecast['away'] = '';
                $forecast['draw'] = '';
                $forecast['date'] = $aForecast->getDate();

                if (is_null($aForecast->getGame()->getResult()) ||
                        is_null($aForecast->getResult()) ||
                        $aForecast->isCorrect()) {
                    $forecast['class'] = "correctResult";
                } else {
                    $forecast['class'] = "incorrectResult";
                }

                if ($aForecast->isHome()) {
                    $forecast['home'] = 'X';
                    $forecast['draw'] = '';
                    $forecast['away'] = '';
                } else if ($aForecast->isAway()) {
                    $forecast['away'] = 'X';
                    $forecast['home'] = '';
                    $forecast['draw'] = '';
                } else if ($aForecast->isDraw()) {
                    $forecast['draw'] = 'X';
                    $forecast['home'] = '';
                    $forecast['away'] = '';
                }

                $resp[$gameId]['users'][] = $forecast;
            }
        }

        return $this->render('ProdeMainBundle:Default:results.html.twig', array('forecasts' => $resp));

//        //  foreach ($users AS $aUser) {
//        foreach ($games AS $aGame) {
//            foreach ($aGame->getForecasts() AS $aForecast) {
//
////                    $forecasts[$aGame->getId()][$aUser->getId()]['home'] = '';
////                    $forecasts[$aGame->getId()][$aUser->getId()]['away'] = '';
////                    $forecasts[$aGame->getId()][$aUser->getId()]['draw'] = '';
////                    $forecasts[$aGame->getId()][$aUser->getId()]['date'] = '';
//                //if ($aForecast->getUser()->getId() == $aUser->getId()) {
//
//                $forecasts[$aGame->getId()][$aUser->getId()]['date'] = $aForecast->getDate();
//
//                if ($aGame->hasStarted()) {
//                    if (is_null($aForecast->getResult()) ||
//                            $aGame->getResult()->getId() != $aForecast->getResult()->getId()) {
//                        $forecasts[$aGame->getId()][$aUser->getId()]['class'] = "correctResult";
//                    } else {
//                        $forecasts[$aGame->getId()][$aUser->getId()]['class'] = "incorrectResult";
//                    }
//                } else {
//                    $forecasts[$aGame->getId()][$aUser->getId()]['class'] = "";
//                }
//
//                if ($aForecast->getResult()->getId() == $aGame->getHome()->getId()) {
//                    $forecasts[$aGame->getId()][$aUser->getId()]['home'] = 'X';
//                    $forecasts[$aGame->getId()][$aUser->getId()]['draw'] = '';
//                    $forecasts[$aGame->getId()][$aUser->getId()]['away'] = '';
//                } else if ($aForecast->getResult()->getId() == $aGame->getAway()->getId()) {
//                    $forecasts[$aGame->getId()][$aUser->getId()]['away'] = 'X';
//                    $forecasts[$aGame->getId()][$aUser->getId()]['home'] = '';
//                    $forecasts[$aGame->getId()][$aUser->getId()]['draw'] = '';
//                } else if ($aForecast->getResult()->getId() === 0) {
//                    $forecasts[$aGame->getId()][$aUser->getId()]['draw'] = 'X';
//                    $forecasts[$aGame->getId()][$aUser->getId()]['home'] = '';
//                    $forecasts[$aGame->getId()][$aUser->getId()]['away'] = '';
//                }
//                // }
//            }
//
//            if (!isset($forecasts[$aGame->getId()][$aUser->getId()])) {
//                $forecasts[$aGame->getId()][$aUser->getId()]['class'] = '';
//                $forecasts[$aGame->getId()][$aUser->getId()]['home'] = '';
//                $forecasts[$aGame->getId()][$aUser->getId()]['away'] = '';
//                $forecasts[$aGame->getId()][$aUser->getId()]['draw'] = '';
//                $forecasts[$aGame->getId()][$aUser->getId()]['date'] = '';
//            }
//        }
//        // }
//
//        return $this->render('ProdeMainBundle:Default:results.html.twig', array('users' => $users, 'games' => $games, 'forecasts' => $forecasts));
    }

    public function positionsAction($tableName = 'positions') {
        $request = $this->getRequest();
        $em = $this->get('doctrine.orm.entity_manager');
        
        $columns = array(
            'position'   => '#',
            'u.username' => 'Usuario',
            'p.firstRound' => '1er Ronda',
            'p.roundOf16' => '8vos',
            'p.quarterfinals' => '4tos',
            'p.semifinals' => 'Semi',
            'p.thirdPlace' => '3er P',
            'p.finals' => 'Final',
            'p.total' => 'Pts',
        );
        
        // process the data table
        $dataTableA = new DataTablePositions($columns);
        $dataTableA->setEm($em);
        $dataTableA->setContainer($this->container);
        if ($tableName == 'positions' && $response = $dataTableA->ProcessRequest($request)) {
            return $response;
        }
        
        return $this->render('ProdeMainBundle:Default:positions.html.twig', 
                array('columnsA' => $dataTableA->getColumns()));
    }
    
//    public function positionsAction() {
//
//        $forecastService = $this->get('forecast_service');
//        $points = $forecastService->getPoints();
//
//        $positions = array();
//
//        foreach ($points AS $aPoint) {
//
//            if (!isset($positions[$aPoint->getUser()->getId()])) {
//                $positions[$aPoint->getUser()->getId()] = array('username' => $aPoint->getUser()->getUsername(),
//                    'firstStage' => 0,
//                    'secondStage' => 0,
//                    'round8' => 0,
//                    'round4' => 0,
//                    'round2' => 0,
//                    'round3' => 0,
//                    'round1' => 0,
//                    'total' => 0);
//            }
//
//            if ($aPoint->getGame()->getStage() == '1') {
//                $positions[$aPoint->getUser()->getId()]['firstStage'] += 1;
//                $positions[$aPoint->getUser()->getId()]['total'] += 1;
//            }
//
//
////            print "\<pre\>POINTS>";
////            \Doctrine\Common\Util\Debug::dump($aPoint);
////            print "\</pre\><br><br>";
//        }
//
//        uasort($positions, array($this, '_cmpPositions'));
//
//        return $this->render('ProdeMainBundle:Default:positions.html.twig', array('users' => $positions));
//    }

    public function rulesAction() {

        set_time_limit(600000);
//        
//        $userService = $this->get('user_service');
//        $gameService = $this->get('game_service');
//        $forecastService = $this->get('forecast_service');
//        $teamService = $this->get('team_service');
//        
//        $users = $userService->getAllUsers();
//        $games = $gameService->getAllGames();
//        
//        $empate = $teamService->getById(0);
//        
//        foreach ( $users AS $aUser ) {
//            foreach ( $games AS $aGame ) {
//                $results = array();
//                $results[0] = $empate;
//                $results[1] = $aGame->getHome();
//                $results[2] = $aGame->getAway();
//                
//                $forecast = new Forecast($aGame, $aUser, $results[rand(0,2)]);
//                $forecastService->insertForecast($forecast);
//                
//            }
//        }

        return $this->render('ProdeMainBundle:Default:rules.html.twig', array());
    }

    public function rssAction() {
        // fetch the FeedReader
        $reader = $this->container->get('debril.reader');

        // this date is used to fetch only the latest items
        $date = new \DateTime("now");

        // the feed you want to read
        $url = 'http://es.fifa.com/worldcup/news/rss.xml';

        // now fetch its (fresh) content
        $feed = $reader->getFeedContent($url, $date);

        // the $content object contains as many Item instances as you have fresh articles in the feed
        $items = $feed->getItems();

        print "\<pre\>";
        \Doctrine\Common\Util\Debug::dump($items);
        print "\</pre\>";
    }

    private function _cmpPositions($p1, $p2) {
        if ($p1['total'] == $p2['total']) {
            return 0;
//            if ($p1['wonRounds'] == $p2['wonRounds']) {
//                if ($p1['bestRound'] == $p2['bestRound']) {
//                    if ($p1['visitante'] == $p2['visitante']) {
//                        if ($p1['empate'] == $p2['empate']) {
//                            if ($p1['tiempos'] == $p2['tiempos'])
//                                return strcasecmp($p1['username'], $p2['username']);
//                            else if ($p1['tiempos'] > $p2['tiempos'])
//                                return -1;
//                            else if ($p1['tiempos'] < $p2['tiempos'])
//                                return 1;
//                        }
//                        else if ($p1['empate'] > $p2['empate'])
//                            return -1;
//                        else if ($p1['empate'] < $p2['empate'])
//                            return 1;
//                    }
//                    else if ($p1['visitante'] > $p2['visitante'])
//                        return -1;
//                    else if ($p1['visitante'] < $p2['visitante'])
//                        return 1;
//                }
//                else if ($p1['bestRound'] > $p2['bestRound'])
//                    return -1;
//                else if ($p1['bestRound'] < $p2['bestRound'])
//                    return 1;
//            }
//            else if ($p1['wonRounds'] > $p2['wonRounds'])
//                return -1;
//            else if ($p1['wonRounds'] < $p2['wonRounds'])
//                return 1;
        } else if ($p1['total'] > $p2['total'])
            return -1;
        else if ($p1['total'] < $p2['total'])
            return 1;
    }

}
