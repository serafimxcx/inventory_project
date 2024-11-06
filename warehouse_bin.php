<?php
    include("navbar.php");

    $bin_code="";
    $zone_id="";

    $bin_id="";
    $bin="";

    if(isset($_GET["txt_bin_id"])){
        $conn->query("update warehouse_bins set zone_id='$_GET[slct_zone]', bin_code='$_GET[txt_bin_code]' where id='$_GET[txt_bin_id]'");

        echo "<script> window.location.href='warehouse_bin.php'</script>";
    }elseif(isset($_GET["txt_bin_code"])){
        $conn->query("insert into warehouse_bins(zone_id, bin_code)values('$_GET[slct_zone]', '$_GET[txt_bin_code]')");

        echo "<script> window.location.href='warehouse_bin.php'</script>";
    }elseif(isset($_GET["del_bin_id"])){
        $conn->query("delete from warehouse_bins where id ='$_GET[del_bin_id]'");
        echo "<script> window.location.href='warehouse_bin.php'</script>";
    }elseif(isset($_GET["edit_bin_id"])){
        $result=$conn->query("select * from warehouse_bins where id='$_GET[edit_bin_id]'");

        while($row=$result->fetch_assoc()){
            $bin_code=$row["bin_code"];
            $zone_id=$row["zone_id"];
        }
 
    }


    if(isset($_GET["bin_item_id"])){
        $conn->query("insert into bin_inventory(bin_id, product_variant_id, quantity)values('$_GET[bin_item_id]', '$_GET[slct_prodvariant]', '$_GET[txt_qty]')");

        echo "<script> window.location.href='warehouse_bin.php?bin_id=$_GET[bin_item_id]&bin_item=true'</script>";
    }elseif(isset($_GET["del_itembin_id"])){
        $conn->query("delete from bin_inventory where id ='$_GET[del_itembin_id]'");
        echo "<script> window.location.href='warehouse_bin.php?bin_id=$_GET[bin_id]&bin_item=true'</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Bins</title>
</head>
<body>
<div class="content_container">
        <div class="row">
            <div class="col-lg-12">
            <h2>Manage Warehouse Bin</h2><hr>
                <form action="warehouse_bin.php" method="get">
                    <table width="100%">
                        <tr>
                            <td>Warehouse Zone: <br></td>
                            <td>
                                <select name="slct_zone" id="slct_zone" class="txt_input form-control">
                                    <option value="">Select Warehouse Zone...</option>
                                    <?php
                                        $result = $conn->query("select warehouses.name as warehouse_name, warehouse_zones.id, warehouse_zones.zone_name from warehouses, warehouse_zones where warehouse_zones.warehouse_id = warehouses.id");

                                        while($row=$result->fetch_assoc()){
                                            echo "<option value='$row[id]'";
                                                if($row["id"] == $zone_id){
                                                    echo "selected";
                                                }
                                            echo ">$row[warehouse_name] - $row[zone_name]</option>";
                                        }
                                    
                                    ?>
                                </select><br>
                            </td>
                        </tr>
                        <tr>
                            <td>Bin Name: <br></td>
                            <td><input type="text" name="txt_bin_code" id="txt_bin_code" class="txt_input form-control" value='<?php echo $bin_code?>'> <br></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right">
                            <?php 
                                    if(isset($_GET["edit_bin_id"])){
                                        echo "
                                        <input type='hidden' name='txt_bin_id' value='$_GET[edit_bin_id]'>
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
                </form>
            </div>
        </div>
        <br><hr><br>
        <h5>Click specific bin to assign an item.</h5><br>
        <div class="record_table">
        <table width="100%" class="table table-bordered bin_table">
            <tr>
                <th>Bin Code</th>
                <th>Warehouse Zone</th>
                <th>Warehouse</th>
                <th></th>
                <th></th>
            </tr>
            <?php 
                $result=$conn->query("select warehouses.name as warehouse_name, warehouse_zones.zone_name, warehouse_bins.id, warehouse_bins.bin_code from warehouses, warehouse_bins, warehouse_zones where warehouse_bins.zone_id = warehouse_zones.id and warehouse_zones.warehouse_id = warehouses.id");

                while($row = $result->fetch_assoc()){
                    echo "<tr class='i_bin_row' bin_id='$row[id]'>
                        <td>$row[bin_code]</td>
                        <td>$row[zone_name]</td>
                        <td>$row[warehouse_name]</td>
                        <td><center><button type='button' class='btn btn-warning btn_edit' bin_id='$row[id]'><i class='bi bi-pencil-square'></i></button></center></td>
                        <td><center><button type='button' class='btn btn-danger btn_del' bin_id='$row[id]'><i class='bi bi-trash-fill'></i></button></center></td>

                    </tr>";
                }
            
            ?>
        </table>
        </div>
    </div>

    <div class="modal" id="bin_item_modal" style='<?php
        if(isset($_GET["bin_id"]) && isset($_GET["bin_item"])){
            echo "display: flex";

            $result = $conn->query("select * from warehouse_bins where id='$_GET[bin_id]'");

            while($row=$result->fetch_assoc()){
                $bin_id = $row["id"];
                $bin = $row["bin_code"];
            }
        }else{
            echo "display: none";
        }
    ?>'>
        <div id="bin_item_div">
        <h2><span class="btn_closemodal"><i class="bi bi-x-lg"></i></span> &nbsp;&nbsp;Add Items in Bin</h2><hr>
        <form action="warehouse_bin.php" method="get">
            <input type="hidden" name="bin_item_id" id="bin_item_id"  value='<?php echo $bin_id?>'>
            <table width="100%">
                <tr>
                    <td>Bin Code: <br></td>
                    <td><input type="text" name="txt_bin" id="txt_bin" class="form-control" value='<?php echo $bin?>' readonly><br></td>
                </tr>
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
                    <td colspan="2" style="text-align:right;">
                        <button type='button' class='btn btn-danger btn_clear'> <i class='bi bi-eraser-fill'></i> &nbsp;Clear</button>
                        <button type='submit' class='btn btn-success'><i class='bi bi-floppy-fill'></i> &nbsp;Add</button>

                    </td>
                </tr>
            </table>
        </form>
        <br><hr><br>
        <table width="100%" class="table table-bordered">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th></th>
            </tr>
            <?php 
            $result = $conn->query("select bin_inventory.id, bin_inventory.quantity, product_variants.variant_name, products.name as product_name from products, product_variants, bin_inventory, warehouse_bins where bin_inventory.bin_id = warehouse_bins.id and bin_inventory.product_variant_id = product_variants.id and product_variants.product_id = products.id and bin_inventory.bin_id = '$bin_id'");

            while($row=$result->fetch_assoc()){
                echo "<tr>
                    <td>$row[product_name] - $row[variant_name]</td>
                    <td>$row[quantity]</td>
                    <td><center><button type='button' class='btn btn-danger btn_itembin_del' itembin_id='$row[id]'><i class='bi bi-trash-fill'></i></button></center></td>

                </tr>";
            }
            
            ?>
        </table>
                        
        </div>
    </div>
</body>
<script>
    $(function(){
        $(".btn_closemodal").click(function(){
            window.location.href = 'warehouse_bin.php';
            
        });
        

        $(".btn_clear").click(function(){
            if(confirm("Clear all the fields?")){
                $(".txt_input").val("");
            }
            
        });

        $("#btn_cancel").click(function(){
            window.location.href = 'warehouse_bin.php';
            
        });

        $(".btn_del").click(function(){
            event.stopPropagation();
            var bin_id = $(this).attr("bin_id");
            if(confirm("Are you sure you want to delete this bin?")){
                window.location.href = 'warehouse_bin.php?del_bin_id='+bin_id;
            }
            
        });

        $(".btn_itembin_del").click(function(){
            event.stopPropagation();
            var itembin_id = $(this).attr("itembin_id");
            var bin_id = $("#bin_item_id").val();
            if(confirm("Are you sure you want to delete this item in this bin?")){
                window.location.href = 'warehouse_bin.php?bin_id='+bin_id+'&bin_item=true'+'&del_itembin_id='+itembin_id;
            }
            
        });

        $(".btn_edit").click(function(){
            event.stopPropagation();
            var bin_id = $(this).attr("bin_id");
            if(confirm("Are you sure you want to edit this bin?")){
                window.location.href = 'warehouse_bin.php?edit_bin_id='+bin_id;
            }
            
        });

        $(".i_bin_row").click(function(){
            var bin_id = $(this).attr("bin_id");

            window.location.href='warehouse_bin.php?bin_id='+bin_id+'&bin_item=true';
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