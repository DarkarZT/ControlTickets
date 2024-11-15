<?php
session_start();
ob_start(); // Iniciar el almacenamiento en búfer de salida

// Incluye el archivo de conexión y PHPMailer
require_once __DIR__ . '/../database/conexion.php';
require __DIR__ . '/PHPMailer.php';
require __DIR__ . '/SMTP.php';
require __DIR__ . '/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Obtener conexión PDO
$pdo = conection();

$id_ticket = $_POST['id_ticket'] ?? null;
$comentario = $_POST['comentario'] ?? null;
$id_usuario = $_SESSION['usuario_id'] ?? null;

if ($id_ticket === null || $comentario === null || $id_usuario === null) {
    die('Error: ID del ticket, comentario o ID del usuario faltante.');
}

// Verifica que el usuario existe
$stmt = $pdo->prepare('SELECT id_usuario, correo_empresarial_usuario FROM usuarios WHERE id_usuario = :id_usuario');
$stmt->execute([':id_usuario' => $id_usuario]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario === false) {
    die('Error: El usuario no existe.');
}

// Insertar el comentario en la base de datos
$sql = 'INSERT INTO comentarios (id_ticket, id_usuario, comentario) VALUES (:id_ticket, :id_usuario, :comentario)';
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id_ticket' => $id_ticket,
    ':id_usuario' => $id_usuario,
    ':comentario' => $comentario
]);

// Enviar notificación por correo electrónico al usuario que creó el ticket
try {
    // Obtener el correo electrónico del usuario que creó el ticket
    $stmt = $pdo->prepare('SELECT correo_empresarial_usuario FROM usuarios WHERE id_usuario = (SELECT usuario_ticket FROM tickets WHERE id_ticket = :id_ticket)');
    $stmt->execute([':id_ticket' => $id_ticket]);
    $correoUsuario = $stmt->fetchColumn();

    if ($correoUsuario) {
        // Configurar PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->SMTPDebug = 0; // Desactiva la depuración detallada para evitar problemas con headers
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'darkarwhitee@gmail.com'; // Cambia esto con tu correo
        $mail->Password = 'vfjp tbkn amou xqyp'; // Cambia esto con tu contraseña o usa una contraseña de aplicación
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('darkarwhitee@gmail.com', 'Empresa muy seria');
        $mail->addAddress($correoUsuario); // Correo del usuario que creó el ticket

        $mail->isHTML(true);
        $mail->Subject = 'Nuevo comentario en tu ticket #' . $id_ticket;
        $mail->Body = 'Se ha agregado un nuevo comentario en tu ticket:<br><br>' . nl2br($comentario);
        $mail->AltBody = 'Se ha agregado un nuevo comentario en tu ticket:' . "\n\n" . $comentario;

        $mail->send();
    }
} catch (Exception $e) {
    echo "No se pudo enviar el correo. Error de PHPMailer: {$mail->ErrorInfo}";
}

// Redirigir de vuelta al detalle del ticket
header('Location: /../' . urlencode($id_ticket));
exit;
?>
