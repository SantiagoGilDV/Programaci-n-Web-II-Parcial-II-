<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>

<h2>Iniciar sesión</h2>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">
    <label>Usuario:</label>
    <input type="text" name="Nombre_Usuario" required>

    <br><br>

    <label>Contraseña:</label>
    <input type="password" name="Contrasenia" required>

    <br><br>

    <button type="submit" name="login">Ingresar</button>
</form>

</body>
</html>
