<?php
require __DIR__ . '/sesionUser/session_check.php'; // Ajusta la ruta según la ubicación de session_check.php

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conócenos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="cssAlternative/conocenos.css">
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
    <header>
        <div class="container mt-4 mb-4">
            <h1 class="text-center">Conócenos</h1>
        </div>
    </header>

    <div class="container mb-4">
        <div class="content mb-4">
            <h2>Sobre Nosotros</h2>
            <p>
                Bienvenido a nuestra página de "Conócenos". Aquí puedes conocer más sobre quiénes somos, nuestra misión y visión.
                Somos una empresa dedicada a ofrecer servicios de alta calidad, comprometidos con la satisfacción de nuestros clientes.
            </p>
            <p>
                Fundada en [año], hemos trabajado con una variedad de clientes y proyectos, siempre con el objetivo de superar sus expectativas.
                Nuestro equipo está formado por profesionales altamente cualificados y apasionados por su trabajo.
            </p>
            <h2>Misión</h2>
            <p>
                Nuestra misión es proporcionar soluciones innovadoras que ayuden a nuestros clientes a alcanzar sus objetivos y
                mejorar su presencia en el mercado.
            </p>
            <h2>Visión</h2>
            <p>
                Nuestra visión es ser líderes en nuestro sector, reconocidos por la calidad de nuestros servicios y el compromiso
                con la excelencia.
            </p>
        </div>

        <div class="content mb-4">
            <h2>Envíanos un Mensaje</h2>
            <form action="procesar_contacto.php" method="post">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="mensaje">Mensaje:</label>
                    <textarea id="mensaje" name="mensaje" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Enviar</button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <footer>
        <p>&copy; 2024 Control-TP. Todos los derechos reservados.</p>
    </footer>

    <script src="js/themeConocenos.js"></script>
</body>

</html>