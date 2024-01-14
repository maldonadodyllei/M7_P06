<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['token'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

// Verifica si se enviaron preferencias
if (isset($_POST['preferencias'])) {
    $preferenciasSeleccionados = $_POST['preferencias'];

    $_SESSION['preferencias'] = $preferenciasSeleccionados;

    // Obtiene el usuario actual (puedes usar tu lógica de obtención de usuario)
    $usuario = obtenerUsuarioPorToken($_SESSION['token']);

    // Actualiza las preferencias del usuario
    $usuario['preferencias'] = $preferenciasSeleccionados;

    // Guarda el usuario actualizado en el archivo JSON
    guardarUsuarioEnJSON($usuario);

    // Redirige o realiza cualquier otra acción necesaria
    header("Location: confirmacion_preferencias.php");
    exit();
}else{
    $preferenciasSeleccionados = [];

    $_SESSION['preferencias'] = $preferenciasSeleccionados;

    // Obtiene el usuario actual (puedes usar tu lógica de obtención de usuario)
    $usuario = obtenerUsuarioPorToken($_SESSION['token']);

    // Actualiza las preferencias del usuario
    $usuario['preferencias'] = $preferenciasSeleccionados;

    // Guarda el usuario actualizado en el archivo JSON
    guardarUsuarioEnJSON($usuario);

    // Redirige o realiza cualquier otra acción necesaria
    header("Location: confirmacion_preferencias.php");
    exit();
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
        $usuarios[$claveUsuario]['preferencias'] = $usuario['preferencias'];
    }

    // Guardar la lista actualizada de usuarios en el archivo JSON
    file_put_contents($rutaArchivo, json_encode($usuarios, JSON_PRETTY_PRINT));
}
?>
