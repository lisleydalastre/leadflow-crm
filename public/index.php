<?php
define('BASE_PATH', dirname(__DIR__));

$cfg = require BASE_PATH . '/config/config.php';
date_default_timezone_set($cfg['app']['timezone']);
define('BASE_URL', rtrim($cfg['app']['url'], '/'));

if ($cfg['app']['debug']) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

session_name($cfg['session']['name']);
session_set_cookie_params([
    'lifetime' => $cfg['session']['lifetime'],
    'path'     => '/',
    'secure'   => false,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

spl_autoload_register(function (string $class) {
    foreach (['/app/core/', '/app/models/', '/app/controllers/'] as $dir) {
        $file = BASE_PATH . $dir . $class . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }
});

$router = new Router();

$router->get('/login',  'AuthController', 'loginForm');
$router->post('/login', 'AuthController', 'login');
$router->get('/logout', 'AuthController', 'logout');

$router->get('/',          'DashboardController', 'index');
$router->get('/dashboard', 'DashboardController', 'index');

$router->get('/leads',             'LeadController', 'index');
$router->get('/leads/create',      'LeadController', 'create');
$router->post('/leads/store',      'LeadController', 'store');
$router->get('/leads/:id',         'LeadController', 'show');
$router->get('/leads/:id/edit',    'LeadController', 'edit');
$router->post('/leads/:id/update', 'LeadController', 'update');
$router->post('/leads/:id/delete', 'LeadController', 'delete');

$router->get('/ventas',             'VentaController', 'index');
$router->get('/ventas/create',      'VentaController', 'create');
$router->post('/ventas/store',      'VentaController', 'store');
$router->post('/ventas/:id/delete', 'VentaController', 'delete');

$router->get('/reportes', 'ReportController', 'index');

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
