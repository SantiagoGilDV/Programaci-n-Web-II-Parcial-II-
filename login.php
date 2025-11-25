<?php
session_start();
include "conexion.php"; 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Musynf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/login.css">

</head>
<body>
<div class="container mt-5">
    <h2>Login</h2>
    <?php if(isset($error)) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label>Usuario</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary">Iniciar sesión</button>
    </form>
</div>
</body>
</html>