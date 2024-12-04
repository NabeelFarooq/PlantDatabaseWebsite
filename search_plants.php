<?php
header('Content-Type: application/json');
include('db.php');

// Get the search term
$searchTerm = isset($_GET['q']) ? $_GET['q'] : '';

// Search for plants in the database
$sql = "SELECT * FROM plants WHERE name LIKE ? OR family LIKE ? OR description LIKE ?";
$stmt = $conn->prepare($sql);
$searchTermWildcards = "%" . $searchTerm . "%";
$stmt->bind_param("sss", $searchTermWildcards, $searchTermWildcards, $searchTermWildcards);

$stmt->execute();
$result = $stmt->get_result();

$plants = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $plants[] = $row;
    }
}

echo json_encode($plants);
$conn->close();
?>
