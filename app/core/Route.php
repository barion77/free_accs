<?php

namespace app\core;

use app\exceptions\RouteException;

class Route
{
    private static $routes = [];
    private static $values = [];
    private static $patterns = [];

    private const PATTERN = '/:[a-z\d\._]+/';
    private const DEFAULT_PATTERN_ID = '[1-9]+';
    private const DEFAULT_PATTERN_SLUG = '(?=.*[a-z]).+';

    public static function get(string $uri, $callback, $pattern = [])
    {
        self::addPattern($pattern);
        self::addRoute(self::prepareUri($uri), 'GET');
        $method = $_SERVER['REQUEST_METHOD'];
        if (preg_match(self::prepareUri($uri), self::getUri())) {
            if ($method == 'GET') {
                self::getValueFromUri($uri);
                if (is_string($callback)) {
                    echo self::stringHandler($callback);
                } else {
                    call_user_func($callback);
                }
            }
        }
    }

    public static function post(string $uri, $callback, $pattern = [])
    {
        self::addPattern($pattern);
        self::addRoute(self::prepareUri($uri), 'POST');
        $method = $_SERVER['REQUEST_METHOD'];

        if (preg_match(self::prepareUri($uri), self::getUri(), $matches)) {
            if ($method == 'POST') {
                if (is_string($callback)) {
                    echo self::stringHandler($callback);
                } else {
                    call_user_func($callback);
                }
            }
        }
    }

    public static function put(string $uri, $callback, $pattern = [])
    {
        self::addPattern($pattern);
        self::addRoute(self::prepareUri($uri), 'PUT');
        $method = isset($_POST['_method']) ? $_POST['_method'] : $_SERVER['REQUEST_METHOD'];

        if (preg_match(self::prepareUri($uri), self::getUri(), $matches)) {
            if ($method == 'PUT') {
                self::getValueFromUri($uri);
                if (is_string($callback)) {
                    echo self::stringHandler($callback);
                } else {
                    call_user_func($callback);
                }
            }
        }
    }

    public static function patch(string $uri, $callback, $pattern = [])
    {
        self::addPattern($pattern);
        self::addRoute(self::prepareUri($uri), 'PATCH');
        $method = isset($_POST['_method']) ? $_POST['_method'] : $_SERVER['REQUEST_METHOD'];

        if (preg_match(self::prepareUri($uri), self::getUri(), $matches)) {
            if ($method == 'PATCH') {
                self::getValueFromUri($uri);
                if (is_string($callback)) {
                    echo self::stringHandler($callback);
                } else {
                    call_user_func($callback);
                }
            }
        }
    }

    public static function delete(string $uri, $callback, $pattern = [])
    {
        self::addPattern($pattern);
        self::addRoute(self::prepareUri($uri), 'DELETE');
        $method = isset($_POST['_method']) ? $_POST['_method'] : $_SERVER['REQUEST_METHOD'];

        if (preg_match(self::prepareUri($uri), self::getUri(), $matches)) {
            if ($method == 'DELETE') {
                self::getValueFromUri($uri);
                if (is_string($callback)) {
                    echo self::stringHandler($callback);
                } else {
                    call_user_func($callback);
                }
            }
        }
    }


    public static function stringHandler($string)
    {
        if (strpos($string, '@')) {
            return self::classHandler($string);
        } else {
            return $string;
        }
    }

    public static function classHandler($string)
    {
        $params = explode('@', $string);
        $controller = 'app\controllers\\' . $params[0];
        $action = $params[1];

        if (class_exists($controller) && method_exists($controller, $action)) {
            $controller = new $controller(self::$values);
            return $controller->$action();
        } else {
            if (Config::getField('APP_DEBUG')) {
                throw new RouteException('Class or method does not exists ' . $controller . ' method: ' . $action);
            } else {
                if (Config::getField('APP_LOG')) {
                    logging('Class or method does not exists ' . $controller . ' method: ' . $action);
                }
                abort(404);
            }
        }
    }

    public static function getUri()
    {
        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $uri = (strpos($uri, '?')) ? substr($uri, 0, strpos($uri, '?')) : $uri;

        return trim($uri, '/');
    }

    public static function prepareUri(string $uri)
    {
        $uri = trim($uri, '/');
        preg_match_all(self::PATTERN, $uri, $matches);
        foreach ($matches[0] as $parameter) {
            $parameter = trim($parameter, ':');
            if (isset(self::$patterns[$parameter])) {
                $pattern = '/:' . $parameter . '/';
                $replacement_pattern = self::$patterns[$parameter];
                $uri = preg_replace($pattern, $replacement_pattern, $uri);
            } else {
                $pattern = '/:' . $parameter . '/';
                $replacement_pattern = $parameter == 'slug' ? self::DEFAULT_PATTERN_SLUG : self::DEFAULT_PATTERN_ID;
                $uri = preg_replace($pattern, $replacement_pattern, $uri);
            }
        }

        return '#^' . $uri . '$#';
    }

    public static function getValueFromUri($uri)
    {
        $uri = explode('/', trim($uri, '/'));
        $current_uri = explode('/', self::getUri());

        foreach ($uri as $key => $value) {
            if (preg_match(self::PATTERN, $value)) {
                $value = trim($value, ':');
                self::$values[$value] = $current_uri[$key];
            }
        }
    }

    public static function addRoute(string $uri, string $method)
    {
        foreach (self::$routes as $route) {
            if ($route['uri'] == $uri && $route['method'] == $method) {
                throw new RouteException("This route already exists [$uri - $method]");
            }
        }

        self::$routes[] = ['uri' => $uri, 'method' => $method];
    }

    public static function addPattern($pattern)
    {
        foreach ($pattern as $key => $value) {
            self::$patterns[$key] = $value;
        }
    }


    public static function check()
    {
        $request_uri = trim($_SERVER['REQUEST_URI'], '/');
        $request_method = isset($_POST['_method']) ? $_POST['_method'] : $_SERVER['REQUEST_METHOD'];
        $flag = true;

        foreach (self::$routes as $route) {
            if (preg_match($route['uri'], $request_uri)) {
                if ($route['method'] == $request_method) {
                    $flag = false;
                }
            }
        }

        // if ($flag) {
        //     abort(404);
        // }
    }
}
