<?php 
    include("navbar.php");

    $name="";
    $location="";

    if(isset($_GET["txt_warehouse_id"])){
        $conn->query("update warehouses set name='".mysqli_real_escape_string($conn, $_GET["txt_name"])."', location='".mysqli_real_escape_string($conn, $_GET["txt_location"])."' where id='$_GET[txt_warehouse_id]'");

        echo "<script> window.location.href='warehouse.php'</script>";
    }elseif(isset($_GET["txt_name"])){
        $conn->query("insert into warehouses(name,location)values('".mysqli_real_escape_string($conn, $_GET["txt_name"])."', '".mysqli_real_escape_string($conn, $_GET["txt_location"])."')");

        echo "<script> window.location.href='warehouse.php'</script>";
    }elseif(isset($_GET["del_warehouse_id"])){
        $conn->query("delete from warehouses where id ='$_GET[del_warehouse_id]'");
        echo "<script> window.location.href='warehouse.php'</script>";
    }elseif(isset($_GET["edit_warehouse_id"])){
        $result=$conn->query("select * from warehouses where id='$_GET[edit_warehouse_id]'");

        while($row=$result->fetch_assoc()){
            $name=$row["name"];
            $location=$row["location"];
        }

      
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouse</title>
</head>
<body>
    <div class="content_container">
        
        <div class="row">
            <div class="col-lg-12">
            <h2>Manage Warehouse</h2><hr>
                <form action="warehouse.php" method="get">
                    <table width="100%">
                        <tr>
                            <td>Name: <br></td>
                            <td><input type="text" name="txt_name" id="txt_name" class="txt_input form-control" value='<?php echo $name?>'> <br></td>
                        </tr>
                        <tr>
                            <td>Location: <br></td>
                            <td><input type="text" name="txt_location" id="txt_location" class="txt_input form-control" value='<?php echo $location?>'> <br></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right">
                            <?php 
                                    if(isset($_GET["edit_warehouse_id"])){
                                        echo "
                                        <input type='hidden' name='txt_warehouse_id' value='$_GET[edit_warehouse_id]'>
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
        <table width="100%" class="table table-bordered">
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th></th>
                <th></th>
            </tr>
            <?php 
                $result=$conn->query("select * from warehouses");

                while($row = $result->fetch_assoc()){
                    echo "<tr>
                        <td>$row[name]</td>
                        <td>$row[location]</td>
                        <td><center><button type='button' class='btn btn-warning btn_edit' warehouse_id='$row[id]'><i class='bi bi-pencil-square'></i></button></center></td>
                        <td><center><button type='button' class='btn btn-danger btn_del' warehouse_id='$row[id]'><i class='bi bi-trash-fill'></i></button></center></td>

                    </tr>";
                }
            
            ?>
        </table>
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
            window.location.href = 'warehouse.php';
            
        });


        $(".btn_del").click(function(){
            var warehouse_id = $(this).attr("warehouse_id");
            if(confirm("Are you sure you want to delete this warehouse?")){
                window.location.href = 'warehouse.php?del_warehouse_id='+warehouse_id;
            }
            
        });

        $(".btn_edit").click(function(){
            var warehouse_id = $(this).attr("warehouse_id");
            if(confirm("Are you sure you want to edit this warehouse?")){
                window.location.href = 'warehouse.php?edit_warehouse_id='+warehouse_id;
            }
            
        });
    });
</script>
</html>