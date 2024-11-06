<?php 
    include("conn.php");
    session_start();

    $user = "";
    $pass = "";
    $user_id="";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $inptuser = $_POST["txt_username"];
        $inptpass = $_POST["txt_password"];
        $role = $_POST["slct_role"];


        $result = $conn->query("select * from users where username like '$inptuser' and password like '$inptpass' and role like '$role'");
        while($row = $result -> fetch_assoc()){
            $user = $row['username'];
            $user_id = $row['id'];
            $pass = $row['password'];
        }

        if($inptpass == "" || $inptuser =="") {
            echo "<script>alert('Login Failed. Blank Input.');
                    window.location.href='login.php'</script>";
        }elseif ($inptuser == $user && $inptpass == $pass) {
            $response = array('success'=>true, 'message'=>" You are successfully logged in. Welcome $name");
            $_SESSION['inventory_user_id'] = $user_id;
            
            echo "<script>alert('Login Success. Welcome $user !!!');
                    window.location.href='index.php'</script>";
        
        } else {
            echo "<script>alert('Login Failed. Invalid Credentials.');
                    window.location.href='login.php'</script>";
        }


    }
?>