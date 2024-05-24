<?php
header('Content-Type: application/json');

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Conexión fallida: " . $conn->connect_error]));
}

// Consulta para obtener las imágenes
$sql = "SELECT id, title, description, image FROM imagenes";
$result = $conn->query($sql);

// Verificar si hay resultados
$images_array = array(); // Array para almacenar las imágenes en formato JSON

if ($result->num_rows > 0) {
    // Obtener las imágenes y agregarlas al array
    while ($row = $result->fetch_assoc()) {
        $image_data = array(
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'image' => base64_encode($row['image']) // Codificar la imagen en base64
        );

        array_push($images_array, $image_data);
    }
}

// Convertir el array a formato JSON
$json_data = json_encode($images_array);

// Mostrar el JSON
echo $json_data;

$conn->close();
?>