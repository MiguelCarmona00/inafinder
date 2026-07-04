<?php

namespace App\Core;
class Router
{
    private $routes = array();

    public function add($route)
    {
        $this->routes[] = $route;
    }

    public function match(string $request)
    {
        // Primer match gana: antes recorría todas las rutas y devolvía la última
        // coincidencia, evaluando ~50 regex por petición sin necesidad
        foreach ($this->routes as $route) {
            if (preg_match($route['path'], $request)) {
                return $route;
            }
        }
        return array();
    }
}
