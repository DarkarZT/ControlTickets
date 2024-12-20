<?php
require __DIR__ . '/sesionUser/session_check.php'; // Ajusta la ruta según la ubicación de session_check.php

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Página de Inicio</title>
    <link rel="stylesheet" href="cssAlternative/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="cssAlternative/nav.css">
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

    <h1 class="Personal-center mt-4">Bienvenidos a Control-TP</h1>

    <div id="main-content">
        <div class="border rounded mx-2 p-3">
            <h2>¿Qué es Control-TP?</h2>
            <p>
                Control-TP es un gestor de tickets diseñado para simplificar la gestión de solicitudes, mejorando la interacción entre usuarios y equipos de soporte con agilidad y eficiencia.
            </p>
        </div>
        <div class="sidebar border rounded mx-2 p-4">
            
            <p>
                En esta sección puedes colocar enlaces adicionales, anuncios, o cualquier contenido relevante que prefieras que esté en la barra lateral.
            </p>
        </div>
    </div>

    <div id="main-content">
        <div class="border rounded mx-2 p-3">
            <h2>Conexión con tus candidatos</h2>
            <p>
                En esta sección, puedes conectar directamente con tus candidatos, garantizando una interacción personalizada y efectiva.
            </p>
        </div>
        <div class="sidebar border rounded mx-2 p-4">
            
            <p>
                En esta sección puedes colocar enlaces adicionales, anuncios, o cualquier contenido relevante que prefieras que esté en la barra lateral.
            </p>
        </div>
    </div>

    <div id="main-content">
        <div class="border rounded mx-2 p-3">
            <h2>¿Conexión por chat en línea?</h2>
            <p>
                Somos un aliado importante para tu marca, brindando cercanía y agilidad a través de nuestra plataforma de chat en línea para atender las necesidades de tus usuarios.
            </p>
        </div>
        <div class="sidebar border rounded mx-2 p-4">
            
            <p>
                En esta sección puedes colocar enlaces adicionales, anuncios, o cualquier contenido relevante que prefieras que esté en la barra lateral.
            </p>
        </div>
    </div>

    <div id="main-content">
        <div class="border rounded mx-2 p-3">
            <h2>¿Por qué escogernos?</h2>
            <p>
                Porque hacemos que la tecnología sea sencilla y práctica, para que te concentres en lo que realmente importa: tus usuarios. ¡Nos gusta trabajar contigo, no para ti!
            </p>
        </div>
        <div class="sidebar border rounded mx-2 p-4">
            
            <p>
                En esta sección puedes colocar enlaces adicionales, anuncios, o cualquier contenido relevante que prefieras que esté en la barra lateral.
            </p>
        </div>
    </div>


    <footer>
        <p>Copyright © 2024 Control-TP</p>
    </footer>

    <script src="js/themeIndex.js"></script>
</body>

</html>