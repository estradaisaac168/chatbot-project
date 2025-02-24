<?php

namespace App;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }

    public function checkRoutes()
    {

        // $currentUrl = parse_url($_SERVER['REQUEST_URI']) ?? '/';
        // $currentUrl = $_SERVER['REQUEST_URI'] ?? '/';

        $currentUrl = strtok($_SERVER["REQUEST_URI"], "?") ?? '/';

        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }


        if ($fn) {
            // Call user fn va a llamar una función cuando no sabemos cual sera
            call_user_func($fn, $this); // This es para pasar argumentos
        } else {
            echo "Página No Encontrada o Ruta no válida";
        }
    }

    public function render($view, $datos = [])
    {
        // Leer lo que le pasamos  a la vista
        foreach ($datos as $key => $value) {
            $$key = $value;  // Doble signo de dolar significa: variable variable, básicamente nuestra variable sigue siendo la original, pero al asignarla a otra no la reescribe, mantiene su valor, de esta forma el nombre de la variable se asigna dinamicamente
        }
        $script = $script ?? '';
        ob_start(); // Almacenamiento en memoria durante un momento...
        include_once __DIR__ . "/views/$view.php"; // entonces incluimos la vista en el layout
        $content = ob_get_clean(); // Limpia el Buffer
        $currentUrl = parse_url($_SERVER['REQUEST_URI']) ?? '/';
        if (str_contains($currentUrl["path"] , '/admin')) {
            include_once __DIR__ . '/views/layout-admin.php';
        } else {
            include_once __DIR__ . '/views/layout.php';
        }
    }
}
