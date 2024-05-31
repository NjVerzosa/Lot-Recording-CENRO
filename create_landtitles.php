<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Data for Land Titles</title>
</head>
<body>
    <h1>Upload Data for Land Titles</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="lot_number">Lot Number:</label><br>
        <input type="text" id="lot_number" name="lot_number" required><br>

        <label for="application_no">Application Number:</label><br>
        <input type="number" id="application_no" name="application_no" required><br>

        <label for="date_filed">Date Filed:</label><br>
        <input type="date" id="date_filed" name="date_filed" required><br>

        <label for="applicant_name">Applicant Name:</label><br>
        <input type="text" id="applicant_name" name="applicant_name" required><br>

        <label for="date_approved">Date Approved:</label><br>
        <input type="date" id="date_approved" name="date_approved" required><br>

        <label for="area">Area:</label><br>
        <input type="text" id="area" name="area" required><br>

        <label for="location">Location:</label><br>
        <input type="text" id="location" name="location"><br>

        <label for="remarks">Remarks</label><br>
        <select id="remarks" name="remarks" required>
            <option value="untitled">Untitled</option>
            <option value="titled">Titled</option>
        </select><br><br>

        <input type="submit" value="Submit">
    </form>

    <?php
    include "config.php";

    // Define variables and initialize with empty values
    $lot_number = $application_no = $date_filed = $applicant_name = $date_approved = $area = $location = $remarks = "";
    $lot_number_err = $application_no_err = $date_filed_err = $applicant_name_err = $date_approved_err = $area_err = "";

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate lot number
        if (empty(trim($_POST["lot_number"]))) {
            $lot_number_err = "Please enter the lot number.";
        } else {
            $lot_number = trim($_POST["lot_number"]);
        }

        // Validate application number
        if (empty(trim($_POST["application_no"]))) {
            $application_no_err = "Please enter the application number.";
        } else {
            $application_no = trim($_POST["application_no"]);
        }

        // Validate date filed
        if (empty(trim($_POST["date_filed"]))) {
            $date_filed_err = "Please enter the date filed.";
        } else {
            $date_filed = trim($_POST["date_filed"]);
        }

        // Validate applicant name
        if (empty(trim($_POST["applicant_name"]))) {
            $applicant_name_err = "Please enter the applicant name.";
        } else {
            $applicant_name = trim($_POST["applicant_name"]);
        }

        // Validate date approved
        if (empty(trim($_POST["date_approved"]))) {
            $date_approved_err = "Please enter the date approved.";
        } else {
            $date_approved = trim($_POST["date_approved"]);
        }

        // Validate area
        if (empty(trim($_POST["area"]))) {
            $area_err = "Please enter the area.";
        } else {
            $area = trim($_POST["area"]);
        }

        // Check if all input fields are filled without errors
        if (empty($lot_number_err) && empty($application_no_err) && empty($date_filed_err) && empty($applicant_name_err) && empty($date_approved_err) && empty($area_err)) {
            // Prepare an insert statement
            $sql = "INSERT INTO land_titles (lot_number, application_no, date_filed, applicant_name, date_approved, area, location, remarks) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sissssss", $param_lot_number, $param_application_no, $param_date_filed, $param_applicant_name, $param_date_approved, $param_area, $param_location, $param_remarks);

                // Set parameters
                $param_lot_number = $lot_number;
                $param_application_no = $application_no;
                $param_date_filed = $date_filed;
                $param_applicant_name = $applicant_name;
                $param_date_approved = $date_approved;
                $param_area = $area;
                $param_location = $_POST["location"] ?? null; // If location is not provided, set it to null
                $param_remarks = $_POST["remarks"] ?? null; // If remarks are not provided, set them to null

                // Attempt to execute the prepared statement
                if (mysqli_stmt_execute($stmt)) {
                    // Redirect to success page
                    header("location: index.php");
                    exit();
                } else {
                    echo "Something went wrong. Please try again later.";
                }

                // Close statement
                mysqli_stmt_close($stmt);
            }
        }
    }

    // Close connection
    mysqli_close($conn);
    ?>
</body>
</html>
