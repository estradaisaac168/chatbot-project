<?php

error_reporting(E_ALL);
ini_set('ignore_repeated_errors', TRUE);
ini_set('display_errors', TRUE); //Solo en ambiente de desarrollo. 
ini_set('log_errors', TRUE);
ini_set('error_log', '/home/user/chatbot-project/php-error.log');
// error_log("Testing errors!");

require_once __DIR__ . '/../core/app.php';

use App\Router;
use Controllers\QuestionController;
use Controllers\ResponseController;
use Controllers\DocumentController;
use Controllers\UserController;
use Controllers\AuthController;
use Controllers\ChatbotController;

$router = new Router();


//Login
$router->get('/', [AuthController::class, 'login']);
$router->post('/', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);


// Questions routes
$router->get('/api/question', [QuestionController::class, 'getquestion']);


//Responses routes
$router->get('/api/responses', [ResponseController::class, 'getresponses']);


//Documents routes
$router->get('/api/document/download', [DocumentController::class, 'download']);
$router->get('/api/document/send', [DocumentController::class, 'send']);
$router->post('/api/document/create', [DocumentController::class, 'create']);


// users
$router->post('/api/user/create', [UserController::class, 'create']);
$router->post('/api/user/login', [UserController::class, 'login']);

// Chatbot
$router->get('/chatbot', [ChatbotController::class, 'chatbot']);


$router->checkRoutes();