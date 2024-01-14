<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Verificar las credenciales en el archivo JSON
    $usuario = verificarCredenciales($email, $password);

    if ($usuario) {
        // Almacenar el token en la variable de sesión
        $_SESSION['token'] = $usuario['token'];

        // Redirigir a la página principal
        header("Location: ../html/homeGZ.html");
        exit();
    } else {
        header("Location: ../html/inicio_sesion.html?error=1");
        exit();
    }
} else {
    header("Location: ../html/inicio_sesion.html");
    exit();
}

function verificarCredenciales($email, $password) {
    $rutaArchivo = '../json/users.json';

    // Leer el contenido del archivo JSON
    $jsonContent = file_get_contents($rutaArchivo);

    // Decodificar el JSON a un array asociativo
    $usuarios = json_decode($jsonContent, true);

    // Verificar si el usuario existe y las credenciales son correctas
    foreach ($usuarios as $usuario) {
        if ($usuario['email'] === $email && password_verify($password, $usuario['password'])) {
            return $usuario; // Devolver el usuario completo
        }
    }

    return false;
}

?>
