<?php 
include("conn.php");
require_once('./pdf/config/tcpdf_config.php');
require_once('./pdf/tcpdf.php');

date_default_timezone_set('Asia/Manila');

$loadstocks = '';

$query="select sales_orders.id as sales_id, sales_orders.order_date, sales_orders.order_no, sales_orders.customer_name, sales_orders.quantity, sales_orders.status, products.name as product_name, product_variants.variant_name, product_variants.price from sales_orders, product_variants, products where sales_orders.product_variant_id = product_variants.id and product_variants.product_id = products.id and sales_orders.status = 'delivered';
";

$totalamount=0;

$result = mysqli_query($conn, $query);

$loadstocks .= '<style>
                table th { 
                    text-align: center; 
                    font-weight: bold; 
                    background-color: #000361; 
                    color: white; 
                    border: 1px solid black;
                }
                table td {
                    border: 1px solid black;
                }
                .txt_date {
                    font-size: 12px;
                    line-height: 1.6;
                    font-weight: 100;
                }
                </style>';

$loadstocks .= '<br><h1 style="text-align:center;">Sales Report
                <br><span class="txt_date"> As of ' . date("F j, Y h:i:s A") .'</span></h1><br>';

$loadstocks .= '<table width="100%" border="1" cellspacing="0" cellpadding="5">
                    <tr>
                        <th>Date</th>
                        <th>Order No.</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Price Per Unit</th>
                        <th>Quantity</th>
                        <th>Amount</th>
                    </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    $amount = $row["price"]*$row["quantity"];
    $loadstocks .= '<tr>
                        <td>' . $row["order_date"] . '</td>
                        <td>' . $row["order_no"] . '</td>
                        <td>' . $row["customer_name"] . '</td>
                        <td>' . $row["product_name"] . ' '. $row["variant_name"]  .'</td>
                        <td>Php ' . number_format($row["price"] , 2). '</td>
                        <td>' . $row["quantity"] . '</td>
                        <td>Php ' . number_format($amount, 2) . '</td>
                    
                </tr>';
    
    $totalamount = $totalamount + $amount;
    
}


$loadstocks .= '<tr>
        <td colspan="7"><span><b>Total Amount: '.number_format($totalamount, 2).'</b></span></td>
</tr>

</table>';



$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($loadstocks);
$filename = "sales" . date("Y-m-d") . ".pdf";
$pdf->Output($filename, 'I');


?>