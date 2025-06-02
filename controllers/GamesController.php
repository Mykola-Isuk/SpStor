<?php

namespace controllers;

use core\Controller;
use core\Core;
use models\Categori;
use models\User;
use models\Game;
use models\Library;

class GamesController extends Controller
{
    public function indexAction()
    {
        $rows = Game::getAllGames();
        $category = Categori::getAllCat();

        return $this->render(null, [
            'rows' => $rows,
            'category' => $category
        ]);
    }

    public function addAction($params)
    {
        $categori_id = intval($params[0] ?? 0);
        if (empty($categori_id)) {
            $categori_id = null;
        }

        $categoris = Categori::getAllCat();

        if (Core::getInstance()->requestMethod === 'POST') {
            $_POST['name'] = trim($_POST['name']);
            $errors = [];

            if (empty($_POST['name'])) {
                $errors['name'] = 'Дане поле не може бути порожнім';
            }
            if ($_POST['price'] < 0) {
                $errors['price'] = 'Ціна не може бути від’ємною';
            }
            if (empty($_POST['short_text'])) {
                $errors['short_text'] = 'Опис не може бути порожнім';
            }

            if (empty($errors)) {
                Game::addGame(
                    $_POST['name'],
                    $_POST['categorie_id'],
                    $_POST['price'],
                    $_POST['short_text'],
                    $_POST['visible'],
                    $_FILES['file']['tmp_name']
                );
                return $this->redirect("/categori/index");
            } else {
                $model = $_POST;
                return $this->render(null, [
                    'errors' => $errors,
                    'model' => $model,
                    'categoris' => $categoris,
                    'categori_id' => $categori_id
                ]);
            }
        }

        return $this->render(null, [
            'categoris' => $categoris,
            'categori_id' => $categori_id
        ]);
    }

    public function viewAction($params)
    {
        $id = intval($params[0]);
        $game = Game::getGameId($id);

        if ($game == null)
            return $this->error(404);

        $libraryGameIds = [];
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            $rows = Library::getGame($userId);
            $libraryGameIds = array_column($rows, 'game_id');
        }

        return $this->render(null, [
            'game' => $game,
            'libraryGameIds' => $libraryGameIds
        ]);
    }

    public function deleteAction($params)
    {
        $id = intval($params[0]);
        $yes = isset($params[1]) && $params[1] === 'yes';

        if (!User::isAdmin())
            return $this->error(403);

        $game = Game::getGameId($id);
        if ($game == null)
            return $this->error(404);

        if ($yes) {
            $filePath = 'files/game/' . $game['photo'];
            if (is_file($filePath))
                unlink($filePath);

            Game::deleteGame($id);
            return $this->redirect("/categori/index");
        }

        return $this->render(null, [
            'game' => $game
        ]);
    }

    public function removeFromLibraryAction($params)
    {
        $gameId = intval($params[0] ?? 0);

        if (!User::isLogged()) {
            return $this->error(403);
        }

        $user = User::getCurrent();
        Library::removeFromLibrary($user['id'], $gameId);

        return $this->redirect('/library/index');
    }
}
