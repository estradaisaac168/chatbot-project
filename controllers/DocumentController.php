<?php

namespace Controllers;


use Model\Document;
use Model\Response;
use Helpers\GeneradorPDF;
use Helpers\EmailSender;

class DocumentController
{

    public static function getresponses()
    {
        session_start();
        isAuth();

        $id = $_GET['id'] ?? null;
        if ($id === null) {
            echo json_encode([
                'status' => false,
                'questions' => "El parametro id no esta definido"
            ]);
            die;
        }


        $responseModel = new Response();
        $responseModel->setId($id);
        $response = $responseModel->getOne();

        if (!$response) {
            echo json_encode([
                'status' => false,
                'response' => 'No se pudo realizar la consulta para las respuestas.'
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


    public static function create()
    {
        session_start();
        isAuth();
        
        $type = validatePost('type');
        $responseId = validatePost('responseId');

        if (!$type && !$responseId) {

            echo json_encode([
                "status" => false,
                "message" => "Las variables no estan especificadas"
            ]);

            die;
        }


        $absolutePath = '';
        $relativePath = 'uploads/documents/';
        $nameFile = '';
        $document = [];

        $directoryPath = '../public/uploads/documents';  // Apunta a la carpeta 'public/uploads/documents'

        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true);  // Si no existe, lo crea
        }

        $generador = new GeneradorPDF(UPLOAD_DIR);

        switch (intval($_POST['type'])) {
            case 1:
                $nameFile = 'constancia_laboral_' . rand(10000, 99999);
                $absolutePath = $generador->generarConstanciaSalarial($nameFile, $_SESSION['fullname'], 'Call Center - RRHH', 1500.00, 200.00, 100.00);
                break;

            case 2:
                $nameFile = 'boleta_pago_' . rand(10000, 99999);
                $absolutePath = $generador->generarBoletaPago($nameFile, $_SESSION['carnet'], $_SESSION['fullname'], 'Call Center - RRHH', 1500.00, 200.00, 100.00);
                break;

            default:
                // $nameFile = 'constancia_laboral_' . rand(10000, 99999);
                // $absolutePath = $generador->generarConstanciaLaboral($nameFile, $_SESSION['fullname'], 'Call Center - RRHH', 'Analista', '01/01/2020');

                break;
        }


        // Guardar en la base de datos
        // $document['name'] = $nameFile . ".pdf";
        // $document['path'] = $filePath;
        // $document['createdAt'] = date('Y-m-d H:i:s');
        // $document['responseId'] = $responseId;

        $documentModel = new Document();
        $documentModel->setName($nameFile . ".pdf");
        $documentModel->setAbsolutePath($absolutePath);
        $documentModel->setRelativePath($relativePath);
        $documentModel->setResponsesId(intval($responseId));
        $document = $documentModel->save();

        if (!$document) {
            echo json_encode([
                "status" => false,
                "message" => "Algo salio mal no se pudo crear el pdf"
            ]);
            die;
        } else {
            echo json_encode([
                "status" => true,
                "message" => "Documento creado con éxito",
                "id" => $document
            ]);
        }
    }


    public static function download()
    {
        session_start();
        isAuth();

        $id = $_GET['id'] ?? null;
        if ($id === null) {
            echo json_encode([
                'status' => false,
                'questions' => "El parametro id no esta definido"
            ]);
            die;
        }

        $documentModel = new Document();
        $documentModel->setId(intval($id));
        $document = $documentModel->getById();

        if ($document) {

            // $url = SITE_URL . 'uploads/documents/' . $document->document_name;
            $url = SITE_URL . $document->relative_path . $document->name;
            // $url = stripslashes($document->file_path);

            echo json_encode([
                'status' => true,
                'message' => 'Documento encontrado',
                "filename" => $document->name,
                "url" => $url
            ], JSON_UNESCAPED_SLASHES);

            // $filePath = $document->file_path;
            // header('Content-Type: application/pdf');
            // header('Content-Disposition: attachment; filename="mi_archivo_real.pdf"');
            // readfile($filePath);
        } else {

            die(json_encode([
                'status' => false,
                'message' => 'Documento no encontrado'
            ]));
        }
    }

    public static function send($id)
    {
        session_start();
        isAuth();

        $id = $_GET['id'] ?? null;
        if ($id === null) {
            echo json_encode([
                'status' => false,
                'message' => "El parametro id no esta definido"
            ]);
            die;
        }

        $documentModel = new Document();
        $documentModel->setId(intval($id));
        $document = $documentModel->getById();

        if ($document) {

            $recipient = $_SESSION['email'];
            $subject = $document->name;
            $message = "<h1>Hola " . $_SESSION['fullname'] . "</h1><p>Se ha adjuntado tu documento.</p>";

            $result = EmailSender::sendEmail($recipient, $subject, $message, $document->absolute_path, $document->name);

            if ($result) {

                echo json_encode([
                    'status' => true,
                    'message' => "Se mando el correo con exito revisa tu bandeja de entrada o carpeta de spam"
                ]);
                die;
            } else {
                echo json_encode([
                    'status' => false,
                    'message' => "No se pudo mandar el correo"
                ]);
                die;
            }
        } else {
            echo json_encode([
                'status' => false,
                'message' => "El documento no existe"
            ]);
            die;
        }
    }
}
