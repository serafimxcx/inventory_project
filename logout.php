<?php
// Start the session
include("conn.php");
session_start();

session_unset();
session_destroy();

echo "<script>alert('Logout Successfully.');
                    window.location.href='login.php'</script>";
?>