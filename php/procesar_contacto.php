<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo_electronico"];
    $mensaje = $_POST["mensaje"];

    // Manejar el archivo JSON
    $rutaArchivo = '../json/contactos.json';

    // Leer el contenido del archivo JSON existente
    $jsonContent = file_get_contents($rutaArchivo);

    // Decodificar el JSON a un array asociativo
    $contactos = json_decode($jsonContent, true);

    // Subir archivo adjunto
    $nombreArchivo = '';
    if (isset($_FILES['file-1'])) {
        $nombreArchivo = $_FILES['file-1']['name'];
        $rutaDestino = '../archivos_adjuntos/' . $nombreArchivo;

        // Mover el archivo a la ubicación deseada
        move_uploaded_file($_FILES['file-1']['tmp_name'], $rutaDestino);
    }

    // Agregar el nuevo contacto al array
    $nuevoContacto = array(
        'nombre' => $nombre,
        'correo' => $correo,
        'mensaje' => $mensaje,
        'archivo_adjunto' => $nombreArchivo
    );

    $contactos[] = $nuevoContacto;

    // Codificar el array a JSON y guardar en el archivo
    file_put_contents($rutaArchivo, json_encode($contactos, JSON_PRETTY_PRINT));

    // Redirigir o responder según tus necesidades
    header("Location: ../html/homeGZ.html");
    exit();
}

?>

