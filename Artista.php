<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db = "musynf";

$conn = new mysqli($host, $user, $pass, $db);
$esAdmin = (isset($_SESSION['Nombre_Usuario']) && $_SESSION['usuario'] === 'admin');

if ($conn->connect_error) {
    header("Location: error.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Artista no especificado.");
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM artista WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Artista no encontrado.");
}

$artista = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?php echo $artista['Nombre_Artistico']; ?></title>
    <link rel="icon" href="./Img/Spotify_icon.svg.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/artist.css">
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
                        <?php if (!$esAdmin): ?>

                            <a href="Perfil.php" class="btn btn-outline-light me-2">
                                Editar perfil
                            </a>
                        <?php endif; ?>

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
        <div id="contenedor_Art">
            <a href="index.php" class="btn btn-secondary mb-3 " id="boton"> Volver</a>

            <div id="card" class="card p-4">
                <h2 class="text-center"><?php echo $artista['Nombre_Artistico']; ?></h2>

                <div class="text-center">
                    <img src="<?php echo $artista['Imagen']; ?>" class="img-fluid rounded" width="300">
                </div>

                <hr>

                <h4>Descripción</h4>
                <p>
                    <?php echo nl2br($artista['Descripcion']); ?>
                </p>
            </div>
        </div>
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

</body>

</html>