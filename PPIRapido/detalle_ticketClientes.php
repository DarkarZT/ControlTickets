<?php
// Incluir el middleware primero para proteger la página
require_once __DIR__ . '/middleware/AuthMiddleware.php';

$authMiddleware = new AuthMiddleware();

// Procesar la solicitud a través del middleware
$authMiddleware->handle($_SERVER, function ($request) {
    // Si el usuario no está autenticado, el middleware redirigirá a login.php y el script se detendrá aquí.
});

// Incluye el archivo select.php para usar las funciones
$selectPath = realpath(__DIR__ . '/Metodos/select.php');
if ($selectPath && file_exists($selectPath)) {
    require_once $selectPath;
} else {
    die('Error: No se pudo encontrar el archivo select.php en la ruta: ' . __DIR__ . '/../Metodos/select.php');
}

// Obtén el ID del ticket desde el formulario POST
$ticketId = 1; //$_POST['id_ticket'] ?? $_GET['id_ticket'] ?? null;
if ($ticketId === null) {
    die('Error: No se recibió el ID del ticket.');
}

// Obtén una instancia de la conexión PDO
$pdo = conection(); // Asegúrate de que la función conection() está definida en conexion.php

// Llama a la función para obtener el ticket por ID
$ticket = obtenerTicketPorId($pdo, $ticketId);
$nombreUsuario = obtenerNombreUsuarioPorId($pdo, $ticket['usuario_ticket']);
$nombreAgente = obtenerNombredelAgenteONo($pdo, $ticket['agente_asignado_ticket']);
$areas = areas_tickets($pdo);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Ticket</title>
    <link rel="stylesheet" href="cssAlternative/detalle_ticketO.css">
    <link rel="stylesheet" href="cssAlternative/nav.css">
    <link rel="stylesheet" href="cssAlternative/chatbotDetalles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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

    <div class="container mb-4 text-center">
        <table class="ticket-table">
            <tr>
                <th colspan="2" style="text-align: center;">Detalle de Ticket</th>
            </tr>
            <tr>
                <th>ID del Ticket</th>
                <td><?php echo htmlspecialchars($ticket['id_ticket'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>Usuario</th>
                <td><input type="text" name="usuario_ticket" value="<?php echo htmlspecialchars($nombreUsuario ?? '', ENT_QUOTES, 'UTF-8'); ?>" disabled></td>
            </tr>
            <tr>
                <th>Área</th>
                <td>
                    <select name="area_ticket" id="seleccionador" disabled>
                        <?php foreach ($areas as $area): ?>
                            <option value="<?php echo htmlspecialchars($area['id_area'], ENT_QUOTES, 'UTF-8'); ?>"
                                <?php echo ($ticket['area_ticket'] == $area['id_area']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($area['nombre_area'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Correo Electrónico</th>
                <td><input type="email" name="correo_electronico" value="<?php echo htmlspecialchars($ticket['correo_electronico'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" disabled></td>
            </tr>
            <tr>
                <th>Descripción</th>
                <td><textarea name="descripcion_ticket" disabled><?php echo htmlspecialchars($ticket['descripcion_ticket'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></td>
            </tr>
            <tr>
                <th>Fecha de Creación</th>
                <td><?php echo htmlspecialchars($ticket['fecha_creacion_ticket'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>Fecha de Cierre</th>
                <td><?php echo htmlspecialchars($ticket['fecha_cierre_ticket'] ?? 'Abierto', ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
                <th>Agente Asignado</th>
                <td>
                    <input type="text" name="agente_asignado_ticket" value="<?php echo htmlspecialchars($nombreAgente ?? '', ENT_QUOTES, 'UTF-8'); ?>" disabled>
                </td>
            </tr>
        </table>

        <a href="provideTicketsCliente.php" class="btn btn-primary mt-3">Regresar a la Lista de Tickets</a>

    </div>
    <div class="asistencia-virtual">
        <div class="chatbot" id="chatbot" onclick="toggleOptions()">
            <img class="chatbot__img" src="images/logoPinguino.png" alt="Asistente Virtual" width="40" height="40">
            <span class="chatbot__text">Asistente virtual</span>
        </div>

        <div class="chatbot-options" id="chatbot-options" style="display: none;">
            <ul class="floatingMenu">
                <li>
                    <button class="option-button" onclick="toggleFAQ()">FAQ</button>
                    <ul id="faq-suboptions" style="display: none; padding-left: 20px;">
                        <li><a href="conocenos.php" class="suboption-link">Contáctanos</a></li>
                        <li><a href="#" class="suboption-link">Preguntas Frecuentes</a></li>
                    </ul>
                </li>
                <li>
                    <button id="chatbotButton" class="option-button" onclick="toggleChatbot()">Chatbot</button>
                </li>
                <li>
                    <button id="theme-toggle-button" class="option-button" onclick="toggleTheme()">🌙 Cambiar Tema</button>
                </li>
            </ul>
        </div>
    </div>

    <!-- La ventana del chatbot -->
    <div class="chat-window" id="chat-window" style="display: none;">
        <div class="chat-header">
            <h2>PingüiChat</h2>
            <button class="back-button" onclick="toggleChatbot()">&#10006;</button>
        </div>

        <div class="chat-body" id="chat-body">
            <div class="bot-message">
                <span class="message">👋 Hola! ¿Como puedo ayudarte? </span>
            </div>
            <div class="user-options">
                <button class="option-button" onclick="sendMessage('I have a question')">I have a question</button>
                <button class="option-button" onclick="sendMessage('Tell me more')">Tell me more</button>
            </div>
        </div>


    </div>
    <script src="js/chatbot.js"></script>
    <script src="js/themeTicketsC.js"></script>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/670e918c2480f5b4f58dcf91/1ia8eh3go';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>

    <footer>
        <p>&copy; 2023 Pinguino Corp. Todos los derechos reservados.</p>

    </footer>


    <!--End of Tawk.to Script-->

</body>

</html>