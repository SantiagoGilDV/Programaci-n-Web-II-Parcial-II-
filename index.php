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
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musynf</title>
    <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/index.css?v=<?php echo time(); ?>">
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
                        <a class="link nav-link" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="link nav-link" href="Artista.php">Artistas</a>
                    </li>

                    <li class="nav-item">
                        <a class="link nav-link" href="Contacto.php">Contacto</a>
                    </li>

                    <li class="nav-item">
                        <a class="link nav-link" href="crear_usuario.php">Crear usuario</a>
                    </li>

                     <?php if (!empty($_SESSION['usuario'])): ?>
    <li class="nav-item">
        <a class="nav-link text-danger" href="logout.php" title="Cerrar sesión">
            <!-- Ícono Logout -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10 12a.5.5 0 0 1-.5-.5V9H4.5A1.5 1.5 0 0 1 3 7.5v-5A1.5 1.5 0 0 1 4.5 1h5A1.5 1.5 0 0 1 11 2.5V6h1V2.5A2.5 2.5 0 0 0 9.5 0h-5A2.5 2.5 0 0 0 2 2.5v5A2.5 2.5 0 0 0 4.5 10H9v1.5a.5.5 0 0 1-.5.5z"/>
                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L14.293 7.5H6.5a.5.5 0 0 0 0 1h7.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
            </svg>
        </a>
    </li>
<?php else: ?>
    <li class="nav-item">
        <a class="nav-link text-success" href="login.php" title="Iniciar sesión">
            <!-- Ícono Login -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6 12a.5.5 0 0 1-.5-.5V9H1.5A1.5 1.5 0 0 1 0 7.5v-5A1.5 1.5 0 0 1 1.5 1h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 0-.5.5v5a.5.5 0 0 0 .5.5H5.5v-2.5a.5.5 0 0 1 1 0v3A.5.5 0 0 1 6 12z"/>
                <path fill-rule="evenodd" d="M10.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L9.293 7.5H2.5a.5.5 0 0 0 0 1h6.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
            </svg>
        </a>
    </li>
<?php endif; ?>


                </ul>
                    <img id="logo" class="d-flex" role="search" src="./Img/spotify_black.png" alt="">
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