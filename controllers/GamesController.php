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
        // Припустимо, ви отримуєте дані для гри (rows) та категорії (category)
        $rows = $this->model->getGames();
        $category = $this->model->getCategory();

        // Передаємо обидві змінні у вигляд:
        $this->render('games/index', [
            'rows' => $rows,
            'category' => $category,  // <- Ось сюди потрібно додати
        ]);
    }


    public function addAction($params){
        $categori_id = intval($params[0]);
        if (empty($categori_id))
            $categori_id = null;
        $categoris = Categori::getAllCat();

        if (Core::getInstance()->requestMethod === 'POST') {
            $_POST['name'] = trim($_POST['name']);
            $errors = [];

            if (empty($_POST['name'])) {
                $errors['name'] = 'Дане поле не може бути прожнім';
            }
            if ($_POST['price'] < 0) {
                $errors['price'] = 'Дане поле не може бути відємним';
            }
            if (empty($_POST['short_text'])) {
                $errors['short_text'] = 'Дане поле не може бути прожнім';
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

    public function viewAction($params){
        $id = intval($params[0]);
        $game = Game::getGameId($id);

        if ($game == null)
            return $this->error(404);

        // Отримати бібліотеку користувача, якщо авторизований
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

    public function deleteAction($params){
        $id = intval($params[0]);
        $yes = boolval($params[1] === 'yes');

        if (!User::isAdmin())
            return $this->error(403);

        if ($id > 0) {
            $game = Game::getGameId($id);
            if ($game == null)
                return $this->error(404);

            if ($yes) {
                $filePath = 'files/categori/' . $game['photo'];
                if (is_file($filePath))
                    unlink($filePath);

                Game::deleteGame($id);
                return $this->redirect("/categori/index");
            }

            return $this->render(null, [
                'game' => $game
            ]);
        } else {
            return $this->error(403);
        }
    }
}
