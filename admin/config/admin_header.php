<?php

    if (isset($message)) {
        foreach($message as $message) {
            echo '
            <div class="message">
                <span>'.$message.'</span>
                <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
            </div>
            ';
        }
    }
?>

<header class="header">
    <section class="flex">
        <a href="../dashboard.php" class="logo">Admin<span>Panel</span></a>

        <nav class="navbar">
            <a href="../dashboard.php">Home</a>
            <a href="../products.php">Products</a>
            <a href="../placed_orders.php">Placed orders</a>
            <a href="../messages.php">Messages</a>
            <a href="../user_accounts.php">User accounts</a>
            <a href="../admin_accounts.php">Admin accounts</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            <?php 
                $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
                $select_profile->execute([$admin_id]);
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <p><?= $fetch_profile['name']; ?></p>
            <a href="../update_profile.php" class="btn">Update profile</a>
            <div class="flex-btn">
                <a href="../register_admin.php" class="option-btn">Register</a>
                <a href="../admin_login.php" class="option-btn">Login</a>
            </div>
            <a href="../config/admin_logout.php" class="delete-btn">Logout</a>
        </div>
    </section>
</header>