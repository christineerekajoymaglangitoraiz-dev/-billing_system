<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
}

include("../config/db.php");

// Add customer
if (isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $contact = $_POST['contact'];

    mysqli_query($conn, "INSERT INTO customers (name, contact)
    VALUES ('$name', '$contact')");
    
    $success = "Customer added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers - Printing Shop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        }
        
        body {
            background-color: #f5f5f7;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 24px;
            margin-bottom: 30px;
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #007AFF;
            background: linear-gradient(135deg, #007AFF, #5856D6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #666;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background-color: #007AFF;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .page-title {
            font-size: 32px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .page-subtitle {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
        }
        
        .navigation {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .nav-button {
            background-color: white;
            color: #333;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .nav-button:hover, .nav-button.active {
            background-color: #007AFF;
            color: white;
            border-color: #007AFF;
        }
        
        .logout-button {
            background-color: #ffeaea;
            color: #d32f2f;
        }
        
        .logout-button:hover {
            background-color: #d32f2f;
            color: white;
            border-color: #d32f2f;
        }
        
        .content-section {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .form-card, .table-card {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
            font-weight: 500;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
            background-color: #fafafa;
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #007AFF;
            background-color: white;
        }
        
        .submit-button {
            background-color: #007AFF;
            color: white;
            border: none;
            padding: 14px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }
        
        .submit-button:hover {
            background-color: #0056cc;
        }
        
        .success-message {
            background-color: #E8F5E9;
            color: #2E7D32;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #C8E6C9;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background-color: #f8f9fa;
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #e9ecef;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 16px;
            border-bottom: 1px solid #e9ecef;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .customer-id {
            font-weight: 600;
            color: #007AFF;
        }
        
        .customer-name {
            font-weight: 500;
            color: #333;
        }
        
        .customer-contact {
            color: #666;
            font-size: 14px;
        }
        
        .customer-date {
            color: #888;
            font-size: 14px;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #007AFF;
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #ddd;
        }
        
        .stats-card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            text-align: center;
            border-top: 4px solid #007AFF;
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        @media (max-width: 1024px) {
            .content-section {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .navigation {
                justify-content: center;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
        }
        
        .search-box {
            margin-bottom: 20px;
        }
        
        .search-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background-color: #fafafa;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #007AFF;
            background-color: white;
        }
    </style>
    <script>
        function searchCustomers() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('customersTable');
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const name = row.cells[1].textContent.toLowerCase();
                const contact = row.cells[2].textContent.toLowerCase();
                const date = row.cells[3].textContent.toLowerCase();
                
                if (name.includes(filter) || contact.includes(filter) || date.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }
        
        // Auto-focus name field when page loads
        window.addEventListener('load', function() {
            document.querySelector('input[name="name"]').focus();
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-top">
                <div class="logo">PrintShop</div>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php 
                        $user = $_SESSION['user'];
                        echo strtoupper(substr($user['username'], 0, 1)); 
                        ?>
                    </div>
                    <div>
                        <strong><?php echo htmlspecialchars($user['fullname']); ?></strong>
                        <div style="font-size: 12px; color: #888;"><?php echo htmlspecialchars($user['role']); ?></div>
                    </div>
                </div>
            </div>
            
            <h1 class="page-title">Customer Management</h1>
            <p class="page-subtitle">Add and manage your printing shop customers</p>
        </div>
        
        <div class="navigation">
            <a href="dashboard.php" class="nav-button">Dashboard</a>
            <a href="customers.php" class="nav-button active">Customers</a>
            <a href="services.php" class="nav-button">Services</a>
            <a href="billing.php" class="nav-button">Billing</a>
            <a href="print_jobs.php" class="nav-button">Print Jobs</a>
            <a href="../auth/logout.php" class="nav-button logout-button">Logout</a>
        </div>
        
        <?php
        // Count total customers
        $customer_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM customers"));
        ?>
        
        <div class="stats-card">
            <div class="stat-value"><?php echo $customer_count; ?></div>
            <div class="stat-label">Total Customers</div>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="success-message">
                ✓ <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <div class="content-section">
            <div class="form-card">
                <h3 class="section-title">Add New Customer</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Customer Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter customer name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact">Contact Number</label>
                        <input type="text" id="contact" name="contact" placeholder="Enter contact number">
                    </div>
                    
                    <button type="submit" name="add_customer" class="submit-button">Add Customer</button>
                </form>
            </div>
            
            <div class="table-card">
                <h3 class="section-title">Customer List</h3>
                
                <div class="search-box">
                    <input type="text" id="searchInput" class="search-input" 
                           placeholder="Search customers by name, contact, or date..." 
                           onkeyup="searchCustomers()">
                </div>
                
                <?php
                $customers = mysqli_query($conn, "SELECT * FROM customers ORDER BY id DESC");
                
                if (mysqli_num_rows($customers) > 0): 
                ?>
                    <table id="customersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($customers)): ?>
                                <tr>
                                    <td class="customer-id">#<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></td>
                                    <td class="customer-name"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td class="customer-contact">
                                        <?php echo htmlspecialchars($row['contact']) ?: 'N/A'; ?>
                                    </td>
                                    <td class="customer-date">
                                        <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-data">
                        No customers found. Add your first customer using the form on the left.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
        
        <!-- Hidden original content for compatibility -->
        <div style="display: none;">
            <h2>Customers</h2>

            <form method="POST">
                <input type="text" name="name" placeholder="Customer Name" required>
                <input type="text" name="contact" placeholder="Contact Number">
                <button name="add_customer">Add Customer</button>
            </form>

            <hr>

            <h3>Customer List</h3>

            <table border="1" cellpadding="5">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Date</th>
                </tr>

                <?php
                mysqli_data_seek($customers, 0);
                while ($row = mysqli_fetch_assoc($customers)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['contact']}</td>
                            <td>{$row['created_at']}</td>
                          </tr>";
                }
                ?>
            </table>

            <br>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>