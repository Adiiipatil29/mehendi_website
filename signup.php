<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "user";

// Create connection
$conn = mysqli_connect($servername, $db_username, $db_password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<?php
$username_err = $password_err = $confirm_password_err = $phone_err = $message_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';
    
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }
    
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "Please enter a phone number.";
    } else {
        $phone = trim($_POST["phone"]);
    }
    
    if (!empty(trim($_POST["message"]))) {
        $message = trim($_POST["message"]);
    } else {
        $message = ""; 
    }
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($phone_err)) {
        $sql = "SELECT * FROM user WHERE username = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 0) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO user (username, password, phone, message, date) VALUES (?, ?, ?, ?, current_timestamp())";

                    if ($stmt = mysqli_prepare($conn, $sql)) {
                        mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_phone, $param_message);
                        $param_username = $username;
                        $param_password = $hash;
                        $param_phone = $phone;
                        $param_message = $message;

                        if (mysqli_stmt_execute($stmt)) {
                            echo "<h3 style='color:green'>Signup Successful</h3>";
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    }
                } else {
                    echo "<h3 style='color:red'>Username not available</h3>";
                }
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            background-image: url('background img2.jpg'); /* Replace 'background_image.jpg' with the path to your image */
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif; /* Optional: Change font-family */
        }

        /* Style for the form */
        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.7); /* Optional: Add background color with opacity */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Optional: Add box shadow for better visibility */
        }

        /* Style for the input fields and labels */
        input[type="text"],
        input[type="password"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        label {
            font-weight: bold;
        }

        /* Style for the submit button */
        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Style for error messages */
        span {
            color: red;
            font-size: 12px;
        }

        /* Style for the link */
        p a {
            color: blue;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h2>Sign Up</h2>
    <form action="" method="post">
        <div>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username">
            <span><?php echo $username_err; ?></span>
        </div>
        <div>
            <label for="password">Password:</label><br>
            <input type="password" name="password">
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" name="confirm_password">
            <span><?php echo $confirm_password_err; ?></span>
        </div>
        <div>
            <label for="phone">Phone Number:</label><br>
            <input type="tel" name="phone">
            <span><?php echo $phone_err; ?></span>
        </div>
        <div>
            <label for="message">Message:</label><br>
            <input type="text" name="message">
            <span><?php echo $message_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Sign Up">
        </div>
        <p>Already have an account? <a href="signin.php">Signin here</a>.</p>
    </form>
</body>

</html>
