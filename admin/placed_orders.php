<?php

    include '../config/connect.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];

    if (!isset($admin_id)) {
        header('location:admin_login.php');
    }

    if (isset($_POST['update_payment'])) {
        $order_id = $_POST['order_id'];
        $payment_status = $_POST['payment_status'];
        $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
        $update_payment = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
        $update_payment->execute([$payment_status, $order_id]);
        $message[] = 'Trạng thái đơn hàng đã cập nhật.';
    }

    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
        $delete_order->execute([$delete_id]);
        header('location:../admin/placed_orders.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Đơn hàng</title>

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

        <section class="placed-orders">
            <h1 class="heading">Đơn hàng</h1>
            <div class="box-container">
                <?php
                    $select_orders = $conn->prepare("SELECT * FROM `orders`");
                    $select_orders->execute();
                    if ($select_orders->rowCount() > 0) {
                        while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <div class="box">
                                <p>Thời gian đặt: <span><?= $fetch_orders['placed_on']; ?></span></p>
                                <p>Tên: <span><?= $fetch_orders['name']; ?></span> </p>
                                <p>Số điện thoại: <span><?= $fetch_orders['number']; ?></span> </p>
                                <p>Địa chỉ: <span><?= $fetch_orders['address']; ?></span> </p>
                                <p>Tổng số sản phẩm: <span><?= $fetch_orders['total_products']; ?></span> </p>
                                <p>Tổng giá tiền: <span>$<?= $fetch_orders['total_price']; ?>/-</span> </p>
                                <p>Phương thức thanh toán: <span><?= $fetch_orders['method']; ?></span> </p>
                            </div>
                            <form action="" method="post">
                                <input type="hidden" name="order_id" value="<?=$fetch_orders['id']; ?>">
                                <select name="payment_status" class="select">
                                    <option select disabled><?=$fetch_orders['payment_status']; ?></option>
                                    <option value="pending">Đang chờ</option>
                                    <option value="completed">Hoàn thành</option>
                                </select>
                                <div class="flex-btn">
                                    <input type="submit" value="update" class="option-btn" name="update_payment">
                                    <a href="../admin/placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Xóa đon hàng này?');">Xóa</a>
                                </div>
                            </form>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p class="empty">Không có đơn hàng nào</p>';
                    }
                ?>
            </div>
        </section>
        
        <script src="../js/admin_script.js"></script>
    </body>
</html>