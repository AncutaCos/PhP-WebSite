<?php

include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
    $admin_id = $_COOKIE['admin_id'];
} else {
    header('location:login.php');
    exit();
}

$select_admin = $conn->prepare("SELECT * FROM `admins` WHERE id = ? LIMIT 1");
$select_admin->execute([$admin_id]);
$fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

$warning_msg = [];
$success_msg = [];

if(isset($_POST['submit'])){

    $name = trim($_POST['name']);
    $name = stripslashes($name);
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

    if(!empty($name)){
        $update_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
        $update_name->execute([$name, $admin_id]);
        $success_msg[] = 'Name updated!';
    }

    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $c_pass = $_POST['c_pass'];

    if(!empty($old_pass) && !empty($new_pass) && !empty($c_pass)){
        if(password_verify($old_pass, $fetch_admin['password'])){
            if($new_pass == $c_pass){
                $new_pass_hashed = password_hash($new_pass, PASSWORD_DEFAULT);
                $update_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
                $update_pass->execute([$new_pass_hashed, $admin_id]);
                $success_msg[] = 'Password updated!';
            } else {
                $warning_msg[] = 'Confirm password not matched!';
            }
        } else {
            $warning_msg[] = 'Old password not matched!';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update</title>
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>update account!</h3>
      <!-- Display success and warning messages -->
      <!-- ... (aggiungi qui il codice per visualizzare i messaggi) -->
      <input type="text" name="name" maxlength="20" placeholder="<?= htmlspecialchars($fetch_admin['name'], ENT_QUOTES, 'UTF-8'); ?>" class="input">
      <input type="password" name="old_pass" maxlength="20" placeholder="enter your old password" class="input">
      <input type="password" name="new_pass" maxlength="20" placeholder="enter your new password" class="input">
      <input type="password" name="c_pass" maxlength="20" placeholder="confirm your new password" class="input">
      <input type="submit" value="update now" name="submit" class="btn">
   </form>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>
   
</body>
</html>
