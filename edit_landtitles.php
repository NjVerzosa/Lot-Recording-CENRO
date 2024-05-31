<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <h1>Index</h1>

    <?php
    // Include config.php
    include "config.php";

    // Define variables and initialize with empty values
    $lot_number = $application_no = $date_filed = $applicant_name = $date_approved = $area = $location = $remarks = "";

    // Process form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST"):
        // Validate and sanitize ID parameter
        $id = trim($_POST["id"]);
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        // Validate and sanitize other form inputs (you can add more validation logic here)

        // Retrieve form data
        $lot_number = $_POST["lot_number"];
        $application_no = $_POST["application_no"];
        $date_filed = $_POST["date_filed"];
        $applicant_name = $_POST["applicant_name"];
        $date_approved = $_POST["date_approved"];
        $area = $_POST["area"];
        $location = $_POST["location"];
        $remarks = $_POST["remarks"];

        // Prepare SQL statement to update land_titles record
        $sql = "UPDATE land_titles SET lot_number = ?, application_no = ?, date_filed = ?, applicant_name = ?, date_approved = ?, area = ?, location = ?, remarks = ? WHERE id = ?";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt):
            // Bind parameters to the prepared statement
            mysqli_stmt_bind_param($stmt, "sissssssi", $lot_number, $application_no, $date_filed, $applicant_name, $date_approved, $area, $location, $remarks, $id);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)):
                echo "Record updated successfully.";
                // Redirect to index.php after successful update
                header("Location: index.php");
                exit;
            else:
                echo "Error updating record: " . mysqli_error($conn);
            endif;

            // Close statement
            mysqli_stmt_close($stmt);
        endif;

        // Close connection
        mysqli_close($conn);
    else:
        // Retrieve the ID parameter from the URL
        $id = trim($_GET["id"]);
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        // Prepare a select statement
        $sql = "SELECT * FROM land_titles WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt):
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $id);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)):
                // Get result set
                $result = mysqli_stmt_get_result($stmt);

                // Fetch data
                if ($row = mysqli_fetch_assoc($result)):
                    $lot_number = $row["lot_number"];
                    $application_no = $row["application_no"];
                    $date_filed = $row["date_filed"];
                    $applicant_name = $row["applicant_name"];
                    $date_approved = $row["date_approved"];
                    $area = $row["area"];
                    $location = $row["location"];
                    $remarks = $row["remarks"];
                    $status = $row["status"];
                else:
                    echo "No record found with that ID.";
                endif;
            else:
                echo "Oops! Something went wrong. Please try again later.";
            endif;

            // Close statement
            mysqli_stmt_close($stmt);
        endif;

        // Close connection
        mysqli_close($conn);
    endif;
    ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <?php if ($status == 1): ?>
            <label for="lot_number">Lot Number:</label><br>
            <input type="text" id="lot_number" name="lot_number" value="<?php echo $lot_number; ?>" readonly>
        <?php else: ?>
            <label for="lot_number">Lot Number:</label><br>
            <input type="text" id="lot_number" name="lot_number" value="<?php echo $lot_number; ?>" required><br>
        <?php endif; ?>
        <br>

        <label for="application_no">Application Number:</label><br>
        <input type="number" id="application_no" name="application_no" value="<?php echo $application_no; ?>" required><br>

        <label for="date_filed">Date Filed:</label><br>
        <input type="date" id="date_filed" name="date_filed" value="<?php echo $date_filed; ?>" required><br>

        <label for="applicant_name">Applicant Name:</label><br>
        <input type="text" id="applicant_name" name="applicant_name" value="<?php echo $applicant_name; ?>" required><br>

        <label for="date_approved">Date Approved:</label><br>
        <input type="date" id="date_approved" name="date_approved" value="<?php echo $date_approved; ?>" required><br>

        <label for="area">Area:</label><br>
        <input type="text" id="area" name="area" value="<?php echo $area; ?>" required><br>
        

        <?php if ($status == 1): ?>
            <label for="location">Location:</label><br>
            <input type="text" id="location" name="location" value="<?php echo $location; ?>" readonly>
        <?php else: ?>
            <label for="location">Location:</label><br>
            <input type="text" id="location" name="location" value="<?php echo $location; ?>"><br><br>
        <?php endif; ?>
        <br>

        <label for="remarks">Remarks:</label><br>
        <?php if ($status == 1): ?>
            <input type="text" id="remarks" name="remarks" value="<?php echo $remarks; ?>" readonly>
            
        <?php else: ?>
            <!-- If status is not 1, allow editing of remarks -->
            <select id="remarks" name="remarks" required>
                <option value="untitled" <?php if ($remarks == 'untitled') echo 'selected'; ?>>Untitled</option>
                <option value="titled" <?php if ($remarks == 'titled') echo 'selected'; ?>>Titled</option>
            </select>
        <?php endif; ?><br><br>

        <input type="submit" value="Update">
    </form>
</body>
</html>
