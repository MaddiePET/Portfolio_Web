<?php
session_start();
// Include the config.php file for database connection
include 'php/config.php';

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];  // Get email from session

    // Fetch staff details from the database
    $sql = "SELECT fname, profile_picture FROM staff WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  // Bind the email parameter
    $stmt->execute();
    $stmt->bind_result($fname, $profile_picture);
    $stmt->fetch();
    $stmt->close();
} else {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Data Analytics Page">
    <meta name="keywords" content="grocery, data, analytics">
    <meta name="author" content="Pookie">
    <link href="styles/stylefordata.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>Data Analytics | GotoGro</title>
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
    <!-- Sidebar Navigation -->
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
        <!-- Google Charts Loader -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <main>
        <section class="data-dashboard">
            <h1>Daily Insights</h1>

            <!-- Filter Options -->
            <div class="filter-container">
                <select id="filterType" onchange="updateFilterInputs()">
                    <option value="overall" selected>Overall</option>
                    <option value="daily">Daily</option>
                    <option value="monthly">Monthly</option>
                </select>
                

                <!-- Date Input for Daily Filter -->
                <input type="date" id="dailyDate" style="display: none;" onchange="fetchChartData(), fetchSummaryData()">

                <!-- Month Input for Monthly Filter -->
                <input type="month" id="monthlyDate" style="display: none;" onchange="fetchChartData(), fetchSummaryData()">
                
            </div>

            <div class="feature-container">
                <div class="feature-box" id="totalSales">
                    <h3>Total Sales</h3>
                    <p>$<span id="totalSalesValue">0.00</span></p>
                </div>
                <div class="feature-box" id="totalUnits">
                    <h3>Total Products Sold</h3>
                    <p><span id="totalUnitsValue">0</span> units</p>
                </div>
                <div class="feature-box" id="newMembers">
                    <h3>New Members</h3>
                    <p><span id="newMembersValue">0</span></p>
                </div>
            </div>
        </section>

        <div class="charts-container">
            <!-- Pie Chart -->
            <div id="piechart" class="chart"></div>
        
            <!-- Column Chart -->
            <div id="columnchart_values" class="chart"></div>
        </div>
        
        <!-- Filter Options -->
        <div class="filter-section">
            <h1>Monthly Performance of Products</h1>
            <div class="filter-container">
                <!-- Product Category Filter -->
                <select id="categoryFilter" onchange="updateProductFilter()">
                    <option value="">Select Category</option>
                </select>
        
                <!-- Product Filter -->
                <select id="productFilter">
                    <option value="">Select Product</option>
                </select>
            </div>
        </div>
        
        <section id="salesTrend">
            <!-- Sales Trend Line Chart will be inserted here -->
            <div id="salesTrendvalues" class="sales-chart"></div>
        </section>
        

    <script type="text/javascript">
        google.charts.load("current", { packages: ["corechart", "line", "column"] });
        google.charts.setOnLoadCallback(fetchChartData);

        window.addEventListener('load', function() {
            // Trigger the updateFilterInputs function to apply the default "overall" filter
            updateFilterInputs();
        });

        function updateFilterInputs() {
            const filterType = document.getElementById("filterType").value;
            document.getElementById("dailyDate").style.display = filterType === "daily" ? "inline" : "none";
            document.getElementById("monthlyDate").style.display = filterType === "monthly" ? "inline" : "none";
           // Fetch data based on the selected filter type
           fetchSummaryData();  // Fetch summary data (overall, daily, or monthly)
           fetchChartData();    // Fetch chart data (overall, daily, or monthly)
           fetchColumnChartData();
        }

        

        function fetchSummaryData() {
                    const filterType = document.getElementById("filterType").value;
                    let url = 'php/data_summary.php';

                    if (filterType === "daily") {
                        const dailyDate = document.getElementById("dailyDate").value;
                        url += `?filter=daily&date=${dailyDate}`;
                    } else if (filterType === "monthly") {
                        const monthlyDate = document.getElementById("monthlyDate").value;
                        url += `?filter=monthly&month=${monthlyDate}`;
                    }

                    fetch(url)
                        .then(response => response.json())
                        .then(data => updateSummaryCards(data))
                        .catch(error => console.error('Error fetching data:', error));
        }
        
        function updateSummaryCards(data) {
                    document.getElementById('totalSalesValue').textContent = parseFloat(data.total_sales).toFixed(2);
                    document.getElementById('totalUnitsValue').textContent = data.total_units;
                    document.getElementById('newMembersValue').textContent = data.new_members;
                    document.getElementById('stockInValue').textContent = data.stock_in;
        }


        function fetchChartData() {
            const filterType = document.getElementById("filterType").value;
            let url = 'php/payment_method_data.php';

            if (filterType === "daily") {
                const dailyDate = document.getElementById("dailyDate").value;
                url += `?filter=daily&date=${dailyDate}`;
            } else if (filterType === "monthly") {
                const monthlyDate = document.getElementById("monthlyDate").value;
                url += `?filter=monthly&month=${monthlyDate}`;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log("Fetched data:", data); // Check if data is in the expected format
                    drawChart(data);
                    fetchColumnChartData();
                })
                .catch(error => console.error('Error fetching data:', error));
        }


        function drawChart(chartData) {
            if (!chartData || chartData.length === 0) {
                console.error("No data to display in the chart.");
                document.getElementById('piechart').innerHTML = `
                            <p style="font-size: 20px; text-align: center; margin-top: 15%;">No data available</p>
                                `;
                return;
            }

            console.log("Data format for chart:", chartData); // Log data to check format

            const data = new google.visualization.DataTable();
            data.addColumn('string', 'Payment Method');
            data.addColumn('number', 'Count');
            data.addRows(chartData);

            const options = {
                title: 'Payment Methods',
                pieHole: 0.4,
                colors: ['#FF7F50', '#6495ED', '#FFD700', '#FF69B4', '#B22222'],
                backgroundColor: 'transparent',
                titleTextStyle: { fontSize: 18, color: '#444' },
                legend: { position: 'bottom', textStyle: { fontSize: 14 } }
            };

            const chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
}
            
function fetchColumnChartData() {
    const filterType = document.getElementById("filterType").value;
    let url = 'php/top_products_data.php'; // Update to your PHP endpoint that fetches the top 5 products per category
 
    if (filterType === "daily") {
        const dailyDate = document.getElementById("dailyDate").value;
        url += `?filter=daily&date=${dailyDate}`;
    } else if (filterType === "monthly") {
        const monthlyDate = document.getElementById("monthlyDate").value;
        url += `?filter=monthly&month=${monthlyDate}`;
    } else if (filterType === "overall") {
        url += `?filter=overall`; // No additional parameters needed
    }
 
    fetch(url)
        .then(response => response.json())
        .then(data => {
            console.log("Fetched data:", data); // Check if data is in the expected format
            drawColumnChart(data); // Pass data to the column chart function
        })
        .catch(error => console.error('Error fetching data:', error));
}

function drawColumnChart(chartData) {
    if (!chartData || chartData.length === 0) {
        console.error("No data to display in the chart.");
        document.getElementById('columnchart_values').innerHTML = `
            <p style="font-size: 20px; text-align: center; margin-top: 15%;">No data available</p>
            `;
        return;
    }

    console.log("Data format for chart:", chartData); // Log data to check format

    const data = new google.visualization.DataTable();
    data.addColumn('string', 'Product');
    data.addColumn('number', 'Units Sold');
    data.addColumn({ type: 'string', role: 'style' }); // For colors

    // Add rows to the data table, setting the same color for each product
    chartData.forEach(product => {
        data.addRow([product.product_name, product.units_sold, product.color]);
    });

    const options = {
        title: 'Top 5 Products by Sales',
        chartArea: { width: '50%' },
        hAxis: {
            title: 'Sales',
            minValue: 0
        },
        vAxis: {
            title: 'Units'
        },
        backgroundColor: 'transparent', // Set transparent background if needed
        legend: { position: 'none' }, // Hide legend if not needed
        bar: { groupWidth: "80%" }, // Adjust bar width
        titleTextStyle: { fontSize: 18, color: '#444' },
        hAxis: { textStyle: { fontSize: 15, color: '#444' } }
    };

    const chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
    chart.draw(data, options);
}

    </script>
            
        
        <script type="text/javascript">
            google.charts.setOnLoadCallback(drawSalesTrendChart);
        
            function fetchSalesTrend(productID) {
                fetch(`php/get_sales_trend.php?productID=${productID}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log('Fetched Sales Trend Data:', data);  // Log the data to see the format
                        drawSalesTrendChart(data);
                    })
                    .catch(error => console.error('Error fetching sales trend:', error));
            }
        
            function drawSalesTrendChart(salesData) {
                if (!salesData || salesData.length === 0) {
                    console.log("No sales data available for the selected product.");
                    document.getElementById('salesTrendvalues').innerHTML = `
                        <p style="font-size: 20px; text-align: center; margin-top: 15%;">No data available</p>
                        `;
                    return;
                }
        
                // Prepare data for the chart
                const data = new google.visualization.DataTable();
                data.addColumn('string', 'Month');
                data.addColumn('number', 'Sales ($)');
                
                salesData.forEach(item => {
                    const date = new Date(item.purchase_date); // Ensure proper date format
                    const monthName = date.toLocaleString('default', { month: 'long' });  // Convert date to month name
                    const sales = parseFloat(item.sales); // Convert sales to a number
            
                    // Check if sales is a valid number
                    if (!isNaN(sales)) {
                        data.addRow([monthName, sales]);
                    } else {
                        console.error('Invalid data:', item.purchase_date, item.sales);
                    }
                });

                // Log sales data to ensure proper format
                console.log('Sales Data for Chart:', salesData);
        
                const options = {
                    title: 'Sales Trend Over Time (Monthly)',
                    curveType: 'function',
                    legend: { position: 'bottom' },
                    hAxis: { title: 'Month' },
                    vAxis: { title: 'Sales ($)' },
                    colors: ['#6495ED']
                };
        
                const chart = new google.visualization.LineChart(document.getElementById('salesTrend'));
                chart.draw(data, options);
            }
        
            // Event listener for product selection
            document.getElementById('productFilter').addEventListener('change', function() {
                const productID = this.value;
                if (productID) {
                    fetchSalesTrend(productID); // Fetch and display sales trend for the selected product
                }
            });
        
        // Fetch categories from get_categories.php and populate the category filter dropdown
        fetch('php/get_categories.php')
        .then(response => response.json())
        .then(data => {
            const categoryFilter = document.getElementById('categoryFilter');
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.category;  // Use the category value as the option's value
                option.textContent = item.category; // Use the category name as the option's text
                categoryFilter.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching categories:', error));

        // Fetch products based on the selected category from get_products.php
        function updateProductFilter() {
        const selectedCategory = document.getElementById('categoryFilter').value;

        if (selectedCategory) {
            fetch(`php/get_products.php?category=${selectedCategory}`)
                .then(response => response.json())
                .then(data => {
                    const productFilter = document.getElementById('productFilter');
                    productFilter.innerHTML = '<option value="">Select Product</option>'; // Reset products
                    
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.productID;  // Use product ID as value
                        option.textContent = item.product_name; // Display product name
                        productFilter.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching products:', error));
        } else {
            // If no category is selected, clear the product filter
            document.getElementById('productFilter').innerHTML = '<option value="">Select Product</option>';
        }
        }

        function loadSalesTrend() {
    var productID = document.getElementById("productFilter").value;  // Ensure you are using the correct product filter ID

    if (productID) {
        // Fetch sales trend for the selected product
        fetch('php/get_sales_trend.php?productID=' + productID)
            .then(response => response.json())
            .then(data => {
                var salesTrendContainer = document.getElementById("salesTrend");
                
                // Clear previous sales trend
                salesTrendContainer.innerHTML = '';

                if (data.length > 0) {
                    // Prepare data for the line chart
                    var chartData = data.map(item => ({
                        date: item.purchase_date,
                        sales: item.sales
                    }));

                    // Create a canvas element for the chart
                    var canvas = document.createElement('canvas');
                    salesTrendContainer.appendChild(canvas);

                    var ctx = canvas.getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartData.map(item => item.date),  // X-axis labels (purchase dates)
                            datasets: [{
                                label: 'Sales Trend',  // Chart label
                                data: chartData.map(item => item.sales),  // Y-axis data (sales)
                                borderColor: '#FF665A',  // Line color (blue)
                                backgroundColor: 'rgba(74, 144, 226, 0.2)',  // Fill color (light blue)
                                borderWidth: 3,  // Line thickness
                                pointBackgroundColor: '#FF665A',  // Point color (same as line color)
                                pointRadius: 6,  // Larger points
                                pointHoverRadius: 8,  // Larger points on hover
                                fill: true  // Fill the area under the line
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Sales Trend for Product',  // Title text
                                    font: {
                                        size: 20,
                                        weight: 'bold'
                                    },
                                    color: '#333'  // Title color (dark gray)
                                },
                                legend: {
                                    position: 'top',
                                    labels: {
                                        color: '#666',  // Legend label color
                                        font: {
                                            size: 14
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    type: 'category',  // X-axis type: category (dates)
                                    title: {
                                        display: true,
                                        text: 'Date',  // X-axis title
                                        color: '#5A5A5A',  // X-axis title color (dark gray)
                                        font: {
                                            size: 16,
                                            weight: 'bold'
                                        }
                                    },
                                    ticks: {
                                        color: '#666',  // X-axis labels color
                                        font: {
                                            size: 12
                                        }
                                    },
                                    grid: {
                                        color: '#e0e0e0',  // Gridline color for x-axis
                                        borderColor: '#ccc',  // Border color for the chart
                                        borderWidth: 1
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Sales',  // Y-axis title
                                        color: '#5A5A5A',  // Y-axis title color
                                        font: {
                                            size: 16,
                                            weight: 'bold'
                                        }
                                    },
                                    ticks: {
                                        color: '#666',  // Y-axis labels color
                                        font: {
                                            size: 12
                                        }
                                    },
                                    grid: {
                                        color: '#e0e0e0',  // Gridline color for y-axis
                                        borderColor: '#ccc',  // Border color for the chart
                                        borderWidth: 1
                                    }
                                }
                            }
                        }
                    });
                } else {
                    salesTrendContainer.innerHTML = 'No sales data available for this product.';
                }
            })
            .catch(error => {
                console.error('Error fetching sales data:', error);
                document.getElementById("salesTrend").innerHTML = 'Error loading data.';
            });
    }
}


    </script>
    </main>

    <!-- Footer -->
    <footer>
        <p>&#169; 2024 GotoGro by Pookie</p>
    </footer>
</body>
</html>