<?php
require __DIR__ . '/../database/conexion.php';

function registrarUsuario($pdo, $nombre, $email, $password, $rol = 1, $es_agente = 0, $agente_validation = '') {
    // Verificar si el usuario ya existe
    $sqlVerificar = "SELECT COUNT(*) FROM usuarios WHERE correo_empresarial_usuario = :email";
    try {
        $stmtVerificar = $pdo->prepare($sqlVerificar);
        $stmtVerificar->bindParam(':email', $email, PDO::PARAM_STR);
        $stmtVerificar->execute();
        $existeUsuario = $stmtVerificar->fetchColumn();

        if ($existeUsuario > 0) {
            echo "El usuario con el correo electrónico $email ya está registrado.";
            return false;
        }
    } catch (PDOException $e) {
        echo "Error al verificar el usuario: " . $e->getMessage();
        return false;
    }

    // Insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (nombre_usuario, correo_empresarial_usuario, contrasena_usuario, id_rol, es_agente_usuario, fecha_creacion_usuario) 
            VALUES (:nombre, :email, :password, :rol, :es_agente, NOW())";
    try {
        $stmt = $pdo->prepare($sql);
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $passwordHashed, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
        $stmt->bindParam(':es_agente', $es_agente, PDO::PARAM_INT);
        $stmt->execute();
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        echo "Error al registrar el usuario: " . $e->getMessage();
        return false;
    }
}
?>

<?php
// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['registro_usuario'])) {
        // Obtener datos del formulario para registro de usuario
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $rol = $_POST['rol'] ?? 1; // Valor por defecto si no se envía
        $es_agente = isset($_POST['es_agente']) ? 1 : 0; // Si el checkbox está marcado
        $agente_validation = $_POST['agente_validation'] ?? '';
        $pdo = conection();
        $nuevoUsuarioId = registrarUsuario($pdo, $nombre, $email, $password, $rol, $es_agente, $agente_validation);

        if ($nuevoUsuarioId) {
            echo "Usuario registrado exitosamente con ID: " . htmlspecialchars($nuevoUsuarioId, ENT_QUOTES, 'UTF-8');
        } else {
            echo "Error al registrar el usuario.";
        }
    } elseif (isset($_POST['insert_ticket'])) {
        // Obtener datos del formulario para insertar ticket
        $usuario = $_POST['usuario'];
        $area = $_POST['area'];
        $descripcion = $_POST['descripcion'];
        $correoElectronico = $_POST['email'];

        $pdo = conection();
        var_dump($pdo); // Verifica que $pdo es una instancia de PDO

        insertData($pdo, $usuario, $area, $descripcion, $correoElectronico);
    }
}
?>