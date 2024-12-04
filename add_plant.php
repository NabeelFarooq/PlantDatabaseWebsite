<?php
header('Content-Type: application/json');
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = $_POST['name'] ?? '';
    $family = $_POST['family'] ?? '';
    $description = $_POST['description'] ?? '';
    $imageURL = $_POST['image'] ?? '';
    $uploadedImagePath = '';

    // Handle file upload if provided
    if (isset($_FILES['image-file']) && $_FILES['image-file']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/"; // Ensure this directory exists and is writable
        $fileName = basename($_FILES['image-file']['name']);
        $targetFilePath = $targetDir . uniqid() . "-" . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($_FILES['image-file']['tmp_name'], $targetFilePath)) {
                $uploadedImagePath = $targetFilePath;
            } else {
                echo json_encode(["success" => false, "message" => "Failed to upload the file."]);
                exit;
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed."]);
            exit;
        }
    }

    // Choose the image source (uploaded file or URL)
    $finalImage = !empty($uploadedImagePath) ? $uploadedImagePath : $imageURL;

    // Insert into database
    if (!empty($name) && !empty($family) && !empty($description) && !empty($finalImage)) {
        $sql = "INSERT INTO plants (name, family, description, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $family, $description, $finalImage);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Plant added successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
    }
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
?>

