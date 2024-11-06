<?php 
include("conn.php");
require_once('./pdf/config/tcpdf_config.php');
require_once('./pdf/tcpdf.php');

date_default_timezone_set('Asia/Manila');

$loadstocks = '';

$query="SELECT 
p.name AS product_name,
pv.variant_name,
pv.price AS price_per_unit,
COALESCE(SUM(so.quantity), 0) AS total_quantity,
COALESCE(SUM(so.quantity * pv.price), 0) AS total_amount
FROM 
product_variants pv
JOIN 
products p ON pv.product_id = p.id
LEFT JOIN 
sales_orders so ON pv.id = so.product_variant_id AND so.status = 'delivered'
GROUP BY 
p.name, pv.variant_name
ORDER BY 
total_amount DESC;
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

$loadstocks .= '<br><h1 style="text-align:center;">Product Sales
                <br><span class="txt_date"> As of ' . date("F j, Y h:i:s A") .'</span></h1><br>';

$loadstocks .= '<table width="100%" border="1" cellspacing="0" cellpadding="5">
                    <tr>
                    <th>Product</th>
                        <th>Variant Name</th>
                        <th>Price Per Unit</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                    </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    $loadstocks .= '<tr>
                        <td>' . $row["product_name"] . '</td>
                        <td>' . $row["variant_name"] . '</td>
                        <td>Php ' . number_format($row["price_per_unit"], 2 ). '</td>
                        <td>' . $row["total_quantity"] . '</td>
                        <td>Php ' . number_format($row["total_amount"], 2) . '</td>
                    
                </tr>';
    

    
}


$loadstocks .= '</table>';



$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML($loadstocks);
$filename = "productsales" . date("Y-m-d") . ".pdf";
$pdf->Output($filename, 'I');


?>