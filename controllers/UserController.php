<?php

namespace Controllers;

use Model\User;

class UserController
{

    public static function create()
    {
        // $carnet = validate($_POST['carnet']);
        // $password = validate($_POST['password']);

        $user = [];

        $user['carnet'] = '123456';
        $user['password'] = password_hash('123', PASSWORD_DEFAULT);

        $userModel = new User();
        $user = $userModel->save($user);

        if($user){
            echo json_encode(['status' => true, 'message' => "Usuario creado."]);
        }else{
            echo json_encode(['status' => false, 'message' => "Algo salio mal."]);
        }
    }

    public static function login(){

        $carnet = validatePost('carnet');
        $password = validatePost('password');
        
        if(!$carnet && !$password){

            echo json_encode([
                "status" => false, 
                "message" => "Las variables no estan definidas"
            ]);

            die;

        }

        $userModel = new User();
        $user = $userModel->login($carnet, $password);

        if($user){
            self::userSession($user);
            die;
        }else{
            echo json_encode([
                "status" => true, 
                "message" => "Usuario logueado"
            ]);

            die;
        }


    }


    public function userSession($user){

        $_SESSION['id'] = $user->id;
        $_SESSION['carnet'] = $user->carnet;

    }


    public function logout(){

        unset($_SESSION['id']);
        unset($_SESSION['carnet']);

    }
}
