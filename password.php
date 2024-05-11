<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: login.php");
    exit;
}

$showError = false;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'src/_dbconnect.php';

    $username = $_SESSION['username'];
    $password = $_POST["oldpassword"];
    
    $passwordn = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    
    $sql = "Select * from user_5s where username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 1 && ($passwordn == $cpassword)){

        $sqla = "UPDATE user_5s SET password = $passwordn WHERE username = '$username'";
        $resulta = mysqli_query($conn, $sqla);
        // $numa = mysqli_num_rows($resulta);
        if($resulta){
            $showError = false;
        }

    } 
    else{
        $showError = true;
        $msg = "invalid details ";
        echo "<script>alert('$msg')</script>"; 
    }
    if ($showError==false) {
        $message = "password reset successfull";
        echo "<script>alert('$message');</script>";
        // 
    }
    // header("location: home.php");
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="shortcut icon" href="./images/logo.png">
    <link rel="stylesheet" href="style.css">

    <style>
        body{
            background-image: url("./rec/bg-1.jpg");
            /* background-repeat: no-repeat;
            background-size: cover; */
        }
        header{position: relative;}
        .change-password-container{
            color: black;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 90vh;
        }
        .change-password-container form{
            color: black;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-radius: var(--border-radius-2);
            padding : 3.5rem;
            /* background-color: var(--color-white); */
            background-color: rgb(89, 98, 222, 0.2);
            box-shadow: var(--box-shadow);
            width: 95%;
            max-width: 32rem;
        }
        .change-password-container form:hover{box-shadow: none;}
        .change-password-container form input[type=password]{
            color: black;
            border: none;
            outline: none;
            border: 1px solid var(--color-light);
            background: transparent;
            height: 2rem;
            width: 100%;
            padding: 0 .5rem;
        }
        .change-password-container form .box{
            padding: .5rem 0;
        }
        .change-password-container form .box p{
            line-height: 2;
        }
        .change-password-container form h2+p{margin: .4rem 0 1.2rem 0;} 
        .btn{
            background: none;
            border: none;
            border: 2px solid var(--color-primary) !important;
            border-radius: var(--border-radius-1);
            padding: .5rem 1rem;
            /* color: var(--color-white); */
            color: black;
            background-color: var(--color-primary);
            cursor: pointer;
            margin: 1rem 1.5rem 1rem 0;
            margin-top: 1.5rem;
        }
        .btn:hover{
            color: var(--color-primary);
            background-color: transparent;
        }
    </style>

</head>
<body>
    <header>
        <div class="logo">
            <img src="./images/logo.png" alt="">
            <h2>N<span class="danger">I</span>T</h2>
            <h2><span class="danger">R</span>R</h2>
        </div>
        <div class="navbar">
            <a href="home.php">
                <span class="material-icons-sharp">home</span>
                <h3>Home</h3>
            </a>
            <a href="timetable.html" onclick="timeTableAll()">
                <span class="material-icons-sharp">today</span>
                <h3>Time Table</h3>
            </a> 
            <a href="exam.html">
                <span class="material-icons-sharp">grid_view</span>
                <h3>Examination</h3>
            </a>
            <a href="password.php" class="active">
                <span class="material-icons-sharp">password</span>
                <h3>Change Password</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div id="profile-btn" style="display: none;">
            <span class="material-icons-sharp">person</span>
        </div>
        
    </header>

    <div class="change-password-container">
        <form id="changepasswordid" action="\student_dasbord_management\password.php" method="post">
            <h2 style="color: black;">Create new password</h2>
            <p class="text-muted" style="color: black;">Your new password must be different from previous used passwords.</p>
            <div class="box">
                <p class="text-muted" style="color: black;">Current Password</p>
                <input type="password" id="currentpass" name="oldpassword" style="color: black;background-color: #eae5e9da;">
            </div>
            <div class="box">
                <p class="text-muted" style="color: black;">New Password</p>
                <!-- <input type="password" id="newpass"> -->
                <input type="password" class="form-control" id="newpass" name="password" style="color: black;background-color: #eae5e9da;>
            </div>
            <div class="box">
                <p class="text-muted" style="color: black;">Confirm Password</p>
                <!-- <input type="password" id="confirmpass"> -->
                <input type="password" class="form-control" id="confirmpass" name="cpassword" style="color: black;background-color: #eae5e9da;>
            </div>
            <div class="button">
                <input type="submit" value="Change Password" class="btn">
                <a href="home.php" class="text-muted btn" id="cancelbtn" style="color: black;background-color: #d4cfd4da;">Cancel</a>
            </div>
            <p style="color: black;background-color: #d4cfd4da;">Forgot password? Contact college IT support for assistance in resetting your account access.</p>
        </form>    
    </div>

</body>

</html>