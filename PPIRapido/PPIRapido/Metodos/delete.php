<?php
    require "../database/conexion.php";

    // Obtener los datos JSON del cuerpo de la solicitud
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    function deleteData($pdo, $id) {
        $sql = "DELETE FROM Tickets WHERE id_ticket = :id";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            echo "Dato eliminado con éxito!";
        } catch (\PDOException $e) {
            echo "Error al eliminar el dato: " . $e->getMessage();
        }
    }

    // Llamada a la función con los datos decodificados del JSON
    $pdo = conection(); // Obtiene la conexión desde el archivo de conexión
    deleteData($pdo, $data['id_ticket']);
?>
