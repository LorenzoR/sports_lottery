<?php

namespace Prode\MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Prode\MainBundle\Entity\Forecast;

class AdminController extends Controller {

    public function loadResultsAction($round, Request $request) {
        $loggedUser = $this->get('security.context')->getToken()->getUser();

        if ($loggedUser == null) {
            
        }

        $gameService = $this->get('game_service');
        $teamService = $this->get('team_service');
        
        if ($request != null && $request->getMethod() == "POST") {
            
            $newGames = $request->get('games');

            foreach ($newGames AS $gameId => $aNewGame) {
                $game = $gameService->getById($gameId);
                
                $teamResult = $teamService->getById($aNewGame);
                $gameService->updateResult($game, $teamResult);
            }

//                $forecast = $forecastService->getForecastByUserAndGame($user, $game);
//
//                $team = $teamService->getById($aNewForecast);
//
//                if ($forecast != null) {
//
//                    if ($team === null) {
//                        
//                    } else {
//
//                        $forecastService->updateForecast($forecast, $team);
//                    }
//                } else {
//                    $newForecast = new Forecast($game, $user, $team);
//                    $forecastService->insertForecast($newForecast);
//                }
//            }
        }

        $games = $gameService->getByRound($round);

        foreach ($games AS $aGame) {

//            $resultTdClass[$aGame->getId()]['correct'] = array();
//            $resultTdClass[$aGame->getId()]['correct']['home'] = "";
//            $resultTdClass[$aGame->getId()]['correct']['away'] = "";
//            $resultTdClass[$aGame->getId()]['correct']['draw'] = "";
//
            $checked[$aGame->getId()] = array();
            $checked[$aGame->getId()]['home'] = "";
            $checked[$aGame->getId()]['away'] = "";
            $checked[$aGame->getId()]['draw'] = "";
//
//            if (count($forecasts) > 0) {
//
//                if (!is_null($aGame->getResult())) {
//                    if ($forecasts[0]->getResult()->getId() == $aGame->getResult()->getId()) {
//                        if ($forecasts[0]->getResult()->getId() == $aGame->getHome()->getId()) {
//                            $resultTdClass[$aGame->getId()]['correct']['home'] = "correctResult";
//                        } else if ($forecasts[0]->getResult()->getId() == $aGame->getAway()->getId()) {
//                            $resultTdClass[$aGame->getId()]['correct']['away'] = "correctResult";
//                        } else {
//                            $resultTdClass[$aGame->getId()]['correct']['draw'] = "correctResult";
//                        }
//                    } else {
//                        if ($forecasts[0]->getResult()->getId() == $aGame->getHome()->getId()) {
//                            $resultTdClass[$aGame->getId()]['correct']['home'] = "incorrectResult";
//                        } else if ($forecasts[0]->getResult()->getId() == $aGame->getAway()->getId()) {
//                            $resultTdClass[$aGame->getId()]['correct']['away'] = "incorrectResult";
//                        } else {
//                            $resultTdClass[$aGame->getId()]['correct']['draw'] = "incorrectResult";
//                        }
//                    }
//                }
//
                $resultTdClass[$aGame->getId()]['checked']['home'] = "";
                $resultTdClass[$aGame->getId()]['checked']['away'] = "";
                $resultTdClass[$aGame->getId()]['checked']['draw'] = "";
                
                if (!is_null($aGame->getResult())) {
                if ($aGame->getResult()->getId() == $aGame->getHome()->getId()) {
                    $checked[$aGame->getId()]['home'] = "checked=\"checked\"";
                    $resultTdClass[$aGame->getId()]['checked']['home'] = "selected_result";
                } else if ($aGame->getResult()->getId() == $aGame->getAway()->getId()) {
                    $checked[$aGame->getId()]['away'] = "checked=\"checked\"";
                    $resultTdClass[$aGame->getId()]['checked']['away'] = "selected_result";
                } else {
                    $checked[$aGame->getId()]['draw'] = "checked=\"checked\"";
                    $resultTdClass[$aGame->getId()]['checked']['draw'] = "selected_result";
                }
                }
//            }
        }
        
        return $this->render('ProdeMainBundle:Admin:loadResults.html.twig', array('round' => $round,
                    'games' => $games,
                    'checked' => $checked,
                    'resultTdClass' => $resultTdClass));
    }
    
    public function editForecastsAction($userId, $round, Request $request) {
        $loggedUser = $this->get('security.context')->getToken()->getUser();

        if ($loggedUser == null) {
            
        }

        $forecastService = $this->get('forecast_service');
        $teamService = $this->get('team_service');
        $gameService = $this->get('game_service');
        $userService = $this->get('user_service');

        if ( $userId == 0 ) {
            $firstUser = $userService->getFirstUser();
            $user = $firstUser[0];
        }
        else {
            $user = $userService->getById($userId);
        }
        
        
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

        $games = $forecastService->getForecastByUserAndRound($user, $round);

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

        $users = $userService->getPlayingUsers();
        
        return $this->render('ProdeMainBundle:Admin:editForecasts.html.twig', array('round' => $round,
                    'games' => $games,
                    'checked' => $checked,
                    'resultTdClass' => $resultTdClass,
                    'users' => $users,
                    'editedUser' => $user));
    }

}