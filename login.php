<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">

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

        <div class="login-extra">

            <p style="margin: 15px 0 8px; font-size: 14px; color: white;">
                ¿Todavía no tenés una cuenta?
            </p>

            <button 
                type="button" 
                onclick="window.location.href='Crear_usuario.php'"
                style="padding: 6px 10px; font-size: 14px; width: auto; margin-bottom: 10px;">
                Registrarse
            </button>

            <br>

            <button 
                type="button" 
                onclick="window.location.href='index.php'"
                style="padding: 5px 10px; font-size: 13px; width: auto; background-color:#159e44;">
                Continuar sin iniciar sesión
            </button>

        </div>




        </form>

    </div>

</body>
</html>

