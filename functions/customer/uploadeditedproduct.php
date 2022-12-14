<?php
include '../db-connection.php';
$product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
$product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
$product_sub_category = mysqli_real_escape_string($conn, $_POST['product_sub_category']);
$product_description = mysqli_real_escape_string($conn, $_POST['product_description']);


if (empty($product_name) || empty($product_price)  || empty($product_sub_category) || empty($product_description)) {
    $message = "
        <script>
            toastr.error('Please Provide all the details');
        </script>
    ";
} else if (!preg_match("/^[a-zA-z0-9 ]*$/", $product_name)) {
    $message = "
        <script>
            toastr.error('Provided an invalid name');
        </script>
    ";
} else {
    if ($_FILES['picture'] === "") {
        $checkcategory = "SELECT *  FROM `sub_categories` WHERE `sub_category_id`= '$product_sub_category'";
        $querycategory = mysqli_query($conn, $checkcategory);
        $checkcategoryrows = mysqli_num_rows($querycategory);
        if ($checkcategoryrows >= 1) {
            while ($fetchcategory = mysqli_fetch_assoc($querycategory)) {
                $fetchedcategory = $fetchcategory['sub_category_category_id'];
                $updateproduct = "UPDATE  `products` SET `product_name`='$product_name', `product_description`='$product_description', `product_price`='$product_price', `product_category_id`='$fetchedcategory', `product_sub_category_id`='$product_sub_category' WHERE `product_id`='$globalproductid'";
                $queryupdateproduct = mysqli_query($conn, $updateproduct);
                if ($queryupdateproduct) {
                    $message = "
                            <script>
                                toastr.success('Product has been updated succesfully');
                            </script>
                                ";
                    echo "<script>window.location.replace('my-products.php');</script>";
                }
            }
        } else {
            $message = "
                        <script>
                            toastr.error('An error occurred ');
                        </script>
                            ";
        }
    } else {
        $filename = $_FILES['picture']['name'];
        $filetmpname = $_FILES['picture']['tmp_name'];
        $filesize = $_FILES['picture']['size'];
        $fileerror = $_FILES['picture']['error'];
        $filetype = $_FILES['picture']['type'];
        $fileext = explode('.', $filename);
        $fileActualExt = strtolower(end($fileext));
        $allowed = array('jpg', 'png', 'jpeg');
        if (in_array($fileActualExt, $allowed)) {
            if ($fileerror === 0) {
                if ($filesize > 10000000) {

                    $message = "
                            <script>
                                toastr.error('Image Upload Too Big');
                            </script>
                                ";
                } else {
                    $filenamenew = uniqid('', true) . "." . $fileActualExt;
                    $filedestination = '../products/' . $filenamenew;
                    move_uploaded_file($filetmpname, $filedestination);
                    $checkcategory = "SELECT *  FROM `sub_categories` WHERE `sub_category_id`= '$product_sub_category'";
                    $querycategory = mysqli_query($conn, $checkcategory);
                    $checkcategoryrows = mysqli_num_rows($querycategory);
                    if ($checkcategoryrows >= 1) {
                        while ($fetchcategory = mysqli_fetch_assoc($querycategory)) {
                            $fetchedcategory = $fetchcategory['sub_category_category_id'];
                            $updateproduct = "UPDATE  `products` SET `product_name`='$product_name', `product_description`='$product_description', `product_price`='$product_price', `product_images`='$filenamenew', `product_category_id`='$fetchedcategory', `product_sub_category_id`='$product_sub_category' WHERE `product_id`='$globalproductid'";
                            $queryupdateproduct = mysqli_query($conn, $updateproduct);
                            if ($queryupdateproduct) {
                                $message = "
                                        <script>
                                            toastr.success('Image Upload success');
                                        </script>
                                            ";
                                echo "<script>window.location.replace('my-products.php');</script>";
                            }
                        }
                    } else {
                        $message = "
                                    <script>
                                        toastr.error('An error occurred ');
                                    </script>
                                        ";
                    }
                }
            } else {
                $message = "
                        <script>
                            toastr.error('An error occurred during images upload');
                        </script>
                            ";
            }
        } else {
            $message = "
                    <script>
                        toastr.error('Upload Only Images with jpg,png, jpeg extensions as product images');
                    </script>
                ";
        }
    }
}
