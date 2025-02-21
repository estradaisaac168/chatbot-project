<?php
require_once __DIR__ . '/../core/app.php';

use App\Router;
use Controllers\QuestionController;


header("Access-Control-Allow-Origin: https://5502-idx-chatbot-1739282265787.cluster-joak5ukfbnbyqspg4tewa33d24.cloudworkstations.dev/");

// // Permitir credenciales
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

header('Content-Type: application/json');

$router = new Router();


$router->get('/api/questions', [QuestionController::class, 'getquestions']);


$router->checkRoutes();