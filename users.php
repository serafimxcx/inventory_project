<?php 
    include("navbar.php");

    $name="";
    $email="";
    $role="";
    $username="";
    $password="";

    if(isset($_POST["txt_user_id"])){
        $conn->query("update users set username='$_POST[txt_username]', password='$_POST[txt_password]', role='$_POST[slct_role]', name='$_POST[txt_name]', email='$_POST[txt_email]' where id='$_POST[txt_user_id]'");
    }elseif(isset($_POST["txt_username"])){
        $conn->query("insert into users(username, password, role, name, email)values('$_POST[txt_username]','$_POST[txt_password]', '$_POST[slct_role]', '$_POST[txt_name]', '$_POST[txt_email]')");

        echo "<script> window.location.href='users.php'</script>";
    }elseif(isset($_GET["del_user_id"])){
        $conn->query("delete from users where id ='$_GET[del_user_id]'");
        echo "<script> window.location.href='users.php'</script>";
    }elseif(isset($_GET["edit_user_id"])){
        $result = $conn->query("select * from users where id='$_GET[edit_user_id]'");
        while($row=$result->fetch_assoc()){
            $name=$row["name"];
            $email=$row["email"];
            $role=$row["role"];
            $username=$row["username"];
            $password=$row["password"];
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
</head>
<body>
    <div class="content_container">
        <h2>Manage Users</h2><br>
        <form action="users.php" method="POST">
        <div class="row">
            <div class="col-lg-6">
                <table width="100%">
                    <tr>
                        <td>Name: <br></td>
                        <td><input type="text" name="txt_name" id="txt_name" class="txt_input form-control" value='<?php echo $name;?>'><br></td>
                    </tr>
                    <tr>
                        <td>Email: <br></td>
                        <td><input type="text" name="txt_email" id="txt_email" class="txt_input form-control" value='<?php echo $email;?>'><br></td>
                    </tr>
                    <tr>
                        <td>Role: <br></td>
                        <td><select name="slct_role" id="slct_role" class="txt_input form-control">
                            <option value="">Select Role...</option>
                            <option value="staff" <?php if($role == "staff"){ echo "selected"; } ?>>Staff</option>
                            <option value="manager" <?php if($role == "staff"){ echo "manager"; } ?>>Manager</option>
                            <option value="admin" <?php if($role == "admin"){ echo "selected"; } ?>>Admin</option>
                        </select></td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-6">
                <table width="100%">
                    <tr>
                        <td>Username: <br></td>
                        <td><input type="text" name="txt_username" id="txt_username" class="txt_input form-control" value='<?php echo $username;?>'><br></td>
                    </tr>
                    <tr>
                        <td>Password: <br></td>
                        <td><input type="password" name="txt_password" id="txt_password" class="txt_input form-control" value='<?php echo $password;?>'><br></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: right">
                        <?php 
                            if(isset($_GET["edit_user_id"])){
                                echo "
                                <input type='hidden' name='txt_user_id' value='$_GET[edit_user_id]'>
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
        <br><hr><br>
        <div class="record_table">
        <table class="table table-bordered" width="100%">
            <tr>
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th></th>
                <th></th>
            </tr>
            <?php
                $result=$conn->query("select * from users");

                while($row=$result->fetch_assoc()){
                    echo "<tr>
                        <td>$row[username]</td>
                        <td>$row[name]</td>
                        <td>$row[email]</td>
                        <td>$row[role]</td>
                        <td><center><button type='button' class='btn btn-warning btn_edit' user_id='$row[id]'><i class='bi bi-pencil-square'></i></button></center></td>
                        <td><center><button type='button' class='btn btn-danger btn_del' user_id='$row[id]'><i class='bi bi-trash-fill'></i></button></center></td>

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

        $(".btn_del").click(function(){
            var user_id = $(this).attr("user_id");
            if(confirm("Are you sure you want to delete this user?")){
                window.location.href = 'users.php?del_user_id='+user_id;
            }
            
        });

        $(".btn_edit").click(function(){
            var user_id = $(this).attr("user_id");
            if(confirm("Are you sure you want to edit this users?")){
                window.location.href = 'users.php?edit_user_id='+user_id;
            }
            
        });

    })
</script>
</html>