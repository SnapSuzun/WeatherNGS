<?php

namespace core;

use core\View;
use \Core;
use Exception;

class Controller
{
    /**
     * @var string
     */
    public $id = null;

    /**
     * @var null | string
     */
    public $layout = null;

    /**
     * @var array
     */
    protected $_actionMap = [];

    /**
     * @var null | string
     */
    protected $_viewPath = null;

    /**
     * @var null | View
     */
    protected $_view = null;

    /**
     * Controller constructor.
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param Exception $exception
     * @return string
     */
    public function actionNotFound($exception)
    {
        header("HTTP/1.0 404 Not Found");
        return $this->render('error', [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode()
        ]);
    }

    /**
     * @param $id
     * @return Controller
     * @throws Exception
     */
    public static function getController($id)
    {
        if (empty($id)) {
            throw new Exception("Empty controller name", 404);
        }
        $parts = explode('/', trim($id, ' /'));
        if (count($parts) > 0) {
            $parts[count($parts) - 1] = ucfirst($parts[count($parts) - 1]);
        }
        $controllerClass = str_replace('/', '\\', Core::$app->getControllerPath()) . '\\' . implode('\\',
                $parts) . 'Controller';
        if (!class_exists($controllerClass)) {
            throw new Exception("Controller not found!", 404);
        }

        $obj = new $controllerClass($id);
        return $obj;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function hasAction($id)
    {
        if (!isset($this->_actionMap[$id])) {
            $action_parts = explode("-", $id);
            $action_parts = array_map(function ($part) {
                return ucfirst($part);
            }, $action_parts);
            $actionName = 'action' . implode("", $action_parts);

            if (method_exists($this, $actionName)) {
                $this->_actionMap[$id] = $actionName;
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $id
     * @param array $args
     * @return mixed
     * @throws Exception
     */
    public function runAction($id, $args = [])
    {
        if (!$this->hasAction($id)) {
            throw new Exception("Action $id not exist", 404);
        }

        $action = $this->_actionMap[$id];

        $method = new \ReflectionMethod($this, $action);

        $methodParams = $method->getParameters();

        if (count($methodParams) > count($args)) {
            throw new Exception("Missing required parameter $" . $methodParams[count($args)], 400);
        }

        $result = call_user_func_array([$this, $action], $args);

        return $result;
    }

    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render($view, $params = [])
    {
        $content = $this->getView()->render($view, $params, $this);
        if ($this->layout !== null && !empty($this->getView()->findViewFile($this->layout))) {
            $content = $this->getView()->render($this->layout, ['content' => $content], $this);
        }
        return $content;
    }

    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function renderView($view, $params = [])
    {
        $content = $this->getView()->render($view, $params, $this);
        return $content;
    }

    /**
     * @return \core\View|null
     */
    public function getView()
    {
        if ($this->_view === null) {
            $this->_view = new View();
        }
        return $this->_view;
    }

    /**
     * @param View $view
     */
    public function setView($view)
    {
        if ($view instanceof View) {
            $this->_view = $view;
        }
    }

    /**
     * @return null|string
     */
    public function getViewPath()
    {
        if ($this->_viewPath === null) {
            $this->_viewPath = Core::$app->getViewPath() . DIRECTORY_SEPARATOR . trim($this->id, '/');
        }

        return $this->_viewPath;
    }

    /**
     * @param string $path
     */
    public function setViewPath($path)
    {
        $this->_viewPath = trim($path, '/');
    }
}
