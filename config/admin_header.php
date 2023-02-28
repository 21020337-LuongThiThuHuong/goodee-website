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
        <a href="../admin/dashboard.php" class="logo">Admin<span>Panel</span></a>

        <nav class="navbar">
            <a href="../admin/dashboard.php">Home</a>
            <a href="../admin/products.php">Products</a>
            <a href="../admin/placed_orders.php">Placed Orders</a>
            <a href="../admin/messages.php">Messages</a>
            <a href="../admin/user_accounts.php">User Accounts</a>
        </nav>
    </section>
</header>