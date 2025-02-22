<?php
require_once __DIR__ . '/../core/app.php';

use App\Router;
use Controllers\QuestionController;
use Controllers\ResponseController;
use Controllers\DocumentController;
use Controllers\UserController;
use Controllers\AuthController;
use Controllers\ChatbotController;

// header("Access-Control-Allow-Origin: https://5502-idx-chatbot-1739282265787.cluster-joak5ukfbnbyqspg4tewa33d24.cloudworkstations.dev/");

// // // Permitir credenciales
// header("Access-Control-Allow-Credentials: true");
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
// header("Access-Control-Allow-Headers: Content-Type, Authorization");

// header('Content-Type: application/json');

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