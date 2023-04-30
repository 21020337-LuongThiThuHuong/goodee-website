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
            <title>Goodee.</title>

            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
            <link rel="stylesheet" href="../css/home_style.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
            <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

        </head>
    <body>
        <!-- header of page -->
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
        
        <!-- hero slide -->
        <section>
            <div class="hero">
                <div class="swiper hero_swiper">
                    <!-- slide item -->
                    <div class="swiper-slide">
                        <div class="hero__bg bg-image" style="background-image: url(../images/lennart-uecker-49bZDF_EgJU-unsplash.jpg);"></div>
                        <div class="container">
                            <div class="hero__content">
                                <div class="hero__txt">
                                    <h2>Niek Air Max</h2>
                                    <p>Nhu cc</p>
                                </div>
                                <div class="hero__img">
                                    <img src="../images/Nike-Shoes-Air-Max-PNG-Image.png" alt="product image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- slide item -->
                    <div class="swiper-slide">
                        <div class="hero__bg bg-image" style="background-image: url(../images/lennart-uecker-49bZDF_EgJU-unsplash.jpg);"></div>
                        <div class="container">
                            <div class="hero__content">
                                <div class="hero__txt">
                                    <h2>Niek Jordan</h2>
                                    <p>Nhu cc thatay chu</p>
                                </div>
                                <div class="hero__img">
                                    <img src="../images/Nike-Shoes-Jordan-PNG-Pic.png" alt="product image">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div> 
        </section>
        <!-- hero slide -->

        <!-- top product -->
        <section>
            <div class="container">
                <div class="top-product__header">
                    <h2 class="section__title no-margin">Top pick</h2>
                    <div class="top-product__swiper__btn" id="top-product__swiper__btn">
                        <div class="swiper-button btn-prev">
                            <i class="bx bx-chevron-left"></i>
                        </div>
                        <div class="swiper-button btn-next">
                            <i class="bx bx-chevron-right"></i>
                        </div>
                    </div>
                </div>
                <div class="swiper top-product__swiper">
                    <div class="swiper-wrapper">
                        <!-- slide item -->
                        <div class="swiper-slide">
                            <div class="product-card">
                                <div class="product__image">
                                    <img src="../images/Nike-Shoes-PNG-Photos.png" alt="product image">
                                </div>
                                <div class="product__info">
                                    <h5 class="product__name">Nike Air</h5>
                                    <span class="product__price"> 1.234.000 </span>
                                </div>
                            </div>
                        </div>
                        <!-- slide item -->
                        <div class="swiper-slide">
                            <div class="product-card">
                                <div class="product__image">
                                    <img src="../images/Nike-Shoes.png" alt="product image">
                                </div>
                                <div class="product__info">
                                    <h5 class="product__name">Nike Air</h5>
                                    <span class="product__price"> 1.234.000 </span>
                                </div>
                            </div>
                        </div>
                        <!-- slide item -->
                        <div class="swiper-slide">
                            <div class="product-card">
                                <div class="product__image">
                                    <img src="../images/Nike-Shoes-PNG-Images-HD.png" alt="product image">
                                </div>
                                <div class="product__info">
                                    <h5 class="product__name">Nike Air</h5>
                                    <span class="product__price"> 1.234.000 </span>
                                </div>
                            </div>
                        </div>
                        <!-- slide item -->
                        <div class="swiper-slide">
                            <div class="product-card">
                                <div class="product__image">
                                    <img src="../images/Nike-Shoes-PNG-File.png" alt="product image">
                                </div>
                                <div class="product__info">
                                    <h5 class="product__name">Nike Air</h5>
                                    <span class="product__price"> 1.234.000 </span>
                                </div>
                            </div>
                        </div>
                        <!-- slide item -->
                        <div class="swiper-slide">
                            <div class="product-card">
                                <div class="product__image">
                                    <img src="../images/Nike-Shoes-PNG-Image.png" alt="product image">
                                </div>
                                <div class="product__info">
                                    <h5 class="product__name">Nike Air</h5>
                                    <span class="product__price"> 1.234.000 </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- category banner -->
        <section>
            <div class="container">
                <h2 class="section__title">Trending now</h2>
                <div class="category__grid">
                    <!-- item -->
                    <div class="category__item category__top">
                        <div class="category__banner bg-image" style="background-image: url(../images/taylor-smith-aDZ5YIuedQg-unsplash.jpg);"></div>
                        <div class="category__overlay"></div>
                        <div class="category__border"></div>
                        <div class="category__txt">Nike Jordan</div>
                    </div>
                    <!-- item -->
                    <div class="category__item category__left">
                        <div class="category__banner bg-image" style="background-image: url(../images/eugene-chystiakov-b2uYNpBG7Ho-unsplash.jpg);"></div>
                        <div class="category__overlay"></div>
                        <div class="category__border"></div>
                        <div class="category__txt">Nike Jordan</div>
                    </div>
                    <!-- item -->
                    <div class="category__item category__right">
                        <div class="category__banner bg-image" style="background-image: url(../images/jake-weirick-pu-PgXMI30I-unsplash.jpgg);"></div>
                        <div class="category__overlay"></div>
                        <div class="category__border"></div>
                        <div class="category__txt">Nike Jordan</div>
                    </div>
                </div>
            </div>
        </section>                   
        
        <!-- promotion product -->
        <section>
            <div class="container">
                <div class="hero__content">
                    <div class="hero__txt">
                        <h2>Nike Foam</h2>
                        <p>hello it's me</p>
                        <button class="btn-home">Go to Collecttion</button>
                    </div>
                    <div class="hero__img">
                        <img src="../images/Nike-Shoes-PNG-File.png" alt="product image">
                    </div>
                </div>
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
        <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    </body>
</html>