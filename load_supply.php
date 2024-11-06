<?php 
include("conn.php");



$query = "select purchase_orders.id as order_id, purchase_orders.order_date, purchase_orders.quantity, purchase_orders.status, purchase_orders.purchase_code, suppliers.name as supplier_name, product_variants.variant_name, products.name as product_name from purchase_orders, suppliers, product_variants, products where purchase_orders.supplier_id = suppliers.id and purchase_orders.product_variant_id = product_variants.id and product_variants.product_id = products.id and purchase_orders.supplier_id like '%$_GET[supplier_id]%' and purchase_orders.status like '%$_GET[status]%' and   purchase_orders.order_date like '%$_GET[order_date]%'";


$result = mysqli_query($conn,$query) or die(mysqli_error($conn));

$getsupply = "";
$getsupply .= "<table width='100%' class='table table-bordered'>
    <tr>
        <th>Date</th>
        <th>Purchase Code</th>
        <th>Supplier</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>Status</th>
    </tr>";
while($row = $result->fetch_assoc()){
    $getsupply .= "<tr>
    <td>$row[order_date]</td>
    <td>$row[purchase_code]</td>
    <td>$row[supplier_name]</td>
    <td>$row[product_name] - $row[variant_name]</td>
    <td>$row[quantity]</td>
    <td>$row[status]</td>
    </tr>";
}

$getsupply .= "</table>";
echo $getsupply;

?>