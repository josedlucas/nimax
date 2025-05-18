<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header('Content-Type: application/json');

$response = ["success" => false, "message" => ""];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cambiar allowedDomain para que sea un array de dominios permitidos
    $allowedDomains = ['taxim.local', 'taxim.com.ar']; // Cambiar por tus dominios permitidos
    if (empty($_SERVER['HTTP_REFERER']) || !in_array(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST), $allowedDomains)) {
        $response['message'] = 'Solicitud no permitida desde este dominio.';
        echo json_encode($response);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    $name = $data['name'] ?? '';
    $nickname = $data['nickname'] ?? '';
    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? '';
    $message = $data['message'] ?? '';
    $captchaToken = $data['captcha'] ?? '';

    if (empty($name) || empty($nickname) || empty($phone) || empty($email) || empty($message) || empty($captchaToken)) {
        $response['message'] = 'Todos los campos son obligatorios, incluido el CAPTCHA.';
        echo json_encode($response);
        exit;
    }

    // Validar el CAPTCHA
    $captchaSecret = '6LeyjRUrAAAAAFuL6tBa6F_oCuzxEGeexaUDW55l'; // Cambiar por tu clave secreta de reCAPTCHA
    $captchaResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$captchaSecret&response=$captchaToken");
    $captchaResult = json_decode($captchaResponse, true);

    if (empty($captchaResult['success']) || !$captchaResult['success']) {
        $response['message'] = 'Error en la validación del CAPTCHA.';
        echo json_encode($response);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'taxim@aunovit.com';
        $mail->Password = 'nlxe ifpz upsb cwnb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('taxim@aunovit.com', 'Formulario de Contacto');
        $mail->addAddress('taxim@aunovit.com'); // Cambiar por el destinatario

        $mail->isHTML(true);
        $mail->Subject = 'Nuevo mensaje del formulario de contacto';
        $mail->Body = "<h1>Nuevo mensaje</h1><p><strong>Nombre:</strong> $name</p><p><strong>Apellido:</strong> $nickname</p><p><strong>Teléfono:</strong> $phone</p><p><strong>Email:</strong> $email</p><p><strong>Mensaje:</strong> $message</p>";

        $mail->send();

        $response['success'] = true;
        $response['message'] = 'Mensaje enviado exitosamente.';
    } catch (Exception $e) {
        // Registro detallado del error
        error_log('Error al enviar el mensaje: ' . $mail->ErrorInfo);
        error_log('Detalles del error: ' . $e->getMessage());

        $response['message'] = 'Error al enviar el mensaje: ' . $mail->ErrorInfo . '. Por favor, verifica la configuración SMTP o consulta los registros de errores.';
    }
} else {
    $response['message'] = 'Método no permitido.';
}

echo json_encode($response);