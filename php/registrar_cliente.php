<?php
include 'conexion_oracle.php';

// Validar que todos los campos existan en POST
$campos_obligatorios = ['nombre', 'primer_apellido', 'segundo_apellido', 'correo', 'contrasenia', 'telefono', 'id_membresia', 'metodo_pago'];
$datos_faltantes = [];

foreach ($campos_obligatorios as $campo) {
    if (empty($_POST[$campo])) {
        $datos_faltantes[] = $campo;
    }
}

if (!empty($datos_faltantes)) {
    echo "❌ Error: Faltan los siguientes campos: " . implode(", ", $datos_faltantes);
    exit;
}

// Recibir datos del formulario
$nombre = trim($_POST['nombre']);
$primer_apellido = trim($_POST['primer_apellido']);
$segundo_apellido = trim($_POST['segundo_apellido']);
$correo = trim($_POST['correo']);
$contrasenia = trim($_POST['contrasenia']);
$telefono = trim($_POST['telefono']);
$id_membresia = trim($_POST['id_membresia']);
$metodo_pago = trim($_POST['metodo_pago']);

// Validaciones adicionales
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Error: El correo no tiene un formato válido.";
    exit;
}

if (!preg_match("/^[0-9]{10}$/", $telefono)) {
    echo "❌ Error: El teléfono debe tener 10 dígitos numéricos.";
    exit;
}

if (!preg_match("/^[0-9]{16}$/", $metodo_pago)) {
    echo "❌ Error: El método de pago debe ser un número de 16 dígitos.";
    exit;
}

// Insertar en la base de datos
$sql = "INSERT INTO CLIENTE (
            METODO_PAGO, CONTRASENIA, NOMBRE, PRIMER_APELLIDO, SEGUNDO_APELLIDO,
            CORREO, TELEFONO, ID_MEMBRESIA)
        VALUES (
            :metodo_pago, :contrasenia, :nombre, :primer_apellido, :segundo_apellido,
            :correo, :telefono, :id_membresia)";

$statement = oci_parse($conn, $sql);

// Vincular o enlazar parámetros
oci_bind_by_name($statement, ':metodo_pago', $metodo_pago);
oci_bind_by_name($statement, ':contrasenia', $contrasenia);
oci_bind_by_name($statement, ':nombre', $nombre);
oci_bind_by_name($statement, ':primer_apellido', $primer_apellido);
oci_bind_by_name($statement, ':segundo_apellido', $segundo_apellido);
oci_bind_by_name($statement, ':correo', $correo);
oci_bind_by_name($statement, ':telefono', $telefono);
oci_bind_by_name($statement, ':id_membresia', $id_membresia);

$resultado = oci_execute($statement);

// Ejecutar y verificar
if ($resultado) {
    echo "✅ Registro exitoso. ¡Bienvenido a CineTECH!";
} else {
    $error = oci_error($statement);
    
    if (strpos($error['message'], 'CLIENTE_CORREO_UK') !== false) {
        echo "❌ Error: El correo ya está registrado.";
    } elseif (strpos($error['message'], 'CLIENTE_TELEFONO_UK') !== false) {
        echo "❌ Error: El número de teléfono ya está registrado.";
    } elseif (strpos($error['message'], 'CLIENTE_MEMBRESIA_FK') !== false) {
        echo "❌ Error: La membresía seleccionada no es válida.";
    } else {
        echo "❌ Error al registrar: " . $error['message'];
    }
}

oci_free_statement($statement);
oci_close($conn);
?>
