<?php

include '../components/connect.php';

if(!isset($_COOKIE['admin_id'])){
    header('location:login.php');
    exit();
}

$admin_id = $_COOKIE['admin_id'];
$select_admin = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
$select_admin->execute([$admin_id]);
$fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Dashboard</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/header.php'; ?>

<section class="dashboard">
    <h1>Welcome, <?= htmlspecialchars($fetch_admin['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
    
    <div class="dashboard-links">
        <a href="manage_appointments.php" class="btn">Manage Appointments</a>
        <a href="manage_admins.php" class="btn">Manage Admins</a>
        <a href="manage_services.php" class="btn">Manage Services</a>
        <a href="view_feedback.php" class="btn">View Feedback</a>
        <a href="settings.php" class="btn">Settings</a>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>

</body>
</html>
