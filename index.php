<?php
$login = false;
$showError = false;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    include 'src/_dbconnect.php';
    $username = $_POST["username"];
    $password = $_POST["password"]; 
    
     
    $sql = "Select * from student_login_info where roll_num='$username' AND password_09='$password'";
    $result = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($result);
    $rowa = $result->fetch_assoc();
    // echo $rowa;
    if ($num == 1){
        $login = true;
        session_start();
        $sqla = "Select name from student_info_01 where roll_num='$username'";
        $resulta = mysqli_query($conn, $sqla);
        $row = $resulta->fetch_assoc();
        
        // $row['name']
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['name'] = $row['name'];
        // echo "loged in " . $_SESSION['name']  ;
        header("location: home.php");

    } 
    else{
        $showError = "Invalid Credentials";
    }
}
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Studen deshboard login</title>
  <link rel="stylesheet" type="text/css" href="./signin.CSS" />
  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>

</head>

<body>
<!-- T:\xampp\htdocs\projects\student_dasbord_management\index.php -->
<?php
    if($login){
    echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> You are logged in
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div> ';
    }
    if($showError){
    echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> '. $showError.'
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div> ';
    }
  ?>

  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <form id="submitid" class="sign-in-form" action="\student_dasbord_management\index.php" method="post">
          <h2 class="title">Sign In</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <!-- <input id="usernameid" type="text" placeholder="username" /> -->
            <!-- <label for="username">Username</label> -->
            <input type="text" class="form-control" id="usernameid" name="username" placeholder="username" aria-describedby="emailHelp">
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <!-- <label for="password">Password</label> -->
            <!-- <input id="passwordid" type="password" name="passwo" placeholder="password" /> -->
            <input type="password" placeholder="password" class="form-control" id="passwordid" name="password">
          </div>
          <!-- <button id="submitid" class="btn solid" onclick="validateLogin()" >Login</button> -->
          <input type="submit" value="Login" class="btn solid" />

          <p class="social-text">Or contect to your collage for log in info</p>
        </form>

      </div>
    </div>
    <div class="panels-container">

      <div class="panel left-panel">
        <div class="content">
          <h3>New here?</h3>
          <p>Welcome to the Student Dashboard. Access grades, assignments, and resources. Login now to stay updated on
            your academic journey!</p>
          <button class="btn transparent" id="sign-in-btn" onclick="login()">log in</button>
        </div>
        <img src="" class="image" alt="">
      </div>
    </div>
  </div>

  <!-- <script src="signin.js">  </script> -->
</body>

</html>