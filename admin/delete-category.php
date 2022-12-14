<?php include 'admin.php'; ?>
<?php
$product_name =  $product_price = $product_description = $message = '';
if (isset($_GET['category'])) {
    $productid = $_GET['category'];
    $checkproduct = "DELETE  FROM `categories` WHERE `category_id` = '$productid'";
    $querycheckproduct = mysqli_query($conn, $checkproduct); 
    if($querycheckproduct){
        echo "<script>window.location.replace('all-categories.php');</script>";
    }
    
} 
