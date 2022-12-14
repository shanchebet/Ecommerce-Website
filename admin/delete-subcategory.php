<?php include 'admin.php'; ?>
<?php
$product_name =  $product_price = $product_description = $message = '';
if (isset($_GET['subcategory'])) {
    $productid = $_GET['subcategory'];
    $checkproduct = "DELETE  FROM `sub_categories` WHERE `sub_category_id` = '$productid'";
    $querycheckproduct = mysqli_query($conn, $checkproduct); 
    if($querycheckproduct){
        echo "<script>window.location.replace('all-sub-categories.php');</script>";
    }
    
} 
