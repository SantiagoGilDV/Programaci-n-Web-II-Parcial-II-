<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "musynf";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
} ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Musynf</title>

    <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="./css/contacto.css">
</head>

<body>

 <?php
session_start(); // necesario para mostrar el nombre
$conf = $conn->query("SELECT * FROM header LIMIT 1")->fetch_assoc();
?>
<header>
    <nav class="navbar navbar-expand-lg" style="background-color: <?php echo $conf['Color_Primario']; ?>;">
        <div id="menu-nav"> 

            <div class="d-flex align-items-center">
                <a class="link navbar-brand d-flex align-items-center me-3" href="index.php">
                    <img src="<?php echo $conf['Logo']; ?>" width="45" style="margin-right:10px;">
                    <h1 style="font-size:30px; margin:0;"><?php echo $conf['Nombre_Sitio']; ?></h1>
                </a>

                <!-- Botón Sobre Nosotros -->
                <a href="Sobre_Nosotros.php" class="btn btn-outline-light" style="height:40px; margin-left:10px;">
                    Sobre Nosotros
                </a>
                <a href="contacto.php" class="btn btn-outline-light" style="height:40px;">
                    Contacto
                </a>
            </div>

            <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
                <?php if (isset($_SESSION['Nombre_Usuario'])): ?>
                    <span class="navbar-text text-white me-3">
                        Hola, <?php echo htmlspecialchars($_SESSION['Nombre_Usuario']); ?>
                    </span>
                    <a href="logout.php" class="btn btn-outline-light">Cerrar sesión</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light">
                        Login
                        <!-- <img src="./Img/login_icon.png" alt="Login" style="width:24px; height:24px;"> -->
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </nav>
</header>

    <main class="main-center">
        <div class="contact-container">
            <h2>Formulario de Contacto</h2>

            <form action="index.php" method="POST">

                <label class="form-label">Nombre completo</label>
                <input type="text" class="form-control" name="nombre" required>

                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="email" required>

                <label class="form-label">Edad</label>
                <input type="number" class="form-control" name="edad" min="1" max="120" required>

                <label class="form-label">Asunto</label>
                <select class="form-select" name="asunto" required>
                    <option value="">Seleccione...</option>
                    <option value="consulta">Consulta</option>
                    <option value="sugerencia">Sugerencia</option>
                    <option value="reclamo">Reclamo</option>
                </select>

                <label class="form-label">Mensaje</label>
                <textarea class="form-control" name="mensaje" rows="4" required></textarea>

                <button class="btn-submit mt-3">Enviar</button>
            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
