<style>
    .p-3 {
        transition: box-shadow 0.5s;
    }

    .p-3:hover {
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.5), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
//Khi di chuột qua phần tử với class .p-3, sẽ có hiệu ứng box shadow.
    .btn:hover {
        color: #1d1d1d;
    }
//Khi di chuột qua nút với class .btn, màu chữ sẽ thay đổi thành màu đen (#1d1d1d).
    .card {
        font-family: 'Gilroy Regular';
    }

    .card .card-title {
        font-family: 'Gilroy Bold';
    }

    #product-price {
        font-family: 'Gilroy Bold';
        font-size: 36px;
    }
//Áp dụng font family và font size cho các phần tử trong card và thông báo alert.
    #footer-price {
        vertical-align: center;
    }

    .card-img-top {
        width: 100%;
        height: 15vw;
        object-fit: scale-down;
    }
//Định dạng hình ảnh trong card sao cho nó chiếm 100% chiều rộng và có chiều cao tối đa là 15% của chiều rộng viewport.
    .alert {
        font-family: 'Gilroy Regular';
        margin: auto;
        text-align: center;
    }
</style>

<div class="bg-light px-3 px-lg-5 py-4 py-lg-5">
    <div class="container">
        <?php
        include "libs/form_categorize_product.php";
        ?>

        <!--Product list-->
        <div class="row mt-5">
            <!--Get product data-->
            <?php
            $product_data = [];
#Kiểm tra nếu có từ khóa tìm kiếm hoặc bộ lọc được áp dụng.
            if (isset($_GET['query']))/* Lấy dữ liệu sản phẩm từ session hoặc truy vấn cơ sở dữ liệu theo từ khóa tìm kiếm*/ {
                $_SESSION['query'] = $_GET['query'];
                $product_data_enc = json_encode($_SESSION['query_temp_data']);
                $product_data = json_decode($product_data_enc, true);
            }
# Lấy dữ liệu sản phẩm sau khi áp dụng bộ lọc
            if (isset($_GET['submit_filter'])) {
                if ($_GET['brand'] == 'all' & $_GET['os'] == 'all' & $_GET['cpu'] == 'all' & $_GET['ram'] == 'all'
                & $_GET['storage'] == 'all' & $_GET['min-price'] == '' & $_GET['max-price'] == '') {
                    $records = $db->getProductsByName($_SESSION['query']);
                    $_SESSION['temp'] = true;
                } /*Lấy dữ liệu sản phẩm theo từ khóa tìm kiếm (nếu có) hoặc theo mặc định*/
                else {
                    $brand = ($_GET['brand'] == 'all') ? '' : $_GET['brand'];
                    $os = ($_GET['os'] == 'all') ? '' : $_GET['os'];
                    $cpu = ($_GET['cpu'] == 'all') ? '' : $_GET['cpu'];
                    $ram = ($_GET['ram'] == 'all') ? '' : $_GET['ram'];
                    $storage = ($_GET['storage'] == 'all') ? '' : $_GET['storage'];
                    $min_price = ($_GET['min-price'] == '') ? '' : (int)$_GET['min-price'];
                    $max_price = ($_GET['max-price'] == '') ? '' : (int)$_GET['max-price'];

                    $records = $db->getFilteredProducts(
                        $_SESSION['query'],
                        $brand,
                        $os,
                        $cpu,
                        $ram,
                        $storage,
                        $min_price,
                        $max_price
                    ); // fetch filtered products
                }
            }

            else {
                $records = $db->getProductsByName($_SESSION['query']);
                $_SESSION['temp'] = false;
            }

            while ($data = mysqli_fetch_assoc($records)) {
                // Store product information lists in this variable
                $product_data[] = $data;
            }
#// Hiển thị danh sách sản phẩm nếu có dữ liệu
            if (count($product_data) > 0) {
                // Sử dụng vòng lặp foreach để hiển thị mỗi sản phẩm trong một card.
                foreach ($product_data as $row) { ?>
                    <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
                        <div class="card shadow-sm bg-white rounded">
                            <div class="p-3">
                                <!--Product information (Image, SKU, CPU, RAM, GPU, Storage, Price-->
                                <img src='<?php echo $row['productPhoto']; ?>' class="card-img-top" alt="<?php echo $row['productName']; ?>">
                                <div class="card-body">
                                    <h3 id="pname" class="card-title"><?php echo $row['productName']; ?></h3>
                                    <p>SKU: <?php echo $row['productSKU']; ?></p>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li id="pcpu" class="list-group-item">CPU: <?php echo $row['productCPU']; ?></li>
                                    <li id="pram" class="list-group-item">RAM: <?php echo $row['productRAM']; ?></li>
                                    <li id="pgpu" class="list-group-item">GPU: <?php echo $row['productGPU']; ?></li>
                                    <li id="psto" class="list-group-item">Storage: <?php echo $row['productSto']; ?></li>
                                </ul>
                                <div class="card-body" id="footer-price">
                                    <h3><label id="product-price" style="float:left;">$<?php echo $row['productPrice']; ?></label></h3>
                                    <form method='POST' action="libs/product/add_cart.php">
                                        <input type="hidden" name='pprice' class="pprice" value="<?php echo $row['productPrice']; ?>">
                                        <input type="hidden" name='psku' class="psku" value="<?php echo $row['productSKU']; ?>">
                                        <input type="hidden" name='pid' class="pid" value="<?php echo $row['pid']; ?>">
                                        <button type='submit' class="btn-custom" style="float:right;"><span>Add to cart</span></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else /* Hiển thị thông báo nếu không có sản phẩm nào được tìm thấy*/{ 
                echo $_SESSION['query'];
                echo $_SESSION['temp']; ?>
                <div class="alert alert-dark col-md-2" role="alert">
                    No products found! #Hiển thị một thông báo nếu không có sản phẩm nào được tìm thấy.
                </div>
            <?php }
            ?>
        </div>
    </div>
</div>