<?php
// Función para procesar el registro de nuevos jugadores
function procesarRegistro($nombreUsuario, $correoElectronico, $contrasena) {
    // Validación del formato del correo electrónico
    if (!filter_var($correoElectronico, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../html/registro.html?error=1");
        exit();
    
    }
    // Validación de la longitud de la contraseña
    if (strlen($contrasena) < 6) {
        header("Location: ../html/registro.html?error=2");
        exit();
    
    }

    if (correoRegistrado($correoElectronico)) {
        header("Location: ../html/registro.html?error=3");
        exit();
    
    }
    //guardar jugador en un archivo json

    $token = generarToken();

    // Crea un arreglo con los datos del nuevo jugador
    $jugador = array(
        "username" => $nombreUsuario,
        "email" => $correoElectronico,
        'password' => password_hash($contrasena, PASSWORD_DEFAULT),
        'token' => $token
    );

    // Codifica el arreglo en formato JSON
    $datosUsuarioJSON  = json_encode($jugador);

    $rutaArchivo = '../json/users.json';

    // Verificar si el archivo ya existe
    if (file_exists($rutaArchivo)) {
        // Leer el contenido actual del archivo
        $contenidoActual = file_get_contents($rutaArchivo);
        // Decodificar el JSON existente
        $datosExistente = json_decode($contenidoActual, true);
        // Agregar los nuevos datos al array existente
        $datosExistente[] = $jugador;
        // Codificar todo el array nuevamente a formato JSON
        $nuevoContenido = json_encode($datosExistente);
    } else {
        // Si el archivo no existe, simplemente codificar los nuevos datos
        $nuevoContenido = $datosUsuarioJSON;
    }

    // Escribir el contenido en el archivo
    file_put_contents($rutaArchivo, $nuevoContenido);

    header("Location: ../html/inicio_sesion.html");
    exit();
}

function correoRegistrado($correoElectronico) {
    $rutaArchivo = '../json/users.json';

    if (file_exists($rutaArchivo)) {
        $contenidoActual = file_get_contents($rutaArchivo);
        $datosExistente = json_decode($contenidoActual, true);

        // Verificar si el correoElectronico ya está presente en el array existente
        foreach ($datosExistente as $usuario) {
            if ($usuario['email'] == $correoElectronico) {
                return true; // El correo electrónico ya está registrado
            }
        }
    }

    return false; 
}

function generarToken($length = 32) {
    return bin2hex(random_bytes($length));
}

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recolecta los datos del formulario
    $nombreUsuario = $_POST["username"];
    $correoElectronico = $_POST["email"];
    $contrasena = $_POST["password"];

    // Procesa el registro utilizando la función procesarRegistro
    procesarRegistro($nombreUsuario, $correoElectronico, $contrasena);

} else {
    // Si la solicitud no es POST, redirige al usuario a la página de registro
    header("Location: ../html/registro.html");
    exit();
}
?>
