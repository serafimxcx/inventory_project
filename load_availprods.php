<?php 
include("conn.php");

$query = "select product_variants.id as variant_id, product_variants.variant_name from product_variants, purchase_orders where purchase_orders.product_variant_id = product_variants.id and purchase_orders.status = 'completed' and product_variants.product_id=".$_REQUEST["product_id"];
$result = mysqli_query($conn,$query) or die(mysqli_error($conn));

$getvariant = "";
$getvariant .= "<option value=''>Select a product variant...</option>";
while($row = $result->fetch_assoc()){
    $getvariant .= "<option value='$row[variant_id]'>$row[variant_name]</option>";
}


echo $getvariant;

?>