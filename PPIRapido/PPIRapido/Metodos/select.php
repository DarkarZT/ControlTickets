<?php
require_once __DIR__ . '/../database/conexion.php';

// Función para contar el total de tickets
function countTotalTickets($pdo)
{
    $sql = "SELECT COUNT(*) FROM tickets";
    return (int) $pdo->query($sql)->fetchColumn();
}

// Función para seleccionar tickets con paginación
function selectTicketsPaginados($pdo, $limit, $offset)
{
    $sql = "SELECT * FROM tickets LIMIT :limit OFFSET :offset";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        echo "Error al obtener los tickets: " . $e->getMessage();
    }
}


// Función para obtener un ticket por su ID
function obtenerTicketPorId($pdo, $id_ticket)
{
    $sql = "SELECT t.*, a.nombre_area 
            FROM tickets t
            JOIN areas a ON t.area_ticket = a.id_area
            WHERE t.id_ticket = :id_ticket;";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_ticket', $id_ticket, PDO::PARAM_INT);
        $stmt->execute();
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$ticket) {
            throw new Exception("Ticket no encontrado");
        }

        return $ticket;
    } catch (\PDOException $e) {
        // Log del error, si es necesario, o re-lanzar la excepción
        error_log("Error al obtener el ticket: " . $e->getMessage());
        return false; // Retorna false en caso de error
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        return false;
    }
}


function areas_tickets($pdo)
{
    $sql = "SELECT * FROM areas"; // Ajusta el nombre de la tabla y las columnas
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Obtener el resultado como un array asociativo
    $areas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $areas;
}
function obtenerComentariosPorTicket($pdo, $id_ticket)
{
    $stmt = $pdo->prepare('
        SELECT c.*, u.nombre_usuario
        FROM comentarios c
        JOIN usuarios u ON c.id_usuario = u.id_usuario
        WHERE c.id_ticket = :id_ticket
        ORDER BY c.fecha_comentario ASC
    ');
    $stmt->execute([':id_ticket' => $id_ticket]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function countTotalTicketsPorSolicitante($pdo, $usuarioId)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE usuario_ticket = :usuarioId");
    $stmt->execute([':usuarioId' => $usuarioId]);
    return $stmt->fetchColumn();
}

function selectTicketsPaginadosPorSolicitante($pdo, $usuarioId, $ticketsPorPagina, $offset)
{
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE usuario_ticket = :usuarioId LIMIT :offset, :ticketsPorPagina");

    // Vincula los parámetros
    $stmt->bindValue(':usuarioId', $usuarioId, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':ticketsPorPagina', $ticketsPorPagina, PDO::PARAM_INT);

    // Ejecuta la consulta
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function obtenerAgentes($pdo, $id_rol, $id_usuario)
{
    if ($id_rol == 1) {
        // Mostrar todos los agentes
        $stmt = $pdo->prepare("SELECT id_usuario, nombre_usuario FROM usuarios WHERE id_rol = '2' or id_usuario  = :id_usuario");
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->execute();
    } elseif ($id_rol == 2) {
        // Mostrar solo el agente con el id_usuario específico
        $stmt = $pdo->prepare("SELECT id_usuario, nombre_usuario FROM usuarios WHERE id_usuario = :id_usuario");
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    } else {
        return []; // Si el rol no es válido, devuelve un array vacío
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerNombreUsuarioPorId($pdo, $usuarioId)
{
    $stmt = $pdo->prepare("SELECT nombre_usuario FROM usuarios WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':id_usuario', $usuarioId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchColumn(); // Devuelve solo el nombre del usuario
}

function obtenerNombredelAgenteONo($pdo, $id_agente)
{   
    if($id_agente){
        $stmt = $pdo->prepare("SELECT nombre_usuario FROM usuarios WHERE id_usuario  = :id_agente");
        $stmt->bindParam(':id_agente', $id_agente, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
        
    }else{
        return [];
    }       
      
    


}
function obtenerUsuarios($pdo,$id_persona) {
    $stmt = $pdo->prepare("SELECT id_usuario, nombre_usuario FROM usuarios WHERE id_usuario = :id_persona"); // Ajusta según tu estructura
    $stmt->bindParam('id_persona', $id_persona, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


$pdo = conection(); // Obtiene la conexión desde el archivo de conexión
