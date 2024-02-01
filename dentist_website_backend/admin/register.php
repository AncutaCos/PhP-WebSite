<?php

include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
    $admin_id = $_COOKIE['admin_id'];
} else {
    header('location:login.php');
    exit(); // Assicurati che lo script si fermi se l'utente non è loggato
}

$warning_msg = [];
$success_msg = [];
$error_msg = [];

if(isset($_POST['submit'])){

    // Raccogli e sanifica i dati in input
    $name = trim($_POST['name']);
    $name = stripslashes($name);
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

    $email = trim($_POST['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $pass = $_POST['pass'];
    $c_pass = $_POST['c_pass'];

    if(!empty($name) && !empty($email) && !empty($pass) && !empty($c_pass)){

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $warning_msg[] = 'Invalid email format!';
        } else {
            $verify_user = $conn->prepare("SELECT * FROM `admins` WHERE name = ? OR email = ?");
            $verify_user->execute([$name, $email]);

            if($verify_user->rowCount() > 0){
                $warning_msg[] = 'Username or Email already taken!';
            } else {
                if($pass !== $c_pass){
                    $warning_msg[] = 'Confirm password not matched!';
                } else {
                    $pass = password_hash($pass, PASSWORD_DEFAULT);
                    $insert_admin = $conn->prepare("INSERT INTO `admins`(name, email, password) VALUES(?,?,?)");
                    $insert_admin->execute([$name, $email, $pass]);

                    if($insert_admin){
                        $success_msg[] = 'Admin registered successfully!';
                        header('location:dashboard.php');
                        exit();
                    } else {
                        $error_msg[] = 'Registration failed!';
                    }
                }
            }
        }
    } else {
        $warning_msg[] = 'Please fill out all fields!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Admin</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>create new admin account!</h3>
      <?php
      // Display warning messages
      if (!empty($warning_msg)) {
          echo '<div class="warning-msg">';
          foreach ($warning_msg as $msg) {
              echo '<p>'.$msg.'</p>';
          }
          echo '</div>';
      }
      // Display success messages
      if (!empty($success_msg)) {
          echo '<div class="success-msg">';
          foreach ($success_msg as $msg) {
              echo '<p>'.$msg.'</p>';
          }
          echo '</div>';
      }
      // Display error messages
      if (!empty($error_msg)) {
          echo '<div class="error-msg">';
          foreach ($error_msg as $msg) {
              echo '<p>'.$msg.'</p>';
          }
          echo '</div>';
      }
      ?>
      <input type="text" name="name" required maxlength="20" placeholder="enter your name" class="input">
      <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="input">
      <input type="password" name="pass" required maxlength="20" placeholder="enter your password" class="input">
      <input type="password" name="c_pass" required maxlength="20" placeholder="confirm your password" class="input">
      <input type="submit" value="register now" name="submit" class="btn">
   </form>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>

</body>
</html>
