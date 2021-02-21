<?php


class Property
{
    public int $id = 0;
    public string $zip = "";
    public string $city = "";
    public string $road = "";
    public string $houseNo = "";
    public string $dinGeoLink = "";
    public string $residentName = "";
    public string $residentCVR = "";
    public int $valuation = -1;
    public int $coverage = -1;
    public string $propUsage = PropertyUsage::FABRIK;
    public string $status = PropertyStatus::WAITING;
    public int $responsible = -1;


    public function __construct(int $id, string $zip, string $city, string $road, string $houseNo, string $dinGeoLink, string $residentName, string $residentCVR, int $valuation, int $coverage, string $propUsage, string $status, int $responsible ) {
        $this->id = $id;
        $this->zip = $zip;
        $this->city = $city;
        $this->road = $road;
        $this->houseNo = $houseNo;
        $this->dinGeoLink = $dinGeoLink;
        $this->residentName = $residentName;
        $this->residentCVR = $residentCVR;
        $this->valuation = $valuation;
        $this->coverage = $coverage;
        $this->propUsage = $propUsage;
        $this->status = $status;
        $this->responsible = $responsible;
    }

    public static function Load(int $id): Property {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "SELECT ID, Zip, City, Road, HouseNo, DinGeoLink, ResidentName, ResidentCVR, Valuation, Coverage, PropUsage, Status, Responsible FROM Properties WHERE ID = ?";
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
                    mysqli_stmt_bind_result($stmt, $id, $zip, $city,  $road, $houseNo,  $dinGeoLink, $residentName, $residentCVR,  $valuation, $coverage,$propUsage, $status,  $responsible);
                    if (mysqli_stmt_fetch($stmt)) {
                        return new Property((int) $id, $zip, $city, $road, $houseNo, $dinGeoLink, $residentName, $residentCVR, (int) $valuation, (int) $coverage, PropertyUsage::FromValue($propUsage), PropertyStatus::FromValue($status), (int) $responsible);
                    }
                }
            }
        }
    }

    public function Insert() {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "INSERT INTO Properties (ID, Zip, City, Road, HouseNo, DinGeoLink, ResidentName, ResidentCVR, Valuation, Coverage, PropUsage, Status, Responsible) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";

        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "", $param_Zip, $param_City, $param_Road, $param_HouseNo, $param_DinGeoLink, $param_ResidenceName, $param_ResidentCVR, $param_Valuation, $param_Coverage, $param_PropUsage, $param_Status, $param_Responsible);
            $param_Zip = $this->zip;
            $param_City = $this->city;
            $param_Road = $this->road;
            $param_HouseNo= $this->houseNo;
            $param_DinGeoLink = $this->dinGeoLink;
            $param_ResidenceName= $this->residentName;
            $param_ResidentCVR= $this->residentCVR;
            $param_Valuation= $this->valuation;
            $param_Coverage= $this->coverage;
            $param_PropUsage = $this->propUsage;
            $param_Status = $this->status;
            $param_Responsible = $this->responsible;

            $stmt->execute();
            $this->id = $stmt->insert_id;
        }
        $conn->close();
        return $this->id;
    }

    public function Save(){
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "UPDATE Properties SET Zip = ?, City = ?, Road = ?, HouseNo = ?, DinGeoLink = ?, ResidentName = ?, ResidentCVR = ?, Valuation = ?, Coverage = ?, PropUsage = ?, Status = ?, Responsible = ? WHERE ID = ?";
        $affectedRows = -1;
        if($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssssssiissii",$param_Zip, $param_City, $param_Road, $param_HouseNo, $param_DinGeoLink, $param_ResidenceName, $param_ResidentCVR, $param_Valuation, $param_Coverage, $param_PropUsage, $param_Status, $param_Responsible, $param_Id);

            $param_Zip = $this->zip;
            $param_City = $this->city;
            $param_Road = $this->road;
            $param_HouseNo= $this->houseNo;
            $param_DinGeoLink = $this->dinGeoLink;
            $param_ResidenceName= $this->residentName;
            $param_ResidentCVR= $this->residentCVR;
            $param_Valuation= $this->valuation;
            $param_Coverage= $this->coverage;
            $param_PropUsage = $this->propUsage;
            $param_Status = $this->status;
            $param_Responsible = $this->responsible;
            $param_Id = $this->id;

            $stmt->execute();

            $affectedRows = $stmt->affected_rows;
        }

        $conn->close();
        return $affectedRows;
    }
    public static function deleteProperty($id) {
        $helper = new WebHelper();
        $conn = $helper->getConnection();
        $sql = "DELETE FROM Properties WHERE ID = " . $id;

        if ($conn->query($sql) === TRUE) {
            $conn->close();
            return "True";
        } else {
            return "Error deleting record: " . $conn->error;
        }

    }

}

class PropertyStatus {
    const WAITING = "Waiting";
    const IN_PROGRESS = "In_Progress";
    const CANCELED = "Canceled";
    const COMPLETE = "Complete";

    public static function FromValue($value) {
        switch ($value) {
            case "Waiting" :
                return PropertyStatus::WAITING;
            case "In_Progress" :
                return PropertyStatus::IN_PROGRESS;
            case "Canceled" :
                return PropertyStatus::CANCELED;
            case "Complete" :
                return PropertyStatus::COMPLETE;
        }
        return PropertyStatus::WAITING;
    }
}
class PropertyUsage
{
    const ERHVERVSEJENDOM = "Erhvervsejendom af speciel karakter";
    const FABRIK_PAA_FREMMED = "Fabrik og lager på fremmed grund.";
    const FABRIK = "Fabrik og lager.";
    const PRIVAT = "Privat institutions- og serviceejendom.";
    const FORRETNING_PAA_FREMMED = "Ren forretning på fremmed grund.";
    const FORRETNING = "Ren forretning.";
    const STATSEJENDOM = "Statsejendom (Bebygget).";

    public static function FromValue($value)
    {
        switch ($value) {
            case "Erhvervsejendom af speciel karakter" :
                return PropertyUsage::ERHVERVSEJENDOM;
            case "Fabrik og lager på fremmed grund." :
                return PropertyUsage::FABRIK_PAA_FREMMED;
            case "Fabrik og lager." :
                return PropertyUsage::FABRIK;
            case "Privat institutions- og serviceejendom." :
                return PropertyUsage::PRIVAT;
            case "Ren forretning på fremmed grund." :
                return PropertyUsage::FORRETNING_PAA_FREMMED;
            case "Ren forretning." :
                return PropertyUsage::FORRETNING;
            case "Statsejendom (Bebygget)." :
                return PropertyUsage::STATSEJENDOM;

        }
        return PropertyUsage::FABRIK;

    }

}