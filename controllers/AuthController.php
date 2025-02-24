<?php

namespace Controllers;

use Model\User;
use App\Router;

class AuthController
{
    public static function login(Router $router)
    {

        $data = [
            'carne' => '',
            'password' => '',
            'carnetError' => '',
            'passwordError' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            foreach ($_POST as $key => $value) {
                $_POST[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }

            $data = [
                'carne' => trim($_POST['carne'] ?? ''),
                'password' => trim($_POST['password'] ?? ''),
                'carneError' => empty($_POST['carne']) ? 'Ingrese su carnet' : '',
                'passwordError' => empty($_POST['password']) ? 'Ingrese su password' : ''
            ];

            if (empty($data['carneError']) && empty($data['passwordError'])) {
                $userModel = new User();
                $userModel->setCarne($data['carne']);
                $userModel->setPassword($data['password']);
                $user = $userModel->login();

                if ($user) {
                    self::userSession($user);
                } else {
                    $data['passwordError'] = 'Password Incorrecto';
                }
            }
        }

        $router->render('auth/login', [
            'title' => "Login Chatbot RRHH",
            'validate' => $data
        ]);
    }


    public static function userSession($user)
    {
        session_start();
        $_SESSION['id'] = $user->id;
        $_SESSION['carne'] = $user->carne;
        $_SESSION['fullname'] = $user->name . " " . $user->lastname;
        $_SESSION['email'] = $user->email;
        $_SESSION['login'] = true;
        header('location: /chatbot');
    }


    public static function logout()
    {
        session_start();
        unset($_SESSION['id']);
        unset($_SESSION['carnet']);
        unset($_SESSION['login']);
        $_SESSION = [];
        header('location: /');
    }
}
