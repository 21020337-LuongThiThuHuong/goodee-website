<?php

    include '../config/connect.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];

    if (!isset($admin_id)) {
        header('location:admin_login.php');
    }

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);
        $cpass = sha1($_POST['cpass']);
        $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

        $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
        $select_admin->execute([$name]);

        if ($select_admin->rowCount() > 0) {
            $message[] = 'Tên người dùng đã tồn tại!';
        } else {
            if ($pass != $cpass) {
                $message[] = 'Mật khẩu xác nhận không trùng khớp!';
            } else {
                $insert_admin = $conn->prepare("INSERT INTO `admins` (name, password) VALUES(?, ?)");
                $insert_admin->execute([$name, $pass]);
                $message[] = 'Đăng ký thành công!';
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
        <title>Đăng ký</title>

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

        <section class="form-container">
            <form action="" method="post">
                <h3>Đăng ký quản lý mới</h3>
                <input type="text" name="name" required placeholder="Nhập tên người dùng" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="pass" required placeholder="Nhập mật khẩu" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="cpass" required placeholder="Xác nhận mật khẩu" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="submit" value="Đăng ký" class="btn" name="submit">
            </form>
        </section>
        
        <script src="../js/admin_script.js"></script>
    </body>
</html>