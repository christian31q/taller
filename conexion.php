<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$response = ["status" => "error", "message" => "Unknown error"]; // Mensaje de error por defecto

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['title']) && isset($_FILES['image']) && isset($_POST['description'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];

            // Manejar la carga de la imagen
            $image = $_FILES['image']['tmp_name'];
            $imageContent = addslashes(file_get_contents($image)); // Asegurar que el contenido de la imagen esté escapado correctamente

            // Conectar a la base de datos
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "mydatabase";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar conexión
            if ($conn->connect_error) {
                throw new Exception("Connection failed: " . $conn->connect_error);
            }

            $sql = "INSERT INTO imagenes (title, image, description) VALUES ('$title', '$imageContent', '$description')";

            if ($conn->query($sql) === TRUE) {
                $response = ["status" => "success", "message" => "Record added successfully"];
            } else {
                throw new Exception("Error: " . $sql . "<br>" . $conn->error);
            }

            $conn->close();
        } else {
            $response = ["status" => "error", "message" => "Missing required fields"];
        }
    } else {
        $response = ["status" => "error", "message" => "Invalid request method"];
    }
} catch (Exception $e) {
    $response = ["status" => "error", "message" => $e->getMessage()];
}

echo json_encode($response);
?>
