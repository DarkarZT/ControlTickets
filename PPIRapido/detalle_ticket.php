<?php
// Incluir el middleware primero para proteger la página
require_once __DIR__ . '/middleware/AuthMiddleware.php';

require __DIR__ . '/sesionUser/session_check.php'; // Ajusta la ruta según la ubicación de session_check.php

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
$ticketId = $_POST['id_ticket'] ?? $_GET['id_ticket'] ?? null;
if ($ticketId === null) {
    die('Error: No se recibió el ID del ticket.');
}

// Obtén una instancia de la conexión PDO
$pdo = conection(); // Asegúrate de que la función conection() está definida en conexion.php

// Llama a la función para obtener el ticket por ID
$ticket = obtenerTicketPorId($pdo, $ticketId);
$areas = areas_tickets($pdo);
$agentes = obtenerAgentes($pdo, $usuarioRol, $usuarioId);
$nombreUsuario = obtenerNombreUsuarioPorId($pdo, $ticket['usuario_ticket']);
$comentarios = obtenerComentariosPorTicket($pdo, $ticketId);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Ticket</title>
    <link rel="stylesheet" href="cssAlternative/detalle_ticket.css">
    <link rel="stylesheet" href="cssAlternative/nav.css">
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
    <button id="theme-toggle-button">🌙</button> <!-- Botón para cambiar el tema -->

    <div class="container mb-4 text-center">
        <!-- Formulario principal para editar ticket -->
        <?php
        $estadoTicket = $ticket['estado_ticket'] ?? null; // Asigna el estado del ticket

        // Si el estado del ticket es 2, deshabilitar los campos
        $disabled = ($estadoTicket == 2) ? 'disabled' : '';
        ?>

        <form action="/Metodos/update_ticket.php" method="POST" class="mb-4">
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
                    <td>
                        <input type="text" name="usuario_ticket" value="<?php echo htmlspecialchars($nombreUsuario ?? '', ENT_QUOTES, 'UTF-8'); ?>" disabled>
                    </td>
                </tr>
                <tr>
                    <th>Área</th>
                    <td>
                        <select name="area_ticket" id="seleccionador" <?php echo $disabled; ?>>
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
                    <td><input type="email" name="correo_electronico" value="<?php echo htmlspecialchars($ticket['correo_electronico'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" <?php echo $disabled; ?>></td>
                </tr>
                <tr>
                    <th>Descripción</th>
                    <td><textarea name="descripcion_ticket" <?php echo $disabled; ?>><?php echo htmlspecialchars($ticket['descripcion_ticket'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></td>
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
                        <select name="agente_asignado_ticket" id="seleccionador" <?php echo $disabled; ?>>
                            <?php foreach ($agentes as $agente): ?>
                                <option value="<?php echo htmlspecialchars($agente['id_usuario'], ENT_QUOTES, 'UTF-8'); ?>"
                                    <?php echo ($ticket['agente_asignado_ticket'] == $agente['id_usuario']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($agente['nombre_usuario'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="id_ticket" value="<?php echo htmlspecialchars($ticket['id_ticket'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit" class="refresh-button" <?php echo $disabled; ?>>Guardar Cambios</button>
        </form>


        <!-- Formulario para actualizar el estado del ticket a 2 -->
        <form action="../Metodos/update_estado_ticket.php" method="POST" id="cerrarTicketForm">
            <input type="hidden" name="id_ticket" value="<?php echo htmlspecialchars($ticket['id_ticket'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="nuevo_estado" value="2">
            <button type="button" class="refresh-button mb-4" style="background-color: red;" data-bs-toggle="modal" data-bs-target="#confirmacionModal">Marcar como Resuelto</button>
        </form>

        <!-- Modal de confirmación -->
        <div class="modal fade" id="confirmacionModal" tabindex="-1" aria-labelledby="confirmacionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmacionModalLabel">Confirmar Acción</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro que deseas marcar este ticket como resuelto?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" onclick="document.getElementById('cerrarTicketForm').submit();">Cerrar Ticket</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario para agregar comentarios -->


        <!-- Sección para mostrar los comentarios existentes -->
        <?php
        $itemsPerPage = 4;
        $totalItems = count($comentarios);
        $totalPages = ceil($totalItems / $itemsPerPage);
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $offset = ($page - 1) * $itemsPerPage;
        $comentariosToDisplay = array_slice($comentarios, $offset, $itemsPerPage);
        ?>
        <div class="comments-section">
            <h3>Comentarios</h3>
            <div id="comments-container">
                <?php foreach ($comentariosToDisplay as $comentario): ?>
                    <div class="comment">
                        <p class="user-namea"><strong><?php echo htmlspecialchars($comentario['nombre_usuario'], ENT_QUOTES, 'UTF-8'); ?>:</strong></p>
                        <p class="user-comment"><?php echo htmlspecialchars($comentario['comentario'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="comment-date"><small><?php echo htmlspecialchars($comentario['fecha_comentario'], ENT_QUOTES, 'UTF-8'); ?></small></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?id_ticket=<?php echo $ticketId; ?>&page=<?php echo $page - 1; ?>" class="page-link">Anterior</a>
                <?php endif; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?id_ticket=<?php echo $ticketId; ?>&page=<?php echo $page + 1; ?>" class="page-link">Siguiente</a>
                <?php endif; ?>
            </div>

            <!-- Formulario para agregar comentarios -->
            <form action="/Metodos/add_comment.php" method="POST" class="mb-4 mt-2">
                <input type="hidden" name="id_ticket" value="<?php echo htmlspecialchars($ticket['id_ticket'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <!-- Textarea con margen inferior de 3 -->
                <textarea name="comentario" rows="3" placeholder="Agregar un comentario" required class="form-control mb-2" <?php echo $disabled; ?>></textarea>

                <!-- Botón con clase Bootstrap estándar -->
                <button type="submit" class="btn btn-primary" <?php echo $disabled; ?>>Agregar Comentario</button>
            </form>
        </div>

    </div>

    <footer>
        <p>&copy; 2023 Pinguino Corp. Todos los derechos reservados.</p>
    </footer>

    <script src="js/themeIndex.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-qPnhR6myfH26JgPCTaQrZnjqfyPnx2ExlpkUeYL8tOkzM+vIm3o7GXYgExQf6ka5" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-dH+FgFvr/yG7RyIToQ4qkmJBIAlCIwIYBi9VLC8T6UMn3Fs4rxH+V/1g8B2aWQej" crossorigin="anonymous"></script>
</body>

</html>