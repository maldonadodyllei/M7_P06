<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['token'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

// Obtener el token de la variable de sesión
$token = $_SESSION['token'];

// Obtener la información del usuario utilizando el token
$usuario = obtenerUsuarioPorToken($token);

// Verificar si se obtuvo la información del usuario
if (!$usuario) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

// Devolver el nombre de usuario como respuesta JSON
header('Content-Type: application/json');
echo json_encode($usuario);
exit();

function obtenerUsuarioPorToken($token) {
    // Tu lógica para obtener el usuario por token
    $rutaArchivo = '../json/users.json';

    // Leer el contenido del archivo JSON
    $jsonContent = file_get_contents($rutaArchivo);

    // Decodificar el JSON a un array asociativo
    $usuarios = json_decode($jsonContent, true);

    // Verificar si el usuario existe y las credenciales son correctas
    foreach ($usuarios as $usuario) {
        if ($usuario['token'] === $token) {
            return $usuario; // Devolver el usuario completo
        }
    }

    // Quiero devolver el username del usuario para que lo reciva el js
    return $usuario;
}
?>
