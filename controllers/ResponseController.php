<?php

namespace Controllers;


use Model\Response;

class ResponseController
{

    public static function getresponses()
    {
        $id = $_GET['id'] ?? null;
        if ($id === null) {
            echo json_encode([
                'status' => false,
                'message' => "El parametro id no esta definido"
            ]);
            die;
        }


        $responseModel = new Response();
        $response = $responseModel->getById($id);

        if (!$response) {
            echo json_encode([
                'status' => false,
                'message' => 'No se pudo realizar la consulta para las respuestas.'
            ]);
            die;
        } else {
            echo json_encode([
                'status' => true,
                'response' => $response
            ]);
            die;
        }
    }
}
