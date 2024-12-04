<?php
include('db.php'); // Ensure this points to your `db.php` file for database connection.

// Pagination Settings

$limit = 9; // Number of plants per page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page = max($page, 1); // Ensure the page is at least 1
$offset = ($page - 1) * $limit;

// Get total plants count
$totalQuery = "SELECT COUNT(*) AS total FROM plants";
$result = $conn->query($totalQuery);
$totalPlants = $result->fetch_assoc()['total'];
$totalPages = ceil($totalPlants / $limit);
$limit = 9; // Number of plants per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$query = "SELECT * FROM plants LIMIT $start, $limit";
$plants = $conn->query($query);

$total_query = "SELECT COUNT(*) as total FROM plants";
$total_result = $conn->query($total_query);
$total = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);


// Fetch plants for the current page
$query = "SELECT * FROM plants LIMIT $limit OFFSET $offset";
$plants = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Plants</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <nav class="bg-blue-500 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.html" class="text-lg font-semibold hover:underline">&larr; Back to Main Site</a>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        

        <!-- Plants Display -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($row = $plants->fetch_assoc()): ?>
                <div class="bg-white shadow-md rounded-lg p-4 overflow-hidden">
                    <h2 class="text-lg font-semibold break-words"><?= htmlspecialchars($row['name']) ?></h2>
                    <p class="text-gray-600 italic break-words"><?= htmlspecialchars($row['family']) ?></p>
                    <p class="mt-2 text-sm break-words"><?= htmlspecialchars($row['description']) ?></p>
                    <?php if (!empty($row['image'])): ?>
                        <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="mt-4 h-40 w-full object-cover rounded-md">
                    <?php else: ?>
                        <div class="mt-4 h-40 w-full bg-gray-200 text-gray-500 flex items-center justify-center rounded-md">No Image</div>
                    <?php endif; ?>
                </div>


            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div class="container mx-auto p-6">
            <h1 class="text-3xl font-bold text-center mb-6">Plant Collection</h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php while ($row = $plants->fetch_assoc()): ?>
                    <div class="bg-white rounded-lg shadow p-4">
                        <img
                            src="<?= htmlspecialchars($row['image']) ?>"
                            alt="<?= htmlspecialchars($row['name']) ?>"
                            class="w-full h-40 object-cover rounded-lg mb-4">
                        <h3 class="text-xl font-bold"><?= htmlspecialchars($row['name']) ?></h3>
                        <p class="text-gray-600"><?= htmlspecialchars($row['family']) ?></p>
                        <p class="text-gray-700"><?= htmlspecialchars($row['description']) ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
            <div class="flex justify-center mt-6">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?= $i ?>"
                        class="mx-1 px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        </div>

    </div>
</body>

</html>