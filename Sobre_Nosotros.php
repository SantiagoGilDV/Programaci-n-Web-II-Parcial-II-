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
session_start(); // necesario para mostrar el nombre
?>

<header>
    <nav class="navbar navbar-expand-lg" style="background-color: #1abc54;">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <!-- Nombre del sitio -->
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="./Img/spotify_black.png" width="45" style="margin-right:10px;">
                <h1 class="m-0" style="font-size:30px;">Musynf</h1>
            </a>

            <!-- Nombre del usuario -->
            <span class="text-white fw-bold">
                <?php 
                    if (isset($_SESSION['Nombre_Usuario'])) {
                        echo "Hola, " . htmlspecialchars($_SESSION['Nombre_Usuario']);
                    } else {
                        echo "Invitado";
                    }
                ?>
            </span>

        </div>
    </nav>
</header>

    <main>
        <h2>Sobre Nosotros</h2>
        <p>
            Musynf es una plataforma diseñada para mostrar artistas y noticias del mundo de la música.
            El objetivo del proyecto es brindar una interfaz limpia, moderna y responsiva, utilizando tecnologías como PHP, MySQL y Bootstrap.
        </p>
        <p>
            Este proyecto tiene fines educativos y está pensado para practicar desarrollo web full-stack.
            Nuestro equipo busca ofrecer una experiencia intuitiva para los usuarios interesados en la música.
        </p>
    </main>

    <footer class="text-center">
        <div class="card-body">
            <h3 class="card-title">©Todos los derechos reservados 2025</h3>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
