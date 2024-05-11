<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
    header("location: index.php");
    exit;
}

function sumofattemdance($roll_number, $tables){
    include 'src/_dbconnect.php';
    $result_array = array();

    foreach ($tables as $table_name) {

        $sql = "SHOW COLUMNS FROM $table_name";
        
        $result = mysqli_query($conn, $sql);
        if($result===false){
            die ("Connection failed: ");
        }
        $allowed_columns = array("jan_attendance", "feb_attendance", "march_attendance", "aprail_attendance" , "may_attendance" , "june_attendance" , "july_attendance" , "august_attendance" , "sept_attendance" , "nov_attendance" , "dec_attendance");
        $num = mysqli_num_rows($result);

        if ($num > 0) {
            // Array to store valid column names
            $valid_columns = array();

            // Process each row to check if column exists in allowed columns array
            while ($row = $result->fetch_assoc()) {
                $column_name = $row['Field'];
                if (in_array($column_name, $allowed_columns)) {
                    $valid_columns[] = $column_name;
                }
            }

            // Construct SQL query to sum up values of valid columns for the specified roll number
            if (!empty($valid_columns)) {
                $columns_str = implode(" + ", $valid_columns);
                $sql_sum = "SELECT SUM($columns_str) AS total_sum FROM $table_name WHERE roll_num = $roll_number";

                // Execute SQL query
                $result_sum = $conn->query($sql_sum);

                if ($result_sum === false) {
                    echo "Error executing query: " . $conn->error;

                } else {
                    if ($result_sum->num_rows > 0) {
                        // Output total sum for the specified roll number
                        $row_sum = $result_sum->fetch_assoc();
                        $total = $row_sum["total_sum"];
                        // echo $total . "<p> hi</p>";
                        $result_array[$table_name] = $total;
                    } else {
                        echo "No records found for Roll Number $roll_number";
                    }
                }
            } else {
                $result_array[$table_name] = "you are just ennorled" ;
            }
        } else {
            $result_array[$table_name] =  "you are not ennorled" ; 
        }
    }

    // Close connection
    $conn->close();
    return $result_array;

}

function progressper($prgs){
    // Progress value (between 0 and 1, where 1 represents 100% progress)
    $progress = $prgs - .05; // 20% progress

    // Calculate the circumference of the circle
    $radius = 36;
    $circumference = 2 * M_PI * $radius;

    // Calculate the dash array values for the progress
    $dasharrayValue = $progress * $circumference . " " . (1 - $progress) * $circumference;

    return $dasharrayValue;
}
function infogather($roll_num){
    include 'src/_dbconnect.php';
    $info = array();
    $sqla = "Select * from student_info_01 where roll_num='$roll_num'";
    $resulta = mysqli_query($conn, $sqla);
    $row = $resulta->fetch_assoc();
    $info['course'] = $row['course'];
    $info['DOB'] = $row['DOB'];
    $info['DOB'] = $row['DOB'];
    $info['contect'] = $row['contect'];
    $info['email'] = $row['email'];
    $info['address'] = $row['address'];
    return $info;
}

$roll_number = $_SESSION['username']; // Replace with the current user roll number
$information=infogather($roll_number);
$tables = array("sub1", "sub2", "sub3" , "sub4" , "sub5"); // Replace with the table names
$result_array = sumofattemdance($roll_number, $tables);
$total_a=123456;//replace with max /total attendance roll num
$total_atd = sumofattemdance($total_a, $tables);

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard management</title>
    <link rel="shortcut icon" href="./rec/logo.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
    // Progress value (between 0 and 1, where 1 represents 100% progress)
    $progress = 0.9; // 20% progress

    // Calculate the circumference of the circle
    $radius = 36;
    $circumference = 2 * M_PI * $radius;

    // Calculate the dash array values for the progress
    $dasharrayValue = $progress * $circumference . " " . (1 - $progress) * $circumference;
    ?>
    <header>
        <div class="logo">
            <img src="./rec/logo.png" alt="">
            <h2>N<span class="danger">I</span>T</h2>
            <h2><span class="danger">R</span>R</h2>
        </div>
        <div class="navbar">
            <a href="home.php" class="active">
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
            <a href="password.php">
                <span class="material-icons-sharp">password</span>
                <h3>Change Password</h3>
            </a>
            <a href="#">
                <span class="material-icons-sharp" onclick="">logout</span>
                <h3>Logout</h3>
            </a>
        </div>
        <div id="profile-btn">
            <span class="material-icons-sharp">person</span>
        </div>
        <!-- <div class="theme-toggler">
            <span class="material-icons-sharp active">light_mode</span>
            <span class="material-icons-sharp">dark_mode</span>
        </div>  -->
        
    </header>

    <div class="container">
        <aside>
            <div class="profile">
                <div class="top">
                    <div class="profile-photo">
                        <!-- <img src="./rec/profile-1.jpg" alt=""> -->
                        <img src="./rec/profile-1.jpg" alt="dp">
                    </div>
                    <div class="info">
                        <p>Hey, <b><?php echo $_SESSION['name'] ?></b> </p>
                        <small class="text-muted"> <i><?php echo $_SESSION['username'] ?></i></small>
                    </div>
                </div>
                <div class="about">
                    <h5>Course</h5>
                    <p><?php echo $information['course']; ?></p>
                    <h5>DOB</h5>
                    <p><?php echo $information['DOB']; ?></p>
                    <h5>Contact</h5>
                    <p><?php echo $information['contect']; ?></p>
                    <h5>Email</h5>
                    <p><?php echo $information['email']; ?></p>
                    <h5>Address</h5>
                    <p><?php echo $information['address']; ?></p>
                </div>
            </div>
        </aside>

        <main>
            <h1>Attendance</h1>
            <div class="subjects">
                <div class="eg">
                    <span class="material-icons-sharp">architecture</span>
                    <h3>Engineering Graphics</h3>
                    <h2>
                        <?php echo $result_array['sub1'] . " / " . $total_atd['sub1'] ?>
                    </h2>
                    <div class="progress">
                        <!-- <svg><circle cx="38" cy="38" r="36"></circle></svg> -->
                        <svg> <circle cx="42" cy="42" r="36" fill="none" stroke="blue" stroke-width="10" 
                        stroke-dasharray="<?php echo progressper($result_array['sub1'] / $total_atd['sub1']); ?>" 
                        stroke-linecap="round" /> </svg>

                        <div class="number"><p>
                            <?php echo intval($result_array['sub1'] / $total_atd['sub1'] * 100) . " %" ?>
                        </p></div>
                    </div>
                    <small class="text-muted">Last 24 Hours</small>
                </div>
                <div class="mth">
                    <span class="material-icons-sharp">functions</span>
                    <h3>Mathematical Engineering</h3>
                    <h2><?php echo $result_array['sub2'] . " / " . $total_atd['sub2'] ?></h2>
                    <div class="progress">
                        <!-- <svg><circle cx="38" cy="38" r="36"></circle></svg> -->
                        <svg> <circle cx="42" cy="42" r="36" fill="none" stroke="blue" stroke-width="10" 
                        stroke-dasharray="<?php echo progressper($result_array['sub2'] / $total_atd['sub2']); ?>" 
                        stroke-linecap="round" /> </svg>

                        <div class="number"><p>
                            <?php echo intval($result_array['sub2'] / $total_atd['sub2'] * 100) . " %" ?>
                        </p></div>
                    </div>
                    <small class="text-muted">updated before 24 Hours</small>
                </div>
                <div class="cs">
                    <span class="material-icons-sharp">computer</span>
                    <h3>Computer Architecture</h3>
                    <h2><?php echo $result_array['sub3'] . " / " . $total_atd['sub3'] ?></h2>
                    <div class="progress">
                        <!-- <svg><circle cx="38" cy="38" r="36"></circle></svg> -->
                        <svg> <circle cx="42" cy="42" r="36" fill="none" stroke="blue" stroke-width="10" 
                        stroke-dasharray="<?php echo progressper($result_array['sub3'] / $total_atd['sub3']); ?>" 
                        stroke-linecap="round" /> </svg>

                        <div class="number"><p>
                            <?php echo intval($result_array['sub3'] / $total_atd['sub3'] * 100) . " %" ?>
                        </p></div>
                    </div>
                    <small class="text-muted">Last 24 Hours</small>
                </div>
                <div class="cg">
                    <span class="material-icons-sharp">dns</span>
                    <h3>Database Management</h3>
                    <h2><?php echo $result_array['sub4'] . " / " . $total_atd['sub4'] ?></h2>
                    <div class="progress">
                        <!-- <svg><circle cx="38" cy="38" r="36"></circle></svg> -->
                        <svg> <circle cx="42" cy="42" r="36" fill="none" stroke="blue" stroke-width="10" 
                        stroke-dasharray="<?php echo progressper($result_array['sub4'] / $total_atd['sub4']); ?>" 
                        stroke-linecap="round" /> </svg>

                        <div class="number"><p>
                            <?php echo intval($result_array['sub4'] / $total_atd['sub4'] * 100) . " %" ?>
                        </p></div>
                    </div>
                    <small class="text-muted">Last 24 Hours</small>
                </div>
                <div class="net">
                    <span class="material-icons-sharp">router</span>
                    <h3>Network Security</h3>
                    <h2><?php echo $result_array['sub5'] . " / " . $total_atd['sub5'] ?></h2>
                    <div class="progress">
                        <!-- <svg><circle cx="38" cy="38" r="36"></circle></svg> -->
                        <svg> <circle cx="42" cy="42" r="36" fill="none" stroke="blue" stroke-width="10" 
                        stroke-dasharray="<?php echo progressper($result_array['sub5'] / $total_atd['sub5']); ?>" 
                        stroke-linecap="round" /> </svg>

                        <div class="number"><p>
                            <?php echo intval($result_array['sub5'] / $total_atd['sub5'] * 100) . " %" ?>
                        </p></div>
                    </div>
                    <small class="text-muted">Last 24 Hours</small>
                </div>
            </div>

            <div class="timetable" id="timetable">
                <div>
                    <span id="prevDay">&lt;</span>
                    <h2>Today's Timetable</h2>
                    <span id="nextDay">&gt;</span>
                </div>
                <span class="closeBtn" onclick="timeTableAll()">X</span>
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Room No.</th>
                            <th>Subject</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </main>

        <div class="right">
            <div class="announcements">
                <h2>Announcements</h2>
                <div class="updates">
                    <div class="message">
                        <p> <b>Academic</b> Summer training internship with Live Projects.</p>
                        <small class="text-muted">2 Minutes Ago</small>
                    </div>
                    <div class="message">
                        <p> <b>Co-curricular</b> Global internship oportunity by Student organization.</p>
                        <small class="text-muted">10 Minutes Ago</small>
                    </div>
                    <div class="message">
                        <p> <b>Examination</b> Instructions for Mid Term Examination.</p>
                        <small class="text-muted">Yesterday</small>
                    </div>
                </div>
            </div>

            <div class="leaves">
                <h2>Teachers on leave</h2>
                <div class="teacher">
                    <div class="profile-photo"><img src="./rec/profile-2.jpeg" alt=""></div>
                    <div class="info">
                        <h3>The Professor</h3>
                        <small class="text-muted">Full Day</small>
                    </div>
                </div>
                <div class="teacher">
                    <div class="profile-photo"><img src="./rec/profile-3.jpg" alt=""></div>
                    <div class="info">
                        <h3>Lisa Manobal</h3>
                        <small class="text-muted">Half Day</small>
                    </div>
                </div>
                <div class="teacher">
                    <div class="profile-photo"><img src="./rec/profile-4.jpg" alt=""></div>
                    <div class="info">
                        <h3>Himanshu Jindal</h3>
                        <small class="text-muted">Full Day</small>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="timeTable.js"></script>
    <script src="app.js"></script>
</body>
</html>