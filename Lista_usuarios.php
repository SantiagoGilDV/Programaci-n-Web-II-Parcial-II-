<?php
session_start();
require_once "Conexion.php";

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    die("Acceso denegado");
}

$result = $conn->query("SELECT Id, Nombre_Usuario, Rol FROM usuario ORDER BY Id ASC");
if (!$result) die("Error: " . $conn->error);
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
      <div class="usuario-card">
        <div class="usuario-info">
          <div class="usuario-nombre">
            <?php echo htmlspecialchars($u['Nombre_Usuario']); ?>
          </div>

          <div class="usuario-meta">
            ID: <?php echo (int)$u['Id']; ?> —
            <span class="badge <?php echo ($u['Rol'] === 'admin') ? 'badge-admin' : 'badge-user'; ?>">
              <?php echo htmlspecialchars($u['Rol']); ?>
            </span>
          </div>
        </div>

        <div class="acciones-user">
          <?php if ($u['Rol'] !== 'admin'): ?>
            <a class="btn-hacer-admin" href="Hacer_admi.php?id=<?php echo (int)$u['Id']; ?>">
              Hacer administrador
            </a>
          <?php else: ?>
            <span class="sin-img">Ya es admin</span>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <a class="btn-volver" href="./index.php">Volver</a>
</div>
</body>
</html>