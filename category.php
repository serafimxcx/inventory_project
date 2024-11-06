<?php 
    include("navbar.php");

    $name="";

    $category="";
    if(isset($_GET["txt_id"])){
        $conn->query("update category set name='$_GET[txt_name]' where id='$_GET[txt_id]'");
        echo "<script> window.location.href='category.php'</script>";
        
    }elseif(isset($_GET["txt_name"])){
        $conn->query("insert into category (name) values ('$_GET[txt_name]')");
        echo "window.location.href='category.php'</script>";
    }elseif(isset($_GET["txtdelid"])){
        $conn->query("delete from category where id=$_GET[txtdelid]");
        echo "<script> window.location.href='category.php'</script>";
    }elseif(isset($_GET["category_id"])){
        $result = $conn->query("select * from category");
        while($row = $result->fetch_assoc()){
            $name = $row["name"];
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Category</title>
</head>
<body>
    <div class="content_container">
        <h2>Product Category</h2>
        <h4>Add new category...</h4>
        <hr>
        <div class="row">
            <div class="col-lg-4">
                <br>
                <form action="category.php" method="GET">
                    <label for="txt_name">Name:</label>&nbsp;
                    <input type="text" class="form-control" name="txt_name" id="txt_name" value='<?php echo $name;?>' style="width: 80%;"><br>
                    <?php 
                        if(isset($_GET["category_id"])){

                            echo "
                                <input type='hidden' name='txt_id' value='$_GET[category_id]'>
                                <button type='button' class='btn btn-danger' onclick='cancel()'><i class='bi bi-x-lg'></i> &nbsp;Cancel</button>&nbsp;
                                <button type='submit' class='btn btn-success'><i class='bi bi-floppy-fill'></i> &nbsp;Update</button>";
                        }else{
                            echo "<button type='submit' class='btn btn-success'><i class='bi bi-floppy-fill'></i> &nbsp;Save</button>
                            ";
                        }
                    
                    ?>
                    
                </form>
                <br><br>
            </div>
            <div class="col-lg-8">
                <table class="table table-bordered" width="100%">
                    <tr>
                        <th width='70%'>Name</th>
                        <th width='10%'></th>
                        <th width='10%'></th>
                        <th width='10%'></th>
                    </tr>
    
                    <?php 
                        $result=$conn->query("select * from category");
                        while($row=$result->fetch_assoc()){
                            echo "<tr>
                                <td>$row[name]</td>
                                <td ><center><a href='product.php?category_name=$row[name]'><button type='button' class='btn btn-info'><i class='bi bi-plus-lg'></i> Products</button>
                                </a></center> </td>
                                <td ><center><button type='button' class='btn btn-warning' onclick=myupdate($row[id])><i class='bi bi-pencil-square'></i></button></center> </td>
                                
                                <td ><center><button type='button' class='btn btn-danger' onclick='mydel($row[id])'><i class='bi bi-trash-fill'></i></button></center> </td>
                                
                            </tr>";
                        }
                    
                    ?>
                </table>
                
            </div>
        </div>
        
    </div>
</body>
<script>
    function mydel(myid){
        if(confirm("Delete this record?")==true){
            window.open("category.php?txtdelid="+myid,"_self")
        }
    }
    function myupdate(myid){
        if(confirm("Edit this record?")==true){
            window.open("category.php?category_id="+myid,"_self")
        }
    }


    function cancel(){
        window.location.href="category.php";
    }
</script>
</html>