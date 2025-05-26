<?php
//archivo: conexion_oracle.php
$usuario = "HR";
$contrasena = "hr";
$cadena_conexion = "//localhost:1521/XEPDB1";

$conn = oci_connect($usuario, $contrasena, $cadena_conexion);

if (!$conn) {
    $e = oci_error();
    die("Error al conectar con Oracle: " . $e['message']);
} else {
    echo "Conexión con Oracle realizada correctamente";
}
?>
