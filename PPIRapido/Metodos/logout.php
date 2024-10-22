<?php
session_start(); // Inicia la sesión

// Verifica si el usuario está autenticado
if (isset($_SESSION['usuario_id'])) {
    // Elimina todas las variables de sesión
    $_SESSION = [];

    // Si se desea eliminar la cookie de la sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"], $params["secure"], $params["httponly"]
        );
    }

    // Finalmente, destruye la sesión
    session_destroy();
}

// Redirige al usuario a la página principal
header("Location: ../index.php");
exit;