<?php
// Función para procesar los comentarios y feedback
function procesarComentario($nombre, $correoElectronico, $comentarioCompleto, $tokenCSRF) {
    // Validación del formato del correo electrónico
    if (!filter_var($correoElectronico, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../html/comentarios.php?error=1");
        exit();
    }
    // Validación del comentario no vacío
    if (empty($comentarioCompleto)) {
        header("Location: ../html/comentarios.php?error=2");
        exit();
    }
    // Validación del token CSRF
    session_start();
    $tokenAlmacenado = $_SESSION["token_csrf"];
    if ($tokenCSRF !== $tokenAlmacenado) {
        header("Location: ../html/comentarios.php?error=3");
        exit();
    }else{

        $comentarioCompleto = array(
            "username" => $nombre,
            "email" => $correoElectronico,
            'comment' => $comentarioCompleto
        );

        $usuarioAC = obtenerUsuarioPorToken($_SESSION['token']);

        $usuarioAC['comentarios'] = $comentarioCompleto;

        guardarUsuarioEnJSON($usuarioAC);

        header("Location: ../html/homeGZ.html");
        exit();
    }

}

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correoElectronico = $_POST["correo_electronico"];
    $comentario = $_POST["comentario"];
    $tokenCSRF = $_POST["token_csrf"];

    // Procesa el comentario utilizando la función procesarComentario
    procesarComentario($nombre, $correoElectronico, $comentario, $tokenCSRF);

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
        if (!isset($usuarios[$claveUsuario]['comentarios'])) {
            $usuarios[$claveUsuario]['comentarios'] = array();
        }

        // Agregar el nuevo comentario al array existente
        $usuarios[$claveUsuario]['comentarios'][] = $usuario['comentarios'];
    }

    // Guardar la lista actualizada de usuarios en el archivo JSON
    file_put_contents($rutaArchivo, json_encode($usuarios, JSON_PRETTY_PRINT));
}


?>


