<?php

namespace vendor\core;

class Router 
{   
    protected static $routes = [];
    protected static $route = [];   
    
    
    public static function add ($regexp, $route = []) 
    {
        self::$routes[$regexp] = $route;
    }     
    
    
    public static function getRoutes () 
    {
        return self::$routes;
    }
    
    
    public static function getRoute () 
    {
        return self::$route;
    }    
    
    
    public static function matchRoute ($url) 
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#$pattern#i", $url, $matches)) {
                debug($matches);
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                       $route[$key] = $value; 
                    }
                }                
                if (!isset($route['action'])) {
                    $route['action'] = 'index';
                }
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                //debug(self::$route);                
                return true;
            }
        }        
        return false;
    }    
    
    
    public static function dispatch ($url) 
    {
        $url = self::removeQueryString($url);
        var_dump($url);
        if (self::matchRoute($url)) {
            $controller = 'app\controllers\\' . self::$route['controller'];
            //debug(self::$route);
            if (class_exists($controller)) {
                $cObj = new $controller(self::$route);
                $action = self::lowerCamelCase(self::$route['action']) . 'Action';
                if (method_exists($cObj, $action)) {
                    $cObj->$action();
                } else {
                    echo "Метод <b>$controller::$action</b> не найден";
                }
            } else {
                echo "Контроллер<b>$controller</b> не найден";
            }
        } else {
            http_response_code(404);
            include '404.html';
        }
    }    
    
    
    protected static function upperCamelCase ($str) 
    {
        $str = str_replace('-', ' ', $str);
        $str = ucwords($str);
        $str = str_replace(' ', '', $str);
        return $str;        
    }    
    
    
    protected static function lowerCamelCase($str) 
    {
        return lcfirst(self::upperCamelCase($str));
    }
    
    
    protected static function removeQueryString($url) 
    {
        if ($url) {
            $params = explode('&', $url, 2);
            if (strpos($params[0], '=') === false) {
                return rtrim($params[0], '/');
            } else {
                return '';
            }
        }
        debug($url);
        return $url;
    }
}
