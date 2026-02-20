<?php
session_start();
include("../config/db.php");
if (!isset($_SESSION['user'])) header("Location: ../auth/login.php");

if (isset($_POST['add'])) {
    mysqli_query($conn,"INSERT INTO services (service_name, price, stock)
    VALUES ('{$_POST['name']}', '{$_POST['price']}', '{$_POST['stock']}')");
    
    $success = "Service added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Printing Shop</title>
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
        
        .form-card, .services-card {
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
        
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
            background-color: #fafafa;
        }
        
        input[type="text"]:focus, input[type="number"]:focus {
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
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .service-item {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            border-left: 4px solid #007AFF;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .service-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        
        .service-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .service-price {
            font-size: 24px;
            font-weight: 700;
            color: #007AFF;
            margin-bottom: 8px;
        }
        
        .service-stock {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stock-high {
            background-color: #E8F5E9;
            color: #388E3C;
        }
        
        .stock-medium {
            background-color: #FFF3E0;
            color: #F57C00;
        }
        
        .stock-low {
            background-color: #FFEBEE;
            color: #D32F2F;
        }
        
        .service-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            font-size: 12px;
            color: #888;
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
        
        .no-services {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #ddd;
            grid-column: 1 / -1;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-align: center;
            border-top: 4px solid;
        }
        
        .services-card {
            border-top-color: #007AFF;
        }
        
        .revenue-card {
            border-top-color: #4CAF50;
        }
        
        .lowstock-card {
            border-top-color: #F44336;
        }
        
        .stat-value {
            font-size: 32px;
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
        
        .currency {
            font-size: 18px;
            color: #666;
        }
        
        @media (max-width: 1024px) {
            .content-section {
                grid-template-columns: 1fr;
            }
            
            .services-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
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
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .input-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .price-input {
            position: relative;
        }
        
        .price-input::before {
            content: "₱";
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-weight: 500;
        }
        
        .price-input input {
            padding-left: 30px;
        }
    </style>
    <script>
        // Auto-format price input
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.querySelector('input[name="price"]');
            
            priceInput.addEventListener('input', function() {
                let value = this.value.replace(/[^\d.]/g, '');
                this.value = value;
            });
            
            // Auto-focus on service name field
            document.querySelector('input[name="name"]').focus();
        });
        
        function getStockClass(stock) {
            if (stock >= 20) return 'stock-high';
            if (stock >= 10) return 'stock-medium';
            return 'stock-low';
        }
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
            
            <h1 class="page-title">Services Management</h1>
            <p class="page-subtitle">Add and manage printing services and pricing</p>
        </div>
        
        <div class="navigation">
            <a href="dashboard.php" class="nav-button">Dashboard</a>
            <a href="customers.php" class="nav-button">Customers</a>
            <a href="services.php" class="nav-button active">Services</a>
            <a href="billing.php" class="nav-button">Billing</a>
            <a href="print_jobs.php" class="nav-button">Print Jobs</a>
            <a href="../auth/logout.php" class="nav-button logout-button">Logout</a>
        </div>
        
        <?php
        // Calculate statistics
        $total_services = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM services"));
        $total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(price) as total FROM services"));
        $low_stock = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM services WHERE stock <= 5"));
        ?>
        
        <div class="stats-grid">
            <div class="stat-card services-card">
                <div class="stat-value"><?php echo $total_services; ?></div>
                <div class="stat-label">Total Services</div>
            </div>
            
            <div class="stat-card revenue-card">
                <div class="stat-value">
                    <span class="currency">₱</span><?php echo number_format($total_revenue['total'] ?? 0, 2); ?>
                </div>
                <div class="stat-label">Total Price Value</div>
            </div>
            
            <div class="stat-card lowstock-card">
                <div class="stat-value"><?php echo $low_stock; ?></div>
                <div class="stat-label">Low Stock Items (≤5)</div>
            </div>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="success-message">
                ✓ <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <div class="content-section">
            <div class="form-card">
                <h3 class="section-title">Add New Service</h3>
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Service Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter service name" required>
                    </div>
                    
                    <div class="form-group price-input">
                        <label for="price">Price (₱)</label>
                        <input type="number" id="price" name="price" placeholder="0.00" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="stock">Stock Quantity</label>
                        <input type="number" id="stock" name="stock" placeholder="Enter stock quantity" min="0" required>
                    </div>
                    
                    <button type="submit" name="add" class="submit-button">Add Service</button>
                </form>
            </div>
            
            <div class="services-card">
                <h3 class="section-title">Available Services</h3>
                
                <?php
                $q=mysqli_query($conn,"SELECT * FROM services");
                
                if (mysqli_num_rows($q) > 0): 
                ?>
                    <div class="services-grid">
                        <?php while($s=mysqli_fetch_assoc($q)): ?>
                            <div class="service-item">
                                <div class="service-name"><?php echo htmlspecialchars($s['service_name']); ?></div>
                                <div class="service-price">₱<?php echo number_format($s['price'], 2); ?></div>
                                <div class="service-stock <?php 
                                    echo $s['stock'] >= 20 ? 'stock-high' : 
                                         ($s['stock'] >= 10 ? 'stock-medium' : 'stock-low'); 
                                ?>">
                                    Stock: <?php echo $s['stock']; ?>
                                </div>
                                <div class="service-meta">
                                    <span>Service ID: #<?php echo str_pad($s['id'], 4, '0', STR_PAD_LEFT); ?></span>
                                    <span>Added: <?php echo date('M d, Y', strtotime($s['created_at'])); ?></span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="no-services">
                        No services found. Add your first service using the form on the left.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
        
        <!-- Hidden original content for compatibility -->
        <div style="display: none;">
            <form method="POST">
                <input name="name" placeholder="Service">
                <input name="price" placeholder="Price">
                <input name="stock" placeholder="Stock">
                <button name="add">Add</button>
            </form>

            <?php
            mysqli_data_seek($q, 0);
            while($s=mysqli_fetch_assoc($q)){
                echo "{$s['service_name']} ₱{$s['price']} Stock:{$s['stock']}<br>";
            }
            ?>
        </div>
    </div>
</body>
</html>