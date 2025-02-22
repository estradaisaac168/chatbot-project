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

        // Proteger Rutas...
        // session_set_cookie_params([
        //     'lifetime' => 0,
        //     'secure' => true,      // Solo permitir en HTTPS
        //     'httponly' => true,    // No accesible por JavaScript
        //     'samesite' => 'Strict' // Evita CSRF
        // ]);
        // session_start();

        // Arreglo de rutas protegidas...
        // $rutas_protegidas = ['/admin', '/propiedades/crear', '/propiedades/actualizar', '/propiedades/eliminar', '/vendedores/crear', '/vendedores/actualizar', '/vendedores/eliminar'];

        // $auth = $_SESSION['login'] ?? null;

        // $currentUrl = $_SERVER['PATH_INFO'] ?? '/';

        $currentUrl = parse_url($_SERVER['REQUEST_URI']) ?? '/';

        $method = $_SERVER['REQUEST_METHOD'];

        // Manejar solicitudes OPTIONS (Preflight)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl['path']] ?? null;
        } else {
            $fn = $this->postRoutes[$currentUrl['path']] ?? null;
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

        ob_start(); // Almacenamiento en memoria durante un momento...

        // entonces incluimos la vista en el layout
        include_once __DIR__ . "/views/$view.php";
        
        $content = ob_get_clean(); // Limpia el Buffer

        $currentUrl = parse_url($_SERVER['REQUEST_URI']) ?? '/';

        if (str_contains($currentUrl['path'], '/admin')) {
            include_once __DIR__ . '/views/layout-admin.php';
        } else {
            include_once __DIR__ . '/views/layout.php';
        }
    }
}
