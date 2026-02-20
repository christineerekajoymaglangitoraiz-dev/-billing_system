<?php
session_start();
include("../config/db.php");
if (!isset($_SESSION['user'])) header("Location: ../auth/login.php");

$sales = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(total) total FROM orders WHERE order_date=CURDATE()"));

$orders = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM orders WHERE order_date=CURDATE()"));

$pending = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM print_jobs WHERE status='Pending'"));

$low = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM services WHERE stock<=5"));

$customers = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM customers"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Printing Shop</title>
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
        
        .dashboard-container {
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
        
        .dashboard-title {
            font-size: 32px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        
        .dashboard-subtitle {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #007AFF;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .sales-card {
            border-top: 4px solid #4CAF50;
        }
        
        .orders-card {
            border-top: 4px solid #2196F3;
        }
        
        .pending-card {
            border-top: 4px solid #FF9800;
        }
        
        .low-card {
            border-top: 4px solid #F44336;
        }
        
        .customers-card {
            border-top: 4px solid #9C27B0;
        }
        
        .navigation {
            background-color: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .nav-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        
        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }
        
        .nav-button {
            background-color: #f8f9fa;
            color: #333;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            border: 1px solid #e9ecef;
        }
        
        .nav-button:hover {
            background-color: #007AFF;
            color: white;
            border-color: #007AFF;
            transform: translateY(-2px);
        }
        
        .logout-button {
            background-color: #ffeaea;
            color: #d32f2f;
            border-color: #ffcdd2;
        }
        
        .logout-button:hover {
            background-color: #d32f2f;
            color: white;
            border-color: #d32f2f;
        }
        
        .welcome-message {
            background: linear-gradient(135deg, #007AFF, #5856D6);
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .welcome-icon {
            font-size: 24px;
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .nav-links {
                flex-direction: column;
            }
            
            .header-top {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .dashboard-title {
                font-size: 28px;
            }
        }
        
        .current-date {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .currency {
            font-size: 20px;
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <div class="header-top">
                <div class="logo">PrintShop</div>
                <div class="user-info">
                    <div class="user-avatar">
                        <?php 
                        // Display first letter of username
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
            
            <h1 class="dashboard-title">Dashboard</h1>
            <p class="dashboard-subtitle">Printing Shop Management System</p>
            <div class="current-date">
                <?php echo date('F j, Y'); ?>
            </div>
        </div>
        
        <div class="welcome-message">
            <span class="welcome-icon">👋</span>
            <div>
                <strong>Welcome back, <?php echo htmlspecialchars($user['fullname']); ?>!</strong>
                <div style="font-size: 14px; opacity: 0.9;">Here's what's happening with your shop today.</div>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card sales-card">
                <div class="stat-value">
                    <span class="currency">₱</span><?= number_format($sales['total'] ?? 0, 2) ?>
                </div>
                <div class="stat-label">Today's Sales</div>
            </div>
            
            <div class="stat-card orders-card">
                <div class="stat-value"><?= $orders ?></div>
                <div class="stat-label">Orders Today</div>
            </div>
            
            <div class="stat-card pending-card">
                <div class="stat-value"><?= $pending ?></div>
                <div class="stat-label">Pending Jobs</div>
            </div>
            
            <div class="stat-card low-card">
                <div class="stat-value"><?= $low ?></div>
                <div class="stat-label">Low Stock Items</div>
            </div>
            
            <div class="stat-card customers-card">
                <div class="stat-value"><?= $customers ?></div>
                <div class="stat-label">Total Customers</div>
            </div>
        </div>
        
        <div class="navigation">
            <div class="nav-title">Quick Navigation</div>
            <div class="nav-links">
                <a href="customers.php" class="nav-button">Customers</a>
                <a href="services.php" class="nav-button">Services</a>
                <a href="billing.php" class="nav-button">Billing</a>
                <a href="print_jobs.php" class="nav-button">Print Jobs</a>
                <a href="../auth/logout.php" class="nav-button logout-button">Logout</a>
            </div>
        </div>
        
        <!-- Keep original content for compatibility (hidden) -->
        <div style="display: none;">
            <h2>Dashboard</h2>
            <p>Today's Sales: ₱<?= $sales['total'] ?? 0 ?></p>
            <p>Orders Today: <?= $orders ?></p>
            <p>Pending Jobs: <?= $pending ?></p>
            <p>Low Stock: <?= $low ?></p>
            <p>Total Customers: <?= $customers ?></p>

            <a href="customers.php">Customers</a> |
            <a href="services.php">Services</a> |
            <a href="billing.php">Billing</a> |
            <a href="print_jobs.php">Print Jobs</a> |
            <a href="../auth/logout.php">Logout</a>
        </div>
    </div>
</body>
</html>