<?php
include "config.php";
include "backend/subdivide.php"

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
        <input type="hidden" name="id" value="<?php echo $id; ?>"><br>
        
        <!-- Display the existing first part of lot_number and allow editing the second part -->
        <label for="lot_info">Lot Info:</label><br>
        <input type="text" id="lot_number_suffix" name="lot_number_suffix" value="<?php echo $row['lot_number']; ?>" readonly> <!-- Display existing first part -->
        - <input type="text" id="lot_info" name="lot_info" required><br> <!-- Allow editing second part -->
        
        <label for="applicant_name">Applicant Name:</label><br>
        <input type="text" id="applicant_name" name="applicant_name" required><br>
        <label for="applicant_no">Applicant No:</label><br>
        <input type="text" id="applicant_no" name="applicant_no" required><br>
        <label for="date_filed">Date Filed:</label><br>
        <input type="date" id="date_filed" name="date_filed" required><br>
        <label for="date_approved">Approved Date:</label><br>
        <input type="date" id="date_approved" name="date_approved" required><br>
        <label for="area">Area</label><br>
        <input type="text" id="area" name="area" required><br>
        <label for="remarks">Remarks</label><br>
        <select id="remarks" name="remarks" required>
            <option value="untitled">Untitled</option>
            <option value="titled">Titled</option>
        </select><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
