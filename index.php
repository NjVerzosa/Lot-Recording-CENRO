<?php
include "config.php";
include "backend/index.php";
// Fetch data from the database
$sql = "SELECT * FROM land_titles";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rowena</title>
    
</head>
<body>
    <a href="create_landtitles.php" rel="noopener noreferrer">Create New</a>
    <?php
    ?>

    <?php
    if ($result->num_rows > 0) {
        // Display the table if there are records
        echo "<table border='1'>
            <tr>
                <th>Lot Number</th>
                <th>Application</th>
                <th>Date Filed</th>
                <th>Applicant Name</th>
                <th>Area</th>
                <th>Location</th>
                <th>Approve Date</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>";
        // Output data of each row
        while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row["lot_number"] ?></td>
                <td><?= $row["application_no"] ?></td>
                <td><?= $row["date_filed"] ?></td>
                <td><?= $row["applicant_name"] ?></td>
                <td><?= $row["area"] ?></td>
                <td><?= $row["location"] ?></td>
                <td><?= $row["date_approved"] ?></td>
                <td>
                    <?php
                    // Check if the land is subdivided
                    if ($row["status"] == 2 || $row["remarks"] == "titled" ) {
                        echo "Titled";
                    } elseif ($row["status"] == 0) {
                        echo "Not Subdivided";
                    } else {
                        echo "<a href='history.php?id=".$row["id"]."'>View</a>";
                    }
                    ?>
                </td>
                <td>
                    <a href='edit_landtitles.php?id=<?= $row["id"] ?>'>Edit</a> | 
                    <a href='subdivide.php?id=<?= $row["id"] ?>' onclick='return confirmSubdivide("<?= $row["remarks"] ?>")'>Subdivide</a> | 
                    <a href='delete_landtitles.php?id=<?= $row["id"] ?>' onclick='return confirmDelete(<?= $row["status"] ?>)'>Delete</a>
                </td>
            </tr>
        <?php endwhile;
        echo "</table>";
    } else {
        // Display a message if the table is empty
        echo "No records found.";
    }
    ?>
</body>
</html>
