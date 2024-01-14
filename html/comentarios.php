<?php
session_start();

$_SESSION["token_csrf"] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameZone | Comentarios</title>
    <link rel="stylesheet" href="../css/comentarios.css">
    <script src="../js/contacto.js" defer></script>
</head>
<body>
    <header>
        <div id="title" onclick="titleClick()">
            <h1>GameZone</h1>
        </div>
        <button class="Btn" id="cerrarSesionBtn">
            <div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div>
            <div class="text">Cerrar Sesión</div>
          </button>     
    </header>
    <video src="../video/bkgComents.mp4" autoplay="true" muted="true" loop="true" class="videoPref"></video>
    <main>
        <form action="../php/procesar_comentario.php" method="post" class="comForm">
            <h2>Deja tu comentario</h2>

            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="correo_electronico">Correo Electrónico</label>
            <input type="email" id="correo_electronico" name="correo_electronico" required>

            <label for="comentario">Comentario</label>
            <textarea id="comentario" name="comentario" rows="4" required></textarea>
            <input type="hidden" name="token_csrf" value="<?=$_SESSION["token_csrf"]?>">

            <button type="submit" class="envCom">Enviar Comentario</button>
        </form>
    </main>
    <script>
        // Función para obtener parámetros de la URL
        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        // Función para mostrar el mensaje emergente si hay un error en la URL
        function mostrarMensajeError() {
            var error = getParameterByName('error');
            if (error === '1') {
                alert("El correo electrónico no es valido.");
            }else if(error === '2'){
                alert("El campo de comentario no puede estar vacío.");
            }else if(error === '3'){
                alert("Error de seguridad: Token CSRF no válido.");
            }
        }
        window.onload = mostrarMensajeError;

        document.getElementById('cerrarSesionBtn').addEventListener('click', function() {

        localStorage.clear();

        fetch('../php/cerrar_sesion.php', {
            method: 'POST',
            credentials: 'include'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cerrar la sesión');
            }
            window.location.href = '../html/inicio_sesion.html';
        })
        .catch(error => {
            console.error(error.message);
        });
        });
    </script>
</body>
</html>
