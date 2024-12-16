<?php
    function conection(){
        $host = 'localhost';
        $db = 'ppi'; // Nombre de la base de datos
        $user = 'root'; // Tu usuario de MySQL
        $pass = 'darkar11'; // Tu contraseña de MySQL
        $charset = 'utf8mb4'; // Opcional, especifica el conjunto de caracteres
    
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
    
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
            return $pdo;
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    
    }
?>
