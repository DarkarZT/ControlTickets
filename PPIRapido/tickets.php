<?php
// Incluye el archivo del middleware y otros archivos necesarios
require_once __DIR__ . "/middleware/AuthMiddleware.php";
require_once __DIR__ . "/Metodos/select.php";
require_once __DIR__ . "/database/conexion.php";
require __DIR__ . '/sesionUser/session_check.php'; // Ajusta la ruta según la ubicación de session_check.php


// Instancia del middleware
$authMiddleware = new AuthMiddleware();

// Función anónima para continuar con la solicitud
$next = function ($request) {
    global $pdo; // Accede a la instancia PDO globalmente
    $areas = areas_tickets($pdo);

    // Incluye el HTML del formulario aquí
};

// Ejecuta el middleware
$authMiddleware->handle($_REQUEST, $next);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cssAlternative/nav.css">
    <link rel="stylesheet" href="cssAlternative/tickets.css">
    <link rel="stylesheet" href="cssAlternative/theme.css"> <!-- Agrega una hoja de estilos para el tema -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <title>Crear Ticket</title>
</head>

<body>
    <nav>
        <img src="images/logoPinguino.png" alt="Logo" width="50px">
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <form action="Metodos/logout.php" method="POST" style="display: inline;">
                <button type="submit" class="btn btn-danger">Salir</button>
            </form>
        <?php else: ?>
            <a href="login.php" class="btn btn-primary" style="margin-top: 7px; margin-right: 2px;">Login</a>
        <?php endif; ?>

        <a href="conocenos.php">Conocenos</a>

        <?php if (isset($usuarioRol)): ?>
            <?php if ($usuarioRol == 1 || $usuarioRol == 2): ?>
                <a href="provideTickets.php">Tickets</a>
            <?php elseif ($usuarioRol == 3): ?>
                <a href="provideTicketsCliente.php">Ver Tickets Cliente</a>
            <?php endif; ?>
        <?php endif; ?>
        <a href="index.php">Inicio</a>
    </nav>
    <button id="theme-toggle-button">🌙</button> <!-- Botón para cambiar el tema -->
    <div class="form-container">
        <h2>Crear Nuevo Ticket</h2>
        <form action="Metodos/create.php" method="POST">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="area">Área:</label>
                <select id="area" name="area" required>
                    <option value="">Selecciona un área</option>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?php echo $area['id_area']; ?>">
                            <?php echo htmlspecialchars($area['nombre_area']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit">Crear Ticket</button>
            </div>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Tu Empresa. Todos los derechos reservados.</p>
    </footer>

    <script src="js/themeTickets.js"></script> <!-- Incluye el script para manejar el cambio de tema -->
</body>

</html>