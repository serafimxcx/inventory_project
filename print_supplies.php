<?php 
include("conn.php");
require_once('./pdf/config/tcpdf_config.php');
require_once('./pdf/tcpdf.php');

date_default_timezone_set('Asia/Manila');

$loadstocks = '';

$query = "select purchase_orders.id as order_id, purchase_orders.order_date, purchase_orders.quantity, purchase_orders.status, purchase_orders.purchase_code, suppliers.name as supplier_name, product_variants.variant_name, products.name as product_name from purchase_orders, suppliers, product_variants, products where purchase_orders.supplier_id = suppliers.id and purchase_orders.product_variant_id = product_variants.id and product_variants.product_id = products.id and purchase_orders.supplier_id like '%$_GET[supplier_id]%' and purchase_orders.status like '%$_GET[status]%' and   purchase_orders.order_date like '%$_GET[order_date]%'";



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

$loadstocks .= '<br><h1 style="text-align:center;">Supplier Report';

                if($_GET["order_date"] != ""){  
                    $loadstocks .= '<br><span class="txt_date"> ' . $_GET["order_date"] .'</span></h1><br>';
                    
                }else{
                    $loadstocks .= '<br><span class="txt_date"> As of ' . date("F j, Y h:i:s A") .'</span></h1><br>';
                }
                

$loadstocks .= '<table width="100%" border="1" cellspacing="0" cellpadding="5">
                    <tr>
                    <th>Date</th>
                    <th>Purchase Code</th>
                    <th>Supplier</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    $loadstocks .= '<tr>
                        <td>' . $row["order_date"] . '</td>
                        <td>' . $row["purchase_code"] . '</td>
                        <td>' . $row["supplier_name"] . '</td>
                        <td>' . $row["product_name"] . ' '. $row["variant_name"]  .'</td>
                        <td>' . $row["quantity"] . '</td>
                        <td>' . $row["status"] . '</td>
                    
                </tr>';
    
}


$loadstocks .= '</table>';



$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($loadstocks);
$filename = "supplies" . date("Y-m-d") . ".pdf";
$pdf->Output($filename, 'I');


?>