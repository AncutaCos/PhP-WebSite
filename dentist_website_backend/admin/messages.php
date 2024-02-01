<?php

include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
    $admin_id = $_COOKIE['admin_id'];
} else {
    header('location:login.php');
    exit();
}

$success_msg = [];
$warning_msg = [];

if(isset($_POST['delete'])){
    $delete_id = $_POST['delete_id'];
    // Assumendo che l'ID sia un numero, lo convertiamo in intero
    $delete_id = intval($delete_id);

    $verify_delete = $conn->prepare("SELECT * FROM `messages` WHERE id = ?");
    $verify_delete->execute([$delete_id]);

    if($verify_delete->rowCount() > 0){
        $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
        $delete_message->execute([$delete_id]);
        $success_msg[] = 'Message deleted!';
    } else {
        $warning_msg[] = 'Message already deleted or does not exist!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/header.php'; ?>

<section class="messages">
   <h1 class="heading">Appointments</h1>

   <div class="box-container">
      <?php
      $select_message = $conn->prepare("SELECT * FROM `messages` ORDER BY date DESC");
      $select_message->execute();
      if($select_message->rowCount() > 0){
         while($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){ ?>
            <div class="box">
               <p>date : <span><?= htmlspecialchars($fetch_message['date'], ENT_QUOTES, 'UTF-8'); ?></span></p>
               <p>name : <span><?= htmlspecialchars($fetch_message['name'], ENT_QUOTES, 'UTF-8'); ?></span></p>
               <p>email : <a href="mailto:<?= htmlspecialchars($fetch_message['email'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($fetch_message['email'], ENT_QUOTES, 'UTF-8'); ?></a></p>
               <p>number : <a href="tel:<?= htmlspecialchars($fetch_message['number'], ENT_QUOTES, 'UTF-8'); ?>"><?= htmlspecialchars($fetch_message['number'], ENT_QUOTES, 'UTF-8'); ?></a></p>
               <form action="" method="post">
                  <input type="hidden" name="delete_id" value="<?= htmlspecialchars($fetch_message['id'], ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="submit" name="delete" value="delete message" onclick="return confirm('Delete this appointment?');" class="delete-btn">
               </form>
            </div>
      <?php }
      } else {
         echo '<p class="empty">no Appointments found!</p>';
      }
      ?>
   </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>
<?php include '../components/alert.php'; ?>
   
</body>
</html>
