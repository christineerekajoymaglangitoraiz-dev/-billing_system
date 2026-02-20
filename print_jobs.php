<?php
session_start();
include("../config/db.php");
if (!isset($_SESSION['user'])) header("Location: ../auth/login.php");

if(isset($_GET['done'])){
    mysqli_query($conn,"UPDATE print_jobs SET status='Done' WHERE id={$_GET['done']}");
}

$q=mysqli_query($conn,"SELECT * FROM print_jobs");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Jobs - Printing Shop</title>
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
        
        .content-card {
            background-color: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .jobs-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .job-item {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            border-left: 4px solid;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .job-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .job-pending {
            border-left-color: #FF9800;
        }
        
        .job-done {
            border-left-color: #4CAF50;
        }
        
        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .job-id {
            font-weight: 600;
            color: #007AFF;
            font-size: 18px;
        }
        
        .job-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending {
            background-color: #FFF3E0;
            color: #F57C00;
        }
        
        .status-done {
            background-color: #E8F5E9;
            color: #388E3C;
        }
        
        .mark-done-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        .mark-done-button:hover {
            background-color: #388E3C;
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
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #ddd;
        }
        
        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
            
            .job-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .navigation {
                justify-content: center;
            }
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
            border-top: 4px solid;
            text-align: center;
        }
        
        .pending-card {
            border-top-color: #FF9800;
        }
        
        .done-card {
            border-top-color: #4CAF50;
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
    </style>
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
            
            <h1 class="page-title">Print Jobs</h1>
            <p class="page-subtitle">Manage and track all printing jobs in your shop</p>
        </div>
        
        <div class="navigation">
            <a href="dashboard.php" class="nav-button">Dashboard</a>
            <a href="customers.php" class="nav-button">Customers</a>
            <a href="services.php" class="nav-button">Services</a>
            <a href="billing.php" class="nav-button">Billing</a>
            <a href="print_jobs.php" class="nav-button active">Print Jobs</a>
            <a href="../auth/logout.php" class="nav-button logout-button">Logout</a>
        </div>
        
        <?php
        // Count jobs by status for stats
        $pending_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM print_jobs WHERE status!='Done'"));
        $done_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM print_jobs WHERE status='Done'"));
        $total_jobs = $pending_count + $done_count;
        ?>
        
        <div class="stats-grid">
            <div class="stat-card pending-card">
                <div class="stat-value"><?php echo $pending_count; ?></div>
                <div class="stat-label">Pending Jobs</div>
            </div>
            
            <div class="stat-card done-card">
                <div class="stat-value"><?php echo $done_count; ?></div>
                <div class="stat-label">Completed Jobs</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-value"><?php echo $total_jobs; ?></div>
                <div class="stat-label">Total Jobs</div>
            </div>
        </div>
        
        <div class="content-card">
            <h2 class="section-title">All Print Jobs</h2>
            
            <?php 
            // Reset the query pointer to start from beginning
            mysqli_data_seek($q, 0);
            
            if (mysqli_num_rows($q) > 0): 
            ?>
                <div class="jobs-list">
                    <?php while($p=mysqli_fetch_assoc($q)): 
                        $isDone = $p['status'] == 'Done';
                    ?>
                        <div class="job-item <?php echo $isDone ? 'job-done' : 'job-pending'; ?>">
                            <div class="job-header">
                                <div class="job-id">Order #<?php echo str_pad($p['id'], 4, '0', STR_PAD_LEFT); ?></div>
                                <div class="job-status <?php echo $isDone ? 'status-done' : 'status-pending'; ?>">
                                    <?php echo htmlspecialchars($p['status']); ?>
                                </div>
                            </div>
                            
                            <div style="margin-bottom: 10px;">
                                <strong>Order ID:</strong> <?php echo htmlspecialchars($p['order_id']); ?>
                            </div>
                            
                            <?php if (!$isDone): ?>
                                <a href="?done=<?php echo $p['id']; ?>" class="mark-done-button">
                                    Mark as Done
                                </a>
                            <?php else: ?>
                                <div style="color: #4CAF50; font-weight: 500; margin-top: 10px;">
                                    ✓ Completed
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    No print jobs found. All jobs are completed or no jobs have been created yet.
                </div>
            <?php endif; ?>
        </div>
        
        <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>