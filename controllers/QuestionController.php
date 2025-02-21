<?php

namespace Controllers;

use Model\Question;

class QuestionController
{

    public static function getquestions()
    {
        $id = $_GET['id'] ?? null;
        if ($id === null) {
            echo json_encode([
                'status' => false,
                'questions' => "El parametro id no esta definido"
            ]);
            die;
        }


        $questionModel = new Question();
        $questions = $questionModel->getById($id);

        if (!$questions) {
            echo json_encode([
                'status' => false,
                'questions' => 'No se pudo realizar la consulta.'
            ]);
            die;
        }else{
            echo json_encode([
                'status' => true,
                'questions' => $questions
            ]);
            die;
        }
    }
}
