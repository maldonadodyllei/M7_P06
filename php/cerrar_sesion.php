<?php
session_start();

// Unset all of the session variables
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir a la página de inicio de sesión u otra página de tu elección
header("Location: ../html/inicio_sesion.html");
exit();
?>
