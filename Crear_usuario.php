<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "musynf";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['Nombre'];
    $apellido = $_POST['Apellido'];
    $usuario = $_POST['Nombre_Usuario'];
    $clave = $_POST['Contrasenia'];
    $correo = $_POST['Correo'];

    $sql = "INSERT INTO usuario (Nombre, Apellido, Nombre_Usuario, Contrasenia, Correo)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $apellido, $usuario, $clave, $correo);

    if ($stmt->execute()) {
        $mensaje = "Usuario creado correctamente.";
    } else {
        $mensaje = "Error al crear usuario: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<div class="container mt-5">
    <h2>Crear nuevo usuario</h2>

    <?php if ($mensaje != ""): ?>
        <div class="alert alert-info"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" name="Nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Apellido:</label>
            <input type="text" name="Apellido" class="form-control">
        </div>

        <div class="mb-3">
            <label>Usuario:</label>
            <input type="text" name="Nombre_Usuario" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Correo:</label>
            <input type="email" name="Correo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Contraseña:</label>
            <input type="password" name="Contrasenia" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Crear usuario</button>
    </form>
</div>

</body>
</html>
