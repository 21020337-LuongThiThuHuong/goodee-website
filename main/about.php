<?php
    include '../config/connect.php';

    session_start();

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $user_id = '';
    }

    include '../config/cart.php';
    
?>

<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Về chúng tôi</title>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
            <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
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

        <!-- story -->

        <section class="about">
            <div class="row">
                <div class="image">
                    <img src="../images/about-img.jpg" alt="">
                </div>

                <div class="content">
                    <h3>Câu chuyện về Goodee.</a></h3>
                    <p> Goodee sinh ra với mong muốn trở thành điển hình về mô hình doanh nghiệp trách nhiệm bằng cách vừa làm kinh doanh bài bản, có lợi nhuận và đồng thời mang lại những giá trị thiết thực và lâu dài cho người dùng và xã hội.</p>
                    <a href="../main/contact.php" class="btn">Liên hệ với chúng tôi</a>
                </div>
            </div>
        </section>

        <!-- review -->
        <section class="reviews">
   
            <h1 class="heading">Reviews</h1>
            <div class="swiper reviews-slider">
                <div class="swiper-wrapper">

                    <div class="swiper-slide slide">
                        <img src="../images/pic-1.png" alt="">
                        <h3>Phi Hùng</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p>"Mua giày tại Goodee. luôn đem lại cho mình cảm giác an tâm về chất lượng và hài lòng về mức giá. Mình đã từng mua giày ở rất nhiều cửa hàng nhưng từ khi tới Goodee. là mình không còn muốn mua giày ở bất kỳ đâu khác nữa."</p>
                    </div>

                    <div class="swiper-slide slide">
                        <img src="../images/pic-2.png" alt="">
                        <h3>Phi Hùng</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p>"Mua giày tại Goodee. luôn đem lại cho mình cảm giác an tâm về chất lượng và hài lòng về mức giá. Mình đã từng mua giày ở rất nhiều cửa hàng nhưng từ khi tới Goodee. là mình không còn muốn mua giày ở bất kỳ đâu khác nữa."</p>
                    </div>

                    <div class="swiper-slide slide">
                        <img src="../images/pic-3.png" alt="">
                        <h3>Phi Hùng</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p>"Mua giày tại Goodee. luôn đem lại cho mình cảm giác an tâm về chất lượng và hài lòng về mức giá. Mình đã từng mua giày ở rất nhiều cửa hàng nhưng từ khi tới Goodee. là mình không còn muốn mua giày ở bất kỳ đâu khác nữa."</p>
                    </div>

                    <div class="swiper-slide slide">
                        <img src="../images/pic-4.png" alt="">
                        <h3>Phi Hùng</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p>"Mua giày tại Goodee. luôn đem lại cho mình cảm giác an tâm về chất lượng và hài lòng về mức giá. Mình đã từng mua giày ở rất nhiều cửa hàng nhưng từ khi tới Goodee. là mình không còn muốn mua giày ở bất kỳ đâu khác nữa."</p>
                    </div>

                    <div class="swiper-slide slide">
                        <img src="../images/pic-5.png" alt="">
                        <h3>Phi Hùng</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p>"Mua giày tại Goodee. luôn đem lại cho mình cảm giác an tâm về chất lượng và hài lòng về mức giá. Mình đã từng mua giày ở rất nhiều cửa hàng nhưng từ khi tới Goodee. là mình không còn muốn mua giày ở bất kỳ đâu khác nữa."</p>
                    </div>

                    <div class="swiper-slide slide">
                        <img src="../images/pic-6.png" alt="">
                        <h3>Phi Hùng</h3>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <p>"Mua giày tại Goodee. luôn đem lại cho mình cảm giác an tâm về chất lượng và hài lòng về mức giá. Mình đã từng mua giày ở rất nhiều cửa hàng nhưng từ khi tới Goodee. là mình không còn muốn mua giày ở bất kỳ đâu khác nữa."</p>
                    </div>

                </div>
                <div class="swiper-pagination"></div>
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

            <!-- review -->

            

            <div class="credit"> &copy; Copyright @<?= date('Y'); ?> by <span>Team 4</span> | All rights reserved.</div>
        </footer>
        <!-- end of footer -->

        <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

        <script src="../js/user_script.js"></script>

        <script>
            var swiper = new Swiper(".reviews-slider", {
                loop:true,
                spaceBetween: 20,
                pagination: {
                    el: ".swiper-pagination",
                    clickable:true,
                },
                breakpoints: {
                    0: {
                        slidesPerView:1,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    991: {
                        slidesPerView: 3,
                    },
                },
            });
        </script>
    </body>
</html>