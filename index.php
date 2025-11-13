<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "musync"; // la que creaste en Workbench

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-green">
            <div class="container-fluid">
                <a class="link navbar-brand" href="#">
                    <h1>Musync</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="link nav-link" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="link nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="link nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="link dropdown-item" href="#">Action</a></li>
                                <li><a class="link dropdown-item" href="#">Another action</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="link dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="link nav-link disabled" aria-disabled="true">Disabled</a>
                        </li>
                    </ul>
                    <form class="d-flex" role="search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
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
                        echo '<div class="d-flex justify-content-center flex-wrap">';

                       
                        $inicio = $i * $por_slide;
                        $fin = min($inicio + $por_slide, $total);

                        for ($j = $inicio; $j < $fin; $j++) {
                            $img = $artistas[$j]["Imagen"];
                            $nombre = $artistas[$j]["Nombre_Artistico"];
                            ?>
                            <div class="contenedor_artista text-center mx-3">
                                <img src="<?php echo $img; ?>" class="artista d-inline" alt="imagen artista">
                                <h3><?php echo htmlspecialchars($nombre); ?></h3>
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
            
            <button class="carousel-control-prev" id="caja-boton-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon bg-black" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button id="caja-boton-next" class="carousel-control-next " type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon bg-black" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>