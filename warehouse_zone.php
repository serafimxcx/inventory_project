<?php
    include("navbar.php");

    $zone_name="";
    $warehouse_id="";

    if(isset($_GET["txt_zone_id"])){
        $conn->query("update warehouse_zones set warehouse_id='$_GET[slct_warehouse]', zone_name='$_GET[txt_zone_name]' where id='$_GET[txt_zone_id]'");

        echo "<script> window.location.href='warehouse_zone.php'</script>";
    }elseif(isset($_GET["txt_zone_name"])){
        $conn->query("insert into warehouse_zones(warehouse_id, zone_name)values('$_GET[slct_warehouse]', '$_GET[txt_zone_name]')");

        echo "<script> window.location.href='warehouse_zone.php'</script>";
    }elseif(isset($_GET["del_zone_id"])){
        $conn->query("delete from warehouse_zones where id ='$_GET[del_zone_id]'");
        echo "<script> window.location.href='warehouse_zone.php'</script>";
    }elseif(isset($_GET["edit_zone_id"])){
        $result=$conn->query("select * from warehouse_zones where id='$_GET[edit_zone_id]'");

        while($row=$result->fetch_assoc()){
            $zone_name=$row["zone_name"];
            $warehouse_id=$row["warehouse_id"];
        }

      
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse Zones</title>
</head>
<body>
<div class="content_container">
        <div class="row">
            <div class="col-lg-12">
            <h2>Manage Warehouse Zone</h2><hr>
                <form action="warehouse_zone.php" method="get">
                    <table width="100%">
                        <tr>
                            <td>Warehouse: <br></td>
                            <td>
                                <select name="slct_warehouse" id="slct_warehouse" class="txt_input form-control">
                                    <option value="">Select Warehouse...</option>
                                    <?php
                                        $result = $conn->query("select * from warehouses");

                                        while($row=$result->fetch_assoc()){
                                            echo "<option value='$row[id]'";
                                                if($row["id"] == $warehouse_id){
                                                    echo "selected";
                                                }
                                            echo ">$row[name]</option>";
                                        }
                                    
                                    ?>
                                </select><br>
                            </td>
                        </tr>
                        <tr>
                            <td>Zone Name: <br></td>
                            <td><input type="text" name="txt_zone_name" id="txt_zone_name" class="txt_input form-control" value='<?php echo $zone_name?>'> <br></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right">
                            <?php 
                                    if(isset($_GET["edit_zone_id"])){
                                        echo "
                                        <input type='hidden' name='txt_zone_id' value='$_GET[edit_zone_id]'>
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
        <div class="record_table">
        <table width="100%" class="table table-bordered">
            <tr>
                <th>Zone</th>
                <th>Warehouse</th>
                <th></th>
                <th></th>
            </tr>
            <?php 
                $result=$conn->query("select warehouses.name as warehouse_name, warehouse_zones.id, warehouse_zones.zone_name from warehouses, warehouse_zones where warehouse_zones.warehouse_id = warehouses.id");

                while($row = $result->fetch_assoc()){
                    echo "<tr>
                        <td>$row[zone_name]</td>
                        <td>$row[warehouse_name]</td>
                        <td><center><button type='button' class='btn btn-warning btn_edit' zone_id='$row[id]'><i class='bi bi-pencil-square'></i></button></center></td>
                        <td><center><button type='button' class='btn btn-danger btn_del' zone_id='$row[id]'><i class='bi bi-trash-fill'></i></button></center></td>

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

        $("#btn_cancel").click(function(){
            window.location.href = 'warehouse_zone.php';
            
        });

        $(".btn_del").click(function(){
            var zone_id = $(this).attr("zone_id");
            if(confirm("Are you sure you want to delete this zone?")){
                window.location.href = 'warehouse_zone.php?del_zone_id='+zone_id;
            }
            
        });

        $(".btn_edit").click(function(){
            var zone_id = $(this).attr("zone_id");
            if(confirm("Are you sure you want to edit this zone?")){
                window.location.href = 'warehouse_zone.php?edit_zone_id='+zone_id;
            }
            
        });
    });
</script>
</html>