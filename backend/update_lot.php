<?php
include "config.php";

if(isset($_GET["id"])){
    // Assuming $specific_id is the specific id you want to match
$specific_id = $_GET["id"]; // Get the id from the URL parameter

// Fetch the land title details for the specific id
$land_title_sql = "SELECT * FROM land_titles WHERE id = $specific_id";
$land_title_result = $conn->query($land_title_sql);
$land_title_row = $land_title_result->fetch_assoc();

// Fetch the subdivided titles for the specific land title id, including the applicant's name, sorted by position
$subdivided_titles_sql = "SELECT *
                          FROM subdivided_titles
                          WHERE land_title_id = $specific_id
                          AND subdivided_to IS NULL
                          ORDER BY position";
$subdivided_titles_result = $conn->query($subdivided_titles_sql);

// Check if the lot number of the land title row matches the lot number of each subdivided title row
while($row = $subdivided_titles_result->fetch_assoc()) {
    if($land_title_row["lot_number"] != $row["lot_number"]) {
        // Update the lot number in the subdivided_titles table
        $new_lot_number = $land_title_row["lot_number"];
        $subdivided_id = $row["id"];
        $update_lot_number_sql = "UPDATE subdivided_titles SET lot_number = '$new_lot_number' WHERE id = $subdivided_id";
        if ($conn->query($update_lot_number_sql) === TRUE) {
            // echo "Lot number updated successfully.";
        } else {
            // echo "Error updating lot number: " . $conn->error;
        }
    }
}
}else if(isset($_GET["cid"])){
    
}
?>
