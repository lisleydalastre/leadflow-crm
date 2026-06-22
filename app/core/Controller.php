<?php
abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = BASE_PATH . "/app/views/{$view}.php";
        if (!file_exists($viewFile)) { http_response_code(404); die("Vista no encontrada: {$view}"); }

        // Capturar contenido de la vista
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Inyectar en el layout (si no es login/403/404)
        $noLayout = ['auth/login', 'partials/403', 'partials/404'];
        if (in_array($view, $noLayout)) {
            echo $content;
        } else {
            require BASE_PATH . '/app/views/partials/layout.php';
        }
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
        exit;
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user_id'])) { $this->redirect('login'); }
    }

    protected function requireRole(string ...$roles): void
    {
        $this->requireAuth();
        if (!in_array($_SESSION['user_rol'] ?? '', $roles, true)) {
            http_response_code(403); die('Acceso denegado.');
        }
    }

    protected function input(string $key, mixed $default = ''): mixed
    {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }

    protected function flash(string $type, string $msg): void
    {
        $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
    }
}
