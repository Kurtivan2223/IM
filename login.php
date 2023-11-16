<?php
    require_once "loader.php";

    if(isset($_SESSION['AdminID']))
    {
        header('Location: http://localhost/admin/index.php');
    }
    else if(isset($_SESSION['ID']))
    {
        header('Location: http://localhost/user/index.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <script src="/js/bootstrap.bundle.min.js"></script>
        <style>
            body {
                height: 100%;
                width: 100%;
                background-image: url('/images/clouds.jpg');
            }

            .container {
                width: 550px !important;
                height: 420px !important;
                background-color: white !important;
            }
            h2 {
                font-size: 15px !important;
            }

            .back, a:link, a:visited, a:hover, a:active {
                text-decoration: none !important;
            }

        </style>
    </head>
    <body>
        <div class="container border border-dark rounded" style="margin-top: 15px;">
        <a class="back" href="index.html"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
        </svg>
        Go Back
        </a>
            <div class="m-5">
                <?php 
                    error_msg(); 
                    success_msg();
                ?>
                <form action="" method="POST" autocomplete="off">
                    <div class="form-group">
                        <h2>Username or Email</h2>
                        <input type="text" class="form-control mb-2" name="useroremail" placeholder="Username or Email" required>
                    </div>
                    <div class="form-group">
                        <h2>Password</h2>
                        <input type="password" class="form-control mb-2" name="password" placeholder="Password"  required>
                    </div>
                    <p>Don't have Account yet? <a href="register.php">Click Here!</a></p>

                    <input name="submit" type="hidden" value="login">
                    <center>
                        <input type="submit" value="Login" class="btn btn-primary btn-block mb-2">
                    </center>
                </form>
            </div>
        </div>
    </body>
</html>