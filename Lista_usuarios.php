<?php
session_start();
require_once "Conexion.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
  header("Location: error.php");
  exit();
}
$usuarioLog = $_SESSION['Nombre_Usuario'] ?? ($_SESSION['usuario'] ?? '');

$result = $conn->query("SELECT Id, Nombre_Usuario, Rol FROM usuario ORDER BY Id ASC");
if (!$result)
  die("Error: " . $conn->error);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Usuarios</title>
  <link rel="stylesheet" href="./css/admin.css">
  <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>

  <div class="admin-wrap">
    <h2 class="admin-title">Lista de usuarios</h2>
    <p class="admin-subtitle">Administrá roles de la plataforma</p>

    <div class="usuarios-list">
      <?php while ($u = $result->fetch_assoc()): ?>
        <?php
        $id = (int) $u['Id'];
        $nombreUsuario = $u['Nombre_Usuario'] ?? '';
        $rol = $u['Rol'] ?? 'user';

        // Protecciones:
        $esAdminPrincipal = (strtolower($nombreUsuario) === 'admin'); // el usuario "admin"
        $esMismoUsuarioLogueado = ($usuarioLog !== '' && $usuarioLog === $nombreUsuario);
        ?>

        <div class="usuario-card">
          <div class="usuario-info">
            <div class="usuario-nombre"><?php echo htmlspecialchars($nombreUsuario); ?></div>

            <div class="usuario-meta">
              ID: <?php echo $id; ?> —
              <span class="badge <?php echo ($rol === 'admin') ? 'badge-admin' : 'badge-user'; ?>">
                <?php echo htmlspecialchars($rol); ?>
              </span>

              <?php if ($esAdminPrincipal): ?>
                <span class="badge badge-admin" style="margin-left:8px;">admin principal</span>
              <?php endif; ?>
            </div>
          </div>

          <div class="acciones-user">
            <?php if ($rol !== 'admin'): ?>
              <a class="btn-hacer-admin" href="Hacer_admi.php?id=<?php echo $id; ?>">Hacer admin</a>
            <?php else: ?>
              <?php
              // No permitir "quitar admin" al usuario admin principal ni al mismo usuario logueado
              $puedeQuitar = (!$esAdminPrincipal && !$esMismoUsuarioLogueado);
              ?>
              <?php if ($puedeQuitar): ?>
                <a class="btn-quitar-admin" href="Quitar_admin.php?id=<?php echo $id; ?>"
                  onclick="return confirm('¿Quitar rol administrador a este usuario?')">
                  Quitar admin
                </a>
              <?php else: ?>
                <span class="sin-img">No modificable</span>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>

      <?php endwhile; ?>
    </div>

    <a class="btn-volver" href="./index.php">Volver</a>
  </div>

</body>

</html>