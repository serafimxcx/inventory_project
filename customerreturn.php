<?php 
    include("navbar.php");

    if(isset($_GET["txt_return_no"])){
        $conn->query("insert into customer_returns(return_no, return_date, sales_order_id, reason, quantity)values('$_GET[txt_return_no]', '".date("Y/m/d H:i:s",strtotime($_REQUEST["txt_date"]))."', '$_GET[slct_order_no]', '".mysqli_real_escape_string($conn, $_GET["txt_reason"])."', $_GET[txt_qty])");

        echo "<script> window.location.href='customerreturn.php'</script>";
    }elseif(isset($_GET["del_return_id"])){
        $conn->query("delete from customer_returns where id = '$_GET[del_return_id]'");

        echo "<script> window.location.href='customerreturn.php'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return from Customers</title>
</head>
<body>
    <div class="content_container">
        <h2>Return from Customers</h2>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <form action="customerreturn.php" method="get">
                <table width="100%">
                    <tr>
                        <td>Date: <br></td>
                        <td><input type="datetime-local" name="txt_date" id="txt_date" class="txt_input form-control"><br></td>
                    </tr>
                    <tr>
                        <td>Return No: <br></td>
                        <td><input type="text" name="txt_return_no" id="txt_return_no" class="txt_input form-control"><br></td>
                    </tr>
                    <tr>
                        <td>Order: <br></td>
                        <td><select name="slct_order_no" id="slct_order_no" class="txt_input form-control">
                            <option value="">Select Order...</option>
                            <?php 
                                $result = $conn->query("select sales_orders.id as sales_id, sales_orders.order_no, sales_orders.quantity, products.name as product_name, product_variants.variant_name from sales_orders, products, product_variants where sales_orders.product_variant_id = product_variants.id and product_variants.product_id = products.id and sales_orders.status = 'delivered'");

                                while($row=$result->fetch_assoc()){
                                    echo "<option value='$row[sales_id]'>$row[order_no]: $row[product_name] - $row[variant_name] (qty: $row[quantity])</option>";
                                }
                            
                            ?>
                        </select><br></td>
                    </tr>
                    <tr>
                        <td>Quantity: <br></td>
                        <td><input type="number" name="txt_qty" id="txt_qty" class="txt_input form-control"><br></td>
                    </tr>
                    <tr>
                        <td>Reason: <br></td>
                        <td><textarea name="txt_reason" id="txt_reason" class="txt_input form-control"></textarea><br></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <a href="sales.php"><button type='button' class='btn btn-primary'> <i class='bi bi-arrow-return-left'></i> &nbsp;Back</button></a>
                    
                            <button type='button' class='btn btn-danger btn_clear'> <i class='bi bi-eraser-fill'></i> &nbsp;Clear</button>
                            <button type='submit' class='btn btn-success'><i class='bi bi-floppy-fill'></i> &nbsp;Add</button>
                        </td>
                    </tr>
                </table>
                </form>
                <br><br>
            </div>

            <div class="col-lg-6">
                <div class="record_table">
                <table class="table table-bordered">
                    <tr>
                        <th>Return No.</th>
                        <th>Customer</th>
                        <th>Order</th>
                        <th>Reason</th>
                        <th>Quantity</th>
                        <th></th>
                    </tr>
                    <?php 
                    $result = $conn->query("select customer_returns.id as return_id, customer_returns.return_no, customer_returns.reason, customer_returns.quantity as return_quantity, sales_orders.order_no, sales_orders.quantity as sales_quantity, sales_orders.customer_name, products.name as product_name, product_variants.variant_name from sales_orders, products, product_variants, customer_returns where sales_orders.product_variant_id = product_variants.id and product_variants.product_id = products.id and customer_returns.sales_order_id = sales_orders.id");

                    while($row = $result->fetch_assoc()){
                        echo "<tr>
                            <td>$row[return_no]</td>
                            <td>$row[customer_name]</td>
                            <td>$row[order_no]: $row[product_name] - $row[variant_name] (qty: $row[sales_quantity])</td>
                            <td>$row[reason]</td>
                            <td>$row[return_quantity]</td>
                            <td><center><input type='button' class='btn btn-danger btn_return_del' return_id='$row[return_id]' value='X'></center></td>
                        </tr>";
                    }
                    
                    ?>
                </table>   
                </div> 
            </div>
        </div>
    </div>
</body>
<script>
    $(function(){
        $(".btn_clear").click(function(){
            if(confirm("Clear all the fields?")){
                $(".txt_input").val("");
            }
            
        });

        $(".btn_return_del").click(function(){
            var return_id = $(this).attr("return_id");
            if(confirm("Are you sure you want to delete this order?")){
                window.location.href = 'customerreturn.php?del_return_id='+return_id;
            }
            
            
        });
    });
</script>
</html>