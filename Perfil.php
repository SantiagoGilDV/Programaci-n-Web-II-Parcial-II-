<?php
session_start();
require_once "Conexion.php";

if (!isset($_SESSION['user_id']) && !isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'] ?? $_SESSION['id_usuario'];

// Traer datos actuales
$stmt = $conn->prepare("SELECT Id, Nombre, Apellido, Nombre_Usuario, Correo, Fecha_Nacimiento, Contrasenia FROM usuario WHERE Id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$u = $stmt->get_result()->fetch_assoc();

if (!$u) {
    die("Usuario no encontrado.");
}

$mensaje = "";
$error = "";

// Procesar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Nombre = trim($_POST['Nombre'] ?? '');
    $Apellido = trim($_POST['Apellido'] ?? '');
    $Nombre_Usuario = trim($_POST['Nombre_Usuario'] ?? '');
    $Correo = trim($_POST['Correo'] ?? '');
    $Fecha_Nacimiento = $_POST['Fecha_Nacimiento'] ?? null;

    $passActual = $_POST['pass_actual'] ?? '';
    $passNueva = $_POST['pass_nueva'] ?? '';
    $passNueva2 = $_POST['pass_nueva2'] ?? '';

    // Validaciones 
    if ($Nombre === '' || $Nombre_Usuario === '' || $Correo === '') {
        $error = "Nombre, nombre de usuario y correo son obligatorios.";
    } elseif (!filter_var($Correo, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo no tiene un formato válido.";
    } else {
        // Verificar si cambió usuario/correo
        $stmtCheck = $conn->prepare("
            SELECT Id, Nombre_Usuario, Correo
            FROM usuario
            WHERE (Nombre_Usuario = ? OR Correo = ?) AND Id <> ?
            LIMIT 1
        ");
        $stmtCheck->bind_param("ssi", $Nombre_Usuario, $Correo, $userId);
        $stmtCheck->execute();
        $dup = $stmtCheck->get_result()->fetch_assoc();

        if ($dup) {
            if ($dup['Nombre_Usuario'] === $Nombre_Usuario)
                $error = "Ese nombre de usuario ya está en uso.";
            elseif ($dup['Correo'] === $Correo)
                $error = "Ese correo ya está en uso.";
            else
                $error = "Usuario o correo ya están en uso.";
        } else {
            // Cambio de contraseña
            $cambiarPass = (trim($passNueva) !== '' || trim($passNueva2) !== '' || trim($passActual) !== '');

            $ContraseniaFinal = $u['Contrasenia'];

            if ($cambiarPass) {
                if ($passActual === '' || $passNueva === '' || $passNueva2 === '') {
                    $error = "Para cambiar la contraseña completá: contraseña actual, nueva y repetir nueva.";
                } elseif ($passActual !== $u['Contrasenia']) {
                    $error = "La contraseña actual no es correcta.";
                } elseif ($passNueva !== $passNueva2) {
                    $error = "La nueva contraseña y su confirmación no coinciden.";
                } elseif (strlen($passNueva) < 4) {
                    $error = "La nueva contraseña debe tener al menos 4 caracteres.";
                } else {

                    $ContraseniaFinal = $passNueva;
                }
            }

            if ($error === "") {
                $stmtUp = $conn->prepare("
                    UPDATE usuario
                    SET Nombre=?, Apellido=?, Nombre_Usuario=?, Correo=?, Fecha_Nacimiento=?, Contrasenia=?
                    WHERE Id=?
                ");
                $stmtUp->bind_param(
                    "ssssssi",
                    $Nombre,
                    $Apellido,
                    $Nombre_Usuario,
                    $Correo,
                    $Fecha_Nacimiento,
                    $ContraseniaFinal,
                    $userId
                );

                if ($stmtUp->execute()) {
                    $mensaje = "Datos actualizados correctamente.";

                    // Actualizar sesiOn si cambio el usuario
                    $_SESSION['Nombre_Usuario'] = $Nombre_Usuario;
                    $_SESSION['usuario'] = $Nombre_Usuario;

                    // Refrescar datos en pantalla
                    $u['Nombre'] = $Nombre;
                    $u['Apellido'] = $Apellido;
                    $u['Nombre_Usuario'] = $Nombre_Usuario;
                    $u['Correo'] = $Correo;
                    $u['Fecha_Nacimiento'] = $Fecha_Nacimiento;
                    $u['Contrasenia'] = $ContraseniaFinal;
                } else {
                    $error = "Error al actualizar: " . $stmtUp->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi perfil</title>
    <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
    <link rel="stylesheet" href="./css/perfil.css">
</head>

<body>

    <div class="perfil-container">
        <h2>Mi perfil</h2>

        <?php if ($mensaje): ?>
            <p class="msg-ok"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="msg-error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Nombre</label>
            <input type="text" name="Nombre" value="<?php echo htmlspecialchars($u['Nombre'] ?? ''); ?>" required>

            <label>Apellido</label>
            <input type="text" name="Apellido" value="<?php echo htmlspecialchars($u['Apellido'] ?? ''); ?>">

            <label>Nombre de usuario</label>
            <input type="text" name="Nombre_Usuario" value="<?php echo htmlspecialchars($u['Nombre_Usuario'] ?? ''); ?>"
                required>

            <label>Correo</label>
            <input type="email" name="Correo" value="<?php echo htmlspecialchars($u['Correo'] ?? ''); ?>" required>

            <label>Fecha de nacimiento</label>
            <input type="date" name="Fecha_Nacimiento"
                value="<?php echo htmlspecialchars($u['Fecha_Nacimiento'] ?? ''); ?>">

            <hr class="separador">

            <h3>Cambiar contraseña (opcional)</h3>
            <label>Contraseña actual</label>
            <input type="password" name="pass_actual" placeholder="Solo si vas a cambiarla">

            <label>Nueva contraseña</label>
            <input type="password" name="pass_nueva" placeholder="Nueva contraseña">

            <label>Repetir nueva contraseña</label>
            <input type="password" name="pass_nueva2" placeholder="Repetir nueva contraseña">

            <button type="submit">Guardar cambios</button>
        </form>

        <div class="acciones">
            <a class="btn-sec" href="index.php">Inicio</a>
        </div>
    </div>

</body>

</html>