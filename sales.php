<?php 
    include("navbar.php");

    if(isset($_GET["txt_order_no"])){
        $conn->query("insert into sales_orders(order_no, customer_name, order_date, product_variant_id, quantity, status)values('$_GET[txt_order_no]', '$_GET[txt_customer]', '".date("Y/m/d H:i:s",strtotime($_REQUEST["txt_date"]))."', '$_GET[slct_prodvariant]', '$_GET[txt_qty]', 'pending')");

        echo "<script> window.location.href='sales.php'</script>";
    }elseif(isset($_GET["del_sales_id"])){
        $conn->query("delete from sales_orders where id = '$_GET[del_sales_id]'");

        echo "<script> window.location.href='sales.php'</script>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales</title>
</head>
<body>
    <div class="content_container">
        <h2>Manage Sales</h2>
        <h4><a href="customerreturn.php"><i class="bi bi-cart-x-fill"></i> Check Return from Customers</a></h4>
        <hr>
        <div class="row">
        <form action="sales.php" method="get">
            <div class="col-lg-6">
            
                <table width="100%">
                    <tr>
                        <td>Date: <br></td>
                        <td><input type="datetime-local" name="txt_date" id="txt_date" class="txt_input form-control"><br></td>
                    </tr>
                    <tr>
                        <td>Order No: <br></td>
                        <td><input type="text" name="txt_order_no" id="txt_order_no" class="txt_input form-control"><br></td>
                    </tr>
                    <tr>
                        <td>Customer: <br></td>
                        <td><input type="text" name="txt_customer" id="txt_customer" class="txt_input form-control"><br></td>
                    </tr>
                   
                </table>
            </div>
            <div class="col-lg-6">
            <table width="100%">
                    <tr>
                        <td>Select Product: <br></td>
                        <td><select name="slct_product" id="slct_product" class="form-control">
                            <option value="">Select a product...</option>
                            <?php 
                                $result = $conn->query("select * from products");

                                while($row=$result->fetch_assoc()){
                                    echo "<option value='$row[id]'>$row[name]</option>";
                                }
                            
                            ?>
                        </select><br></td>
                    </tr>
                    <tr>
                        <td>Select Variant: <br> </td>
                        <td><select name="slct_prodvariant" id="slct_prodvariant" class="txt_input form-control">
                            <option value="">Select a product first...</option>
                        </select><br></td>
                        
                    </tr>
                    <tr>
                        <td>Quantity: <br></td>
                        <td><input type="number" name="txt_qty" id="txt_qty" class="txt_input form-control"><br></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right;">
                            <button type='button' class='btn btn-danger btn_clear'> <i class='bi bi-eraser-fill'></i> &nbsp;Clear</button>
                            <button type='submit' class='btn btn-success'><i class='bi bi-floppy-fill'></i> &nbsp;Add</button>
                        </td>
                    </tr>
                </table>
            </div>
        </form>
        </div>
        <br><hr><br>
        <div class="record_table">
        <table width="100%" class="table table-bordered">
            <tr>
                <th>Date</th>
                <th>Order No.</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Price Per Unit</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th>Status</th>
                <th></th>
            </tr>
            <?php
                $result = $conn->query("select sales_orders.id as sales_id, sales_orders.order_date, sales_orders.order_no, sales_orders.customer_name, sales_orders.quantity, sales_orders.status, products.name as product_name, product_variants.variant_name, product_variants.price from sales_orders, product_variants, products where sales_orders.product_variant_id = product_variants.id and product_variants.product_id = products.id");

                while($row = $result->fetch_assoc()){
                    echo "<tr>
                        <td>$row[order_date]</td>
                        <td>$row[order_no]</td>
                        <td>$row[customer_name]</td>
                        <td>$row[product_name] - $row[variant_name]</td>
                        <td>₱ $row[price]</td>
                        <td>$row[quantity]</td>
                        <td>₱ ".$row["price"]*$row["quantity"]."</td>
                        <td><select name='slct_status' id='slct_status$row[sales_id]' class='slct_status form-control' sales_id='$row[sales_id]'";
                        if($row["status"] == "delivered" || $row["status"] == "cancelled"){
                            echo "disabled";
                        }
                        echo">
                            <option value='pending'";
                                if($row["status"] == "pending"){
                                    echo "selected";
                                }
                            echo ">Pending</option>
                            <option value='shipped'";
                                if($row["status"] == "shipped"){
                                    echo "selected";
                                }
                            echo ">Shipped</option>
                            <option value='delivered'";
                            if($row["status"] == "delivered"){
                                echo "selected";
                            }
                             echo ">Delivered</option>
                            <option value='cancelled'";
                            if($row["status"] == "cancelled"){
                                echo "selected ";
                            }
                            echo ">Cancelled</option>
                        </select></td>
                        <td><center><button type='button' class='btn btn-danger btn_sales_del' sales_id='$row[sales_id]'><i class='bi bi-trash-fill'></i></button></center></td>

                    </tr>";
                }
            
            ?>
        </table>
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

        $("#slct_product").change(function(){
            if($("#slct_product").val() != ""){
                var cParam = "product_id="+$("#slct_product").val();

                $.ajax({
                    "type": "GET",
                    "url": "load_availprods.php",
                    "data": cParam,
                    "success": function(text){
                        $("#slct_prodvariant").empty();
                        $("#slct_prodvariant").append(text);


                    }
                });
            }else{
                $("#slct_prodvariant").empty();
                $("#slct_prodvariant").append("<option value=''>Select a product first...</option>");
            }
            
        });

        $(".slct_status").change(function(){
            var sales_id = $(this).attr("sales_id");
            var status = $(this).val();
            if(status == "delivered"){
                if(confirm("Are you sure the order is delivered?")){
                    updateStatus(sales_id, status);
                }
            }else if(status == "cancelled"){
                if(confirm("Are you sure you want to cancel this order?")){
                    updateStatus(sales_id, status);
                }
            }else if(status == "shipped"){
                if(confirm("Are you sure you want to ship this order?")){
                    updateStatus(sales_id, status);
                }
            }
        });

        $(".btn_sales_del").click(function(){
            var sales_id = $(this).attr("sales_id");
            if(confirm("Are you sure you want to delete this order?")){
                window.location.href = 'sales.php?del_sales_id='+sales_id;
            }
            
            
        });
    });

    function updateStatus(sales_id, status){
        var cParam = "";
        cParam = "sales_id="+sales_id;
        cParam += "&status="+status;

        $.ajax({
            "type": "GET",
            "url": "update_salesstatus.php",
            "data": cParam,
            "success": function(text){
                location.reload();
            }
        });
    }
</script>
</html>