<?php


class User
{
    public int $id = 0;
    public string $username = "";
    public ?string $password = null;
    public string $name = "";
    public string $email = "";
    public string $role = "User";//UserRole::User;

    public function __construct(int $id, string $username, ?string $password, string $name, string $email, string $role)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }

    public static function Load(int $id): User  {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "SELECT Username, Password, Name, Email, Role FROM Users WHERE ID = ?";
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
                    mysqli_stmt_bind_result($stmt,$username, $password, $name, $email, $role);
                    if (mysqli_stmt_fetch($stmt)) {
                        return new User((int) $id, $username, null, $name, $email, $role);
                    }
                }
            }
        }
    }

    public function Insert() : int {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "INSERT INTO Users (ID, Username, Password, name, email, role) VALUES (NULL, ?, PASSWORD(?), ?, ?, ?); ";

        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssss", $param_Username, $param_Password, $param_Name, $param_Email, $param_Role);
            $param_Username = $this->username;
            $param_Password = $this->password;
            $param_Name = $this->name;
            $param_Email = $this->email;
            $param_Role = $this->role;

            $stmt->execute();
            $this->id = $stmt->insert_id;
        }
        $conn->close();
        return $this->id;
    }

    public function Save() : int {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "UPDATE Users SET Username = ?, Password = PASSWORD(?), Name = ?, Email =?, Role = ? WHERE ID = ?";
        if ($this->password === null)
            $sql = "UPDATE Users SET Username = ?, Name = ?, Email =?, Role = ? WHERE ID = ?";
        $affectedRows = -1;
        if($stmt = mysqli_prepare($conn, $sql)) {
            if ($this->password === null) {
                mysqli_stmt_bind_param($stmt, "ssssi", $param_Username, $param_Name, $param_Email, $param_Role, $param_Id);
                $param_Username = $this->username;
                $param_Name = $this->name;
                $param_Email = $this->email;
                $param_Role = $this->role;
                $param_Id = $this->id;
            }
            else {
                mysqli_stmt_bind_param($stmt, "sssssi", $param_Username, $param_Password, $param_Name, $param_Email, $param_Role, $param_Id);
                $param_Username = $this->username;
                $param_Password = $this->password;
                $param_Name = $this->name;
                $param_Email = $this->email;
                $param_Role = $this->role;
                $param_Id = $this->id;
            }


            $stmt->execute();

            $affectedRows = $stmt->affected_rows;
        }

        $conn->close();
        return $affectedRows;
    }

    public static function deleteUser($id) : string {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "DELETE FROM Users WHERE ID = " . $id;

        if ($conn->query($sql) === TRUE) {
            $conn->close();
            return "True";
        } else {
            return "Error deleting record: " . $conn->error;
        }

    }
    public static function getUsers() {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "SELECT ID, name, role FROM Users order by role,name";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            $prevRole = "";
            while($row = $result->fetch_assoc()) {
                if ($prevRole != $row["role"]) {
                    switch ($row["role"]) {
                        case "Admin":
                            echo "<h3>Administratorer</h3>";
                            break;
                        case "Sales" :
                            echo "<h3>Normale brugere</h3>";
                            break;
                        case "User" :
                            echo "<h3>Superbrugere</h3>";
                            break;
                    }

                }
                $prevRole = $row["role"];
                echo "<li class='user".$row["role"] ."'><a href='editUser.php?id=" . $row["ID"]. "' title='Rediger denne bruger'>".$row["name"] ."</a>&nbsp;<a href='". $row["name"] ."#id=". $row["ID"]."' class='deleteButton' title='Slet denne bruger'><img src='delete_icon.png' title='Slet denne bruger'></a></li>";
            }
        } else {
            echo "<li><span color='red'>Ingen brugere</span></li> ";
        }
        $conn->close();

    }

}
/*
class UserRole
{
    const ADMIN = "Admin";
    const USER1 = "User";
    const SALES = "Sales";

    public static function FromValue($value) {
        switch ($value) {
            case "Admin" :
                return self::ADMIN;
            case "User" :
                return self::USER1;
            case "Sales" :
                return self::SALES;
        }
        return self::USER1;
    }
}*/