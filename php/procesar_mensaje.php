<?php

session_start();  
function enviarMensaje($destinatario, $mensaje) {

        // Validación del comentario no vacío
        if (empty(trim($mensaje))){
            header("Location: ../html/enviar_mensaje.html?error=1");
            exit();
        }else{
                // Agrega el mensaje a la bandeja de entrada del destinatario
            $nuevoMensaje = array(
                'destinatario' => $destinatario,
                'mensaje' => $mensaje,
                'fecha' => date('Y-m-d H:i:s')  
            );

            $usuarioAC = obtenerUsuarioPorToken($_SESSION['token']);

            $usuarioAC['mensajes'] = $nuevoMensaje;

            guardarUsuarioEnJSON($usuarioAC);

            header("Location: ../html/enviar_mensaje.html");
            exit();
        }
}

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $destinatario = $_POST["item"];
    $mensaje = $_POST["mensaje"];

    // Procesa el mensaje utilizando la función enviarMensaje
    enviarMensaje($destinatario, $mensaje);
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

    // Verificar si el usuario existe
    if ($claveUsuario !== false) {
        // Verificar si el usuario ya tiene un array de comentarios
        if (!isset($usuarios[$claveUsuario]['mensajes'])) {
            $usuarios[$claveUsuario]['mensajes'] = array();
        }

        // Agregar el nuevo comentario al array existente
        $usuarios[$claveUsuario]['mensajes'][] = $usuario['mensajes'];
    }

    // Guardar la lista actualizada de usuarios en el archivo JSON
    file_put_contents($rutaArchivo, json_encode($usuarios, JSON_PRETTY_PRINT));
}

?>
