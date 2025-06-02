<?php

namespace controllers;

use core\Controller;
use models\Game;
use models\Library;

class LibraryController extends Controller
{
    public function indexAction()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/user/login');
            return;
        }

        $userId = $_SESSION['user']['id'];
        $rows = Library::getGame($userId);
        $games = Game::getAllGames();
        $libraryGameIds = array_column($rows, 'game_id');

        return $this->render(null, [
            'games' => $games,
            'rows' => $rows,
            'libraryGameIds' => $libraryGameIds,
        ]);
    }

    public function addAction()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/user/login');
            return;
        }

        if (isset($_GET['game_id'])) {
            $userId = $_SESSION['user']['id'];
            $gameId = (int)$_GET['game_id'];

            if (!Library::isGameInLibrary($userId, $gameId)) {
                Library::addGame($userId, $gameId);
            }
        }

        $this->redirect('/library/index');
    }

    public function deleteAction()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/user/login');
            return;
        }

        if (isset($_GET['game_id'])) {
            $userId = $_SESSION['user']['id'];
            $gameId = (int)$_GET['game_id'];

            if (Library::isGameInLibrary($userId, $gameId)) {
                Library::deleteGame($userId, $gameId);
            }
        }

        $this->redirect('/library/index');
    }

    public function viewAction()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/user/login');
            return;
        }

        if (!isset($_GET['id'])) {
            $this->redirect('/library/index');
            return;
        }

        $gameId = (int)$_GET['id'];
        $game = Game::getGameId($gameId);

        if (!$game) {
            $this->redirect('/library/index');
            return;
        }

        $userId = $_SESSION['user']['id'];
        $rows = Library::getGame($userId);
        $libraryGameIds = array_column($rows, 'game_id');

        return $this->render(null, [
            'game' => $game,
            'libraryGameIds' => $libraryGameIds,
        ]);
    }


    public function deleteMultipleAction()
    {
        if (!isset($_SESSION['user'])) {
            $this->redirect('/user/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_games'])) {
            $userId = $_SESSION['user']['id'];

            foreach ($_POST['selected_games'] as $gameId) {
                $gameId = (int)$gameId;

                if (Library::isGameInLibrary($userId, $gameId)) {
                    Library::deleteGame($userId, $gameId);
                }
            }
        }

        $this->redirect('/library/index');
    }
}
