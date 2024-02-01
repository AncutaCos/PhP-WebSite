<header class="header">

   <section class="flex">

      <a href="dashboard.php" class="logo">Admin<span>Panel</span></a>

      <nav class="navbar">
         <a href="dashboard.php">home</a>
         <a href="messages.php">appointments</a>
         <a href="login.php">login</a>
         <a href="register.php">register</a>
         <?php 
            if(isset($_COOKIE['admin_id'])){
         ?>
         <a href="../components/logout.php" onclick="return confirm('Logout from this website?');" style="color:var(--red);">logout</a>
         <?php }; ?> 
      </nav>

      <div id="menu-btn" class="fas fa-bars-staggered"></div>

   </section>

</header>