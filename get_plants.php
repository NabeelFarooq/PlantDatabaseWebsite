<?php
header('Content-Type: application/json');
include('db.php');

// Fetch all plants from the database
$sql = "SELECT * FROM plants";
$result = $conn->query($sql);

$plants = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $plants[] = $row;
    }
}

echo json_encode($plants);
$conn->close();
?>
