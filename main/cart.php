<?php
    include '../config/connect.php';

    session_start();

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $user_id = '';
    }

    if (isset($_POST['delete'])) {
        $cart_id = $_POST['cart_id'];
        $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
        $delete_cart_item->execute([$cart_id]);
    }
    
    if (isset($_GET['delete_all'])) {
        $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart_item->execute([$user_id]);
        header('location:cart.php');
    }
    
    if (isset($_POST['update_qty'])) {
        $cart_id = $_POST['cart_id'];
        $qty = $_POST['qty'];
        $qty = filter_var($qty, FILTER_SANITIZE_STRING);
        $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
        $update_qty->execute([$qty, $cart_id]);
        $message[] = 'Đơn hàng đã cập nhật';
    }

    include '../config/cart.php';
    
?>

<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Giỏ hàng</title>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
            <link rel="stylesheet" href="../css/home_style.css">

        </head>
    <body>
        <!-- header of pages -->
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

                <a href="../main/home.php" class="logo">Good<span>ee.</span></a>

                <nav class="navbar">
                    <a href="../main/home.php">Trang chủ</a>
                    <a href="../main/about.php">Về chúng tôi</a>
                    <a href="../main/orders.php">Đơn hàng</a>
                    <a href="../main/shop.php">Gian hàng</a>
                    <a href="../main/contact.php">Liên hệ</a>
                </nav>

                <div class="icons">
                    <?php
                        $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                        $count_cart_items->execute([$user_id]);
                        $total_cart_counts = $count_cart_items->rowCount();
                    ?>
                    <div id="menu-btn" class="fas fa-bars"></div>
                    <a href="../main/search_page.php"><i class="fas fa-search"></i></a>
                    <a href="../main/cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts; ?>)</span></a>
                    <div id="user-btn" class="fas fa-user" onclick="toggleForm()"></div>
                </div>

                <div class="profile" id="pf">
                    <?php          
                        $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                        $select_profile->execute([$user_id]);
                        if ($select_profile->rowCount() > 0) {
                            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <p><?= $fetch_profile["name"]; ?></p>

                        <a href="../main/update_user.php" class="btn">Cập nhật hồ sơ</a>

                        <div class="flex-btn">
                            <a href="../main/user_register.php" class="option-btn">Đăng ký</a>
                            <a href="../main/user_login.php" class="option-btn">Đăng nhập</a>   
                        </div>

                        <a href="../config/user_logout.php" class="delete-btn" onclick="return confirm('Đăng xuất khỏi Goodie.?');">Đăng xuất</a>

                        <?php
                            } else {
                            ?>
                            <p>Bạn cần phải Đăng nhập hoặc Đăng ký trước!</p>
                            <div class="flex-btn">
                                <a href="../main/user_register.php" class="option-btn">Đăng ký</a>
                                <a href="../main/user_login.php" class="option-btn">Đăng nhập</a>
                            </div>
                            <?php
                        }
                    ?>      
                </div>
            </section>
        </header>
        <!-- end of header -->
         
        <section class="products shopping-cart">
            <h3 class="heading">Giỏ hàng</h3>
            <div class="box-container">
                <?php
                    $grand_total = 0;
                    $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                    $select_cart->execute([$user_id]);
                    if ($select_cart->rowCount() > 0) {
                        while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <form action="" method="post" class="box">
                                <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                                <a href="quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
                                <img src="../uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
                                <div class="name"><?= $fetch_cart['name']; ?></div>
                                <div class="flex">
                                    <div class="price"><?= $fetch_cart['price']; ?> VND</div>
                                    <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
                                    <button type="submit" class="fas fa-edit" name="update_qty"></button>
                                </div>
                                <div class="sub-total"> Giá tiền: <span><?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?> VND</span> </div>
                                <input type="submit" value="Xóa" onclick="return confirm('Xóa sản phẩm này?');" class="delete-btn" name="delete">
                            </form>
                            <?php
                            $grand_total += $sub_total;
                        }
                    } else {
                        echo '<p class="empty">Không có sản phẩm nào</p>';
                    }
                ?>
            </div>

            <div class="cart-total">
                <p>Tổng giá trị đơn hàng : <span><?= $grand_total; ?> VND</span></p>
                <a href="shop.php" class="option-btn">Tiếp tục mua sắm</a>
                <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('Xóa tất cả sản phẩm?');">Xóa tất cả sản phẩm</a>
                <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Thanh toán</a>
            </div>

        </section>
        
        <!-- footer of page -->
        <footer class="footer">
            <section class="grid">
                <div class="box">
                    <h3>Liên kết nhanh</h3>
                    <a href="../main/home.php"><i class="fas fa-angle-right"></i>Trang chủ</a>
                    <a href="../main/about.php"><i class="fas fa-angle-right"></i>Về chúng tôi</a>
                    <a href="../main/shop.php"><i class="fas fa-angle-right"></i>Gian hàng</a>
                    <a href="../main/contact.php"><i class="fas fa-angle-right"></i>Liên hệ</a>
                </div>

                <div class="box">
                    <h3>Liên kết khác</h3>
                    <a href="../main/orders.php"><i class="fas fa-angle-right"></i>Đơn hàng</a>
                    <a href="../main/cart.php"><i class="fas fa-angle-right"></i>Giỏ hàng</a>
                    <a href="../main/login.php"><i class="fas fa-angle-right"></i>Đăng nhập</a>
                </div>

                <div class="box">
                    <h3>Thông tin liên hệ</h3>
                    <a href="tel:+84 842906955"><i class="fas fa-phone"></i>0842906955</a>
                    <a href="mailto:goodeeteam@gmail.com"><i class="fas fa-envelope"></i>goodeeteam@gmail.com</a>
                </div>

                <div class="box">
                    <h3>Theo dõi các mạng xã hội của chúng tôi</h3>
                    <a href="#"><i class="fab fa-facebook-f"></i>Facebook</a>
                    <a href="#"><i class="fab fa-instagram"></i>Instagram</a>
                    <a href="#"><i class="fab fa-youtube"></i>Youtube</a>
                    <a href="#"><i class="fab fa-twitter"></i>Twitter</a>
                </div>
            </section>

            <div class="credit"> &copy; Copyright @<?= date('Y'); ?> by <span>Team 4</span> | All rights reserved.</div>
        </footer>
        <!-- end of footer -->
        <script src="../js/user_script.js"></script>
    </body>
</html>