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


    <main class="main-center">
        <div class="contact-container">
            <h2>Formulario de Contacto</h2>

            <form action="procesar_contacto.php" method="POST">

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
