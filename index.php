<?php 
    include("navbar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <div class="content_container_index">
        <div class="row">
            <div class="col-lg-6" style="padding: 10px;">
                <div class="report_container">
                    <h3><b>Minimal Stocks</b></h3>
                    <h5>These products already meet its reorder points and needs to be replenished...</h5><hr>
                    <?php
                    $result=$conn->query("SELECT 
                    p.id AS product_id,
                    p.name AS product_name,
                    pv.variant_name,
                    COALESCE(stocks_in_hand.total_quantity, 0) AS stock_in_hand,
                    p.reorder_point
                    FROM 
                        products p
                    JOIN 
                        product_variants pv ON p.id = pv.product_id
                    LEFT JOIN 
                        (
                            SELECT 
                                pv.id AS product_variant_id,
                                SUM(
                                    CASE 
                                        WHEN po.status = 'completed' THEN po.quantity 
                                        ELSE 0 
                                    END
                                ) - 
                                SUM(
                                    CASE 
                                        WHEN so.status = 'delivered' THEN so.quantity 
                                        ELSE 0 
                                    END
                                ) + 
                                SUM(
                                    CASE 
                                        WHEN cr.quantity IS NOT NULL THEN cr.quantity 
                                        ELSE 0 
                                    END
                                ) - 
                                SUM(
                                    CASE 
                                        WHEN w.quantity IS NOT NULL THEN w.quantity 
                                        ELSE 0 
                                    END
                                ) - 
                                SUM(
                                    CASE 
                                        WHEN bi.quantity IS NOT NULL THEN bi.quantity 
                                        ELSE 0 
                                    END
                                ) AS total_quantity
                            FROM 
                                product_variants pv
                            LEFT JOIN 
                                purchase_orders po ON pv.id = po.product_variant_id AND po.status = 'completed'
                            LEFT JOIN 
                                sales_orders so ON pv.id = so.product_variant_id AND so.status = 'delivered'
                            LEFT JOIN 
                                customer_returns cr ON cr.sales_order_id = so.id
                            LEFT JOIN 
                                wastages w ON pv.id = w.product_variant_id
                            LEFT JOIN 
                                bin_inventory bi ON pv.id = bi.product_variant_id
                            GROUP BY 
                                pv.id
                        ) AS stocks_in_hand ON pv.id = stocks_in_hand.product_variant_id
                    WHERE 
                        COALESCE(stocks_in_hand.total_quantity, 0) <= p.reorder_point
                    ORDER BY 
                        p.id, pv.variant_name;
                    ");

                    while($row=$result->fetch_assoc()){
                        echo "<p>-&nbsp; $row[product_name] - $row[variant_name] has only <b>$row[stock_in_hand]</b> stocks in hand.</p>";
                    }
                    ?>
                </div>
                <br>
                <div class="report_container">
                    <h3><b>Pending Supplies</b></h3><hr>
                    <?php 
                    $result=$conn->query("select purchase_orders.id as order_id, purchase_orders.order_date, purchase_orders.quantity, purchase_orders.status, purchase_orders.purchase_code, suppliers.name as supplier_name, product_variants.variant_name, products.name as product_name from purchase_orders, suppliers, product_variants, products where purchase_orders.supplier_id = suppliers.id and purchase_orders.product_variant_id = product_variants.id and product_variants.product_id = products.id and purchase_orders.status like 'pending' ORDER BY  purchase_orders.order_date DESC");
                    
                    while($row=$result->fetch_assoc()){
                        $date = new DateTime($row["order_date"]);
                        $formatted_date = $date->format('F j, Y H:i:s');
                        echo "<p>-&nbsp; [$formatted_date] $row[purchase_code]: <b>$row[product_name] - $row[variant_name] (Qty: $row[quantity])</b> from $row[supplier_name] 
                        
                        
                        
                        
                        
                        </p>";
                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-6" style="padding: 10px;">
                <div class="report_container">
                    <h3><b>Best Selling Product</b></h3><hr>
                    <?php
                    $result = $conn->query("SELECT 
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
                        total_amount DESC
                    LIMIT 1 ;
                    ");

                    while($row=$result->fetch_assoc()){
                        echo "<p><center>$row[product_name] - $row[variant_name] sold $row[total_quantity] stocks, earning a total of <b>₱ $row[total_amount] </p></center></p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>