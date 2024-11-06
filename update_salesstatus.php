<?php 
    include("conn.php");

    $query = "update sales_orders set
			status='$_REQUEST[status]'
			where id=".intval($_REQUEST["sales_id"]);
    mysqli_query($conn,$query) or die(mysqli_error($conn)."<br>".$query);

echo "";

?>