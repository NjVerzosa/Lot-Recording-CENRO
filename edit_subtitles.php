<?php
// Include config.php
include "config.php";

// Define variables and initialize with empty values
$date_filed = $applicant_name = $area = $remarks = $date_approved = $application_no = $lot_info = "";
$id = $_GET["id"];

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize ID parameter
    $id = trim($_POST["id"]);
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    // Retrieve form data
    $date_filed = $_POST["date_filed"];
    $applicant_name = $_POST["applicant_name"];
    $area = $_POST["area"];
    $remarks = $_POST["remarks"];
    $date_approved = $_POST["date_approved"];
    $application_no = $_POST["application_no"];
    $lot_info = trim($_POST["lot_info"]); // Extracted first part of lot_number
    $lot_number_suffix = trim($_POST["lot_number_suffix"]); // Updated second part of lot_number

    // Combine lot_info and lot_number_suffix to form updated lot_number
    $updated_lot_number = $lot_info . ", " . $lot_number_suffix;

    // Prepare SQL statement to update subdivided_titles record
    $sql = "UPDATE subdivided_titles SET date_filed = ?, applicant_name = ?, area = ?, remarks = ?, date_approved = ?, application_no = ?, lot_number = ? WHERE id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "sssssssi", $date_filed, $applicant_name, $area, $remarks, $date_approved, $application_no, $updated_lot_number, $id);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "Record updated successfully.";
            // Redirect based on land_title_id and subdivided_to
            $query = "SELECT land_title_id, subdivided_to FROM subdivided_titles WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $land_title_id, $subdivided_to);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
            
            if ($land_title_id == null) {
                header("Location: sub_history.php?cid=$subdivided_to");
            } elseif ($subdivided_to == null) {
                header("Location: history.php?id=$land_title_id");
            } else {
                echo "Neither land_title_id nor subdivided_to is null.";
            }
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
} else {
    // Prepare a select statement
    $sql = "SELECT * FROM subdivided_titles WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Get result set
            $result = mysqli_stmt_get_result($stmt);

            // Fetch data
            if ($row = mysqli_fetch_assoc($result)) {
                $date_filed = $row["date_filed"];
                $applicant_name = $row["applicant_name"];
                $area = $row["area"];
                $remarks = $row["remarks"];
                $date_approved = $row["date_approved"];
                $application_no = $row["application_no"];
                $lot_number = $row["lot_number"];
                $status = $row["status"];
                // Extract first part of lot_number and update second part
                $lot_parts = explode(", ", $lot_number);
                $lot_info = $lot_parts[0]; // First part of lot_number
                $lot_number_suffix = $lot_parts[1]; // Second part of lot_number
            } else {
                echo "No record found with that ID.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subtitles</title>
</head>
<body>
    <h1>Edit Subtitles</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label for="date_filed">Date Filed:</label><br>
        <input type="date" id="date_filed" name="date_filed" value="<?php echo $date_filed; ?>" required><br>

        <label for="applicant_name">Applicant Name:</label><br>
        <input type="text" id="applicant_name" name="applicant_name" value="<?php echo $applicant_name; ?>" required><br>

        <label for="area">Area:</label><br>
        <input type="text" id="area" name="area" value="<?php echo $area; ?>" required><br>

        <!-- Display the existing first part of lot_number and allow editing the second part -->
        <label for="lot_info">Lot Info:</label><br>
        <input type="text" id="lot_info" name="lot_info" value="<?php echo $lot_info; ?>" readonly> <!-- Display existing first part -->
        - <input type="text" id="lot_number_suffix" name="lot_number_suffix" value="<?php echo $lot_number_suffix; ?>" required><br> <!-- Allow editing second part -->

        <?php if ($status == 1): ?>
            <!-- If status is 2 or 3, make remarks field read-only -->
            <label for="remarks">Remarks:</label><br>
            <input type="text" id="remarks" name="remarks" value="<?php echo $remarks; ?>" readonly><br>
        <?php else: ?>
            <!-- If status is not 2 or 3, allow editing of remarks -->
            <label for="remarks">Remarks:</label><br>
            <select id="remarks" name="remarks" required>
                <option value="untitled" <?php if ($remarks == "untitled") echo "selected"; ?>>Untitled</option>
                <option value="titled" <?php if ($remarks == "titled") echo "selected"; ?>>Titled</option>
            </select><br>
        <?php endif; ?>

        <label for="date_approved">Approved Date:</label><br>
        <input type="date" id="date_approved" name="date_approved" value="<?php echo $date_approved; ?>" required><br>

        <label for="application_no">Application No:</label><br>
        <input type="text" id="application_no" name="application_no" value="<?php echo $application_no; ?>" required><br><br>

        <input type="submit" value="Submit">
    </form>

    
</body>
</html>
