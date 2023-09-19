<?php
include 'config.php';

$id = "";

// Verifica si se ha enviado un ID de incidente válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Función para eliminar un incidente de la base de datos
    function eliminarIncidente($id) {
        global $conn;

        // Consulta SQL para eliminar el incidente
        $sql = "DELETE FROM incidentes WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Redirigir a la página principal después de eliminar el incidente
            header("Location: index.php");
            exit();
        } else {
            // Manejar errores de eliminación si es necesario
            echo "Error al eliminar el incidente: " . $conn->error;
        }
    }

    // Llama a la función para eliminar el incidente
    eliminarIncidente($id);
} else {
    echo "ID de incidente no válido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Incidente</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h1>Eliminar Incidente</h1>
    <p>¿Estás seguro de que deseas eliminar este incidente?</p>
    
    <form method="POST" action="eliminar.php?id=<?php echo $id; ?>">
        <button type="submit" name="eliminar">Eliminar</button>
        <a href="index.php">Cancelar</a>
    </form>
</body>
</html>
