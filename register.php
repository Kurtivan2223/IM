<?php
    ob_start();

    require_once "./include/config.php";
    require_once "./include/functions.php";
    require_once "./include/Database.php";
    require_once "./include/user.php";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Register</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="container border border-dark" style="margin-top: 15px;">
            <div class="m-5">
                <?php 
                    error_msg(); 
                    success_msg();
                ?>
                <form action="" method="POST" autocomplete="off">
                    <div class="form-group">
                        <h2>Username</h2>
                        <input type="text" class="form-control mb-4" name="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <h2>E-mail</h2>
                        <input type="email" class="form-control mb-4" name="email" placeholder="E-mail">
                    </div>
                    <div class="form-group">
                        <h2>First Name</h2>
                        <input type="text" class="form-control mb-4" name="fname" placeholder="First Name">
                    </div>
                    <div class="form-group">
                        <h2>Last Name</h2>
                        <input type="text" class="form-control mb-4" name="lname" placeholder="Last Name">
                    </div>
                    <div class="form-group">
                        <h2>Password</h2>
                        <input type="password" class="form-control mb-4" name="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <h2>Re-Type Password</h2>
                        <input type="password" class="form-control mb-4" name="repassword" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <h2>Gender</h2>
                        <select class="form-control form-select  mb-4" name="gender">
                            <option selected> - Select Gender - </option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </div>
                    <input name="submit" type="hidden" value="register">
                    <input type="submit" value="Submit" class="btn btn-primary btn-block mb-4">
                </form>
            </div>
        </div>
    </body>
</html>