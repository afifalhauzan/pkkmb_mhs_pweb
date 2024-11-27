<?php
$current_page = basename($_SERVER['PHP_SELF']); // Get current page name
?>
<div class="navbar">
    <a href="index.php" class="navbar-item <?php echo $current_page == 'index.php' ? 'active' : ''; ?>">PortalMaba</a>
    <a href="tentang.php" class="navbar-item <?php echo $current_page == 'tentang.php' ? 'active' : ''; ?>">Tentang</a>
    <a href="informasi.php" class="navbar-item <?php echo $current_page == 'informasi.php' ? 'active' : ''; ?>">Informasi</a>
    <a href="login.php" class="navbar-item <?php echo $current_page == 'login.php' ? 'active' : ''; ?>">Profil</a>
</div>
