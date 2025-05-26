<?php

// Establecer la conexión a la base de datos Oracle
// $conn = oci_connect("hr", "hr", "localhost/XEPDB1");

$server_name = "hr";
$server_pass = "hr";
$server_conn_str = "localhost/XEPDB1"; 

$conn = oci_connect($server_name, $server_pass, $server_conn_str);

if (!$conn) {
    $e = oci_error();
    die("Error de conexión: " . $e['message']);
} else{
    echo "Conexión exitosa a la base de datos Oracle.<br>";
}

// Consulta para obtener los datos de la tabla PELICULA

$sql = "SELECT ID_PELICULA, NOMBRE, CLASIFICACION, DURACION, GENERO FROM PELICULA"; // Asumiendo que tu tabla se llama "CLIENTE"
$stid = oci_parse($conn, $sql);
oci_execute($stid);

while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
    foreach ($row as $item) {
        echo ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . " ";
    }
    echo "<br>\n";
}

oci_free_statement($stid);
oci_close($conn);
?>