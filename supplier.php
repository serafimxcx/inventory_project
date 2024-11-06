<?php 
    include("navbar.php");

    $name = "";
    $contact="";
    $prod_offer="";

    $supplier_id="";
    $supplier_name="";

    // suppliers
    if(isset($_GET["txt_supplier_id"])){
        $conn->query("update suppliers set name='$_GET[txt_name]', contact_no='$_GET[txt_contact]', product_offerings='".mysqli_real_escape_string($conn, $_GET["txt_prod_offer"])."' where id='$_GET[txt_supplier_id]'");

        echo "<script> window.location.href='supplier.php'</script>";
    }elseif(isset($_GET["txt_name"])){
        $conn->query("insert into suppliers(name, contact_no, product_offerings)values('$_GET[txt_name]', '$_GET[txt_contact]', '".mysqli_real_escape_string($conn, $_GET["txt_prod_offer"])."')");
        
        echo "<script> window.location.href='supplier.php'</script>";
    }elseif(isset($_GET["del_supplier_id"])){
        $conn->query("delete from suppliers where id='$_GET[del_supplier_id]'");
        echo "<script> window.location.href='supplier.php'</script>";
    }elseif(isset($_GET["edit_supplier_id"])){
        $result = $conn->query("select * from suppliers where id='$_GET[edit_supplier_id]'");

        while($row=$result->fetch_assoc()){
            $name=$row["name"];
            $contact=$row["contact_no"];
            $prod_offer=$row["product_offerings"];
        }
    }

    //order products
    if(isset($_GET["txt_purchase_code"])){
        $conn->query("insert into purchase_orders(purchase_code, supplier_id, order_date, product_variant_id, quantity, status)values('$_GET[txt_purchase_code]', '$_GET[supplier_id]', '".date("Y/m/d H:i:s",strtotime($_REQUEST["txt_date"]))."', '$_GET[slct_prodvariant]', '$_GET[txt_qty]', 'pending')");

        echo "<script> window.location.href='supplier.php?supplier_id=$_GET[supplier_id]&order_item=true'</script>";
    }elseif(isset($_GET["del_order_id"])){
        $conn->query("delete from purchase_orders where id='$_GET[del_order_id]'");
        echo "<script> window.location.href='supplier.php?supplier_id=$_GET[supplier_id]&order_item=true'</script>";
    }

    //return products
    if(isset($_GET["txt_return_no"])){
        $conn->query("insert into return_purchase(return_no, supplier_id, return_date, product_variant_id, quantity, reason)values('$_GET[txt_return_no]', '$_GET[supplier_id]', '".date("Y/m/d H:i:s",strtotime($_REQUEST["txt_date"]))."', '$_GET[slct_prodvariant]', '$_GET[txt_qty]', '".mysqli_real_escape_string($conn, $_GET["txt_reason"])."')");

        echo "<script> window.location.href='supplier.php?supplier_id=$_GET[supplier_id]&return_item=true'</script>";
    }elseif(isset($_GET["del_return_id"])){
        $conn->query("delete from return_purchase where id='$_GET[del_return_id]'");
        echo "<script> window.location.href='supplier.php?supplier_id=$_GET[supplier_id]&return_item=true'</script>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers</title>
</head>
<body>
    <div class="content_container">
        <h2>Manage Suppliers</h2>
        <hr>
        
        <form action="" method="get">
            <div class="row">
                <div class="col-lg-6">
                    <table width="100%">
                        <tr>
                            <td>Name: <br></td>
                            <td><input type="text" name="txt_name" id="txt_name" class="txt_input form-control" value='<?php echo $name;?>' required><br></td>
                        </tr>
                        <tr>
                            <td>Contact No: <br></td>
                            <td><input type="text" name="txt_contact" id="txt_contact" class="txt_input form-control" value='<?php echo $contact;?>' required><br></td>
                        </tr>
                    </table>
                </div>
                <div class="col-lg-6">
                    <table width="100%">
                        <tr>
                            <td>Product Offerings: <br></td>
                            <td><textarea name="txt_prod_offer" id="txt_prod_offer" class="txt_input form-control"><?php echo $prod_offer;?></textarea><br></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right;">
                                <?php 
                                    if(isset($_GET["edit_supplier_id"])){
                                        echo "
                                        <input type='hidden' name='txt_supplier_id' value='$_GET[edit_supplier_id]'>
                                        <button type='button' class='btn btn-danger' id='btn_cancel'><i class='bi bi-x-lg'></i> &nbsp;Cancel</button>
                                        <button type='submit' class='btn btn-success'><i class='bi bi-floppy-fill'></i> &nbsp;Update</button>
                                        ";
                                    }else{
                                        echo "
                                        <button type='button' class='btn btn-danger btn_clear'> <i class='bi bi-eraser-fill'></i> &nbsp;Clear</button>
                                        <button type='submit' class='btn btn-success'><i class='bi bi-floppy-fill'></i> &nbsp;Add</button>
                                        ";
                                    }
                                
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div> 
        </form>
        <hr><br>
        <div class="record_table">
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <th>Contact No.</th>
                <th>Product Offerings</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <?php 
                $result = $conn->query("select * from suppliers");

                while($row = $result->fetch_assoc()){
                    echo "<tr>
                        <td>$row[name]</td>
                        <td>$row[contact_no]</td>
                        <td>$row[product_offerings]</td>
                        <td><center><input type='button' class='btn btn-primary btn_order' supplier_id='$row[id]' value='Order'></center></td>
                        <td><center><input type='button' class='btn btn-primary btn_return' supplier_id='$row[id]' value='Return'></center></td>
                        <td><center><button type='button' class='btn btn-warning btn_edit' supplier_id='$row[id]'><i class='bi bi-pencil-square'></i></button></center></td>
                        <td><center><button type='button' class='btn btn-danger btn_del' supplier_id='$row[id]'><i class='bi bi-trash-fill'></i></button></center></td>


                    </tr>";
                }
            
            ?>
        </table>
        </div>
            
     
    </div>
    
    <!--order products-->
    <div class="modal" id="order_item_modal" style='<?php
        if(isset($_GET["order_item"]) && isset($_GET["supplier_id"]) ){
            echo "display: flex";

            $result = $conn->query("select * from suppliers where id='$_GET[supplier_id]'");

            while($row = $result->fetch_assoc()){
                $supplier_id=$row["id"];
                $supplier_name=$row["name"];

            }
        }else{
            echo "display: none";
        }
    
    ?>'>
        <div id="order_item_div">
        <h2><span class="btn_closemodal"><i class="bi bi-x-lg"></i></span> &nbsp;&nbsp;Order Products</h2><hr>
        <form action="supplier.php" method="get">
            <input type="hidden" name="supplier_id" id="supplier_id" value='<?php echo $supplier_id; ?>'>
            <div class="row">
                <div class="col-lg-6">
                    <table width="100%">
                        <tr>
                            <td>Supplier: <br></td>
                            <td><input type="text" name="txt_supplier_name" id="txt_supplier_name" class="form-control" value='<?php echo $supplier_name; ?>' readonly><br></td>
                        </tr>
                        <tr>
                            <td>Purchase Code: <br></td>
                            <td><input type="text" name="txt_purchase_code" id="txt_purchase_code" class="txt_input form-control" required><br></td>
                        </tr>
                        <tr>
                            <td>Date: <br></td>
                            <td><input type="datetime-local" name="txt_date" id="txt_date" class="txt_input form-control" required><br></td>
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
                                <button type='submit' class='btn btn-success'><i class="bi bi-cart-fill"></i> &nbsp;Order Now</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
        </form>
        <br><hr><br>
        <div class="record_table">
        <table width="100%" class="table table-bordered">
            <tr>
                <th>Date</th>
                <th>Purchase Code</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Status</th>
                <th></th>
            </tr>
            <?php 
                $result = $conn->query("select purchase_orders.id as order_id, purchase_orders.order_date, purchase_orders.quantity, purchase_orders.status, purchase_orders.purchase_code, suppliers.name as supplier_name, product_variants.variant_name, products.name as product_name from purchase_orders, suppliers, product_variants, products where purchase_orders.supplier_id = suppliers.id and purchase_orders.product_variant_id = product_variants.id and product_variants.product_id = products.id and purchase_orders.supplier_id = '$supplier_id'");

                while($row=$result->fetch_assoc()){
                    echo "<tr>
                        <td>$row[order_date]</td>
                        <td>$row[purchase_code]</td>
                        <td>$row[product_name] - $row[variant_name]</td>
                        <td>$row[quantity]</td>
                        <td><select name='slct_status' id='slct_status$row[order_id]' class='slct_status form-control' order_id='$row[order_id]'";
                        if($row["status"] == "completed" || $row["status"] == "cancelled"){
                            echo "disabled";
                        }
                        echo">
                            <option value='pending'";
                                if($row["status"] == "pending"){
                                    echo "selected";
                                }
                            echo ">Pending</option>
                            <option value='completed'";
                            if($row["status"] == "completed"){
                                echo "selected";
                            }
                             echo ">Completed</option>
                            <option value='cancelled'";
                            if($row["status"] == "cancelled"){
                                echo "selected ";
                            }
                            echo ">Cancelled</option>
                        </select></td>
                        <td><center><button type='button' class='btn btn-danger btn_order_del' order_id='$row[order_id]'><i class='bi bi-trash-fill'></i></button></center></td>

                    </tr>";
                }
            
            ?>
        </table>
        </div>
        </div>
    </div>
    
    <!--return products/return to seller-->
    <div class="modal" id="return_item_modal" style='<?php
        if(isset($_GET["return_item"]) && isset($_GET["supplier_id"]) ){
            echo "display: flex";

            $result = $conn->query("select * from suppliers where id='$_GET[supplier_id]'");

            while($row = $result->fetch_assoc()){
                $supplier_id=$row["id"];
                $supplier_name=$row["name"];

            }
        }else{
            echo "display: none";
        }
    
    ?>'>
        <div id="return_item_div">
        <h2><span class="btn_closemodal"><i class="bi bi-x-lg"></i></span> &nbsp;&nbsp;Return Products</h2><hr>
        <form action="supplier.php" method="get">
            <input type="hidden" name="supplier_id" id="supplier_id2" value='<?php echo $supplier_id; ?>'>
            <div class="row">
                <div class="col-lg-6">
                    <table width="100%">
                        <tr>
                            <td>Supplier: <br></td>
                            <td><input type="text" name="txt_supplier_name" id="txt_supplier_name" class="form-control" value='<?php echo $supplier_name; ?>' readonly><br></td>
                        </tr>
                        <tr>
                            <td>Date: <br></td>
                            <td><input type="datetime-local" name="txt_date" id="txt_date" class="txt_input form-control" required><br></td>
                        </tr>
                        <tr>
                            <td>Return No: <br></td>
                            <td><input type="text" name="txt_return_no" id="txt_return_no" class="txt_input form-control" required><br></td>
                        </tr>
                        
                    </table>
                </div>
                <div class="col-lg-6">
                    <table width="100%">
                        <tr>
                            <td>Product: <br></td>
                            <td><select name="slct_prodvariant" class="txt_input form-control" >
                                <option value="">Select product to return...</option>
                                <?php 
                                    $result = $conn->query("select purchase_orders.product_variant_id, product_variants.variant_name, products.name as product_name from purchase_orders, suppliers, product_variants, products where purchase_orders.supplier_id = suppliers.id and purchase_orders.product_variant_id = product_variants.id and product_variants.product_id = products.id and purchase_orders.supplier_id = '$supplier_id'");

                                    while($row = $result->fetch_assoc()){
                                        echo "<option value='$row[product_variant_id]'>$row[product_name] - $row[variant_name]</option>";
                                    }
                                ?>
                            </select><br></td>
                        </tr>
                        <tr>
                            <td>Reason: <br></td>
                            <td><textarea name="txt_reason" id="txt_reason" class="txt_input form-control"></textarea><br></td>
                        </tr>
                        <tr>
                            <td>Quantity: <br></td>
                            <td><input type="number" name="txt_qty" id="txt_qty" class="txt_input form-control"><br></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right">
                            <button type='button' class='btn btn-danger btn_clear'> <i class='bi bi-eraser-fill'></i> &nbsp;Clear</button>
                            <button type='submit' class='btn btn-success'><i class="bi bi-cart-x-fill"></i> &nbsp;Return Now</button>
                            </td>
                        </tr>
                    </table>
                    
                </div>
            </div>
        </form>
        <br><hr><br>
        <div class="record_table">
        <table width="100%" class="table table-bordered">
            <tr>
                <th>Date</th>
                <th>Return No.</th>
                <th>Product</th>
                <th>Reason</th>
                <th>Quantity</th>
                <th></th>
            </tr>

            <?php
                $result = $conn->query("select return_purchase.id as return_id, return_purchase.return_date, return_purchase.quantity, return_purchase.reason, return_purchase.return_no, suppliers.name as supplier_name, product_variants.variant_name, products.name as product_name from return_purchase, suppliers, product_variants, products where return_purchase.supplier_id = suppliers.id and return_purchase.product_variant_id = product_variants.id and product_variants.product_id = products.id and return_purchase.supplier_id = '$supplier_id'");

                while($row = $result->fetch_assoc()){
                    echo "<tr>
                        <td>$row[return_date]</td>
                        <td>$row[return_no]</td>
                        <td>$row[product_name] - $row[variant_name]</td>
                        <td>$row[reason]</td>
                        <td>$row[quantity]</td>
                        <td><center><button type='button' class='btn btn-danger btn_return_del' return_id='$row[return_id]'><i class='bi bi-trash-fill'></i></button></center></td>

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
        $(".btn_clear").click(function(){
            if(confirm("Clear all the fields?")){
                $(".txt_input").val("");
            }
            
        });

        $(".btn_del").click(function(){
            var supplier_id = $(this).attr("supplier_id");
            if(confirm("Are you sure you want to delete this supplier?")){
                window.location.href = 'supplier.php?del_supplier_id='+supplier_id;
            }
            
        });

        $(".btn_edit").click(function(){
            var supplier_id = $(this).attr("supplier_id");
            if(confirm("Are you sure you want to edit this supplier?")){
                window.location.href = 'supplier.php?edit_supplier_id='+supplier_id;
            }
            
        });

        $("#btn_cancel").click(function(){
            window.location.href = 'supplier.php';
            
        });

        $(".btn_order").click(function(){
            var supplier_id = $(this).attr("supplier_id");
            window.location.href = 'supplier.php?supplier_id='+supplier_id+"&order_item=true";
            
        });

        $(".btn_return").click(function(){
            var supplier_id = $(this).attr("supplier_id");
            window.location.href = 'supplier.php?supplier_id='+supplier_id+"&return_item=true";
            
        });

        $(".btn_closemodal").click(function(){
            window.location.href = 'supplier.php';
            
        });

        $(".btn_order_del").click(function(){
            var order_id = $(this).attr("order_id");
            var supplier_id = $("#supplier_id").val();
            if(confirm("Are you sure you want to delete this order?")){
                window.location.href = 'supplier.php?supplier_id='+supplier_id+"&del_order_id="+order_id;
            }
            
            
        });

        $(".btn_return_del").click(function(){
            var return_id = $(this).attr("return_id");
            var supplier_id = $("#supplier_id2").val();
            if(confirm("Are you sure you want to delete this returned product?")){
                window.location.href = 'supplier.php?supplier_id='+supplier_id+"&del_return_id="+return_id;
            }
            
            
        });


        $("#slct_product").change(function(){
            if($("#slct_product").val() != ""){
                var cParam = "product_id="+$("#slct_product").val();

                $.ajax({
                    "type": "GET",
                    "url": "load_variant.php",
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
            var order_id = $(this).attr("order_id");
            var status =$(this).val();
            if(status == "completed"){
                if(confirm("Are you sure the process is complete?")){
                    updateStatus(order_id, status);
                }
            }else if(status == "cancelled"){
                if(confirm("Are you sure you want to cancel this order?")){
                    updateStatus(order_id, status);
                }
            }
        });
    });

    function updateStatus(order_id, status){
        var cParam = "";
        cParam = "order_id="+order_id;
        cParam += "&status="+status;

        $.ajax({
            "type": "GET",
            "url": "update_orderstatus.php",
            "data": cParam,
            "success": function(text){
                location.reload();
            }
        });
    }
</script>
</html>