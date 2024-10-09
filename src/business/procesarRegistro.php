<?php
require_once '../data/usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Verificar que las contraseñas coinciden
    if ($password !== $confirmPassword) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    // Verificar si el usuario ya existe
    if (verificarUsuario($username)) {
        echo "El nombre de usuario ya está en uso.";
        exit;
    }

    // Registrar el nuevo usuario
    if (registrarUsuario($username, $password)) {
        echo "Registro exitoso. Puedes iniciar sesión.";
        // Redirigir a la página de inicio de sesión o mostrar un mensaje
    } else {
        echo "Error en el registro. Intenta de nuevo.";
    }
}
?>
