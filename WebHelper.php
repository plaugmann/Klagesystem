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

    function getFields() {
        $conn = $this->getConnection();
        $sql = "SELECT ID, name, status FROM Fields order by name";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<li><a href='editField.php?id=" . $row["ID"]. "' title='Rediger dette felt' class='field".$row["status"] ."'>".$row["name"] ."</a>&nbsp;<a href='". $row["name"] ."#id=". $row["ID"]."' class='deleteButton' title='Slet dette felt'><img src='delete_icon.png' title='Slet dette felt'></a></li>";
            }
        } else {
            echo "<li><span color='red'>0 results</span></li> ";
        }
        $conn->close();

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


    function deleteField($id) {
        $sql = "DELETE FROM Fields WHERE ID = " . $id;
        $conn = $this->getConnection();

        if ($conn->query($sql) === TRUE) {
            $conn->close();
            return "True";
        } else {
            return "Error deleting record: " . $conn->error;
        }

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

class Field {
    public int $id = 0;
    public string $name = "";
    public ?string $description =null;
    public ?string $autoFill = null;
    public string $status = FieldStatus::ACTIVE;

    public function __construct(int $id, string $name, ?string $description, ?string $autoFill, string $status) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->autoFill = $autoFill;
        $this->status = $status;
    }

    public static function Load(int $id): Field  {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "SELECT ID, Name, Description, AutoFill, Status FROM Fields WHERE ID = ?";
        if($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_ID);

            // Set parameters
            $param_ID = $id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if field exists
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $name, $description, $autoFill, $Status);
                    if (mysqli_stmt_fetch($stmt)) {
                        return new Field((int) $id, $name, $description, $autoFill, FieldStatus::FromValue($Status));
                    }
                }
            }
        }
    }

    public function Insert() {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "INSERT INTO Fields (ID, Name, Description, AutoFill, Status) VALUES (NULL, ?, ?, ?, ?); ";

        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssss", $param_Name, $param_Description, $param_AutoFill, $param_Status);
            $param_Name = $this->name;
            $param_Description = $this->description;
            $param_AutoFill = $this->autoFill;
            $param_Status = $this->status;

            $stmt->execute();
            $this->id = $stmt->insert_id;
        }
        $conn->close();
        return $this->id;
    }

    public function Save(){
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "UPDATE Fields SET Name = ?, Description = ?, AutoFill = ?, Status =? WHERE ID = ?";
        $affectedRows = -1;
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssi", $param_Name, $param_Description, $param_AutoFill, $param_Status, $param_Id);

            $param_Name = $this->name;
            $param_Description = $this->description;
            $param_AutoFill = $this->autoFill;
            $param_Status = $this->status;
            $param_Id = $this->id;

            $stmt->execute();

            $affectedRows = $stmt->affected_rows;
        }

        $conn->close();
        return $affectedRows;
    }

}

class FieldStatus {
    const ACTIVE = 'Active';
    const INACTIVE = 'Inactive';

    public static function FromValue($value) {
        switch ($value) {
            case "Active" :
                return FieldStatus::ACTIVE;
            case "Inactive" :
                return FieldStatus::INACTIVE;
        }
        return FieldStatus::ACTIVE;
    }
}

