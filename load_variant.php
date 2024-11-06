<?php 
include("conn.php");

$query = "select * from product_variants where product_id=".$_REQUEST["product_id"];
$result = mysqli_query($conn,$query) or die(mysqli_error($conn));

$getvariant = "";
$getvariant .= "<option value=''>Select a product variant...</option>";
while($row = $result->fetch_assoc()){
    $getvariant .= "<option value='$row[id]'>$row[variant_name]</option>";
}


echo $getvariant;

?>