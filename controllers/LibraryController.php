<?php

namespace controllers;

use core\Controller;
use core\Core;
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

        // Отримати всі записи бібліотеки користувача
        $rows = Library::getGame($userId);

        // Отримати всі ігри (якщо потрібні у списку)
        $games = Game::getAllGames();

        // Витягнути з $rows лише id ігор у бібліотеці для простішої перевірки
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

    // Метод для перегляду конкретної гри з можливістю додати/видалити її з бібліотеки
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
}
