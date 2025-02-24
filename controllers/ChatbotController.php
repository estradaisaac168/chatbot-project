<?php

namespace Controllers;

use Model\Question;
use Model\Response;
use App\Router;

class ChatbotController
{

    public static function chatbot(Router $router)
    {
        error_log("ChatbotController::chatbot() ");

        session_start();
        isAuth();

        $router->render('chatbot', [
            'title' => "Chatbot RRHH"
        ]);
    }
}
