

<?php
$server = "server";
$username = "username";
$password = ""; //add password
$database = ""; //add database name

$conn = mysqli_connect($server, $username, $password, $database);
if (!$conn){
    
    die("Error". mysqli_connect_error());
}
// else{
//     echo "success";
// }
?>


