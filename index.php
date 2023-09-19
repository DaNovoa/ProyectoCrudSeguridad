<?php
include 'config.php';

// Función para obtener la lista de incidentes desde la base de datos
function obtenerIncidentes($conn)
{
    $sql = "SELECT * FROM incidentes";
    $result = $conn->query($sql);

    $incidentes = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $incidentes[] = $row;
        }
    }

    return $incidentes;
}

$incidentes = obtenerIncidentes($conn);

// Variables para almacenar los datos del incidente a editar
$editar_id = "";
$editar_titulo = "";
$editar_descripcion = "";
$editar_ubicacion = "";
$editar_fecha = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar el formulario de agregar/editar/eliminar incidentes
    if (isset($_POST["agregar"])) {
        $titulo = $_POST["titulo"];
        $descripcion = $_POST["descripcion"];
        $ubicacion = $_POST["ubicacion"];
        $fecha = $_POST["fecha"];

        agregarIncidente($conn, $titulo, $descripcion, $ubicacion, $fecha);
    } elseif (isset($_POST["editar"])) {
        // Al hacer clic en "Editar," cargar los datos del incidente existente
        $editar_id = $_POST["id"];
        $editar_titulo = $_POST["titulo"];
        $editar_descripcion = $_POST["descripcion"];
        $editar_ubicacion = $_POST["ubicacion"];
        $editar_fecha = $_POST["fecha"];
    } elseif (isset($_POST["eliminar"])) {
        $id = $_POST["id"];

        eliminarIncidente($conn, $id);
    } elseif (isset($_POST["guardarCambios"])) {
        // Procesar el formulario de "Guardar cambios" y actualizar el incidente
        $id = $_POST["editar_id"];
        $titulo = $_POST["editar_titulo"];
        $descripcion = $_POST["editar_descripcion"];
        $ubicacion = $_POST["editar_ubicacion"];
        $fecha = $_POST["editar_fecha"];

        editarIncidente($conn, $id, $titulo, $descripcion, $ubicacion, $fecha);
    }

    // Redirigir a la página principal después de realizar una operación CRUD
    header("Location: index.php");
    exit();
}

// Función para agregar un incidente a la base de datos
function agregarIncidente($conn, $titulo, $descripcion, $ubicacion, $fecha)
{
    // Escapar los valores para evitar SQL Injection (mejor usar sentencias preparadas)
    $titulo = mysqli_real_escape_string($conn, $titulo);
    $descripcion = mysqli_real_escape_string($conn, $descripcion);
    $ubicacion = mysqli_real_escape_string($conn, $ubicacion);
    $fecha = mysqli_real_escape_string($conn, $fecha);

    // Consulta SQL para insertar el incidente en la base de datos
    $sql = "INSERT INTO incidentes (titulo, descripcion, ubicacion, fecha) VALUES ('$titulo', '$descripcion', '$ubicacion', '$fecha')";

    if ($conn->query($sql) === TRUE) {
        // Éxito
    } else {
        // Manejar errores de inserción si es necesario
        echo "Error al agregar el incidente: " . $conn->error;
    }
}

// Función para editar un incidente en la base de datos
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

// Función para eliminar un incidente de la base de datos
function eliminarIncidente($conn, $id)
{
    // Consulta SQL para eliminar el incidente
    $sql = "DELETE FROM incidentes WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Éxito
    } else {
        // Manejar errores de eliminación si es necesario
        echo "Error al eliminar el incidente: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incidentes de Seguridad en Bogotá</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">    
    <style>
        .button-container {
            display: flex;
        }

        .inline-buttons {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <h1>Incidentes de Seguridad en Bogotá</h1>

    <form method="POST" action="index.php">
        <!-- Formulario para agregar incidentes -->
        <input type="hidden" name="id" value="<?php echo $editar_id; ?>">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo $editar_titulo; ?>" required><br>
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?php echo $editar_descripcion; ?></textarea><br>
        <label for="ubicacion">Ubicación:</label>
        <input type="text" id="ubicacion" name="ubicacion" value="<?php echo $editar_ubicacion; ?>" required><br>
        <label for="fecha">Fecha:</label>
        <input type="text" id="fecha" name="fecha" value="<?php echo $editar_fecha; ?>" required><br>
        <button type="submit" name="agregar">Agregar</button>
    </form>

    <ul>
        <?php foreach ($incidentes as $incidente): ?>
            <li>
                <!-- Mostrar detalles del incidente -->
                <h2>
                    <?php echo $incidente['titulo']; ?>
                </h2>
                <p>Descripción:
                    <?php echo $incidente['descripcion']; ?>
                </p>
                <p>Ubicación:
                    <?php echo isset($incidente['ubicacion']) ? $incidente['ubicacion'] : 'N/A'; ?>
                </p>
                <p>Fecha:
                    <?php echo $incidente['fecha']; ?>
                </p>
                <form method="POST" action="editar.php" class="button-container">
                    <input type="hidden" name="id" value="<?php echo $incidente['id']; ?>">
                    <button type="submit" name="editar" class="inline-buttons">Editar</button>
                </form>
                <form method="POST" action="index.php" class="button-container">
                    <input type="hidden" name="id" value="<?php echo $incidente['id']; ?>">
                    <button type="submit" name="eliminar" class="inline-buttons"
                        onclick="return confirm('¿Estás seguro de que deseas eliminar este incidente?')">Eliminar</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

</body>

</html>
