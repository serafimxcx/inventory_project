<?php 
include("conn.php");
require_once('./pdf/config/tcpdf_config.php');
require_once('./pdf/tcpdf.php');

date_default_timezone_set('Asia/Manila');

$prodvariant_id = $_GET["prodvariant_id"];
$prodvariant_name = $_GET["prodvariant_name"];

$loadstocks = '';


$query="SELECT 
description,
in_qty,
out_qty,
@stock_balance := @stock_balance + in_qty - out_qty AS stock_balance
FROM (
SELECT 
    'Stock from Supplier' AS description,
    po.quantity AS in_qty,
    0 AS out_qty,
    po.order_date AS date
FROM 
    purchase_orders po
WHERE 
    po.product_variant_id = '$prodvariant_id' AND po.status = 'completed'
UNION ALL
SELECT 
    'Return to Supplier' AS description,
    0 AS in_qty,
    rp.quantity AS out_qty,
    rp.return_date AS date
FROM 
    return_purchase rp
WHERE 
    rp.product_variant_id ='$prodvariant_id'
UNION ALL
SELECT 
    'Sales' AS description,
    0 AS in_qty,
    so.quantity AS out_qty,
    so.order_date AS date
FROM 
    sales_orders so
WHERE 
    so.product_variant_id ='$prodvariant_id' AND so.status = 'delivered'
UNION ALL
SELECT 
    'Return from Customer' AS description,
    cr.quantity AS in_qty,
    0 AS out_qty,
    cr.return_date AS date
FROM 
    customer_returns cr
JOIN 
    sales_orders so ON cr.sales_order_id = so.id
WHERE 
    so.product_variant_id = '$prodvariant_id'
UNION ALL
SELECT 
    'Wastages' AS description,
    0 AS in_qty,
    w.quantity AS out_qty,
    w.wastage_date AS date
FROM 
    wastages w
WHERE 
    w.product_variant_id = '$prodvariant_id'
UNION ALL
SELECT 
    'Added in Warehouse Bin' AS description,
    0 AS in_qty,
    bi.quantity AS out_qty,
    bi.created_at AS date
FROM 
    bin_inventory bi
WHERE 
    bi.product_variant_id = '$prodvariant_id'
) AS history
CROSS JOIN (SELECT @stock_balance := 0) AS init
ORDER BY 
date;

";

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

$loadstocks .= '<br><h1 style="text-align:center;">'.$prodvariant_name.' History
                <br><span class="txt_date"> As of ' . date("F j, Y h:i:s A") .'</span></h1><br>';

$loadstocks .= '<table width="100%" border="1" cellspacing="0" cellpadding="5">
                    <tr>
                    <th>Description</th>
                    <th>In</th>
                    <th>Out</th>
                    <th>Stock Balance</th>
                    </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    $loadstocks .= '<tr>
                        <td>' . $row["description"] . '</td>
                        <td>' . $row["in_qty"] . '</td>
                        <td>' . $row["out_qty"] . '</td>
                        <td>' . $row["stock_balance"] . '</td>
                    
                </tr>';
    

    
}


$loadstocks .= '</table>';



$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($loadstocks);
$filename = "stocks" . date("Y-m-d") . ".pdf";
$pdf->Output($filename, 'I');


?>