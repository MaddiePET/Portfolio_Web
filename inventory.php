<?php
session_start();
// Include the config.php file for database connection
include 'php/config.php';


if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];  // Get email from session

    // Fetch staff details from the database based on the email
    $sql = "SELECT fname, profile_picture FROM staff WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  // Bind the email parameter
    $stmt->execute();
    $stmt->bind_result($fname, $profile_picture);
    $stmt->fetch();
    $stmt->close();
}

$categoryOptions = [];
$result = $conn->query("SHOW COLUMNS FROM product LIKE 'category'");
if ($result) {
    $row = $result->fetch_assoc();
    preg_match("/^enum\((.*)\)$/", $row['Type'], $matches);
    $categoryOptions = str_getcsv($matches[1], ',', "'");
}

$message = "";

function getCategoryPrefix($category) {
    $prefixes = [
        'Dairy' => 'D',
        'Vegetable' => 'V',
        'Fruit' => 'F',
        'Beverage' => 'B',
        'Fruits' => 'F',
        'Pastry' => 'P',
        'Meat' => 'M',
        'Personal Care' => 'PC',
        'Snacks' => 'S',
        'Grains' => 'G',
        'Household Supplies' => 'HS',

    ];
    
    return $prefixes[$category] ?? '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        // Adding a new product
        $productName = $_POST['product_name'];
        $productPrice = $_POST['product_price'];
        $category = $_POST['category'];
        $inventoryQuantity = $_POST['inv_qty'];
        $restockDate = $_POST['last_restock_date'];

        // Check if product name already exists
        $checkProductQuery = "SELECT * FROM product WHERE product_name = '$productName'";
        $checkProductResult = $conn->query($checkProductQuery);

        if ($checkProductResult->num_rows > 0) {
            $errormessage = "The product '$productName' already exists.";
        } else {
            if ($conn->query("INSERT INTO product (product_name, product_price, category, inv_qty, last_restock_date) 
            VALUES ('$productName', '$productPrice', '$category', '$inventoryQuantity', '$restockDate')")) {
                $message = "'$productName' has been added successfully!"; 
            } else {
                echo "Error: " . $conn->error; 
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'update') {
        $productID = $_POST['product_id'];
        $newQuantity = $_POST['inv_qty'];
        $restockDate = $_POST['last_restock_date'];
    
        $productQuery = "SELECT inv_qty, product_name FROM product WHERE productID = $productID";
        $productResult = $conn->query($productQuery);
    
        if ($productResult && $productResult->num_rows > 0) {
            $productRow = $productResult->fetch_assoc();
            $currentQuantity = $productRow['inv_qty'];
            $product_name = $productRow['product_name']; 
    
            $updatedQuantity = $currentQuantity + $newQuantity;
    
            if ($conn->query("UPDATE product SET inv_qty = $updatedQuantity, last_restock_date = '$restockDate' WHERE productID = $productID")) {
                $message = "'$product_name' has been updated successfully!";
            } else {
                echo "Error: " . $conn->error;
            }
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        $productID = $_POST['product_id'];

        $productQuery = "SELECT product_name FROM product WHERE productID = $productID";
        $productResult = $conn->query($productQuery);


        if ($productResult && $productResult->num_rows > 0) {
            $productRow = $productResult->fetch_assoc();
            $productName = $productRow['product_name']; 

            $conn->query("DELETE FROM transaction_item WHERE productID = $productID");

            if ($conn->query("DELETE FROM product WHERE productID = $productID")) {
                $message = "'$productName' has been deleted successfully!"; 
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }
} 

$filterConditions = [];

// Handle stock status filter
if (!empty($_POST['stock_status'])) {
    $stockConditions = [];
    foreach ($_POST['stock_status'] as $status) {
        if ($status == 'low-stock') {
            $stockConditions[] = "inv_qty < 20";
        } elseif ($status == 'out-of-stock') {
            $stockConditions[] = "inv_qty = 0";
        }
    }
    if (!empty($stockConditions)) {
        $filterConditions[] = '(' . implode(' OR ', $stockConditions) . ')';
    }
}

// Handle category filter
if (!empty($_POST['category']) && is_array($_POST['category'])) {
    $categoryConditions = array_map(fn($cat) => "'$cat'", $_POST['category']);
    $filterConditions[] = "category IN (" . implode(',', $categoryConditions) . ")";
}

// Build final SQL query with filters
$sql = "SELECT * FROM product";
if (!empty($filterConditions)) {
    $sql .= " WHERE " . implode(" AND ", $filterConditions);
}
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . $conn->error); 
}

$conn->close(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Inventory Management Page">
    <meta name="keywords" content="grocery, inventory"> 
    <link href="styles/styleforinventory.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>Inventory | GotoGro</title>
</head>
<body>

<header class="topbar">
    <div class="profile-logout-container">
        <div class="profile-picture">
            <img src="<?php echo file_exists($profile_picture) ? htmlspecialchars($profile_picture) : 'staff_profile_picture/default.jpg'; ?>" alt="Profile Picture" class="profile-img">
            <span class="greeting">Welcome, <?php echo htmlspecialchars($fname); ?>!</span>
        </div>
        <div class="logout-button">
            <a href="login.php" class="btn-logout">Logout</a>
        </div>
    </div>
</header>
<header>
        <nav class="navbar">
            <div class="logo-container">
                <img src="styles/images/logo.png" alt="GotoGro Logo" class="logo">
                <a href="index.html" class="nav-title">GotoGro-MRMS</a>
            </div>
            <div class="nav-links">
                <a href="data.php"><img src="styles/images/analytics.png">Dashboard</a>
                <a href="members.php"><img src="styles/images/members.png">Members</a>
                <a href="inventory.php"><img src="styles/images/inventory.png">Inventory</a>
                <a href="sales.php"><img src="styles/images/sales.png">Sales</a>
                <a href="report.php"><img src="styles/images/report.png">Report</a>
                <a href="notification.php"><img src="styles/images/notification.png">Notifications</a>
                <a href="account.php"><img src="styles/images/account.png">Account</a>
            </div>
        </nav>
    </header>

    <main>
        <section class="inventory-dashboard">
            <h1>Inventory</h1>

            <?php if (!empty($message)): ?>
                <div class="notification">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errormessage)): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($errormessage); ?>
                </div>
            <?php endif; ?>

            <div class="filter-container">
                <input type="text" class="search-bar" id="searchBar" placeholder="Search by Product ID or Name">

                <div class="search-filter">
                    <button class="filter-button" id="filterButton"><img src="styles/images/filter.png">Filter Product</button>
                    <button class="add-button" id="AddButton"><img src="styles/images/add-product.png">Add Product</button>
                </div>
            </div>

            <div class="sidebar" id="filterSidebar">
                <span class="close-button" id="closeSidebar">&times;</span>

                <h3>Filter & Sort</h3>
                <form method="POST" action="inventory.php">
                    <div class="filter-options" id="stockOptions">
                        <h3>Stock Status</h3>
                        <div class="checkbox-item">
                            <label><input type="checkbox" name="stock_status[]" value="low-stock">Low Stock</label><br>
                        </div>
                        <div class="checkbox-item">
                            <label><input type="checkbox" name="stock_status[]" value="out-of-stock">Out of Stock</label><br>
                        </div>
                    </div>

                    <div class="filter-options">
                        <h3>Categories</h3>
                            <div class="checkbox-item">
                                <label><input type="checkbox" id="selectAllCategories" onclick="toggleCategoryCheckboxes()">All</label><br>
                            </div>
                            <?php foreach ($categoryOptions as $option): ?>
                            <div class="checkbox-item">
                                <label><input type="checkbox" name="category[]" value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></label><br>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="filter-buttons">
                        <button type="submit" class="but apply-button" id="applyButton">Apply</button>
                        <button type="button" class="but clear-filter" onclick="clearFilters()">Clear Filter</button>
                    </div>
                </form>
            </div>


            <div class="product-cards" id="productCards">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): 
                        $productID = htmlspecialchars($row['productID']);
                        $productName = htmlspecialchars($row['product_name']);
                        $productPrice = htmlspecialchars($row['product_price']);
                        $category = htmlspecialchars($row['category']);
                        $inventoryQty = htmlspecialchars($row['inv_qty']);
                        $lastRestockDate = htmlspecialchars($row['last_restock_date']);

                        $categoryPrefix = getCategoryPrefix($category);
                        $displayID = $categoryPrefix . $productID; // Concatenate prefix with product ID
                    ?>
                        <div class="product-card" id="product-<?= $productID ?>"> 
                            <h3><?= $productName ?></h3>
                            <p><strong>Product ID:</strong> <?= $displayID ?></p> <!-- Display prefixed product ID -->
                            <p><strong>Price:</strong> $<?= $productPrice ?></p>
                            <p><strong>Category:</strong> <?= $category ?></p>
                            <div class="card-button">
                                <button class="btn update-button" onclick="openUpdateForm(<?= $productID ?>, '<?= $productName ?>', <?= $productPrice ?>, '<?= $category ?>', <?= $inventoryQty ?>, '<?= $lastRestockDate ?>')">
                                    <img src="styles/images/edit.png" alt="Update">
                                </button>
                                <button class="btn view-button" onclick="openViewForm('<?= $productName ?>', <?= $inventoryQty ?>, '<?= $lastRestockDate ?>')">
                                    <img src="styles/images/view.png" alt="View">
                                </button>
                                <button class="btn delete-button" onclick="openDeleteForm(<?= $productID ?>, '<?= $productName ?>')">
                                    <img src="styles/images/delete.png" alt="Delete">
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No products found.</p>
                <?php endif; ?>
            </div>

            <div class="form" id="addForm">
                <div class="add-form">
                    <span class="close-button" id="closeAddForm">&times;</span>
                    <h2>New Product</h2>
                    <form method="POST" action="inventory.php">
                        <input type="hidden" name="action" value="add">
                        
                        <p>
                            <label for="category">Category:</label>
                            <select id="category" name="category" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categoryOptions as $option): ?>
                                    <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                        
                        <p>
                            <label for="product_name">Product Name:</label>
                            <input type="text" id="product_name" name="product_name" required disabled>
                        </p>

                        <p>
                            <label for="product_price">Product Price:</label>
                            <input type="number" id="product_price" name="product_price" step="0.01" required min="0.00" disabled>
                        </p>

                        <p>
                            <label for="inv_qty">Quantity:</label>
                            <input type="number" id="inv_qty" name="inv_qty" required min="1" disabled>
                        </p>

                        <p>
                            <label for="last_restock_date">Restock Date:</label>
                            <input type="date" id="last_restock_date" name="last_restock_date" readonly>
                        </p>
                        
                        <div class="button-container">
                            <button type="submit" class="but ad-button">Add</button>
                            <button type="reset" class="but clear-button">Clear</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="form" id="updateForm">
                <div class="update-form">
                    <span class="close-button" id="closeUpdateForm">&times;</span>
                    <h2 id="updateFormTitle"></h2>
                    <form method="POST" action="inventory.php">
                        <input type="hidden" name="action" value="update">

                        <input type="hidden" id="update_product_id" name="product_id">
                        
                        <p>
                            <label for="update_product_name">Product Name:</label>
                            <input type="text" id="update_product_name" name="product_name" readonly>
                        </p>

                        <p>
                            <label for="update_inv_qty">Add Quantity:</label>
                            <input type="number" id="update_inv_qty" name="inv_qty" required min="1">
                        </p>

                        <p>
                            <label for="update_restock_date">Restock Date:</label>
                            <input type="date" id="update_restock_date" name="last_restock_date" required>
                        </p>
                        
                        <div class="button-container">
                            <button type="submit" class="but up-button">Update</button>
                            <button type="button" class="but cancel-button" onclick="resetForm()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="form" id="viewForm">
                <div class="view-form">
                    <span class="close-button" id="closeViewForm">&times;</span>
                    <h2 id="viewFormTitle"></h2>
                    <table id="viewFormTable">
                    <thead>
                        <tr>
                            <th>Last Restock Date</th>
                            <th>Current Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="restockDateValue"></td>
                            <td id="stockValue"></td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>

            <div class="form" id="deleteForm">
                <div class="delete-form">
                <h2 id="deleteFormTitle"></h2>
                <p>Are you sure you want to delete this product?</p>
                    <form method="POST" action="inventory.php">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" id="delete_product_id" name="product_id">
                        
                        <div class="button-container">
                            <button type="submit" class="but del-button">Yes, Delete</button>
                            <button type="button" class="but cancel-button" id="cancelButton">No, Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&#169; 2024 GotoGro by Pookie</p>
    </footer>

    <script src="javascript/inventory.js"></script>
    <script src="javascript/notification.js"></script>

</body>
</html>