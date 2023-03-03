<?php

    include '../config/connect.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];

    if (!isset($admin_id)) {
        header('location:admin_login.php');
    }

    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $delete_admin = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
        $delete_admin->execute([$delete_id]);
        header('location:admin_accounts');
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Quản lý</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <link rel="stylesheet" href="../css/admin_style.css">

    </head>
    <body>
        <?php
            if (isset($message)) {
                foreach ($message as $message) {
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

        <section class="accounts">
            <h1 class="heading">Quản lý</h1>
            <div class="box-container">
                <div class="box">
                    <p>Thêm người quản lý mới</p>
                    <a href="../admin/register_admin.php" class="option-btn">Đăng ký quản lý</a>
                </div>
                <?php
                    $select_account = $conn->prepare("SELECT * FROM `admins`");
                    $select_account->execute();
                    if ($select_account->rowCount() > 0) {
                        while ($fetch_account = $select_account->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <div class="box">
                                <p>Mã quản lý: <span><?= $fetch_account['id']; ?></span></p>
                                <p>Tên quản lý: <span><?= $fetch_account['name']; ?></span></p>
                                <div class="flex-btn">
                                    <a href="../admin/admin_accounts.php?delete=<?= $fetch_account['id']; ?>" onclick="return confirm('Xóa người quản lý này?')" class="delete-btn">Xóa</a>
                                    <?php
                                        if ($fetch_account['id'] == $admin_id) {
                                            echo '<a href="../admin/update_profile.php" class="option-btn">Cập nhật</a>';
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p class="empty">Không có người quản lý nào!</p>';
                    }
                ?>
            </div>
        </section>
        
        <script src="../js/admin_script.js"></script>
    </body>
</html>