<?php
session_start();

// Include config.php for database connection
include 'config.php';


$columnMaps = [
    'Members' => [
        "First Name" => "fname",
        "Last Name" => "lname",
        "Date of Birth" => "dob",
        "Gender" => "gender",
        "Email" => "email",
        "Phone Number" => "phno",
        "Address" => "address",
        "Registration Date" => "registration_date"
    ],
    'Products' => [
        "Product Name" => "product_name",
        "Product Price" => "product_price",
        "Category" => "category",
        "Inventory Quantity" => "inv_qty",
        "Last Restock Date" => "last_restock_date"
    ],
    'Sales' => [
        "Transaction ID" => "transactionID",
        "Member ID" => "memberID",        
        "Total Price" => "total_price",
        "Payment Method" => "payment_method",
        "Date of Transaction" => "purchase_date" 
    ],
];

// Ensure Content-Type is JSON for non-CSV responses
header('Content-Type: application/json');

$table = $_POST['table'] ?? null;

if (!isset($columnMaps[$table])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid table selected.']);
    exit;
}

$columnMap = $columnMaps[$table];
$selectedColumns = $_POST['columns'] ?? [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($selectedColumns)) {
        echo json_encode(['status' => 'error', 'message' => 'No columns selected.']);
        exit;
    }

    // Map columns for SQL query
    $mappedColumns = array_map(function ($column) use ($columnMap) {
        return "`" . $columnMap[$column] . "`";
    }, $selectedColumns);

    $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
    $endDate = mysqli_real_escape_string($conn, $_POST['endDate']);

    $query = "";
    if ($table === 'Products') {
        $query = "SELECT " . implode(", ", $mappedColumns) . " FROM `product` WHERE DATE(last_restock_date) BETWEEN '$startDate' AND '$endDate'";
    } elseif ($table === 'Members') {
        $query = "SELECT " . implode(", ", $mappedColumns) . " FROM `member` WHERE DATE(registration_date) BETWEEN '$startDate' AND '$endDate'";
    } elseif ($table === 'Sales') {
        $query = "SELECT " . implode(", ", $mappedColumns) . " FROM `sales_transactions` WHERE DATE(purchase_date) BETWEEN '$startDate' AND '$endDate'";
    }

    $result = $conn->query($query);
    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
        exit;
    }

    if ($result->num_rows > 0) {
        // If we have CSV data, start CSV output and stop JSON output
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment;filename={$table}_report.csv");

        $output = fopen('php://output', 'w');
        fputcsv($output, $selectedColumns); // Add column headers

        while ($row = $result->fetch_assoc()) {
            $csvRow = array_map(function ($col) use ($row, $columnMap) {
                return $row[$columnMap[$col]] ?? '';
            }, $selectedColumns);
            fputcsv($output, $csvRow);
        }
        fclose($output);
        exit;
    } else {
        echo json_encode(['status' => 'info', 'message' => 'No records found for the selected date range and columns.']);
        exit;
    }
}
?>
