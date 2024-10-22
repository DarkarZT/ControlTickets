<?php
require __DIR__ . '/../database/conexion.php'; // Incluye la conexión a la base de datos

function iniciarSesion($pdo, $email, $password) {
    // Consulta SQL para obtener el usuario por correo empresarial
    $sql = "SELECT * FROM usuarios WHERE correo_empresarial_usuario = :email LIMIT 1";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($password, $usuario['contrasena_usuario'])) {
            // Si la contraseña es correcta, iniciar la sesión del usuario
            session_start();
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_nombre'] = $usuario['nombre_usuario'];
            $_SESSION['usuario_rol'] = $usuario['id_rol'];
            $_SESSION['es_agente'] = $usuario['es_agente_usuario'];

            // Redirigir al usuario al dashboard o página principal
            header("Location: ../index.php");
            exit;
        } else {
            // Si las credenciales son incorrectas
            return false;
        }
    } catch (PDOException $e) {
        return false;
    }
}
?>
