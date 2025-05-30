<?php
session_start();

if(isset($_SESSION["user"]))
{
 header("location:index.php");
}

include('connection.php');

if(isset($_POST['submit']))
{
 $email=$_POST['username'];
 $password=$_POST['password'];

 $query=mysqli_query($con,"select * from users where email='$email' and password='$password'");
 $row=mysqli_fetch_array($query);

 if($row)
 {
  $_SESSION["user"]=$row['id'];
  header("Location: index.php");
 }
 else
 {
  $msg="Invalid Email or Password";
 }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Grand Sindhu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 100px;
        }
        .card {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <h3 class="text-center mb-4">Login</h3>
                    <?php if(isset($msg)) echo "<div class='alert alert-danger'>$msg</div>"; ?>
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="username">Email:</label>
                            <input type="email" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="register.php">Create an account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
