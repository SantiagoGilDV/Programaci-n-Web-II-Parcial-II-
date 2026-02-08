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
    <title>Sobre Nosotros - Musynf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/sobre.css">

</head>

<body>
    <?php
    session_start();
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
                        </a>
                    <?php endif; ?>
                </div>

            </div>
        </nav>
    </header>

    <main>
        <h2>Sobre Nosotros</h2>
        <p>
            Musynf es una plataforma diseñada para mostrar artistas y noticias del mundo de la música.
            El objetivo del proyecto es brindar una interfaz limpia, moderna y responsiva, utilizando tecnologías como
            PHP, MySQL y Bootstrap.
        </p>
        <p>
            Este proyecto tiene fines educativos y está pensado para practicar desarrollo web full-stack.
            Nuestro equipo busca ofrecer una experiencia intuitiva para los usuarios interesados en la música.
        </p>
    </main>

    <?php
    $footer = $conn->query("SELECT * FROM footer_info LIMIT 1")->fetch_assoc();
    ?>

    <footer>
        <div class="container text-center">
            <p><?php echo $footer['Texto']; ?></p>
            <p>Contacto: <?php echo $footer['Email']; ?></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>