<?php

class AuthMiddleware {
    public function handle($request, $next) {
        // Iniciar la sesión si no está ya iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar si la sesión tiene un usuario autenticado
        if (isset($_SESSION['usuario_id'])) {
            // Si el usuario ya está autenticado y está accediendo a login.php o registro.php, redirigir a index.php
            if (in_array(basename($_SERVER['PHP_SELF']), ['login.php', 'registro.php'])) {
                header("Location: index.php"); // Redirigir a la página principal
                exit;
            }

            // El usuario está autenticado, continuar con la solicitud
            return $next($request);
        } else {
            // El usuario no está autenticado, redirigir al login si intenta acceder a páginas protegidas
            header("Location: login.php");
            exit;
        }
    }
}

