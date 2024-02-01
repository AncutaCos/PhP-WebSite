<?php

include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
    header('location:dashboard.php');
    exit(); // Redirige l'utente alla dashboard se già loggato
}

$warning_msg = [];

if(isset($_POST['submit'])){
    // Sanitizza e prepara il nome utente
    $name = trim($_POST['name']);
    $name = stripslashes($name);
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $pass = $_POST['pass']; // La password verrà verificata, quindi non necessita di sanitizzazione

    if(!empty($name) && !empty($pass)){
        $verify_user = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
        $verify_user->execute([$name]);

        if($verify_user->rowCount() > 0){
            $fetch = $verify_user->fetch(PDO::FETCH_ASSOC);
            if(password_verify($pass, $fetch['password'])){
                setcookie('admin_id', $fetch['id'], time() + 60*60*24*30, '/');
                header('location:dashboard.php');
                exit();
            } else {
                $warning_msg[] = 'Incorrect password!';
            }
        } else {
            $warning_msg[] = 'Username not found!';
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
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/header.php'; ?>

<section class="form-container">
    <form action="" method="post">
        <h3>Login</h3>
        <?php 
        if (!empty($warning_msg)) {
            echo '<div class="warning-msg">';
            foreach ($warning_msg as $msg) {
                echo '<p>'.$msg.'</p>';
            }
            echo '</div>';
        }
        ?>
        <input type="text" name="name" required maxlength="20" placeholder="enter your username" class="input">
        <input type="password" name="pass" required maxlength="20" placeholder="enter your password" class="input">
        <input type="submit" value="login now" name="submit" class="btn">
    </form>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>

</body>
</html>
