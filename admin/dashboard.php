<?php

    include '../config/connect.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];

    if (!isset($admin_id)) {
        header('location:admin_login.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <link rel="stylesheet" href="../css/admin_style.css">

    </head>
    <body>
        <header class="header">

            <section class="flex">

                <a href="../admin/dashboard.php" class="logo">Good<span>ee.</span></a>

                <nav class="navbar">
                    <a href="../admin/dashboard.php">Trang chủ</a>
                    <a href="../admin/products.php">Sản phẩm</a>
                    <a href="../admin/placed_orders.php">Đơn hàng</a>
                    <a href="../admin/admin_accounts.php">Quản lý</a>
                    <a href="../admin/user_account.php">Người dùng</a>
                    <a href="../admin/messages.php">Tin nhắn</a>
                </nav>

                <div class="icons">
                    <div id="menu-btn" class="fas fa-bars"></div>
                    <div id="user-btn" class="fas fa-user" onclick="toggleForm()"></div>
                </div>

                <div class="profile" id="pf">
                    <?php
                        $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
                        $select_profile->execute([$admin_id]);
                        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <p><?= $fetch_profile['name']; ?></p>
                    <a href="../admin/update_profile.php" class="btn">Cập nhật hồ sơ</a>
                    <div class="flex-btn">
                        <a href="../admin/register_admin.php" class="option-btn">Đăng ký tài khoản mới</a>
                    </div>
                    <a href="../config/admin_logout.php" class="delete-btn" onclick="return confirm('Đăng xuất?');">Đăng xuất</a> 
                </div>
            </section>
        </header>

        <section class="dashboard">
            <h1 class="heading">Dashboard</h1>
            <div class="box-container">
                <div class="box">
                    <h3>Xin chào!</h3>
                    <p><?= $fetch_profile['name']; ?></p>
                    <a href="update_profile.php" class="btn">Cập nhật hồ sơ</a>
                </div>

                <div class="box">
                    <?php
                        $total_pendings = 0;
                        $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                        $select_pendings->execute(['pending']);
                        if ($select_pendings->rowCount() > 0) {
                            while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
                                $total_pendings += $fetch_pendings['total_price'];
                            }
                        }
                    ?>
                    <h3><span>$</span><?= $total_pendings; ?><span>/-</span></h3>
                    <p>Đơn hàng đang chờ</p>
                    <a href="../admin/placed_orders.php" class="btn">Xem các đơn hàng</a>
                </div>

                <div class="box">
                    <?php
                        $total_completes = 0;
                        $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
                        $select_completes->execute(['completed']);
                        if ($select_completes->rowCount() > 0) {
                            while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
                                $total_completes += $fetch_completes['total_price'];
                            }
                        }
                    ?>
                    <h3><span>$</span><?= $total_completes; ?><span>/-</span></h3>
                    <p>Đơn hàng hoàn thành</p>
                    <a href="../admin/placed_orders.php" class="btn">Xem các đơn hàng</a>
                </div>

                <div class="box">
                    <?php
                        $select_orders = $conn->prepare("SELECT * FROM `orders`");
                        $select_orders->execute();
                        $number_of_orders = $select_orders->rowCount()
                    ?>
                    <h3><?= $number_of_orders; ?></h3>
                    <p>Đơn hàng đã đặt</p>
                    <a href="../admin/placed_orders.php" class="btn">Xem các đơn hàng</a>
                </div>

                <div class="box">
                    <?php
                        $select_products = $conn->prepare("SELECT * FROM `products`");
                        $select_products->execute();
                        $number_of_products = $select_products->rowCount()
                    ?>
                    <h3><?= $number_of_products; ?></h3>
                    <p>Số sản phẩm hiện có</p>
                    <a href="../admin/products.php" class="btn">Xem các sản phẩm</a>
                </div>

                <div class="box">
                    <?php
                        $select_users = $conn->prepare("SELECT * FROM `users`");
                        $select_users->execute();
                        $number_of_users = $select_users->rowCount()
                    ?>
                    <h3><?= $number_of_users; ?></h3>
                    <p>Số người dùng</p>
                    <a href="users_accounts.php" class="btn">Xem số người dùng</a>
                </div>

                <div class="box">
                    <?php
                        $select_admins = $conn->prepare("SELECT * FROM `admins`");
                        $select_admins->execute();
                        $number_of_admins = $select_admins->rowCount()
                    ?>
                    <h3><?= $number_of_admins; ?></h3>
                    <p>Số quản lý</p>
                    <a href="admin_accounts.php" class="btn">Xem số quản lý</a>
                </div>

                <div class="box">
                    <?php
                        $select_messages = $conn->prepare("SELECT * FROM `messages`");
                        $select_messages->execute();
                        $number_of_messages = $select_messages->rowCount()
                    ?>
                    <h3><?= $number_of_messages; ?></h3>
                    <p>Tin nhắn mới</p>
                    <a href="messagess.php" class="btn">Xem tin nhắn</a>
                </div>
            </div>
        </section>
        <script src="../js/admin_script.js"></script>
    </body>
</html>