<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Preferencias</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=MuseoModerno&family=Poppins&family=Silkscreen&display=swap');
        
        *{
            font-family: 'Poppins', sans-serif;
        }

        body{
            text-align: center;
        }
    </style>

</head>
<body>

    <h2>¡Gracias por seleccionar tus preferencias de videojuegos!</h2>

    <?php
    // Muestra un mensaje con los géneros de videojuegos seleccionados por el jugador
    if (isset($_SESSION['preferencias']) && !empty($_SESSION['preferencias'])) {
        $preferenciasSeleccionados = $_SESSION['preferencias'];
        echo "<p>Tus géneros favoritos: " . implode(", ", $preferenciasSeleccionados) . "</p>";

        // Limpiar la variable de sesión después de mostrar la información
        unset($_SESSION['preferencias']);
    } else {
        echo "<p>No se han seleccionado géneros.</p>";
    }
    ?>

    <script>
        // Redirigir a la página deseada después de 3 segundos
        setTimeout(function() {
            window.location.href = '../html/homeGZ.html';
        }, 3000);
    </script>

</body>
</html>
