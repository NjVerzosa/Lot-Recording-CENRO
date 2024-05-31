<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form submission
    $id = $_POST["id"];
    $date_filed = $_POST["date_filed"];
    $applicant_name = $_POST["applicant_name"];
    $area = $_POST["area"];
    $application_no = $_POST["application_no"];
    $date_approved = $_POST["date_approved"];
    $lot_info = $_POST["lot_info"];
    $lot_number_suffix = $_POST["lot_number_suffix"];
    $remarks = $_POST["remarks"];
    
    // Check if there is a record in subdivided_titles with the same land_title_id
    $check_sql = "SELECT position FROM subdivided_titles WHERE subdivided_to = $id";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        // If a record exists, use the position from the subdivided_titles table
        $check_row = mysqli_fetch_assoc($check_result);
        $new_position = $check_row["position"] + 1;
    } else {
        // If no record exists, use the position from the land_titles table
        $get_position_sql = "SELECT position FROM subdivided_titles WHERE id = $id";
        $get_position_result = mysqli_query($conn, $get_position_sql);
        $get_position_row = mysqli_fetch_assoc($get_position_result);
        $new_position = $get_position_row["position"] + 1;
    }
    
    // Insert the new subdivided record into subdivided_titles table
    $insert_sql = "INSERT INTO subdivided_titles (lot_number, application_no, date_filed, date_approved, applicant_name, area, location, remarks, position, subdivided_to)
            SELECT CONCAT('$lot_info', ', ', '$lot_number_suffix'), '$application_no', '$date_filed', '$date_approved', '$applicant_name', '$area', location, '$remarks', $new_position, $id
            FROM subdivided_titles
            WHERE id = $id";
    
    if (mysqli_query($conn, $insert_sql)) {
        // Update the isSubdivide column in the subtitles table to 1
        $update_sql = "UPDATE subdivided_titles SET status = 1 WHERE id = $id";
        if (mysqli_query($conn, $update_sql)) {
            echo "Record subdivided successfully";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
        // Redirect to index.php after successful insertion and update
        header("Location: sub_history.php?cid=" . $_POST['id']);
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
$sql = "SELECT * FROM subdivided_titles WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subdivide Land</title>
</head>
<body>
    <h1>Subdivide Land</h1>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <?php 
        $lot_number_parts = explode(',', str_replace(' ', '', $row["lot_number"]));

        ?>
        <!-- Display the existing first part of lot_number and allow editing the second part -->
        <label for="lot_info">Lot Info:</label><br>
        <input type="text" id="lot_info" name="lot_info" value="<?php echo $lot_number_parts[0]; ?>" readonly> <!-- Display existing first part -->
        - <input type="text" id="lot_number_suffix" name="lot_number_suffix" required><br> <!-- Allow editing second part -->
        
        <label for="applicant_name">Applicant Name:</label><br>
        <input type="text" id="applicant_name" name="applicant_name" required><br>

        <label for="applicant_name">Application No</label><br>
        <input type="text" id="application_no" name="application_no" required><br>

        <label for="date_filed">Date Filed:</label><br>
        <input type="date" id="date_filed" name="date_filed" required><br>

        <label for="date_filed">Date Approved:</label><br>
        <input type="date" id="date_approved" name="date_approved" required><br>

        <label for="applicant_name">Area</label><br>
        <input type="text" id="area" name="area" required><br>

        <label for="remarks">Remarks</label><br>
        <select id="remarks" name="remarks" required>
            <option value="untitled">Untitled</option>
            <option value="titled">Titled</option>
        </select><br>

        <br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
