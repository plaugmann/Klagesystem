<?php


class WebHelper
{



    function getConnection() {
        $servername = "kili05.dk.mysql";
        $username = "kili05_dkklagesystem";
        $password = "Kongenr1finke";
        $dbname = "kili05_dkklagesystem";
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    function login($username, $password) {
        // Prepare a select statement
        $sql = "SELECT id, username, name, role, password FROM Users WHERE username = ? and password = PASSWORD(?)";
        $errorMsg = $sql;
        $conn = $this->getConnection();

        if($stmt = mysqli_prepare($conn, $sql)){
                     $errorMsg = "Got in";
                     // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

                     // Set parameters
                     $param_username = $username;
                     $param_password = $password;

                     // Attempt to execute the prepared statement
                     if(mysqli_stmt_execute($stmt)){
                         // Store result
                         mysqli_stmt_store_result($stmt);

                         // Check if username exists, if yes then verify password
                         if(mysqli_stmt_num_rows($stmt) == 1){
                             // Bind result variables
                             mysqli_stmt_bind_result($stmt, $id, $username, $name, $role, $hashed_password);
                             if(mysqli_stmt_fetch($stmt)){
                                     session_start();

                                     // Store data in session variables
                                     $_SESSION["loggedin"] = true;
                                     $_SESSION["user.id"] = $id;
                                     $_SESSION["user.username"] = $username;
                                     $_SESSION["user.name"] = $name;
                                     $_SESSION["user.role"] = $role;
                                     $_SESSION['timestamp'] = time();


                                 // Redirect user to welcome page
                                     $errorMsg = "Sucess";
                                     if ($role === "Admin")
                                        header("location: admin/");
                                     else
                                         header("location: index.php");
                                     exit;
                             }
                         } else{
                             // Display an error message if username doesn't exist
                             $errorMsg = "No account found with that username and/or password.";
                         }
                     } else{
                         $errorMsg = "Oops! Something went wrong. Please try again later.";

                         echo "Oops! Something went wrong. Please try again later.";
                     }

                     // Close statement
                     mysqli_stmt_close($stmt);
                 }


        return  $errorMsg;
    }

    function sendEmail($to) {
        $subject = 'Test fra hjemmeside';
        $message = 'Kongen er en finke';
        $headers = 'From: Klagesystemet <klagesystem@kili05.dk>' . "\r\n" .
            'Reply-To: klagesystem@kili05.dk' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        mail($to, $subject, $message, $headers);
        echo "<p>Mail sent.</p>";
    }
}

