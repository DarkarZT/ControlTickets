<?php
require_once __DIR__ . '/../database/conexion.php';
require __DIR__ . '/PHPMailer.php';
require __DIR__ . '/SMTP.php';
require __DIR__ . '/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$pdo = conection(); // Asegúrate de usar el nombre correcto de la función

// Obtener el correo electrónico del usuario que creó el ticket
$id_ticket = 1;
$stmt = $pdo->prepare('SELECT correo_empresarial_usuario FROM usuarios WHERE id_usuario = (SELECT usuario_ticket FROM tickets WHERE id_ticket = :id_ticket)');
$stmt->execute([':id_ticket' => $id_ticket]);
$correoUsuario = $stmt->fetchColumn();

// Inicializar comentario (ejemplo)
$comentario = "Este es un comentario de ejemplo.";

if ($correoUsuario) {
    // Configurar PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->SMTPDebug = 2; // Mostrar depuración detallada
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'darkarwhitee@gmail.com'; // Cambia esto con tu correo
        $mail->Password = 'vfjp tbkn amou xqyp'; // Cambia esto con tu contraseña o usa una contraseña de aplicación
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('darkarwhitee@gmail.com', 'Empresa muy seria');
        $mail->addAddress('carlos_meneses23222@elpoli.edu.co'); // Correo del usuario que creó el ticket

        $mail->isHTML(true);
        $mail->Subject = 'Nuevo comentario en tu ticket #' . $id_ticket;
        $mail->Body    = 'Se ha agregado un nuevo comentario en tu ticket:<br><br>' . nl2br($comentario);
        $mail->AltBody = 'Se ha agregado un nuevo comentario en tu ticket:' . "\n\n" . $comentario;

        $mail->send();
        echo 'Correo enviado correctamente.';
    } catch (Exception $e) {
        echo "No se pudo enviar el correo. Error de PHPMailer: {$mail->ErrorInfo}";
    }
}
?>
