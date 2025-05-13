<?php

namespace core;

use controllers\MainController;

class Core
{
    private static $instance = null;
    public $app;
    public $db;
    public $pageParams;
    public $requestMethod;

    private function __construct()
    {
        $this->app = [];
        $this->pageParams = [];
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function Initialize()
    {
        session_start();
        $this->db = new DB(DATABASE_HOST, DATABASE_LOGIN, DATABASE_PASSWORD, DATABASE_BASENAME);
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    public function Run()
    {
        // Роутінг
        $routeParts = isset($_GET['route']) ? explode('/', $_GET['route']) : [];

        $moduleNameRaw = array_shift($routeParts);
        $moduleName = $moduleNameRaw !== null ? strtolower($moduleNameRaw) : 'main';

        $actionNameRaw = array_shift($routeParts);
        $actionName = $actionNameRaw !== null ? strtolower($actionNameRaw) : 'index';

        $this->app['moduleName'] = $moduleName;
        $this->app['actionName'] = $actionName;

        $controllerName = '\\controllers\\' . ucfirst($moduleName) . 'Controller';
        $controllerActionName = $actionName . 'Action';

        $statusCode = 200;

        if (class_exists($controllerName)) {
            $controller = new $controllerName();

            if (method_exists($controller, $controllerActionName)) {
                $actionResult = $controller->$controllerActionName($routeParts);
                if ($actionResult instanceof Error) {
                    $statusCode = $actionResult->code;
                }
                $this->pageParams['content'] = $actionResult;
            } else {
                $statusCode = 404;
            }
        } else {
            $statusCode = 404;
        }

        $statusCodeType = intval($statusCode / 100);
        if ($statusCodeType === 4) {
            $mainController = new MainController();
            $this->pageParams['content'] = $mainController->errorAction($statusCode);
        }
    }

    public function Done()
    {
        $pathToLayout = 'thems/MainThem/layout.php';
        $tpl = new Template($pathToLayout);
        $tpl->setParams($this->pageParams);
        $html = $tpl->getHTML();
        echo $html;
    }
}
