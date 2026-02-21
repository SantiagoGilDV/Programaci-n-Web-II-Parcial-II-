<?php

session_start();
mysqli_report(MYSQLI_REPORT_OFF);

$host = "localhost";
$db_user = "root";
$pass = "";
$db = "musynf";

$conn = new mysqli($host, $db_user, $pass, $db);

if ($conn->connect_error) {
    header("Location: error.php");
    exit();
}

if (isset($_POST['login'])) {

    if (empty($_POST['Nombre_Usuario']) || empty($_POST['Contrasenia'])) {
        $error = "Todos los campos son obligatorios.";
    } else {

        $username = $_POST['Nombre_Usuario'];
        $password = $_POST['Contrasenia'];

        $sql = "SELECT * FROM usuario WHERE Nombre_Usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if ($password === $user['Contrasenia']) {

                $_SESSION['user_id'] = $user['Id'];
                $_SESSION['Nombre_Usuario'] = $user['Nombre_Usuario'];
                $_SESSION['rol'] = $user['Rol'];

                header("Location: index.php");
                exit();

            } else {
                $error = "Contraseña incorrecta.";
            }

        } else {
            $error = "Usuario no encontrado.";
        }
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


<?php
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

            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                <a href="./Admin_lista.php" class="btn btn-outline-light me-2">
                    Panel Admin
                </a>
                <a href="./Lista_usuarios.php" class="btn btn-outline-light me-2">
                    Modificar rol
                </a>
            <?php endif; ?>

            <a href="logout.php" class="btn btn-outline-light">
                Cerrar sesión
            </a>

        <?php else: ?>

            <a href="login.php" class="btn btn-outline-light me-2">
                Login
            </a>

            <a href="Crear_usuario.php" class="btn btn-success">
                Registrarse
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
                <a class="link_Artist" href=<?php echo isset($_SESSION['user_id']) ? './Artista.php?id=' . $id : 'login.php'; ?>>
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



        <div id="carouselNoti" class="carousel slide">
        <h2>Últimas noticias</h2>

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
                        $noticia = $artistas[$j]["Noticia"];
            ?>

            <div id="cont_Art" class="contenedor_artista text-center mx-3">
                <a class="link_Artist" id="art_not" href=<?php echo isset($_SESSION['user_id']) ? './Artista.php?id=' . $id : 'login.php'; ?>>
                    <img src="<?php echo $img; ?>" class="artista d-inline" alt="imagen noticia">
                    <p><?php echo htmlspecialchars($noticia); ?></p>
                </a>
            </div>

            <?php
                    }

                    echo '</div></div>';
                }
            } else {
                echo "<p>No hay noticias disponibles.</p>";
            }
            ?>

        </div>

        <button class="carousel-control-prev" id="caja-boton-prev" type="button" data-bs-target="#carouselNoti"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>

        <button id="caja-boton-next" class="carousel-control-next" type="button" data-bs-target="#carouselNoti"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>


</main>


<?php
$footer = $conn->query("SELECT * FROM footer_info LIMIT 1")->fetch_assoc();
?>

<footer >
    <div class="container text-center">
        <p><?php echo $footer['Texto']; ?></p>
        <p>Contacto: <?php echo $footer['Email']; ?></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
