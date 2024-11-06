<?php
    include("navbar.php");

    $product_name="";
    $product_id="";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
</head>
<body>
    <div class="content_container">
        <div class="row">
           
            <div class="col-lg-12">
            <h2>Stocks Report</h2>
            <h5>Click specific products to check variant stocks</h5><br>
            <button type="button" class="btn btn-info" onclick="print1()"><i class="bi bi-printer-fill"></i> &nbsp;Print</button>
                
            <br><hr><br>
                <div class="record_table">
                <table width="100%" class="table table-bordered stocks_table">
                    <tr>
                        <th>Product Code</th>
                        <th>Product Name</th>
                        <th>Stocks In Hand</th>
                        <th>Stocks On Order</th>
                        <th>Stocks Reserved</th>
                    </tr>
                    <?php 
                    $result = $conn->query("SELECT 
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
                        ");

                        while($row=$result->fetch_assoc()){
                            echo "<tr class='i_product_stock' product_id='$row[product_id]'>
                                <td>$row[product_code]</td>
                                <td>$row[product_name]</td>
                                <td>$row[stocks_in_hand]</td>
                                <td>$row[stocks_on_order]</td>
                                <td>$row[stocks_reserved]</td>
                            </tr>";
                        }
                        
                        ?>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="prodvariant_stocks_modal" style='<?php 
        if(isset($_GET["prodvariant_stocks"]) && isset($_GET["product_id"])){
            echo "display:flex";

            $result = $conn->query("select * from products where id='$_GET[product_id]'");

            while($row=$result->fetch_assoc()){
                $product_id = $row["id"];
                $product_name = $row["name"];
            }
        }else{
            echo "display: none";
        }
                    
    ?>'>
        <div id="prodvariant_stocks_div">
        <h2><span class="btn_closemodal"><i class="bi bi-x-lg"></i></span> &nbsp;&nbsp;Product Variant Stocks</h2><br>
        <input type="hidden" name="product_id" id="product_id" value='<?php echo $product_id;?>'>
        <table>
            <tr>
                <td>Product: &nbsp;&nbsp;</td>
                <td><input type="text" name="txt_prod_name" id="txt_prod_name" class="form-control" value='<?php echo $product_name;?>' readonly></td>
                <td><button type="button" class="btn btn-info" style="margin-left: 5px;" onclick="print2()"><i class="bi bi-printer-fill"></i> &nbsp;Print</button></td>
            </tr>
        </table>
        <br><hr><br>
        <div class="record_table">
        <table width="100%" class="table table-bordered">
            <tr>
                <th>Variant Name</th>
                <th>Stocks In Hand</th>
                <th>Stocks On Order</th>
                <th>Stocks Reserved</th>
                <th></th>
            </tr>
            <?php 
                $result=$conn->query("SELECT
                pv.id AS prodvariant_id,
                pv.variant_name AS variant_name,
                                IFNULL(stocks_in_hand.stocks_in_hand, 0) AS stocks_in_hand,
                                IFNULL(stocks_on_order.stocks_on_order, 0) AS stocks_on_order,
                                IFNULL(stocks_reserved.stocks_reserved, 0) AS stocks_reserved
                                FROM
                                    product_variants pv
                                JOIN
                                    products p ON pv.product_id = p.id
                                LEFT JOIN 
                                    (
                                        SELECT 
                                            pv.id AS product_variant_id,
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
                                            pv.id
                                    ) AS stocks_in_hand ON pv.id = stocks_in_hand.product_variant_id
                                LEFT JOIN 
                                    (
                                        SELECT 
                                            pv.id AS product_variant_id,
                                            SUM(po.quantity) AS stocks_on_order
                                        FROM
                                            product_variants pv
                                        LEFT JOIN 
                                            purchase_orders po ON pv.id = po.product_variant_id AND po.status = 'pending'
                                        GROUP BY 
                                            pv.id
                                    ) AS stocks_on_order ON pv.id = stocks_on_order.product_variant_id
                                LEFT JOIN 
                                    (
                                        SELECT 
                                            pv.id AS product_variant_id,
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
                                            pv.id
                                    ) AS stocks_reserved ON pv.id = stocks_reserved.product_variant_id
                                WHERE
                                    p.id = '$product_id'
                                GROUP BY 
                                    pv.id;
                ");

                while($row=$result->fetch_assoc()){
                    echo "<tr>
                        <td>$row[variant_name]</td>
                        <td>$row[stocks_in_hand]</td>
                        <td>$row[stocks_on_order]</td>
                        <td>$row[stocks_reserved]</td>
                        <td><center><input type='button' class='btn btn-warning btn_history' value='History' prodvariant_name='$row[variant_name]' prodvariant_id='$row[prodvariant_id]'></center></td>
                    </tr>";
                }
            
            ?>
        </table>
        </div>
        </div>
    </div>
</body>
<script>
    $(function(){
        $(".btn_closemodal").click(function(){
            window.location.href = 'reports.php';
            
        });

        $(".i_product_stock").click(function(){
            var product_id = $(this).attr("product_id");
            window.location.href="reports.php?product_id="+product_id+"&prodvariant_stocks=true";
        });

        $(".btn_history").click(function(){
            var prodvariant_id = $(this).attr("prodvariant_id");
            var prodvariant_name = $(this).attr("prodvariant_name");
            print3(prodvariant_id, prodvariant_name);
        });

    });

    function print1(){
        var cFile = "print_prodstocks.php";
        window.open(cFile, "_blank");
    }

    function print2(){
        var product_id = $("#product_id").val();
        var product_name = $("#txt_prod_name").val();
        var cFile = "print_variantstocks.php?product_id="+product_id+"&product_name="+product_name;
        window.open(cFile, "_blank");
    }

    function print3(prodvariant_id, prodvariant_name){
        var cFile = "print_history.php?prodvariant_id="+prodvariant_id+"&prodvariant_name="+prodvariant_name;
        window.open(cFile, "_blank");
    }

</script>
</html>