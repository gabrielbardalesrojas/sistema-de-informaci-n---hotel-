<?php

// Incluir archivo de conexión a la base de datos
include 'conexion.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Cifrado de la contraseña

    // Verificar que el usuario o el correo no exista
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->rowCount() > 0) {
        echo "<script>
            Swal.fire({
                title: 'Error',
                text: 'El nombre de usuario o correo ya está registrado.',
                icon: 'error'
            });
        </script>";
    } else {
        // Insertar nuevo usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $email, $password])) {
            echo "<script>
                Swal.fire({
                    title: '¡Registro Exitoso!',
                    text: 'Tu cuenta ha sido creada exitosamente.',
                    icon: 'success'
                }).then(() => {
                    window.location.href = 'admin/reservas.php'; // Redirigir a la página de inicio de sesión
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un problema al registrar el usuario.',
                    icon: 'error'
                });
            </script>";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup Form</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="sweetalert2.min.css">
    
</head>
<body>
<div class="contiene">
    <div class="form-container">
        <!-- Icono de cierre (X) -->
        
        
        <div class="form login-form">
        <a onclick="window.location='index.php'" class="close-btn">×</a>
            <h2 class="form-title">Login</h2>
            <form action="conectar.php"  method="post">
                <div class="input-group">
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <a href="#" class="forgot-link">Forgot password?</a>
                <button type="submit" class="btn">Login</button>
                <p>No tienes una cuenta? <a href="#" class="toggle-form" data-target="signup">Registro</a></p>
                <a href="#" class="help-link">Need help?</a>
            </form>
        </div>
        
        <div class="form signup-form">
        <a href="index.php" class="close-btn">×</a>
            <h2 class="form-title">Registro</h2>
            <form  method="post">
                <div class="input-group">
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>
                <div class="input-group">
                    <input type="email" name="email" required>
                    <label>Email Id</label>
                </div>
                <div class="input-group">
                    <input type="password" name="password" required>
                    <label>Create password</label>
                </div>
                <p>By creating an account, I agree to <a href="#">Terms and Conditions</a></p>
                <button type="submit" class="btn">Create Account</button>
                <p>Tienes una cuenta? <a href="#" class="toggle-form" data-target="login">Login</a></p>
                <a href="#" class="help-link">Need help?</a>
            </form>
        </div>
    </div>
</div>
<script src="js/login.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
