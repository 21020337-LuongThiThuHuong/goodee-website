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
        
        $update_profile_name = $conn->prepare("UPDATE `admins` SET name = ? WHERE id = ?");
        $update_profile_name->execute([$name, $admin_id]);
        
        $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
        $prev_pass = $_POST['prev_pass'];
        $old_pass = sha1($_POST['old_pass']);
        $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
        $new_pass = sha1($_POST['new_pass']);
        $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
        $cpass = sha1($_POST['cpass']);
        $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
        
        if ($old_pass == $empty_pass) {
            $message[] = '';
        } elseif ($old_pass != $prev_pass) {
            $message[] = 'Mật khẩu cũ không trùng khớp.';
        } elseif ($new_pass != $cpass) {
            $message[] = 'Mật khẩu xác nhận không trùng khớp!';
        } else {
            if ($new_pass != $empty_pass) {
                $update_admin_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE id = ?");
                $update_admin_pass->execute([$cpass, $admin_id]);
                $message[] = 'Mật khẩu đã cập nhật thành công!';
            } else {
                $message[] = 'Hãy nhập mật khẩu mới!';
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
        <title>Cập nhật hồ sơ</title>

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
                <h3>Cập nhật hồ sơ</h3>
                <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
                <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" required placeholder="Nhập tên người dùng" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="old_pass" placeholder="Nhập mật khẩu cũ" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="new_pass" placeholder="Nhập mật khẩu mới" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="password" name="cpass" placeholder="Xác nhận mật khẩu mới" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
                <input type="submit" value="Cập nhật" class="btn" name="submit">
            </form>
        </section>
        
        <script src="../js/admin_script.js"></script>
    </body>
</html>