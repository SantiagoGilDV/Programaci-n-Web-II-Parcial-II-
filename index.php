<?php

session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "musynf";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

if (isset($_POST['login'])) {

    $username = $_POST['Nombre_Usuario'];
    $password = $_POST['Contrasenia'];

    // Buscar usuario por Nombre_Usuario
    $sql = "SELECT * FROM usuario";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        // Como tus contraseñas NO están encriptadas:
        if ($password === $user['Contrasenia']) {

            // Guardar datos en sesión
            $_SESSION['user_id'] = $user['Id'];
            $_SESSION['Nombre_Usuario'] = $user['Nombre_Usuario'];

            header("Location: Artista.php");
            exit;

        } else {
            $error = "Contraseña incorrecta.";
        }

    } else {
        $error = "Usuario no encontrado.";
    }
}

?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musynf</title>
    <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-green">
            <div id="menu-nav" class="container-fluid">
                <a class="link navbar-brand" href="index.php">
                    <h1>Musynf</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="link nav-link" aria-current="page" href="index.php">Home</a>
                        </li>
                    </ul>
                    <img id="logo" class="d-flex" role="search" src="./Img/spotify_black.png" alt="">
                </div>
                <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
            <?php if (isset($_SESSION['username'])): ?>
                <span class="navbar-text text-white me-3">
                    Hola, <?php echo htmlspecialchars($_SESSION['username']); ?>
                </span>
                <a href="?logout=1" class="btn btn-outline-light">Cerrar sesión</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-light">
                    <img src="./Img/login_icon.png" alt="Login" style="width:24px; height:24px;">
                </a>
            <?php endif; ?>
        </div>
            </div>
        </nav>
    </header>

    <main>
        <div id="carouselExample" class="carousel slide">
            <h2>Artistas populares</h2>
            <div class="carousel-inner">

                <?php
                $sql = "SELECT id, Imagen, Nombre_Artistico FROM artista";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $artistas = $result->fetch_all(MYSQLI_ASSOC);
                    $total = count($artistas);
                    $por_slide = 4;
                    $num_slides = ceil($total / $por_slide);

                    for ($i = 0; $i < $num_slides; $i++) {
                        $active = ($i == 0) ? "active" : "";
                        echo '<div class="carousel-item ' . $active . '">';
                        echo '<div id="artista" class="d-flex justify-content-center flex-wrap">';

                        $inicio = $i * $por_slide;
                        $fin = min($inicio + $por_slide, $total);

                        for ($j = $inicio; $j < $fin; $j++) {
                            $id = $artistas[$j]["id"];
                            $img = $artistas[$j]["Imagen"];
                            $nombre = $artistas[$j]["Nombre_Artistico"];
                            ?>


                            <div id="cont_Art" class="contenedor_artista text-center mx-3">
                                <a class="link_Artist" href="./Artista.php?id=<?php echo $id; ?>">
                                    <img id="img_art" src="<?php echo $img; ?>" class="artista d-inline" alt="imagen artista">
                                    <h3><?php echo htmlspecialchars($nombre); ?></h3>
                                </a>

                            </div>

                            <?php
                        }

                        echo '</div></div>';
                    }
                } else {
                    echo "<p>No hay artistas registrados.</p>";
                }
                ?>

            </div>

            <button class="carousel-control-prev" id="caja-boton-prev" type="button" data-bs-target="#carouselExample"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon " aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button id="caja-boton-next" class="carousel-control-next " type="button" data-bs-target="#carouselExample"
                data-bs-slide="next">
                <span class="carousel-control-next-icon " aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>


        </div>



        <!-- -------------------------------------NOTICIAS----------------------------------------- -->

        <div id="carouselNoti" class="carousel slide">
            <h2>Ultimas noticias</h2>
            <div class="carousel-inner">

                <?php
                $sql = "SELECT id, Nombre_Artistico, Noticia, Imagen_Not FROM artista";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $artistas = $result->fetch_all(MYSQLI_ASSOC);
                    $total = count($artistas);
                    $por_slide = 1;
                    $num_slides = ceil($total / $por_slide);

                    for ($i = 0; $i < $num_slides; $i++) {
                        $active = ($i == 0) ? "active" : "";
                        echo '<div class="carousel-item ' . $active . '">';
                        echo '<div id="artista" class="d-flex justify-content-center flex-wrap">';

                        $inicio = $i * $por_slide;
                        $fin = min($inicio + $por_slide, $total);

                        for ($j = $inicio; $j < $fin; $j++) {
                            $id = $artistas[$j]["id"];
                            $img = $artistas[$j]["Imagen_Not"];
                            $nombre = $artistas[$j]["Nombre_Artistico"];
                            $noticia = $artistas[$j]["Noticia"];
                            ?>


                            <div id="cont_Art" class="contenedor_artista text-center mx-3">
                                <a class="link_Artist" id="art_not" href="./Artista.php?id=<?php echo $id; ?>">
                                    <img src="<?php echo $img; ?>" class="artista d-inline" alt="imagen artista">
                                    <!-- <h3><?php //echo htmlspecialchars($nombre); ?></h3> -->
                                    <p><?php echo htmlspecialchars($noticia); ?></p>
                                </a>

                            </div>

                            <?php
                        }

                        echo '</div></div>';
                    }
                } else {
                    echo "<p>No hay artistas registrados.</p>";
                }
                ?>

            </div>

            <button class="carousel-control-prev" id="caja-boton-prev" type="button" data-bs-target="#carouselNoti"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon " aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button id="caja-boton-next" class="carousel-control-next " type="button" data-bs-target="#carouselNoti"
                data-bs-slide="next">
                <span class="carousel-control-next-icon " aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>


        </div>

    </main>
    <?php
$footer = $conn->query("SELECT * FROM footer_info LIMIT 1")->fetch_assoc();
?>

<footer>
    <div class="container text-center">

        <p >
            <?php echo $footer['Texto']; ?>
        </p>
        <p >Contacto: <?php echo $footer['Email']; ?></p>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>