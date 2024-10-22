<?php
session_start(); // Asegúrate de iniciar la sesión al principio del archivo
if (isset($_SESSION['usuario_id'])) {
    // Redirige al usuario a la página principal si ya ha iniciado sesión
    header("Location: index.php"); 
    exit;
}
require_once __DIR__ . '/middleware/AuthMiddleware.php';

require __DIR__ . "/Metodos/crearUsuarios.php";

$pdo = conection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['registro_usuario'])) {
        $nombre = $_POST['nombre'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $validacion_agente = $_POST['agente_validation'] ?? '';
        $rol = $_POST['rol'] ?? 1;
        $es_agente = isset($_POST['es_agente']) ? 1 : 0;

        echo "Nombre: $nombre, Email: $email, Rol: $rol, Es Agente: $es_agente"; // Mensaje de depuración

        $pdo = conection();
        $nuevoUsuarioId = registrarUsuario($pdo, $nombre, $email, $password, $rol, $es_agente, $agente_validation);

        if ($nuevoUsuarioId) {
            echo "Usuario registrado exitosamente con ID: " . htmlspecialchars($nuevoUsuarioId, ENT_QUOTES, 'UTF-8');
        } else {
            echo "Error al registrar el usuario.";
        }
    }
}
require_once  __DIR__ . "/Metodos/selecRol.php";
$roles = rolesCh($pdo);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="cssAlternative/registro.css">
</head>

<body>

    <div class="container">
        <h2 class="text-center">Registro de Usuario</h2>
        <form action="/Metodos/crearUsuarios.php" method="POST">
            <input type="hidden" name="registro_usuario" value="1">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" maxlength="50" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo:</label>
                <input type="email" id="email" name="email" maxlength="50" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña:</label>
                <input type="password" id="password" name="password" maxlength="255" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="rol" class="form-label">Rol:</label>
                <select id="rol" name="rol" class="form-select" required>
                    <option value="" selected disabled>Selecciona un rol</option> <!-- Opción por defecto -->
                    <?php if (!empty($roles) && is_array($roles)): ?>
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?= htmlspecialchars($rol['id_rol'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($rol['nombre_rol'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No hay roles disponibles</option>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Contenedor para el campo adicional -->
            <div id="agente-fields" class="mb-3" style="display: none;">
                <label for="agente_info" id="agente-label" class="form-label">Código de Verificación</label>
                <input type="text" id="agente_validation" name="agente_validation" class="form-control">
            </div>

            <script>
                // Obtener elementos del DOM
                const selectRol = document.getElementById('rol');
                const agenteFields = document.getElementById('agente-fields');
                const agenteLabel = document.getElementById('agente-label');
                const rolesAgente = ['Administrador', 'Agente']; // Definir roles que requieren validación

                // Escuchar el cambio en el select de rol
                selectRol.addEventListener('change', function() {
                    const selectedRol = selectRol.options[selectRol.selectedIndex].text; // Obtener texto seleccionado

                    if (rolesAgente.includes(selectedRol)) {
                        agenteFields.style.display = 'block'; // Mostrar el campo de verificación

                        // Cambiar el texto del label según el rol seleccionado
                        if (selectedRol === 'Administrador') {
                            agenteLabel.textContent = 'Digita el código de verificación del Administrador';
                        } else if (selectedRol === 'Agente') {
                            agenteLabel.textContent = 'Digita el código de verificación del Agente';
                        }
                    } else {
                        agenteFields.style.display = 'none'; // Ocultar el campo de verificación si no es Agente o Administrador
                    }
                });
            </script>
            <button type="submit" class="refresh-button w-100">Registrar</button>
        </form>

    </div>

    <button id="theme-toggle-button" class="btn btn-secondary">🌙</button>
    <script src="js/themeIndex.js"></script>
    <script src="js/verificacion.js"></script>

</body>

</html>