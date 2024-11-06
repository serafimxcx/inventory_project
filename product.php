<?php 
    include("navbar.php");

    $category_id = "";
    $category_name = "";

    $name = "";
    $code= "";
    $desc="";
    $tags="";
    $price="";
    $discount="";
    $reorderpts="";

    $prod_id="";
    $prod_name="";

    if(isset($_GET["category_name"])){

        $result = $conn->query("select * from category where name='$_GET[category_name]'");
        while($row=$result->fetch_assoc()){
            $category_id = $row["id"];
            $category_name = $row["name"];
        }
    }

    if(isset($_GET["txt_prod_id"])){
        $conn->query("update products set code='$_GET[txt_code]', name='".mysqli_real_escape_string($conn, $_GET["txt_name"])."', description='".mysqli_real_escape_string($conn, $_GET["txt_desc"])."', tags='$_GET[txt_tag]', discount='$_GET[txt_discount]', reorder_point='$_GET[txt_reorderpts]' where id='$_GET[txt_prod_id]'");

        echo "<script> window.location.href='product.php?category_name=$_GET[txt_category_name]'</script>";
    }elseif(isset($_GET["txt_code"])){
        $conn->query("insert into products(code, name, description, category_id, tags, discount, reorder_point)values('$_GET[txt_code]', '".mysqli_real_escape_string($conn, $_GET["txt_name"])."', '".mysqli_real_escape_string($conn, $_GET["txt_desc"])."', '$_GET[txt_category_id]', '$_GET[txt_tag]', '$_GET[txt_discount]', '$_GET[txt_reorderpts]')");
        echo "<script> window.location.href='product.php?category_name=$_GET[txt_category_name]'</script>";
    }elseif(isset($_GET["del_prod_id"])){
        $conn->query("delete from products where id='$_GET[del_prod_id]'");
        echo "<script> window.location.href='product.php?category_name=$_GET[category_name]'</script>";
    }elseif(isset($_GET["edit_prod_id"])){
        $result = $conn->query("select * from products where id='$_GET[edit_prod_id]'");

        while($row = $result->fetch_assoc()){
            $code=$row["code"];
            $name=$row["name"];
            $desc=$row["description"];
            $tags=$row["tags"];
            $discount=$row["discount"];
            $reorderpts=$row["reorder_point"];

        }
    }

    if(isset($_GET["txt_variant_name"])){
        $conn->query("insert into product_variants(product_id, variant_name, variant_desc, price)values('$_GET[product_id]', '".mysqli_real_escape_string($conn, $_GET["txt_variant_name"])."', '".mysqli_real_escape_string($conn, $_GET["txt_variant_desc"])."', '$_GET[txt_price]')");

        echo "<script> window.location.href='product.php?category_name=$_GET[txt_category_name]&product_id=$_GET[product_id]&product_variant_modal=true'</script>";
    }elseif(isset($_GET["del_prodvar_id"])){
        $conn->query("delete from product_variants where id='$_GET[del_prodvar_id]'");
        echo "<script> window.location.href='product.php?category_name=$_GET[category_name]&product_id=$_GET[product_id]&product_variant_modal=true'</script>";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
</head>
<body>
    <div class="content_container">
        <h2>Manage Products</h2>
        <?php 
                if(isset($_GET["category_name"])){
                    echo "<h4>$category_name Products</h4>";
                }
            ?>
        <hr>
        <div class="row" id="product_container">
            
            <div class="col-lg-12">
                <div id="product_info_container" style="<?php
                    if(isset($_GET["category_name"])){
                        echo "display: block;";
                    }else{
                        echo "display: none;";
                    }

                ?>">
                    <form action="product.php" method="get">
                        <input type="hidden" name="txt_category_id" value='<?php echo $category_id;?>'>
                        <div class="row">
                            <div class="col-lg-6">
                            <table width="100%">
                                <tr>
                                    <td>Category: <br></td>
                                    <td> <input type="text" name="txt_category_name" id="txt_category_name" class=" form-control" value='<?php echo $category_name;?>' readonly><br></td>
                                </tr>
                                <tr>
                                    <td>Code: <br></td>
                                    <td><input type="text" name="txt_code" id="txt_code" class="txt_input form-control" value='<?php echo $code; ?>' required><br></td>
                                </tr>
                                <tr>
                                    <td>Name: <br></td>
                                    <td><input type="text" name="txt_name" id="txt_name" class="txt_input form-control" value='<?php echo $name; ?>' required><br></td>
                                </tr>
                                <tr>
                                    <td>Description: <br></td>
                                    <td><textarea name="txt_desc" id="txt_desc" class="txt_input form-control"><?php echo $desc; ?> </textarea><br></td>
                                </tr>
                                
                            </table>
                            </div>
                            <div class="col-lg-6">
                            <table width="100%">
                                <tr>
                                    <td>Tags: <br></td>
                                    <td><input type="text" name="txt_tag" id="txt_tag" class="txt_input form-control" value='<?php echo $tags; ?>' ><br></td>
                                </tr>
                                <tr>
                                    <td>Discount: <br></td>
                                    <td><input type="number" name="txt_discount" id="txt_discount" class="txt_input form-control" step="any"  value='<?php echo $discount; ?>' ><br></td>
                                </tr>
                                <tr>
                                    <td>Reorder Points: <br></td>
                                    <td><input type="number" name="txt_reorderpts" id="txt_reorderpts" class="txt_input form-control" value='<?php echo $reorderpts; ?>' ><br></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: right">
                                    <?php 
                                        if(isset($_GET["edit_prod_id"])){
                                            echo "
                                            <input type='hidden' name='txt_prod_id' value='$_GET[edit_prod_id]'>
                                            <button type='button' class='btn btn-danger' id='btn_cancel'><i class='bi bi-x-lg'></i> &nbsp;Cancel</button>
                                            <button type='submit' class='btn btn-success'><i class='bi bi-floppy-fill'></i> &nbsp;Update</button>
                                            ";
                                        }else{
                                            echo "
                                            <a href='category.php'><button type='button' class='btn btn-primary'> <i class='bi bi-arrow-return-left'></i> &nbsp;Back</button></a>
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
                </div>
                
            </div>
        </div>
        <br>
        <div class="all_products" style="<?php
                    if(isset($_GET["category_name"])){
                        echo "display: block;";
                    }else{
                        echo "display: none;";
                    }

                ?>">
        <hr>
            
            <h5>Click specific item to assign variants.</h5><br>
            <table class="table table-bordered">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Tags</th>
                    <th>Discount</th>
                    <th>Reorder Points</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php 
                    
                    $result = $conn->query("select category.name as category_name, products.id as product_id, products.code, products.name as product_name, products.description, products.tags, products.discount, products.reorder_point from products, category where products.category_id = '$category_id' and products.category_id = category.id");

                    while($row=$result->fetch_assoc()){
                        echo "<tr class='i_product_variant' product_id='$row[product_id]' category_name='$row[category_name]'>
                            <td>$row[code]</td>
                            <td>$row[product_name]</td>
                            <td>$row[description]</td>
                            <td>$row[tags]</td>
                            <td>$row[discount]</td>
                            <td>$row[reorder_point]</td>
                            <td><center><button type='button' class='btn btn-warning btn_edit' product_id='$row[product_id]' category_name='$row[category_name]'><i class='bi bi-pencil-square'></i></button></center></td>
                            <td><center><button type='button' class='btn btn-danger btn_del' product_id='$row[product_id]' category_name='$row[category_name]'><i class='bi bi-trash-fill'></i></button></center></td>

                        </tr>";
                    }
                
                ?>
            </table>
            <br><br>

        </div>
    </div>

    <div class="modal" id="product_variant_modal" style="<?php 
        if(isset($_GET["product_variant_modal"]) && isset($_GET["product_id"])){
            echo "display: flex";

            $result = $conn->query("select * from products where id='$_GET[product_id]'");

            while($row = $result->fetch_assoc()){
                $prod_id=$row["id"];
                $prod_name=$row["name"];

            }
            }else{
                echo "display: none";
            }
    ?>">
        <div id="product_variant_div">
            <h2><span class="btn_closemodal"><i class="bi bi-x-lg"></i></span> &nbsp;&nbsp;Add Variant</h2><hr>
            <form action="product.php" method="get">
            <input type="hidden" name="txt_category_name" value='<?php echo $category_name;?>'>
            <input type="hidden" name="product_id" value='<?php echo $prod_id;?>'>
                <table width="100%">
                    <tr>
                        <td>Product: <br></td>
                        <td><input type="text" class="form-control" value='<?php echo $prod_name; ?>' readonly>
                        <br></td>
                    </tr>
                    <tr>
                        <td>Variant Name: <br></td>
                        <td><input type="text" name="txt_variant_name" class="txt_input form-control" required>
                        <br></td>
                    </tr>
                    <tr>
                        <td>Description: <br></td>
                        <td><textarea name="txt_variant_desc" id="txt_variant_desc" class="txt_input form-control"></textarea>
                        <br></td>
                    </tr>
                    <tr>
                        <td>Price: <br></td>
                        <td><input type="number" name="txt_price" id="txt_price" class="txt_input form-control" step="any" ><br></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:right;">
                            <button type='button' class='btn btn-danger btn_clear'> <i class='bi bi-eraser-fill'></i> &nbsp;Clear</button>
                            <button type='submit' class='btn btn-success'><i class='bi bi-floppy-fill'></i> &nbsp;Add</button>
                        </td>
                    </tr>
                </table>
            </form>
            <br>
            <table width="100%" class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th></th>
                </tr>
                <?php 
                    $result=$conn->query("select * from product_variants where product_id = '$prod_id' ");

                    while($row = $result->fetch_assoc()){
                        echo "<tr>
                            <td>$row[variant_name]</td>
                            <td>$row[variant_desc]</td>
                            <td>$row[price]</td>
                            <td><center><button type='button' class='btn btn-danger btn_variant_del' product_id='$row[product_id]' prod_variant_id='$row[id]'><i class='bi bi-trash-fill'></i></button></center></td>

                        </tr>";
                    }
                
                ?>
            </table>
            <br>
        </div>

    </div>
</body>
<script>
    $(function(){
        $(".btn_variant_del").click(function(){
            var prod_variant_id = $(this).attr("prod_variant_id");
            var product_id = $(this).attr("product_id");
            var category_name = $("#txt_category_name").val();

            if(confirm("Are you sure you want to delete this variant?")){
                window.location.href = 'product.php?category_name='+category_name+"&product_id="+product_id+"&del_prodvar_id="+prod_variant_id;
            }
            
            
        });

        $(".btn_closemodal").click(function(){
            var category_name = $("#txt_category_name").val();
            window.location.href = 'product.php?category_name='+category_name;
            
        });
        
        $(".i_product_variant").click(function(){
            
            var product_id = $(this).attr("product_id");
            var category_name = $(this).attr("category_name");

            window.location.href = 'product.php?category_name='+category_name+'&product_id='+product_id+"&product_variant_modal=true";
            
        });

        $(".btn_clear").click(function(){
            if(confirm("Clear all the fields?")){
                $(".txt_input").val("");
            }
            
        });

        $("#btn_cancel").click(function(){
            var category_name = $("#txt_category_name").val();
            window.location.href = 'product.php?category_name='+category_name;
            
        });

        $(".btn_del").click(function(){
            event.stopPropagation();
            var product_id = $(this).attr("product_id");
            var category_name = $(this).attr("category_name");

            if(confirm("Are you sure you want to delete this product?")){
                window.location.href = 'product.php?category_name='+category_name+'&del_prod_id='+product_id;
            }
        });

        $(".btn_edit").click(function(){
            event.stopPropagation();
            var product_id = $(this).attr("product_id");
            var category_name = $(this).attr("category_name");

            if(confirm("Are you sure you want to edit this product?")){
                window.location.href = 'product.php?category_name='+category_name+'&edit_prod_id='+product_id;
            }
        });
    });
</script>
</html>