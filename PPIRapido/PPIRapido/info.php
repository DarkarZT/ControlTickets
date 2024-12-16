<?php
require __DIR__ . '/sesionUser/session_check.php'; // Ajusta la ruta según la ubicación de session_check.php

// Ahora puedes usar las variables de sesión aquí
echo "ID del usuario: " . $usuarioId;
echo "Nombre del usuario: " . $usuarioNombre;
echo "Rol del usuario: " . $usuarioRol;
echo "Agente del usuario: " . ($esAgente ? 'Sí' : 'No');

// El resto del código de la vista
?>
<?php
phpinfo();
?>














