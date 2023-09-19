<?php
include 'config.php';

// Verifica si se ha enviado un ID de incidente válido
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Consultar la base de datos para obtener los detalles del incidente seleccionado
    $sql = "SELECT * FROM incidentes WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $incidenteDetalles = $result->fetch_assoc();
    } else {
        echo "Incidente no encontrado.";
        exit(); // Opcionalmente, puedes redirigir al usuario a la página principal aquí.
    }
} else {
    echo "ID de incidente no proporcionado.";
    exit(); // Opcionalmente, puedes redirigir al usuario a la página principal aquí.
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["guardarCambios"])) {
        // Obtener los datos del formulario de edición
        $titulo = $_POST["titulo"];
        $descripcion = $_POST["descripcion"];
        $ubicacion = $_POST["ubicacion"];
        $fecha = $_POST["fecha"];

        // Actualizar los datos del incidente en la base de datos
        // Define la función editarIncidente aquí
        function editarIncidente($conn, $id, $titulo, $descripcion, $ubicacion, $fecha)
        {
            // Escapar los valores para evitar SQL Injection (mejor usar sentencias preparadas)
            $id = intval($id); // Asegurarse de que $id sea un número entero válido
            $titulo = mysqli_real_escape_string($conn, $titulo);
            $descripcion = mysqli_real_escape_string($conn, $descripcion);
            $ubicacion = mysqli_real_escape_string($conn, $ubicacion);

            // Consulta SQL para actualizar el incidente existente
            $sql = "UPDATE incidentes SET titulo='$titulo', descripcion='$descripcion', ubicacion='$ubicacion', fecha='$fecha' WHERE id = $id";

            if ($conn->query($sql) === TRUE) {
                // Éxito
            } else {
                // Manejar errores de actualización si es necesario
                echo "Error al editar el incidente: " . $conn->error;
            }
        }

        editarIncidente($conn, $id, $titulo, $descripcion, $ubicacion, $fecha);

        // Redirigir de nuevo a la página principal después de guardar
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Incidente</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>

<body>
    <h1>Editar Incidente</h1>
    <form method="POST" action="editar.php">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo $incidenteDetalles['titulo']; ?>" required><br>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion"
            required><?php echo $incidenteDetalles['descripcion']; ?></textarea><br>
        <label for="ubicacion">Ubicación:</label>
        <input type="text" id="ubicacion" name="ubicacion" value="<?php echo $incidenteDetalles['ubicacion']; ?>"
            required><br>
        <label for="fecha">Fecha:</label>
        <input type="text" id="fecha" name="fecha" value="<?php echo $incidenteDetalles['fecha']; ?>" required><br>
        <button type="submit" name="guardarCambios">Guardar Cambios</button>
    </form>
</body>

</html>