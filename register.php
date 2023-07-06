/*

  Setup JWT for me
  
*/

<?php
    if(isset($_POST["submit"])) {
        // Getting variables
        $name             = $_POST["name"];
        $email            = $_POST["email"];
        $password         = $_POST["password"];
        $confirm_password = $_POST["confirm-password"];
        $password_hash    = password_hash($password, PASSWORD_DEFAULT); // Hashing password
        require_once "database.php"; // Including database connection

        // Checking for errors
        if(empty($name) || empty($password) || empty($email) || empty($confirm_password)) {
            $errbox_2 = "<div class='alert-box'>All Fields are required!</div>";
        }
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errbox_3 = "<div class='alert-box'>Invalid Email</div>";
        }
        elseif (strlen($password) < 4 ) {
            $errbox_4 = "<div class='alert-box'>Password must be 4 characters long!</div>";
        }
        elseif ($password !== $confirm_password) {
            $errbox_5 = "<div class='alert-box'>Password did not match!</div>";
        }
        else {
            // Checking if the email already exists
            $sql = "SELECT * FROM r_users WHERE email = ?";
            $stmt = mysqli_stmt_init($conn);
            $perpareStmt = mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row_count = mysqli_num_rows($result);

            if ($row_count > 0) {
                $errbox_1 = "<div class='alert-box'>Email Already Exists!</div>";
            } else {
                // Inserting the user into the database
                $sql = "INSERT INTO r_users (name, email, password) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);
                $perpareStmt = mysqli_stmt_prepare($stmt, $sql);
                if ($perpareStmt) {
                    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $password_hash);
                    mysqli_stmt_execute($stmt);
                    $succbox_1 = "<div class='alert-box-succ'>You're Registered</div>";
                } else {
                    die("Something went wrong: " . mysqli_stmt_error($stmt));
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/form.css">
    <title>Register Form</title>
</head>
<body>
    <div class="header">
        <p>Register Form</p>
    </div>
    <div class="wrapper">
        <form method="post">
            <?php
                echo $succbox_1;
                echo $errbox_1;
                echo $errbox_2;
                echo $errbox_3;
                echo $errbox_4;
                echo $errbox_5;
            ?>
            <input type="text" placeholder="Name" autocomplete="off" name="name">
            <input type="text" placeholder="Email Address" autocomplete="off" name="email">
            <input type="password" placeholder="Password" autocomplete="off" name="password">
            <input type="password" placeholder="Confirm Password" autocomplete="off" name="confirm-password">
            <button type="submit" name="submit"><span></span>Register</button>
            <span>Already Have an Account? <a href="login.php">Login here!</a></span>
        </form>
    </div>
</body>
</html>
