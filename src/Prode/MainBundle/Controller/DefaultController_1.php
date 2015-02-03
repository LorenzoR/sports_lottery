<?php

namespace Prode\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Prode\MainBundle\Entity\Forecast;
use Prode\MainBundle\Entity\Task;
use Prode\MainBundle\Entity\ForecastForm;
use Prode\MainBundle\Entity\Tag;
use Prode\MainBundle\Form\Type\TaskType;
use Prode\MainBundle\Form\Type\NewForecastFormType;
use Prode\MainBundle\Form\Type\NewForecastCollectionFormType;

class DefaultController extends Controller {

//    public function indexAction($name)
//    {
//        return $this->render('ProdeMainBundle:Default:index.html.twig', array('name' => $name));
//    }

    public function indexAction() {
        return $this->render('ProdeMainBundle:Default:index.html.twig');
    }

    public function gameAction($round, Request $request) {
        $user = $this->get('security.context')->getToken()->getUser();

        if ($user == null) {
            
        }

        $forecastService = $this->get('forecast_service');
        $teamService = $this->get('team_service');
        $gameService = $this->get('game_service');

        if ($request != null && $request->getMethod() == "POST") {

            $newForecasts = $request->get('games');

            foreach ($newForecasts AS $gameId => $aNewForecast) {

                $game = $gameService->getById($gameId);

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



//        $qb = $em->createQueryBuilder();
//        $qb->select('g', 'f')
//                ->from('Prode\MainBundle\Entity\Game', 'g')
//                ->leftJoin('g.forecasts', 'f', 'WITH', 'f.user = :user_id')
//                ->setParameter('user_id', $user->getId())
//                ->where('g.round = :round')
//                ->setParameter('round', $round)
//                ->orderBy('g.id', 'ASC');

        $games = $forecastService->getForecastByUserAndRound($user, $round);

//        $forecasts = array();
//
//        $forecastForm = new ForecastForm();
//
        $resultTdClass = array();

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
                } else if ($forecasts[0]->getResult()->getId() == $aGame->getAway()->getId()) {
                    $checked[$aGame->getId()]['away'] = "checked=\"checked\"";
                } else {
                    $checked[$aGame->getId()]['draw'] = "checked=\"checked\"";
                }
            }
        }
//
//
//
//            //$forecast = $aGame->getForecasts();
//            //$forecasts[] = $forecast[0];
//            $forecast = new Forecast($aGame, $user);
//            $forecasts[] = $forecast;
//            $forecastForm->addForecast($forecast);
//        }
//        print "\<pre\>";
//        \Doctrine\Common\Util\Debug::dump($forecasts);
//        print "\</pre\>";
//        exit;
//        $games = $this->getDoctrine()
//                ->getRepository('ProdeMainBundle:Game')
//                ->findBy(array('round' => $round)
//        );
        //$forecast = new Forecast();
//
//        $forecastCollection = array('forecasts' => $forecasts);
//        $user->setForecasts($forecasts);
        //$form = $this->createForm(new NewForecastFormType(), $forecastCollection, array('home' => 'Brasil', 'home_id' => '1', 'away' => 'Argentina', 'away_id' => '2',));
//        $form = $this->createForm(new TaskType(), $user, array('home' => $games[1]->getHome(), 'away' => $games[1]->getAway()));
//
//        $task = new Task();
//
//        // dummy code - this is here just so that the Task has some tags
//        // otherwise, this isn't an interesting example
//        $tag1 = new Tag();
//        $tag1->name = 'tag1';
//        $task->getTags()->add($tag1);
//        $tag2 = new Tag();
//        $tag2->name = 'tag2';
//        $task->getTags()->add($tag2);
        // end dummy code
        //$form = $this->createForm(new TaskType(), $task);
//        $form = $this->createFormBuilder($forecastForm)
//            ->add('forecasts', 'collection')
//            //->add('forecasts', 'text')
//            ->add('save', 'submit')
//            ->getForm();
        //$form->handleRequest($request);

        $forecastsLeftArray = $forecastService->getForecastsLeft($user, $round);

        $forecastsLeft = $forecastsLeftArray['gamesQty'] - $forecastsLeftArray['resultsLeft'];

        return $this->render('ProdeMainBundle:Default:games.html.twig', array('round' => $round,
                    'games' => $games,
                    'checked' => $checked,
                    'forecastsLeft' => $forecastsLeft,
                    'resultTdClass' => $resultTdClass));
    }

    public function resultAction($round) {
//        if ($loginService->activeSession() && $loginService->getActiveUser()->level == 2 && $loginService->getActiveUser()->userId == 1) {
//            $games = $gameService->getAllForecast(Constants::$tournament, $round);
//        } else {
//            $games = $gameService->getForecast(Constants::$tournament, $round);
//        }

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

    public function positionsAction() {

        $forecastService = $this->get('forecast_service');
        $points = $forecastService->getPoints();

        $positions = array();

        foreach ($points AS $aPoint) {

            if (!isset($positions[$aPoint->getUser()->getId()])) {
                $positions[$aPoint->getUser()->getId()] = array('username' => $aPoint->getUser()->getUsername(),
                    'firstStage' => 0,
                    'secondStage' => 0,
                    'round8' => 0,
                    'round4' => 0,
                    'round2' => 0,
                    'round3' => 0,
                    'round1' => 0,
                    'total' => 0);
            }

            if ($aPoint->getGame()->getStage() == '1') {
                $positions[$aPoint->getUser()->getId()]['firstStage'] += 1;
                $positions[$aPoint->getUser()->getId()]['total'] += 1;
            }


//            print "\<pre\>POINTS>";
//            \Doctrine\Common\Util\Debug::dump($aPoint);
//            print "\</pre\><br><br>";
        }

        uasort($positions, array($this, '_cmpPositions'));

        return $this->render('ProdeMainBundle:Default:positions.html.twig', array('users' => $positions));
    }

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
