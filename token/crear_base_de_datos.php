<?php
$servername = "localhost";
$username = "root";
$password = "";

// Crear conexion
$conn = new mysqli($servername, $username, $password);

// para verificar conexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Crear base de datos
$sql = "CREATE DATABASE autenticacion"; 
if ($conn->query($sql) === TRUE) {
    echo "Base de datos creada perfectamente";
} else {
    echo "Error creando base de datos: " . $conn->error;
}

// Crear tabla 'usuarios'
$conn->select_db("autenticacion"); // seccion para seleccionar la base de datos que acabas de crear
$sql = "CREATE TABLE usuarios (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(30) NOT NULL,
    password VARCHAR(255) NOT NULL,
    token VARCHAR(255),
    token_expiracion DATETIME
)";

if ($conn->query($sql) === TRUE) {
    echo "Tabla 'usuarios' creada perfectamente";
} else {
    echo "Error creando tabla: " . $conn->error;
}

$conn->close();
?>
