<?php 
    include("conn.php");
    session_start();

    $user_name="";
    $user_role="";
    if(!isset($_SESSION["inventory_user_id"])){
        echo "<script>alert('Please login your account.');
                    window.location.href='login.php'</script>";
    }else{
        $result=$conn->query("select * from users where id='$_SESSION[inventory_user_id]'");

        while($row=$result->fetch_assoc()){
            $user_name = explode(" ", $row["name"]);
            $user_role = $row["role"];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
    <table width="100%">
        <tr>
            <td><span class="navbar-btn" onclick="openNav()">&#9776;</span></td>
            <td style='text-align:right'><h2><b>VANGE INVENTORY SYSTEM</b></h2></td>
        </tr>
    </table>
    </div>


<div id="mySidenav" class="sidenav">
    <span class="user_name"><?php echo "Hello, ".$user_name[0]; ?></span>
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <br>
    <ul>
        <li><a href="index.php"><i class="bi bi-house-door-fill"></i> &nbsp;Dashboard</a></li>
        <li><a href="category.php"><i class="bi bi-basket3-fill"></i> &nbsp;Products</a></li>
        <li><a href="supplier.php"><i class="bi bi-people-fill"></i> &nbsp;Suppliers</a></li>
        <li><a href="sales.php"><i class="bi bi-cash-coin"></i> &nbsp;Sales</a></li>
        <li><a href="wastages.php"><i class="bi bi-trash2-fill"></i> &nbsp;Wastages</a></li>
        <li id="m_warehouse"><span id="a_warehouse"><i class="bi bi-house-down-fill"></i> &nbsp;Manage Warehouse &nbsp;<span class="glyphicon glyphicon-triangle-bottom"></span></span></li>
            <li class="warehouse"><a href="warehouse.php">Warehouse</a></li>
            <li class="warehouse"><a href="warehouse_zone.php">Warehouse Zone</a></li>
            <li class="warehouse"><a href="warehouse_bin.php">Warehouse Bin</a></li>
            <li class="warehouse"><a href="transfer_stocks.php">Transfer Stocks</a></li>


        <li id="m_reports"><span id="a_reports"><i class="bi bi-file-earmark-text-fill"></i> &nbsp;View Reports &nbsp;<span class="glyphicon glyphicon-triangle-bottom"></span></span></li>
            <li class="reports"><a href="reports.php">Stocks Report</a></li>
            <li class="reports"><a href="sales_report.php">Sales Report</a></li>
            <li class="reports"><a href="supplier_report.php">Suppliers Report</a></li>

        <?php 
            if($user_role == "admin"){
                echo " <li> <a href='users.php'><i class='bi bi-person-fill-gear'></i> &nbsp;Users</a></li>";
            }
        ?>
       
        <li> <a href="logout.php"><i class="bi bi-door-open-fill"></i> &nbsp;Logout</a></li>

    </ul>
    
    
    
    
    
    
    
    
   
    
</div>

<script>
    $(function(){
        $("#m_reports").click(function(){
            if($(".reports").css("display") == "none"){
                $(".reports").css({"display":"block"});
                $("#a_reports").html("<i class='bi bi-file-earmark-text-fill'></i> &nbsp;View Reports &nbsp;<span class='glyphicon glyphicon-triangle-top'></span>");
            }else{
                $(".reports").css({"display":"none"});
                $("#a_reports").html("<i class='bi bi-file-earmark-text-fill'></i> &nbsp;View Reports &nbsp;<span class='glyphicon glyphicon-triangle-bottom'></span>");
            }
        });

        $("#m_warehouse").click(function(){
            if($(".warehouse").css("display") == "none"){
                $(".warehouse").css({"display":"block"});
                $("#a_warehouse").html("<i class='bi bi-house-down-fill'></i> &nbsp;Manage Warehouse &nbsp;<span class='glyphicon glyphicon-triangle-top'></span>");
            }else{
                $(".warehouse").css({"display":"none"});
                $("#a_warehouse").html("<i class='bi bi-house-down-fill'></i> &nbsp;Manage Warehouse &nbsp;<span class='glyphicon glyphicon-triangle-bottom'></span>");
            }
        });
    });
    function openNav() {
        $("#mySidenav").css("display", "block");
    }

    function closeNav() {
        $("#mySidenav").css("display", "none");
    }
</script>

</body>
</html>