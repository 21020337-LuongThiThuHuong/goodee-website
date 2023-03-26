<?php
    include '../config/connect.php';

    session_start();

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $user_id = '';
        header('location:user_login.php');
    }

    if (isset($_POST['order'])) {
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $number = $_POST['number'];
        $number = filter_var($number, FILTER_SANITIZE_STRING);
        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
        $method = $_POST['method'];
        $method = filter_var($method, FILTER_SANITIZE_STRING);
        $address = 'flat no. '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
        $address = filter_var($address, FILTER_SANITIZE_STRING);
        $total_products = $_POST['total_products'];
        $total_price = $_POST['total_price'];
        
        $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $check_cart->execute([$user_id]);
        
        if ($check_cart->rowCount() > 0) {   
            $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
            $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);
        
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);
        
            $message[] = 'Đặt hàng thành công!';
        } else {
            $message[] = 'Giỏ hàng trống';
        }
    }
    
?>

<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Thanh toán</title>

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
        
        <section class="checkout-orders">
            <form action="" method="POST">
                <h3>Đơn hàng của bạn</h3>
                <div class="display-orders">
                    <?php
                        $grand_total = 0;
                        $cart_items[] = '';
                        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                        $select_cart->execute([$user_id]);
                        if ($select_cart->rowCount() > 0) {
                            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                                        $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
                                        $total_products = implode($cart_items);
                                        $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                                ?>
                                    <p> <?= $fetch_cart['name']; ?> <span>(<?= '$'.$fetch_cart['price'].'/- x '. $fetch_cart['quantity']; ?>)</span> </p>
                                <?php
                            }
                        } else {
                            echo '<p class="empty">your cart is empty!</p>';
                        }
                    ?>
                    <input type="hidden" name="total_products" value="<?= $total_products; ?>">
                    <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
                    <div class="grand-total">Tổng tiền: <span>VND <?= $grand_total; ?>/-</span></div>
                </div>

                <h3>Đặt hàng</h3>
                <div class="flex">
                    <div class="inputBox">
                        <span>Tên:</span>
                        <input type="text" name="name" placeholder="enter your name" class="box" maxlength="20" required>
                    </div>
                    <div class="inputBox">
                        <span>Số điện thoại:</span>
                        <input type="number" name="number" placeholder="enter your number" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
                    </div>
                    <div class="inputBox">
                        <span>Email:</span>
                        <input type="email" name="email" placeholder="enter your email" class="box" maxlength="50" required>
                    </div>
                    <div class="inputBox">
                        <span>Phương thức thanh toán:</span>
                        <select name="method" class="box" required>
                        <option value="cash on delivery">Tiền mặt</option>
                        <option value="credit card">Thẻ tín dụng</option>
                        <option value="paytm">Zalopay</option>
                        <option value="paypal">Paypal</option>
                        </select>
                    </div>
                    <div class="inputBox">
                        <span>Số nhà:</span>
                        <input type="text" name="flat" placeholder="e.g. flat number" class="box" maxlength="50" required>
                    </div>
                    <div class="inputBox">
                        <span>Quận/Huyện:</span>
                        <input type="text" name="street" placeholder="e.g. street name" class="box" maxlength="50" required>
                    </div>
                    <div class="inputBox">
                        <span>Thành phố:</span>
                        <input type="text" name="city" placeholder="e.g. mumbai" class="box" maxlength="50" required>
                    </div>
                    <div class="inputBox">
                        <span>mã thành phố:</span>
                        <input type="text" name="state" placeholder="e.g. maharashtra" class="box" maxlength="50" required>
                    </div>
                    <div class="inputBox">
                        <span>Quốc gia:</span>
                        <input type="text" name="country" placeholder="e.g. India" class="box" maxlength="50" required>
                    </div>
                    <div class="inputBox">
                        <span>Postcode:</span>
                        <input type="number" min="0" name="pin_code" placeholder="e.g. 123456" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
                    </div>
                </div>
                <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="Đặt hàng">
            </form>
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