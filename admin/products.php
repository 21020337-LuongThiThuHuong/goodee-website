<?php
    include '../config/connect.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];

    if (!isset($admin_id)) {
        header('location:admin_login');
    }

    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $price = $_POST['price'];
        $price = filter_var($price, FILTER_SANITIZE_STRING);
        $details = $_POST['details'];
        $details = filter_var($details, FILTER_SANITIZE_STRING);

        $image_01 = $_FILES['image_01']['name'];
        $image_01 = filter_var($image_01, FILTER_SANITIZE_STRING);
        $image_size_01 = $_FILES['image_01']['size'];
        $image_tmp_name_01 = $_FILES['image_01']['tmp_name'];
        $image_folder_01 = '../uploaded_img/'.$image_01;

        $image_02 = $_FILES['image_02']['name'];
        $image_02 = filter_var($image_02, FILTER_SANITIZE_STRING);
        $image_size_02 = $_FILES['image_02']['size'];
        $image_tmp_name_02 = $_FILES['image_02']['tmp_name'];
        $image_folder_02 = '../uploaded_img/'.$image_02;

        $image_03 = $_FILES['image_03']['name'];
        $image_03 = filter_var($image_03, FILTER_SANITIZE_STRING);
        $image_size_03 = $_FILES['image_03']['size'];
        $image_tmp_name_03 = $_FILES['image_03']['tmp_name'];
        $image_folder_03 = '../uploaded_img/'.$image_03;
        
        $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
        $select_products->execute([$name]);

        if ($select_products->rowCount() > 0) {
            $message[] = 'Tên sản phẩm này đã tồn tại.';
        } else {
            $insert_products = $conn->prepare("INSERT INTO `products` (name, details, price, image_01, image_02, image_03) VALUES(?, ?, ?, ?, ?, ?)");
            $insert_products->execute([$name, $details, $price, $image_01, $image_02, $image_03]);

            if ($insert_products) {
                if ($image_size_01 > 2000000 or $image_size_02 > 2000000 or $image_size_03 > 2000000) {
                    $message[] = 'Kích cỡ ảnh quá lớn.';
                } else {
                    move_uploaded_file($image_tmp_name_01, $image_folder_01);
                    move_uploaded_file($image_tmp_name_02, $image_folder_02);
                    move_uploaded_file($image_tmp_name_03, $image_folder_03);
                    $message[] = 'Đã thêm sản phẩm mới.';
                }
            }
        }
    }

    if (isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $delete_pd_iamge = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
        $delete_pd_iamge->execute([$delete_id]);
        $fetch_delete_image = $delete_pd_iamge->fetch(PDO::FETCH_ASSOC);

        unlink('../uploaded_img/'.$fetch_delete_image['image_01']);
        unlink('../uploaded_img/'.$fetch_delete_image['image_02']);
        unlink('../uploaded_img/'.$fetch_delete_image['image_03']);

        $delete_pd = $conn->prepare("DELETE FROM `products` WHERE id = ?");
        $delete_pd->execute([$delete_id]);
        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
        $delete_cart->execute([$delete_id]);
        $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
        $delete_wishlist->execute([$delete_id]);

        header('location:products.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản phẩm</title>

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
        <section class="add-products">
            <h1 class="heading">Thêm sản phẩm</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="flex">
                    <div class="inputBox">
                        <span>Tên sản phẩm (*)</span>
                        <input type="text" class="box" required maxlength="100" placeholder="Nhập tên sản phẩm.." name="name">
                    </div>
                    <div class="inputBox">
                        <span>Giá (*)</span>
                        <input type="number" min="0" class="box" required max="9999999999" placeholder="Chọn giá sản phẩm.." onkeypress="if(this.value.length == 10) return false;" name="price">
                    </div>
                    <div class="inputBox">
                        <span>Ảnh 01 (*)</span>
                        <input type="file" name="image_01" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
                    </div>
                    <div class="inputBox">
                        <span>Ảnh 02 (*)</span>
                        <input type="file" name="image_02" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
                    </div>
                    <div class="inputBox">
                        <span>Ảnh 03 (*)</span>
                        <input type="file" name="image_03" accept="image/jpg, image/jpeg, image/png, image/webp" class="box" required>
                    </div>
                    <div class="inputBox">
                        <span>Mô tả sản phẩm (*)</span>
                        <textarea name="details" placeholder="Nhập mô tả.." class="box" required maxlength="500" cols="30" rows="10"></textarea>
                    </div>
                </div>

                <input type="submit" value="Thêm sản phẩm" class="btn" name="add_product">
            </form>
        </section>

        <section class="show-products">

            <h1 class="heading">Sản phẩm đã thêm</h1>
            <div class="box-container">
                <?php
                    $select_products = $conn->prepare("SELECT * FROM `products`");
                    $select_products->execute();
                    if ($select_products->rowCount() > 0) {
                        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) { 
                            ?>
                            <div class="box">
                                <img src="../uploaded_img/<?= $fetch_products['image_01']; ?>" alt="">
                                <div class="name"><?= $fetch_products['name']; ?></div>
                                <div class="price">$<span><?= $fetch_products['price']; ?></span>/-</div>
                                <div class="details"><span><?= $fetch_products['details']; ?></span></div>
                                <div class="flex-btn">
                                    <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Cập nhật</a>
                                    <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Xóa sản phẩm?');">Xóa</a>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p class="empty">Không có sản phẩm nào</p>';
                    }
                ?>
            </div>
        </section>

        <script src="../js/admin_script.js"></script>
    </body>
</html>