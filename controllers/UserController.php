<?php

namespace controllers;

use core\Controller;
use core\Core;
use models\User;

class UserController extends Controller
{
    public function registerAction()
    {
        if (User::IsAutUser()) {
            $this->redirect('/');
        }

        $model = []; // ← добавляем чтобы всегда передавать

        if (Core::getInstance()->requestMethod === 'POST') {
            $errors = [];

            if (!filter_var($_POST['login'], FILTER_VALIDATE_EMAIL)) {
                $errors['login'] = 'Помилка при введені логіна!';
            }

            if (User::isLoginExists($_POST['login'])) {
                $errors['login'] = 'Користувач з таким логіном вже є!';
            }

            if ($_POST['password'] != $_POST['password2']) {
                $errors['password'] = 'Паролі не співпадають!';
            }

            if (count($errors) > 0) {
                $model = $_POST;
                return $this->render(null, [
                    'errors' => $errors,
                    'model' => $model
                ]);
            } else {
                User::addUser($_POST['login'], $_POST['password'], $_POST['nick_name']);
                return $this->redirect('login');
            }
        }

        // ← при GET-запросе тоже передаем пустую модель
        return $this->render(null, [
            'model' => $model
        ]);
    }

    public function loginAction()
    {
        if (User::IsAutUser()) {
            $this->redirect('/');
        }

        $error = null;

        if (Core::getInstance()->requestMethod === 'POST') {
            $user = User::getUser($_POST['login'], $_POST['password']);

            if (empty($user)) {
                $error = 'Неправильний логін або пароль!';
            } else {
                User::autUser($user);
                $this->redirect('/');
            }
        }

        return $this->render(null, [
            'error' => $error
        ]);
    }

    public function logoutAction()
    {
        User::logoutUser();
        $this->redirect('/user/login');
    }
}
