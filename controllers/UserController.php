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

        $user['carnet'] = '102592';
        $user['name'] = 'Odilio';
        $user['lastname'] = 'Rosales';
        $user['email'] = 'odiliorosales00@gmail.com';
        $user['password'] = password_hash('123', PASSWORD_DEFAULT);
        $user['carnet'] = '102592';
        $user['login'] = 'login';


        $userModel = new User();
        $userModel->setName('Odilio');
        $userModel->setLastname('Rosales');
        $userModel->setEmail('odiliorosales00@gmail.com');
        $userModel->setPassword(password_hash('123', PASSWORD_DEFAULT));
        $userModel->setCarne('102592');
        $userModel->setLogin(1);
        $user = $userModel->save($user);

        if($user){
            echo json_encode(['status' => true, 'message' => "Usuario creado."]);
        }else{
            echo json_encode(['status' => false, 'message' => "Algo salio mal."]);
        }
    }
}
