<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Musynf</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .error-container {
            text-align: center;
            background-color: #1a1a1a;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.6);
        }

        .error-container h1 {
            font-size: 80px;
            color: #d9534f;
            margin-bottom: 20px;
        }

        .error-container h2 {
            font-size: 28px;
            color: #1abc54;
            margin-bottom: 20px;
        }

        .error-container p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .btn-back {
            background-color: #1abc54;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-back:hover {
            background-color: #159c42;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>¡Error!</h1>
        <h2>No se pudieron obtener los datos</h2>
        <p>Hubo un problema al intentar acceder a la información que solicitaste. Por favor, intenta de nuevo más tarde.</p>
        <a href="index.php" class="btn-back">Volver al inicio</a>
    </div>
</body>
</html>
