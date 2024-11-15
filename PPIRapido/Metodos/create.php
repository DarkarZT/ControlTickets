<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/../database/conexion.php';

function insertData($pdo, $usuario, $area, $descripcion, $correoElectronico) {
    echo "Valores recibidos en insertData:<br>";
    echo "Usuario: " . htmlspecialchars($usuario) . "<br>";
    echo "Área: " . htmlspecialchars($area) . "<br>";
    echo "Correo Electrónico: " . htmlspecialchars($correoElectronico) . "<br>";
    echo "Descripción: " . htmlspecialchars($descripcion) . "<br>";
    
    $sql = "INSERT INTO Tickets (usuario_ticket, area_ticket, correo_electronico, descripcion_ticket, fecha_creacion_ticket) VALUES (:usuario, :area, :correoElectronico, :descripcion, NOW())";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':area', $area);
        $stmt->bindParam(':correoElectronico', $correoElectronico);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->execute();
        header("Location: ../index.php"); // Cambia "success.php" a la URL deseada
        exit();        
    } catch (\PDOException $e) {
        echo "Error al insertar el dato: " . $e->getMessage();
    }
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos enviados desde el formulario
    $usuario = $_POST['usuario'];
    $area = $_POST['area'];
    $descripcion = $_POST['descripcion'];
    $correoElectronico = $_POST['email'];

    // Conectar a la base de datos
    $pdo = conection();

    // Insertar los datos en la base de datos
    insertData($pdo, $usuario, $area, $descripcion, $correoElectronico);
}
?>
