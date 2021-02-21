<?php


class Field {
    public int $id = 0;
    public string $name = "";
    public ?string $description =null;
    public ?string $autoFill = null;
    public string $status = FieldStatus::ACTIVE;
    public string $renderGroup = RenderGroup::GROUP_A;
    public string $contentType = ContentType::SINGLE_TEXT;

    public function __construct(int $id, string $name, ?string $description, ?string $autoFill, string $status, string $renderGroup, string $contentType) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->autoFill = $autoFill;
        $this->status = $status;
        $this->renderGroup = $renderGroup;
        $this->contentType = $contentType;
    }

    public static function Load(int $id): Field  {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "SELECT ID, Name, Description, AutoFill, Status, RenderGroup, ContentType FROM Fields WHERE ID = ?";
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
                    mysqli_stmt_bind_result($stmt, $id, $name, $description, $autoFill, $Status, $renderGroup, $contentType);
                    if (mysqli_stmt_fetch($stmt)) {
                        return new Field((int) $id, $name, $description, $autoFill, FieldStatus::FromValue($Status), RenderGroup::FromValue($renderGroup), ContentType::FromValue($contentType));
                    }
                }
            }
        }
    }

    public function Insert() {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "INSERT INTO Fields (ID, Name, Description, AutoFill, Status, RenderGroup, ContentType) VALUES (NULL, ?, ?, ?, ?, ?, ?); ";

        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssss", $param_Name, $param_Description, $param_AutoFill, $param_Status, $param_RenderGroup, $param_ContentType);
            $param_Name = $this->name;
            $param_Description = $this->description;
            $param_AutoFill = $this->autoFill;
            $param_Status = $this->status;
            $param_RenderGroup = $this->renderGroup;
            $param_ContentType = $this->contentType;

            $stmt->execute();
            $this->id = $stmt->insert_id;
        }
        $conn->close();
        return $this->id;
    }

    public function Save(){
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "UPDATE Fields SET Name = ?, Description = ?, AutoFill = ?, Status =?, RenderGroup = ?, ContentType = ? WHERE ID = ?";
        $affectedRows = -1;
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssssi", $param_Name, $param_Description, $param_AutoFill, $param_Status, $param_RenderGroup, $param_ContentType, $param_Id);

            $param_Name = $this->name;
            $param_Description = $this->description;
            $param_AutoFill = $this->autoFill;
            $param_Status = $this->status;
            $param_RenderGroup = $this->renderGroup;
            $param_ContentType = $this->contentType;
            $param_Id = $this->id;

            $stmt->execute();

            $affectedRows = $stmt->affected_rows;
        }

        $conn->close();
        return $affectedRows;
    }
    public static function deleteField($id) {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "DELETE FROM Fields WHERE ID = " . $id;

        if ($conn->query($sql) === TRUE) {
            $conn->close();
            return "True";
        } else {
            return "Error deleting record: " . $conn->error;
        }

    }
    public static function getFields() {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
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
class RenderGroup {
    const GROUP_A = 'A';
    const GROUP_B = 'B';
    const GROUP_C = 'C';
    const GROUP_D = 'D';
    const GROUP_E = 'E';
    const GROUP_F = 'F';
    const GROUP_G = 'G';

    public static function FromValue($value) {
        switch ($value) {
            case "A" :
                return RenderGroup::GROUP_A;
            case "B" :
                return RenderGroup::GROUP_B;
            case "C" :
                return RenderGroup::GROUP_C;
            case "D" :
                return RenderGroup::GROUP_D;
            case "E" :
                return RenderGroup::GROUP_E;
            case "F" :
                return RenderGroup::GROUP_F;
            case "G" :
                return RenderGroup::GROUP_G;
        }
        return RenderGroup::GROUP_A;
    }
}

class ContentType {
    const NUMBER = 'Number';
    const SINGLE_TEXT = 'Single_Text';
    const MULTI_TEXT = 'Multi_Text';

    public static function FromValue($value) {
        switch ($value) {
            case "Number" :
                return ContentType::NUMBER;
            case "Single_Text" :
                return ContentType::SINGLE_TEXT;
            case "Multi_Text" :
                return ContentType::MULTI_TEXT;

        }
        return ContentType::SINGLE_TEXT;
    }
}