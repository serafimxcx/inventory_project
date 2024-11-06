<?php 
include("conn.php");
require_once('./pdf/config/tcpdf_config.php');
require_once('./pdf/tcpdf.php');

date_default_timezone_set('Asia/Manila');

$loadstocks = '';

$query="
SELECT 
                        p.id AS product_id,
                        p.code AS product_code,
                        p.name AS product_name,
                        IFNULL(stocks_in_hand.stocks_in_hand, 0) AS stocks_in_hand,
                        IFNULL(stocks_on_order.stocks_on_order, 0) AS stocks_on_order,
                        IFNULL(stocks_reserved.stocks_reserved, 0) AS stocks_reserved
                    FROM
                        products p
                    LEFT JOIN 
                        (
                            SELECT 
                                pv.product_id,
                                SUM(CASE 
                                        WHEN po.status = 'completed' THEN po.quantity 
                                        ELSE 0 
                                    END
                                ) -
                                SUM(CASE 
                                        WHEN so.status = 'delivered' THEN so.quantity 
                                        ELSE 0 
                                    END
                                ) +
                                SUM(CASE 
                                        WHEN cr.quantity IS NOT NULL THEN cr.quantity 
                                        ELSE 0 
                                    END
                                ) -
                                SUM(CASE 
                                        WHEN w.quantity IS NOT NULL THEN w.quantity 
                                        ELSE 0 
                                    END
                                ) -
                                SUM(CASE 
                                        WHEN bi.quantity IS NOT NULL THEN bi.quantity 
                                        ELSE 0 
                                    END
                                ) -
                                SUM(CASE 
                                        WHEN rp.quantity IS NOT NULL THEN rp.quantity 
                                        ELSE 0 
                                    END
                                ) AS stocks_in_hand
                            FROM
                                product_variants pv
                            LEFT JOIN 
                                purchase_orders po ON pv.id = po.product_variant_id AND po.status = 'completed'
                            LEFT JOIN 
                                sales_orders so ON pv.id = so.product_variant_id AND so.status = 'delivered'
                            LEFT JOIN 
                                customer_returns cr ON so.id = cr.sales_order_id
                            LEFT JOIN 
                                wastages w ON pv.id = w.product_variant_id
                            LEFT JOIN 
                                bin_inventory bi ON pv.id = bi.product_variant_id
                            LEFT JOIN 
                                return_purchase rp ON pv.id = rp.product_variant_id
                            GROUP BY 
                                pv.product_id
                        ) AS stocks_in_hand ON p.id = stocks_in_hand.product_id
                    LEFT JOIN 
                        (
                            SELECT 
                                pv.product_id,
                                SUM(po.quantity) AS stocks_on_order
                            FROM
                                product_variants pv
                            LEFT JOIN 
                                purchase_orders po ON pv.id = po.product_variant_id AND po.status = 'pending'
                            GROUP BY 
                                pv.product_id
                        ) AS stocks_on_order ON p.id = stocks_on_order.product_id
                    LEFT JOIN 
                        (
                            SELECT 
                                pv.product_id,
                                SUM(CASE 
                                        WHEN so.status IN ('pending', 'shipped') THEN so.quantity 
                                        ELSE 0 
                                    END
                                ) +
                                SUM(CASE 
                                        WHEN bi.quantity IS NOT NULL THEN bi.quantity 
                                        ELSE 0 
                                    END
                                ) AS stocks_reserved
                            FROM
                                product_variants pv
                            LEFT JOIN 
                                sales_orders so ON pv.id = so.product_variant_id AND so.status IN ('pending', 'shipped')
                            LEFT JOIN 
                                bin_inventory bi ON pv.id = bi.product_variant_id
                            GROUP BY 
                                pv.product_id
                        ) AS stocks_reserved ON p.id = stocks_reserved.product_id
                    GROUP BY 
                        p.id;
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

$loadstocks .= '<br><h1 style="text-align:center;">Stocks
                <br><span class="txt_date"> As of ' . date("F j, Y h:i:s A") .'</span></h1><br>';

$loadstocks .= '<table width="100%" border="1" cellspacing="0" cellpadding="5">
                    <tr>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Stocks In Hand</th>
                    <th>Stocks On Order</th>
                    <th>Stocks Reserved</th>
                    </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    $loadstocks .= '<tr>
                        <td>' . $row["product_code"] . '</td>
                        <td>' . $row["product_name"] . '</td>
                        <td>' . $row["stocks_in_hand"] . '</td>
                        <td>' . $row["stocks_on_order"] . '</td>
                        <td>' . $row["stocks_reserved"] . '</td>
                    
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