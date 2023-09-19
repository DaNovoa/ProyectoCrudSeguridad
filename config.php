<?php
$servername = "localhost"; // Nombre del servidor de la base de datos
$username = "root";         // Tu nombre de usuario de la base de datos
$password = "";             // Deja el campo de contraseña en blanco
$dbname = "appsecurity";    // Nombre de la base de datos

// Crear la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
?>
