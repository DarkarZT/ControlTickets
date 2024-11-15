<?php

// Habilita la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluye el archivo que contiene el middleware
require_once __DIR__ . "/middleware/AuthMiddleware.php";
require __DIR__ . '/sesionUser/session_check.php'; // Ajusta la ruta según la ubicación de session_check.php

// Instancia y aplica el middleware
$middleware = new AuthMiddleware();
$middleware->handle($_REQUEST, function ($request) {
    // Aquí iría el código que se ejecuta si el usuario está autenticado.
    return $request;
});

// Incluye el archivo select.php para usar las funciones de conexión y selección de tickets
require "../PPIRapido/Metodos/select.php";

// Establece el número de tickets por página
$ticketsPorPagina = 25;

// Obtén la página actual desde la URL, si no está presente, usa la primera página
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $ticketsPorPagina;

// Obtén una instancia de la conexión PDO
$pdo = conection();

// Obtén el total de tickets
$totalTickets = countTotalTickets($pdo);

// Calcula el número total de páginas
$totalPaginas = ceil($totalTickets / $ticketsPorPagina);

// Obtén los tickets para la página actual
$tickets = selectTicketsPaginados($pdo, $ticketsPorPagina, $offset);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Tickets</title>
    <link rel="stylesheet" href="cssAlternative/provideTicket.css">
    <link rel="stylesheet" href="cssAlternative/nav.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

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
    <div class="container mb-4">
        <div class="content mb-4">
            <table class="ticket-table">
                <thead>
                    <tr>
                        <th colspan="7" style="text-align: center;">Administrador de Tickets</th>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Solicitante</th>
                        <th>Área</th>
                        <th>Descripción</th>
                        <th>Fecha de Creación</th>
                        <th>Tiempo Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="ticket-table-body">
                    <?php
                    if (!empty($tickets)) {
                        foreach ($tickets as $ticket) {
                            $id_ticket = htmlspecialchars($ticket['id_ticket'] ?? '', ENT_QUOTES, 'UTF-8');
                            $usuario_ticket = htmlspecialchars($ticket['usuario_ticket'] ?? '', ENT_QUOTES, 'UTF-8');
                            $area_ticket = htmlspecialchars($ticket['area_ticket'] ?? '', ENT_QUOTES, 'UTF-8');
                            $descripcion_ticket = htmlspecialchars($ticket['descripcion_ticket'] ?? '', ENT_QUOTES, 'UTF-8');
                            $fecha_creacion_ticket = htmlspecialchars($ticket['fecha_creacion_ticket'] ?? '', ENT_QUOTES, 'UTF-8');
                            $fecha_cierre_ticket = htmlspecialchars($ticket['fecha_cierre_ticket'] ?? '', ENT_QUOTES, 'UTF-8');

                            $horas = $minutos = ''; // Inicializar variables

                            if ($fecha_creacion_ticket && $fecha_cierre_ticket) {
                                // Convertir las fechas a objetos DateTime
                                $fechaCreacion = new DateTime($fecha_creacion_ticket);
                                $fechaCierre = new DateTime($fecha_cierre_ticket);

                                // Calcular la diferencia en segundos
                                $diferencia = $fechaCreacion->diff($fechaCierre);

                                // Obtener la diferencia en horas y minutos
                                $horas = $diferencia->h + ($diferencia->days * 24); // Agregar las horas de los días completos
                                $minutos = $diferencia->i;
                            }
                    ?>
                            <tr>
                                <td><?php echo $id_ticket; ?></td>
                                <td><?php echo $usuario_ticket; ?></td>
                                <td><?php echo $area_ticket; ?></td>
                                <td><?php echo $descripcion_ticket; ?></td>
                                <td><?php echo $fecha_creacion_ticket; ?></td>
                                <td><?php echo !empty($horas) && !empty($minutos) ? "$horas" . "h $minutos" . "min" : "N/A"; ?></td>
                                <td>
                                    <form action="detalle_ticket.php" method="POST">
                                        <input type="hidden" name="id_ticket" value="<?php echo $id_ticket; ?>">
                                        <button type="submit">Ver Detalle</button>
                                    </form>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6'>No se encontraron tickets.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="pagination">
                <?php
                for ($pagina = 1; $pagina <= $totalPaginas; $pagina++) {
                    echo '<a href="?pagina=' . $pagina . '">' . $pagina . '</a> ';
                }
                ?>
            </div>

            <button class="refresh-button">Refresh Tickets</button>
        </div>
    </div>

    <button id="theme-toggle-button">🌙</button>

    <script src="js/themeIndex.js"></script>

    <footer>
        <p>&copy; 2024 Tu Empresa. Todos los derechos reservados.</p>
    </footer>
</body>

</html>