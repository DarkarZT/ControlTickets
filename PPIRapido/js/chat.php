<?php
require_once __DIR__ . '/../database/conexion.php';
require_once __DIR__ . '/conversaciones.php'; // Incluye el archivo de manejo de conversaciones
require __DIR__ . '/../sesionUser/session_check.php'; // Verifica la sesión del usuario

$pdo = conection();

// Asegúrate de que las variables de sesión estén definidas
if (!isset($usuarioId) || !isset($usuarioNombre) || !isset($usuarioRol)) {
    error_log("Las variables de sesión no están definidas.");
    echo json_encode(['status' => 'error', 'message' => 'Sesión no válida.']);
    exit;
}

// Almacena el id del cliente desde la sesión
$id_cliente = $usuarioId;
$id_asesor = 1; // Asigna el ID real del asesor

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Obtén el cuerpo de la solicitud en formato JSON
   $data = json_decode(file_get_contents("php://input"), true);

   // Asegúrate de que las claves 'remitente' y 'mensaje' estén definidas
   if (!isset($data['remitente']) || !isset($data['mensaje'])) {
       echo json_encode(['status' => 'error', 'message' => 'Datos inválidos.']);
       exit;
   }

    // Almacena los valores del JSON
    $remitente = $data['remitente'];
    $mensaje = $data['mensaje'];

    // Verificar que $id_cliente no sea null
    if (!$id_cliente) {
        echo json_encode(['status' => 'error', 'message' => 'ID de cliente no válido.']);
        exit;
    }

    // Iniciar una nueva conversación o recuperar la existente
    $id_conversacion = iniciarConversacion($id_cliente, $id_asesor, $pdo);

    // Enviar el mensaje asociado a esa conversación
    $stmt = $pdo->prepare("INSERT INTO mensajes (remitente, mensaje, fecha, id_conversacion) VALUES (?, ?, NOW(), ?)");
    $stmt->execute([$remitente, $mensaje, $id_conversacion]);

    echo json_encode(['status' => 'success', 'id_conversacion' => $id_conversacion]); // Retorna el ID de la conversación
    exit;
}

// Para recuperar mensajes
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id_conversacion = $_GET['id_conversacion']; // Asegúrate de que este ID se pase adecuadamente
    $stmt = $pdo->prepare("SELECT * FROM mensajes WHERE id_conversacion = ? ORDER BY fecha ASC");
    $stmt->execute([$id_conversacion]);
    $mensajes = $stmt->fetchAll();
    echo json_encode($mensajes);
    exit;
}
?>
