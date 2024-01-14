<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['token'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

$token = $_SESSION['token'];
$usuarios = obtenerUsuarios($token);

header('Content-Type: application/json');
echo json_encode($usuarios);
exit();

function obtenerUsuarios($token){

    // Tu lógica para obtener el usuario por token
    $rutaArchivo = '../json/users.json';

    // Leer el contenido del archivo JSON
    $jsonContent = file_get_contents($rutaArchivo);

    // Decodificar el JSON a un array asociativo
    $usuariosLista = json_decode($jsonContent, true);

    // Verificar si el usuario existe y las credenciales son correctas
    foreach ($usuariosLista as $key => $usuario) {
        if ($usuario['token'] === $token) {
            // Eliminar el usuario actual del array
            unset($usuariosLista[$key]);
        }
    }

    return array_values($usuariosLista); // Reindexar el array para evitar huecos en los índices
}
?>
