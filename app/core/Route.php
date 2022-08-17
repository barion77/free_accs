<?php

namespace app\core;

use app\exceptions\RouteException;

class Route
{ 
    private static $routes = [];
    private static $values = [];

    public static function get(string $uri, $callback)
    {
        self::addRoute('GET', self::prepareUri($uri));
        $method = $_SERVER['REQUEST_METHOD'];

        if (preg_match(self::prepareUri($uri), self::getUri(), $matches)) {
            if ($method == 'GET') {
                if (is_string($callback)) {
                    echo self::stringHandler($callback);
                } else {
                    call_user_func($callback);
                }
            }
        }
    }

    public static function post(string $uri, $callback)
    {
        self::addRoute('POST', self::prepareUri($uri));
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

    public static function put(string $uri, $callback)
    {
        self::addRoute('PUT', self::prepareUri($uri));
        $method = isset($_POST['_method']) ? $_POST['_method'] : $_SERVER['REQUEST_METHOD'];

        if (preg_match(self::prepareUri($uri), self::getUri(), $matches)) {
            if ($method == 'PUT') {
                if (is_string($callback)) {
                    echo self::stringHandler($callback);
                } else {
                    call_user_func($callback);
                }
            }
        }
    }

    public static function patch(string $uri, $callback)
    {
        self::addRoute('PATCH', self::prepareUri($uri));
        $method = isset($_POST['_method']) ? $_POST['_method'] : $_SERVER['REQUEST_METHOD'];

        if (preg_match(self::prepareUri($uri), self::getUri(), $matches)) {
            if ($method == 'PATCH') {
                if (is_string($callback)) {
                    echo self::stringHandler($callback);
                } else {
                    call_user_func($callback);
                }
            }
        }
    }

    public static function delete(string $uri, $callback)
    {
        self::addRoute('DELETE', self::prepareUri($uri)); 
        $method = isset($_POST['_method']) ? $_POST['_method'] : $_SERVER['REQUEST_METHOD'];

        if (preg_match(self::prepareUri($uri), self::getUri(), $matches)) {
            if ($method == 'DELETE') {
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
        if (self::getValueFromUri(self::getUri())) {
            self::$values = array_combine(self::$values, self::getValueFromUri(self::getUri()));
        }

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

        return $uri;
    }

    public static function prepareUri(string $uri)
    {
        $pattern = '/:[a-z]+/';
        if (preg_match_all($pattern, $uri, $matches)) {
            self::$values = array_map(function($flag) {
                return trim($flag, ':');
            }, $matches[0]);
        }

        $uri = '#^' . preg_replace($pattern, '[0-9]+', trim($uri, '/')) . '$#';
        return $uri;
    }

    public static function getValueFromUri(string $uri)
    {
        $pattern = '/[1-9]+/';
        preg_match_all($pattern, $uri, $matches);

        if (count($matches[0]) > 0) {
            return $matches[0];
        }
    }

    public static function addRoute(string $method, string $route)
    {
        if (!isset(self::$routes[$route]) || self::$routes[$route] != $method) {
            self::$routes[$route] = $method;
        } else {
            throw new RouteException("Route [$route] already exists");
        }
    }

    public static function check()
    {
        $request_uri = trim($_SERVER['REQUEST_URI'], '/');
        $request_method = isset($_POST['_method']) ? $_POST['_method'] : $_SERVER['REQUEST_METHOD'];
        $flag = true;

        foreach (self::$routes as $uri => $method) {
            if (preg_match($uri, $request_uri)) {
                if ($method == $request_method) {
                    $flag = false;
                }
            }
        }

        if ($flag) {
            abort(404);
        }
    }
}
