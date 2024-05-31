<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form submission
    $id = $_POST["id"];
    $applicant_name = $_POST["applicant_name"];
    $applicant_no = $_POST["applicant_no"];
    $date_filed = $_POST["date_filed"];
    $date_approved = $_POST["date_approved"];
    $remarks = $_POST["remarks"];
    $area = $_POST["area"];
    $lot_info = $_POST["lot_info"];
    $lot_number_suffix = $_POST["lot_number_suffix"];
    
    // Check if there is a record in subdivided_titles with the same land_title_id
    $check_sql = "SELECT position FROM subdivided_titles WHERE land_title_id = $id";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        // If a record exists, use the position from the subdivided_titles table
        $check_row = mysqli_fetch_assoc($check_result);
        $new_position = $check_row["position"] + 1;
    } else {
        // If no record exists, use the position from the land_titles table
        $get_position_sql = "SELECT position FROM land_titles WHERE id = $id";
        $get_position_result = mysqli_query($conn, $get_position_sql);
        $get_position_row = mysqli_fetch_assoc($get_position_result);
        $new_position = $get_position_row["position"] + 1;
    }
    
    // Insert the new subdivided record into subdivided_titles table
    $insert_sql = "INSERT INTO subdivided_titles (lot_number, applicant_name, application_no, date_filed,area, location, remarks, position, land_title_id)
            SELECT CONCAT(lot_number, ', ', '$lot_info'), '$applicant_name', '$applicant_no', '$date_filed', '$area', location, '$remarks', $new_position, $id
            FROM land_titles
            WHERE id = $id";

    
    if (mysqli_query($conn, $insert_sql)) {
        // Update the isSubdivide column in the land_titles table to 1
        $update_sql = "UPDATE land_titles SET status = 1 WHERE id = $id";
        if (mysqli_query($conn, $update_sql)) {
            echo "Record subdivided successfully";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
        // Redirect to index.php after successful insertion and update
        header("Location: history.php?id=" . $id);
        exit;
    } else {
        echo "Error: " . $insert_sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
    exit;
}

// If the form is not submitted, show the form
$id = $_GET["id"];

// Get the current record details
$sql = "SELECT * FROM land_titles WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

?>