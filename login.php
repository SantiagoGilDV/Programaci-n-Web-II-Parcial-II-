<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>

    <div class="login-container">

        <h2>Iniciar Sesión</h2>

        <?php if (isset($_GET['error'])): ?>
        <p class="error-msg"><?php echo $_GET['error']; ?></p>
        <?php endif; ?>


        <form method="POST" action="Procesar_login.php">
            
            <label>Usuario:</label>
            <input type="text" name="Nombre_Usuario" required>

            <label>Contraseña:</label>
            <input type="password" name="Contrasenia" required>

            <button type="submit" name="login">Ingresar</button>

        </form>

    </div>

</body>
</html>

