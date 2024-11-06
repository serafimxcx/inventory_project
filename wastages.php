<?php
    include("navbar.php");

    if(isset($_GET["txt_wastage_no"])){
        $conn->query("insert into wastages(wastage_date, wastage_no, product_variant_id, quantity, reason)values('".date("Y/m/d H:i:s",strtotime($_REQUEST["txt_date"]))."','$_GET[txt_wastage_no]', '$_GET[slct_prodvariant]', '$_GET[txt_qty]', '".mysqli_real_escape_string($conn, $_GET["txt_reason"])."')");

        echo "<script> window.location.href='wastages.php'</script>";
    }elseif(isset($_GET["del_wastage_id"])){
        $conn->query("delete from wastages where id='$_GET[del_wastage_id]'");

        echo "<script> window.location.href='wastages.php'</script>";

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wastages</title>
</head>
<body>
    <div class="content_container">
        <h2>Wastages</h2>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <form action="wastages.php" method="get">
                    <table width="100%">
                        <tr>
                            <td>Date: <br></td>
                            <td><input type="datetime-local" name="txt_date" id="txt_date" class="txt_input form-control"><br></td>
                        </tr>
                        <tr>
                            <td>Wastage No: <br></td>
                            <td><input type="text" name="txt_wastage_no" id="txt_wastage_no" class="txt_input form-control"><br></td>
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
                                <button type='button' class='btn btn-danger btn_clear'> <i class='bi bi-eraser-fill'></i> &nbsp;Clear</button>
                                <button type='submit' class='btn btn-success'><i class='bi bi-floppy-fill'></i> &nbsp;Add</button>
                            </td>
                        </tr>
                    </table>
                </form><br>
            </div>
            <div class="col-lg-6">
                <div class="record_table">
                <table width="100%" class="table table-bordered">
                    <tr>
                        <th>Date</th>
                        <th>Wastage No.</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Reason</th>
                        <th></th>
                    </tr>
                    <?php
                        $result=$conn->query("select wastages.id, wastages.wastage_date, wastages.wastage_no, wastages.quantity, wastages.reason, product_variants.variant_name, products.name as product_name from wastages, product_variants, products where wastages.product_variant_id = product_variants.id and product_variants.product_id = products.id");

                        while($row =$result->fetch_assoc()){
                            echo "<tr>
                                <td>$row[wastage_date]</td>
                                <td>$row[wastage_no]</td>
                                <td>$row[product_name] - $row[variant_name]</td>
                                <td>$row[quantity]</td>
                                <td>$row[reason]</td>
                                <td><center><button type='button' class='btn btn-danger btn_del' wastage_id='$row[id]'><i class='bi bi-trash-fill'></i></button></center></td>

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
        $(".btn_del").click(function(){
            var wastage_id = $(this).attr("wastage_id");
            if(confirm("Are you sure you want to delete this wastage?")){
                window.location.href = 'wastages.php?del_wastage_id='+wastage_id;
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