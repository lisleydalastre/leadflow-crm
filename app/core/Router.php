<?php
/**
 * Router - Enrutador frontal simple
 *
 * Mapea URIs a controladores y métodos.
 * Soporta segmentos dinámicos (:id).
 */
class Router
{
    private array $routes = [];

    /** Registra una ruta GET. */
    public function get(string $uri, string $controller, string $method): void
    {
        $this->routes['GET'][$uri] = [$controller, $method];
    }

    /** Registra una ruta POST. */
    public function post(string $uri, string $controller, string $method): void
    {
        $this->routes['POST'][$uri] = [$controller, $method];
    }

    /**
     * Resuelve la URI actual y despacha al controlador.
     */
    public function dispatch(string $uri, string $httpMethod): void
    {
        $httpMethod = strtoupper($httpMethod);
        $uri        = '/' . trim(parse_url($uri, PHP_URL_PATH), '/');

        // Eliminar prefijo de sub-directorio si aplica
        $cfg    = require BASE_PATH . '/config/config.php';
        $base   = parse_url($cfg['app']['url'], PHP_URL_PATH);
        if ($base && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base)) ?: '/';
        }

        $params = [];

        foreach ($this->routes[$httpMethod] ?? [] as $route => [$controller, $method]) {
            $pattern = preg_replace('/:([a-z_]+)/', '([^/]+)', $route);
            $pattern = "@^{$pattern}$@";
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                $params = $matches;
                $this->invoke($controller, $method, $params);
                return;
            }
        }

        http_response_code(404);
        require BASE_PATH . '/app/views/partials/404.php';
    }

    private function invoke(string $controller, string $method, array $params): void
    {
        $file = BASE_PATH . "/app/controllers/{$controller}.php";
        if (!file_exists($file)) {
            die("Controlador no encontrado: {$controller}");
        }
        require_once $file;
        $obj = new $controller();
        $obj->$method(...$params);
    }
}
