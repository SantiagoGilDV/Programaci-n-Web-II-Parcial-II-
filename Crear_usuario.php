<?php

session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "musynf";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    header("Location: error.php");
    exit();
}


$errores = [];
$clave = "";
$nombre = "";
$apellido = "";
$usuario = "";
$correo = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['Nombre'];
    $apellido = $_POST['Apellido'];
    $usuario = $_POST['Nombre_Usuario'];
    $clave = $_POST['Contrasenia'];
    $correo = $_POST['Correo'];

    // Verificar si ya existe usuario o correo
    $checkSql = "SELECT * FROM usuario WHERE Nombre_Usuario = ? OR Correo = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $usuario, $correo);
    $checkStmt->execute();
    $resultado = $checkStmt->get_result();

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            if ($fila['Nombre_Usuario'] == $usuario) {
                $errores['usuario'] = "Usuario ya existente.";
            }
            if ($fila['Correo'] == $correo) {
                $errores['correo'] = "Correo ya existente.";
            }
        }
    }

    // Si no hay errores, insertar
    if (empty($errores)) {

        $sql = "INSERT INTO usuario (Nombre, Apellido, Nombre_Usuario, Contrasenia, Correo)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $apellido, $usuario, $clave, $correo);

        if ($stmt->execute()) {

            $id_nuevo = $stmt->insert_id;

            $_SESSION['user_id'] = $id_nuevo;
            $_SESSION['Nombre_Usuario'] = $usuario;
            $_SESSION['rol'] = 'user'; // o el rol por defecto que tengas

            header("Location: Bienvenida.php");
            exit();
        } else {
            $mensaje = "Error al crear usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
</head>

<body>
    <div class="container mt-5">
        <h2>Crear nuevo usuario</h2>

        <form method="POST">

            <div class="mb-3">
                <label>Nombre:</label>
                <input type="text" name="Nombre" class="form-control" value="<?php echo htmlspecialchars($nombre); ?>"
                    required>
            </div>

            <div class="mb-3">
                <label>Apellido:</label>
                <input type="text" name="Apellido" class="form-control"
                    value="<?php echo htmlspecialchars($apellido); ?>">
            </div>

            <div class="mb-3">
                <label>Usuario:</label>
                <input type="text" name="Nombre_Usuario" class="form-control"
                    value="<?php echo htmlspecialchars($usuario); ?>" required>

                <?php if (isset($errores['usuario'])): ?>
                    <div style="color:red;"><?php echo $errores['usuario']; ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label>Correo:</label>
                <input type="email" name="Correo" class="form-control" value="<?php echo htmlspecialchars($correo); ?>"
                    required>

                <?php if (isset($errores['correo'])): ?>
                    <div style="color:red;"><?php echo $errores['correo']; ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label>Contraseña:</label>
                <input type="password" name="Contrasenia" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Crear usuario</button>


        </form>
        <br>
        <button type="button" class="btn btn-primary" onclick="window.location.href='index.php'">
            Volver
        </button>
    </div>

</body>

</html>