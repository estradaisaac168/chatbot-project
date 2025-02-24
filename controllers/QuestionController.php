<?php

namespace Controllers;

use Model\Question;
use Model\Response;

class QuestionController
{

    public static function getquestion()
    {
        $id = $_GET['id'] ?? null;
        if ($id === null) {
            echo json_encode([
                'status' => false,
                'message' => "El parametro id no esta definido"
            ]);
            die;
        }


        $questionModel = new Question();
        $questionModel->setId($id);
        $question = $questionModel->getOne();

        if (!$question) {
            echo json_encode([
                'status' => false,
                'message' => 'No se pudo realizar la consulta para las preguntas.'
            ]);
            die;
        } else {

            $responseModel = new Response();
            $responseModel->setQuestionId($question->id);
            $responses = $responseModel->getAllById();

            if (!$responses) {
                echo json_encode([
                    'status' => false,
                    'message' => 'No se pudo realizar la consulta para las respuestas.'
                ]);
                die;
            } else {
                echo json_encode([
                    'status' => true,
                    'question' => $question,
                    'responses' => $responses
                ]);
                die;
            }
        }
    }
}
