<?php

require_once "AutoLoader.php";

use core\AutoLoader;
use controllers\SiteController;
use core\Controller;

/**
 * Class Core
 * @package core
 *
 */
class Core
{
    /**
     * @var null | Core
     */
    public static $app = null;

    /**
     * @var null | MongoDB
     */
    public $db = null;

    /**
     * @var null | MongoClient
     */
    public $mClient = null;

    /**
     * @var string
     */
    protected $_defaultController = 'site';

    /**
     * @var string
     */
    protected $_controllerPath = 'controllers';
    /**
     * @var string
     */
    protected $_viewPath = 'views';

    /**
     * @var string | null
     */
    protected $_basePath = '/';

    /**
     * @var string
     */
    public $layout = null;

    /**
     * @var string
     */
    public $charset = 'UTF-8';

    /**
     * @param $value
     * @return Core|null
     */
    public static function setApp($value)
    {
        return self::$app;
    }

    /**
     * @param array $config
     * @return Core
     */
    public static function init($config = [])
    {
        if (empty(self::$app)) {
            try {
                self::$app = new Core();
                AutoLoader::register();
                self::$app->preInit($config);
                self::$app->initDB($config);
            } catch (Exception $e) {

            }
            self::$app->run();
        }

        return Core::$app;
    }

    /**
     *
     */
    public function run()
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return;
        }
        $controller = $this->_defaultController;
        $action = 'index';
        $params = [];
        if ($_SERVER['REQUEST_URI'] != '/') {

            $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $uri_parts = explode('/', trim($url_path, ' /'));

            $action = array_shift($uri_parts);

            for ($i = 0; $i < count($uri_parts); $i++) {
                $params[] = $uri_parts[$i];
            }
        }

        try {

            /**
             * @var \core\Controller $objController
             */
            $objController = Controller::getController($controller);

            if ($this->layout !== null && $objController->layout === null) {
                $objController->layout = $this->layout;
            }

            $result = $objController->runAction($action, $params);


        } catch (Exception $e) {
            $objController = new Controller('');
            if ($this->layout !== null && $objController->layout === null) {
                $objController->layout = $this->layout;
            }
            $result = $objController->actionNotFound($e);
        }

        print $result;
    }

    /**
     * @param $config
     * @throws Exception
     */
    protected function initDB($config)
    {
        if (!isset($config['db'])) {
            throw new Exception("Database settings not found");
        }

        $dbConf = $config['db'];

        if (!isset($dbConf['host']) || !isset($dbConf['dbname'])) {
            throw new Exception('Database settings not full');
        }

        $host = $dbConf['host'];

        if (isset($dbConf['username'])) {
            $password = isset($dbConf['password']) ? ':' . $dbConf['password'] : '';
            $host = $dbConf['username'] . $password . '@' . $host;
        }

        $options = [];
        if (isset($dbConf['options'])) {
            $options = $dbConf['options'];
        }

        $this->mClient = new MongoClient("mongodb://$host", $options);

        $this->db = $this->mClient->selectDB($dbConf['dbname']);
    }

    /**
     * @param array $config
     */
    protected function preInit($config = [])
    {
        foreach ($config as $key => $value) {
            switch ($key) {
                case 'basePath': {
                    $this->_basePath = $value;
                    break;
                }
                case 'layout': {
                    $this->layout = $value;
                    break;
                }
                case 'defaultController': {
                    $this->_defaultController = $value;
                    break;
                }
                case 'controllersPath': {
                    $this->_controllerPath = $value;
                    break;
                }
                case 'viewsPath': {
                    $this->_viewPath = $value;
                    break;
                }
            }
        }
    }

    /**
     * @return string
     */
    public function getViewPath()
    {
        return $this->getBasePath() . DIRECTORY_SEPARATOR . trim($this->_viewPath, '/');
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return trim($this->_basePath, '/');
    }

    /**
     * @return string
     */
    public function getControllerPath()
    {
        return $this->getBasePath() . DIRECTORY_SEPARATOR . trim($this->_controllerPath, '/');
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function get($name = '')
    {
        if (empty($name)) {
            return $_GET;
        }
        if (!isset($_GET[$name])) {
            return false;
        }
        return $_GET[$name];
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function post($name = '')
    {
        if (empty($name)) {
            return $_POST;
        }
        if (!isset($_POST[$name])) {
            return false;
        }
        return $_POST[$name];
    }

    /**
     * @param string $url
     * @param bool $fullRoute
     * @return bool
     */
    public function redirect($url, $fullRoute = false)
    {
        if (!$fullRoute) {
            $host = $_SERVER['HTTP_HOST'];
            $url = $host . ltrim($url);
        }
        header("Location: http://$url");

        return true;
    }

    /**
     * @param string $name
     * @param string $value
     * @param null | integer $time
     * @param string $path
     */
    public function setCookie($name, $value, $time = null, $path = '/')
    {
        if ($time === null) {
            $time = strtotime('+30 days');
        }

        setcookie($name, $value, $time, $path);
    }

    /**
     * @param string $name
     * @return string
     */
    public function getCookie($name)
    {
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }

        return '';
    }

    /**
     * @param string $name
     */
    public function deleteCookie($name)
    {
        if (isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasCookie($name)
    {
        if (isset($_COOKIE[$name])) {
            return true;
        }

        return false;
    }
}
