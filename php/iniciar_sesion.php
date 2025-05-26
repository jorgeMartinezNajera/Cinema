<?php
include 'conexion_oracle.php';
session_start();

// Obtener datos del formulario
$correo = $_POST['correo'] ?? '';
$contrasenia = $_POST['contrasenia'] ?? '';


// Preparar y ejecutar la consulta
$sql = "SELECT correo, contrasenia FROM cliente WHERE correo = :correo";
$stid = oci_parse($conn, $sql);
oci_bind_by_name($stid, ":correo", $correo);
oci_execute($stid);

$row = oci_fetch_assoc($stid);

if ($row && password_verify($contrasenia, $row['CONTRASENIA'])) {
    // Inicio de sesión exitoso
    $_SESSION['usuario_cliente'] = $row['ID_CLIENTE'];
    echo json_encode(["success" => true]);
    exit;
} else {
    echo "Correo o contraseña incorrectos.";
}

// Cerrar conexión
oci_free_statement($stid);
oci_close($conn);
