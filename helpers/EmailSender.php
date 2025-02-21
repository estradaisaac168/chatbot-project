<?php 

namespace Helpers;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender {
    public static function sendEmail($recipient, $subject, $message, $filePath, $name_file) {
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'estradaisaac168@gmail.com';
            $mail->Password   = 'uimycmxvllhcepqp';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Remitente y destinatario
            $mail->setFrom('estradaisaac168@gmail.com', 'Chatbot RRHH');
            $mail->addAddress($recipient);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            // Adjuntar archivo
            $mail->addAttachment($filePath, $name_file);

            // Enviar el correo
            $mail->send();
            return true;
        } catch (Exception $e) {
            return 'Error al enviar el correo: ' . $mail->ErrorInfo;
        }
    }
}
