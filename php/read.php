<?php
// Set the content type to application/json for the response
header('Content-Type: application/json');

// Database connection details (ensure these are correct)
$db_user = 'hr';
$db_pass = 'hr';
$db_conn_str = '//localhost:1521/proyecto';

$conn = oci_connect($db_user, $db_pass, $db_conn_str);

if (!$conn) {
    $e = oci_error();
    echo json_encode(["error" => "Oracle Connection Error: " . $e['message']]);
    exit;
}

$movieId = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($movieId === null || $movieId <= 0) {
    echo json_encode(["error" => "Invalid or missing movie ID."]);
    oci_close($conn);
    exit;
}

// IMPORTANT: Actualiza los nombres de tabla y columnas según tu esquema.
// Seleccionamos el título, sinopsis, la ruta de imagen (como fallback),
// el BLOB del póster y su tipo MIME.
$sql = "SELECT ID_PELICULA, TITULO, SINOPSIS, RUTA_IMAGEN_FUNCION, POSTER_BLOB, POSTER_MIMETYPE 
        FROM PELICULA 
        WHERE ID_PELICULA = :id_bv";
// Reemplaza PELICULA, ID_PELICULA, TITULO, SINOPSIS, RUTA_IMAGEN_FUNCION, POSTER_BLOB, POSTER_MIMETYPE
// con los nombres reales de tu tabla y columnas.

$stid = oci_parse($conn, $sql);

if (!$stid) {
    $e = oci_error($conn);
    echo json_encode(["error" => "Oracle Parse Error: " . $e['message']]);
    oci_close($conn);
    exit;
}

oci_bind_by_name($stid, ":id_bv", $movieId);

if (!oci_execute($stid)) {
    $e = oci_error($stid);
    echo json_encode(["error" => "Oracle Execute Error: " . $e['message']]);
    oci_free_statement($stid);
    oci_close($conn);
    exit;
}

$movieData = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS);

if ($movieData) {
    // Procesar el BLOB del póster si existe
    if (isset($movieData['POSTER_BLOB']) && is_object($movieData['POSTER_BLOB'])) {
        $lob = $movieData['POSTER_BLOB']; // Esto es un objeto OCI-Lob
        $imageData = $lob->read($lob->size()); // Leer todo el contenido del BLOB
        $lob->free(); // Liberar el descriptor LOB

        $movieData['POSTER_BASE64'] = base64_encode($imageData); // Codificar a Base64

        // Asegúrate de que POSTER_MIMETYPE esté presente.
        // Si no lo almacenas en la BD, deberás asignarlo aquí.
        // Ejemplo: if (empty($movieData['POSTER_MIMETYPE'])) { $movieData['POSTER_MIMETYPE'] = 'image/jpeg'; }
        // Es MUY recomendable almacenar el tipo MIME en la base de datos.
    } else {
        $movieData['POSTER_BASE64'] = null; // No hay BLOB o no es un objeto LOB
    }
    // No envíes el objeto LOB crudo en el JSON, ya lo procesamos.
    unset($movieData['POSTER_BLOB']);

    echo json_encode($movieData);
} else {
    echo json_encode(["error" => "Movie with ID " . $movieId . " not found."]);
}

oci_free_statement($stid);
oci_close($conn);
?>