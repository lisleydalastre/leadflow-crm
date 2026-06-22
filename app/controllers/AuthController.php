<?php
require_once BASE_PATH . '/app/core/Controller.php';
require_once BASE_PATH . '/app/models/UserModel.php';

/**
 * AuthController - Maneja login y logout
 */
class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        $this->render('auth/login');
    }

    public function login(): void
    {
        $email    = $this->input('email');
        $password = $this->input('password');

        if (!$email || !$password) {
            $this->flash('danger', 'Completa todos los campos.');
            $this->redirect('login');
            return;
        }

        $model = new UserModel();
        $user  = $model->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->flash('danger', 'Credenciales incorrectas.');
            $this->redirect('login');
            return;
        }

        // Regenerar ID de sesión por seguridad
        session_regenerate_id(true);

        $_SESSION['user_id']     = $user['id'];
        $_SESSION['user_nombre'] = $user['nombre'];
        $_SESSION['user_email']  = $user['email'];
        $_SESSION['user_rol']    = $user['rol'];

        $this->redirect('dashboard');
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        $this->redirect('login');
    }
}
