<?php

function dump($param)
{
    var_dump($param);
    die;
}


function validatePost($key)
{
    if (!isset($_POST[$key])) {
        return false;
    }

    $valor = trim($_POST[$key]);

    if (empty($valor)) {
        return false;
    }

    return $valor;
}


function isAuth() : void {
    if (!isset($_SESSION['login'])) {
        header('Location: /');
    }
}
