<?php

session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['token'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["archivo"])) {
        $nombreArchivo = $_FILES["archivo"]["name"];
        $tipoArchivo = $_FILES["archivo"]["type"];

        // Verifica que sea una imagen
        $permitidos = array("image/jpg", "image/jpeg", "image/png", "image/gif");
        if (in_array($tipoArchivo, $permitidos)) {

            $usuario = obtenerUsuarioPorToken($_SESSION['token']);

            $usuario['avatar'] = $nombreArchivo;

            guardarUsuarioEnJSON($usuario);
        } else {
            header("Location: ../html/homeGZ.html?error=1");
            exit();
        }
    } else {
        header("Location: ../html/homeGZ.html?error=2");
        exit();
    }
}


function obtenerUsuarioPorToken($token) {
    // Tu lógica para obtener el usuario por token
    $rutaArchivo = '../json/users.json';
    $usuarios = json_decode(file_get_contents($rutaArchivo), true);
    return current(array_filter($usuarios, function ($usuario) use ($token) {
        return $usuario['token'] === $token;
    }));
}

function guardarUsuarioEnJSON($usuario) {
    $rutaArchivo = '../json/users.json';
    $usuarios = json_decode(file_get_contents($rutaArchivo), true);

    // Buscar el usuario específico que coincide con el token de sesión
    $claveUsuario = array_search($usuario['token'], array_column($usuarios, 'token'));
    
    // Actualizar las preferencias del usuario
    if ($claveUsuario !== false) {
        $usuarios[$claveUsuario]['avatar'] = $usuario['avatar'];
    }

    // Guardar la lista actualizada de usuarios en el archivo JSON
    file_put_contents($rutaArchivo, json_encode($usuarios, JSON_PRETTY_PRINT));

    header("Location: ../html/homeGZ.html");
    exit();
}
?>
