<?php
session_start(); // Asegúrate de iniciar la sesión al principio del archivo

// Verifica si el usuario ya está autenticado
if (isset($_SESSION['usuario_id'])) {
    // Redirige al usuario a la página principal si ya ha iniciado sesión
    header("Location: index.php"); 
    exit;
}

require __DIR__ . '/Metodos/login_M.php'; 
require_once __DIR__ . '/middleware/AuthMiddleware.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $email = $_POST['username'];
        $password = $_POST['password'];

        $pdo = conection(); // Conexión a la base de datos
        if (!iniciarSesion($pdo, $email, $password)) {
            $error = "Correo electrónico o contraseña incorrectos.";
        }
    } else {
        $error = "Por favor, complete todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="cssAlternative/master.css">
</head>

<body>

    <a href="javascript:history.back()" class="back-arrow">&#8592;</a>
    <button id="theme-toggle" class="btn btn-secondary">🌙</button>

    <div class="login-container">
        <h2 class="text-center">Iniciar Sesión</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <button type="submit">Iniciar Sesión</button>
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger mt-3">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            <div class="text-center">
                <p><a href="#">¿Olvidaste tu contraseña?</a></p>
                <p>¿No tienes una cuenta? <a href="registro.php">Regístrate</a></p>
            </div>
        </form>
    </div>

    <script src="js/arrow.js"></script>
    <script src="js/theme.js"></script>
</body>
</html>
