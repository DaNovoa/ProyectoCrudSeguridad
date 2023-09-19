<?php
include 'config.php';

$titulo = $descripcion = $ubicacion = $fecha = "";
$tituloErr = $descripcionErr = $ubicacionErr = $fechaErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación de campos
    if (empty($_POST["titulo"])) {
        $tituloErr = "El título es obligatorio";
    } else {
        $titulo = $_POST["titulo"];
    }

    if (empty($_POST["descripcion"])) {
        $descripcionErr = "La descripción es obligatoria";
    } else {
        $descripcion = $_POST["descripcion"];
    }

    if (empty($_POST["ubicacion"])) {
        $ubicacionErr = "La ubicación es obligatoria";
    } else {
        $ubicacion = $_POST["ubicacion"];
    }

    if (empty($_POST["fecha"])) {
        $fechaErr = "La fecha es obligatoria";
    } else {
        $fecha = $_POST["fecha"];
    }

    // Si no hay errores de validación, agregamos el incidente a la base de datos
    if (empty($tituloErr) && empty($descripcionErr) && empty($ubicacionErr) && empty($fechaErr)) {
        agregarIncidente($titulo, $descripcion, $ubicacion, $fecha);
    }
}

// Función para agregar un incidente a la base de datos
function agregarIncidente($titulo, $descripcion, $ubicacion, $fecha) {
    global $conn;

    // Escapar los valores para evitar SQL Injection (mejor usar sentencias preparadas)
    $titulo = mysqli_real_escape_string($conn, $titulo);
    $descripcion = mysqli_real_escape_string($conn, $descripcion);
    $ubicacion = mysqli_real_escape_string($conn, $ubicacion);
    $fecha = mysqli_real_escape_string($conn, $fecha);

    // Consulta SQL para insertar el incidente en la base de datos
    $sql = "INSERT INTO incidentes (titulo, descripcion, ubicacion, fecha) VALUES ('$titulo', '$descripcion', '$ubicacion', '$fecha')";

    if ($conn->query($sql) === TRUE) {
        // Redirigir a la página principal después de agregar el incidente
        header("Location: index.php");
        exit();
    } else {
        // Manejar errores de inserción si es necesario
        echo "Error al agregar el incidente: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Título</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h1>Agregar Incidente</h1>
    
    <form method="POST" action="agregar.php">
        <!-- Formulario para agregar incidentes -->
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo $titulo; ?>" required>
        <span class="error"><?php echo $tituloErr; ?></span><br>

        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?php echo $descripcion; ?></textarea>
        <span class="error"><?php echo $descripcionErr; ?></span><br>

        <label for="ubicacion">Ubicación:</label>
        <input type="text" id="ubicacion" name="ubicacion" value="<?php echo $ubicacion; ?>" required>
        <span class="error"><?php echo $ubicacionErr; ?></span><br>

        <label for="fecha">Fecha:</label>
        <input type="text" id="fecha" name="fecha" value="<?php echo $fecha; ?>" required>
        <span class="error"><?php echo $fechaErr; ?></span><br>

        <button type="submit">Agregar</button>
    </form>
</body>
</html>
