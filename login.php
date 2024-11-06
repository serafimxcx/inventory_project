<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <title>Login Page</title>
</head>
<body>
    <div class="login_div">
        <center><h3><b>VANGE INVENTORY SYSTEM</b></h3>
                <h4>Login your account</h4></center>
        <hr>
        <form action="authenticate.php" method="POST">
        <table width="100%">
            <tr>
                <td width="10%"><i class="bi bi-person-fill-gear login_txt"></i></td>
                <td><span class="login_txt">Role:</span></td>
                <td><select name="slct_role" id="slct_role" class="txt_input form-control">
                    <option value="">Select Role...</option>
                    <option value="staff">Staff</option>
                    <option value="manager">Manager</option>
                    <option value="admin">Admin</option>
                </select></td>
            </tr>
            <tr>
                <td><i class="bi bi-person-fill login_txt"></i></td>
                <td> <span class="login_txt">Username:</span> </td>
                <td><input type="text" name="txt_username" id="txt_username" class="txt_input form-control"></td>
            </tr>
            <tr>
                <td><i class="bi bi-key-fill login_txt"></i></td>
                <td> <span class="login_txt">Password:</span> </td>
                <td><input type="password" name="txt_password" id="txt_password" class="txt_input form-control"></td>
            </tr>
            <tr>
                <td colspan="3">
                    <center><br><input type="submit" value="LOGIN" class="btn btn-primary btn_login"></center>
                </td>
            </tr>
        </table>
        </form>
    </div>
</body>
</html>