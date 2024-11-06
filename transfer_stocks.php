<?php 
    include("navbar.php");

    if(isset($_GET["txt_transfer_no"])){
        $conn->query("insert into warehouse_transfers(transfer_no, product_variant_id, from_warehouse, to_warehouse, transfer_date, quantity)values('$_GET[txt_transfer_no]', '$_GET[slct_prodvariant]', '$_GET[slct_from]', '$_GET[slct_to]', '".date("Y/m/d H:i:s",strtotime($_REQUEST["txt_date"]))."', '$_GET[txt_qty]')");

        echo "<script> window.location.href='transfer_stocks.php'</script>";
    }elseif(isset($_GET["del_transfer_id"])){
        $conn->query("delete from warehouse_transfers where id ='$_GET[del_transfer_id]'");
        echo "<script> window.location.href='transfer_stocks.php'</script>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer Stocks</title>
</head>
<body>
    <div class="content_container">
        <div class="row">
            <div class="col-lg-12">
            <h2>Transfer Stocks</h2><hr>
                <form action="" method="get">
                    <div class="row">
                        <div class="col-lg-6">
                            <table width="100%">
                                <tr>
                                    <td>Date: <br></td>
                                    <td><input type="datetime-local" name="txt_date" id="txt_date" class="txt_input form-control"><br></td>
                                </tr>
                                <tr>
                                    <td>Transfer No: <br></td>
                                    <td><input type="text" name="txt_transfer_no" id="txt_transfer_no" class="txt_input form-control"><br></td>
                                </tr>
                                <tr>
                                    <td>Select Product: <br></td>
                                    <td><select name="slct_product" id="slct_product" class="txt_input form-control">
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
                                     
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <table width="100%">
                                
                                <tr>
                                    <td>Quantity: <br></td>
                                    <td><input type="number" name="txt_qty" id="txt_qty" class="txt_input form-control"><br></td>
                                </tr>
                                <tr>
                                    <td>From: <br></td>
                                    <td><select name="slct_from" id="slct_from" class="txt_input form-control">
                                        <option value="">Select Warehouse...</option>
                                        <?php
                                            $result = $conn->query("select * from warehouses");

                                            while($row=$result->fetch_assoc()){
                                                echo "<option value='$row[id]'>$row[name]</option>";
                                            }
                                        ?>
                                    </select><br></td>
                                </tr>
                                <tr>
                                    <td>To: <br></td>
                                    <td><select name="slct_to" id="slct_to" class="txt_input form-control">
                                        <option value="">Select Warehouse...</option>
                                        <?php
                                            $result = $conn->query("select * from warehouses");

                                            while($row=$result->fetch_assoc()){
                                                echo "<option value='$row[id]'>$row[name]</option>";
                                            }
                                        ?>
                                    </select><br></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align:right;">
                                        <button type='button' class='btn btn-danger btn_clear'> <i class='bi bi-eraser-fill'></i> &nbsp;Clear</button>
                                        <button type='submit' class='btn btn-success'><i class='bi bi-floppy-fill'></i> &nbsp;Add</button>
                                    </td>
                                </tr>
                                
                            </table>
                            
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
        <br><hr><br>
        <div class="record_table">
        <table class="table table-bordered" width="100%">
            <tr>
                <th>Date</th>
                <th>Transfer No.</th>
                <th>From</th>
                <th>To</th>
                <th>Product</th>
                <th>Quantity</th>
                <th></th>
            </tr>
            <?php 
                $result = $conn->query("select warehouse_transfers.id , warehouse_transfers.transfer_date, warehouse_transfers.quantity, warehouse_transfers.transfer_no, from_warehouse.name as from_warehouse_name, to_warehouse.name as to_warehouse_name, product_variants.variant_name, products.name as product_name from warehouse_transfers, warehouses as from_warehouse, warehouses as to_warehouse, product_variants, products where warehouse_transfers.from_warehouse = from_warehouse.id and warehouse_transfers.to_warehouse = to_warehouse.id and warehouse_transfers.product_variant_id = product_variants.id and product_variants.product_id = products.id");
                                    
                while($row = $result->fetch_assoc()){
                    echo "<tr>
                        <td>$row[transfer_date]</td>
                        <td>$row[transfer_no]</td>
                        <td>$row[from_warehouse_name]</td>
                        <td>$row[to_warehouse_name]</td>
                        <td>$row[product_name] - $row[variant_name]</td>
                        <td>$row[quantity]</td>
                        <td><center><button type='button' class='btn btn-danger btn_del' transfer_id='$row[id]'><i class='bi bi-trash-fill'></i></button></center></td>
                    </tr>";
                }
            ?>
        </table>
        </div>
        <br><br>
    </div>
</body>
<script>
    $(function(){
        $(".btn_del").click(function(){
            var transfer_id = $(this).attr("transfer_id");
            if(confirm("Are you sure you want to delete this transfer?")){
                window.location.href = 'transfer_stocks.php?del_transfer_id='+transfer_id;
            }
            
        });

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
    });
</script>
</html>