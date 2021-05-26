<?php

namespace App\Classes;

use ReflectionFunction;
use ReflectionMethod;

class Router
{

    public static function route($requestMethod, $uri, $callback, $middleware = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == $requestMethod) {
            $uri = trim($uri, '/');
            $url = parse_url($_SERVER['REQUEST_URI']);
            $path = trim($url['path'], '/');

            $uriPieces = explode('/', $uri);
            $pathPieces = explode('/', $path);
            $isSamePath = false;

            preg_match_all('/\{(.*)\}/U', $uri, $matches);
            $matches = !empty($matches[0]) ? $matches[0] : null;

            $routeParams = [];

            if (count($uriPieces) == count($pathPieces)) {
                foreach ($uriPieces as $i => $uriPiece) {
                    if ($uriPiece != $pathPieces[$i] && !empty($matches) && in_array($uriPiece, $matches)) {
                        $uriKeyPiece = trim($uriPiece, '{,}');
                        $routeParams[$uriKeyPiece] = $pathPieces[$i];
                        $uri = str_replace($uriPiece, $pathPieces[$i], $uri);
                    }
                }

                $isSamePath = ($uri == $path);
            }

            if ($isSamePath) {

                if (!is_null($middleware)) {
                    $middlewareClass = new $middleware();
                    $middlewareClass();
                }

                $funcArgs = [];
                $callbackArgNames = self::get_func_argNames($callback);

                if (!empty ($callbackArgNames)) {
                    foreach ($callbackArgNames as $callbackArgName) {
                        if (!empty($routeParams[$callbackArgName])) {
                            $funcArgs[$callbackArgName] = $routeParams[$callbackArgName];
                        }
                    }

                    $funcArgs['request'] = new Request(!in_array($requestMethod, [Request::METHOD_GET, Request::METHOD_DELETE]));
                }

                call_user_func_array($callback, $funcArgs);
                die;
            }
        }
    }

    public static function routeToController($requestMethod, $uri, $controller, $middleware = null)
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == $requestMethod) {
            $uri = trim($uri, '/');
            $url = parse_url($_SERVER['REQUEST_URI']);
            $path = trim($url['path'], '/');

            $uriPieces = explode('/', $uri);
            $pathPieces = explode('/', $path);
            $isSamePath = false;

            preg_match_all('/\{(.*)\}/U', $uri, $matches);
            $matches = !empty($matches[0]) ? $matches[0] : null;

            $routeParams = [];

            if (count($uriPieces) == count($pathPieces)) {
                foreach ($uriPieces as $i => $uriPiece) {
                    if ($uriPiece != $pathPieces[$i] && !empty($matches) && in_array($uriPiece, $matches)) {
                        $uriKeyPiece = trim($uriPiece, '{,}');
                        $routeParams[$uriKeyPiece] = $pathPieces[$i];
                        $uri = str_replace($uriPiece, $pathPieces[$i], $uri);
                    }
                }

                $isSamePath = ($uri == $path);
            }

            if ($isSamePath) {

                if (!is_null($middleware)) {
                    $middlewareClass = new $middleware();
                    $middlewareClass();
                }

                $controllerPieces = explode('@', $controller);
                $controllerClass = '\\App\\Controllers\\' . $controllerPieces[0];
                $controllerMethod = $controllerPieces[1];

                $funcArgs = [];
                $callbackArgNames = self::get_class_argNames($controllerClass, $controllerMethod);

                if (!empty($callbackArgNames)) {
                    foreach ($callbackArgNames as $callbackArgName) {
                        if (!empty($routeParams[$callbackArgName])) {
                            $funcArgs[$callbackArgName] = $routeParams[$callbackArgName];
                        }
                    }

                    $funcArgs['request'] = new Request(!in_array($requestMethod, [Request::METHOD_GET, Request::METHOD_DELETE]));
                }

                call_user_func_array([new $controllerClass(), $controllerMethod], $funcArgs);

                die;
            }
        }
    }

    private static function get_func_argNames($funcName)
    {
        $f = new ReflectionFunction($funcName);
        $result = [];
        foreach ($f->getParameters() as $param) {
            $result[] = $param->name;
        }
        return $result;
    }

    private static function get_class_argNames($className, $methodName)
    {
        $f = new ReflectionMethod($className, $methodName);
        $result = [];
        foreach ($f->getParameters() as $param) {
            $result[] = $param->getName();
        }
        return $result;
    }

}