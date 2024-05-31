<?php
include "config.php";

// Assuming $specific_id is the specific id you want to match
$specific_id = $_GET["cid"]; // Get the id from the URL parameter

// Fetch the land title details for the specific id
$land_title_sql = "SELECT * FROM subdivided_titles WHERE id = $specific_id";
$land_title_result = mysqli_query($conn, $land_title_sql);
$land_title_row = mysqli_fetch_assoc($land_title_result);

// Fetch the subdivided titles for the specific land title id, including the applicant's name, sorted by position
$subdivided_titles_sql = "SELECT *
                          FROM subdivided_titles
                          WHERE subdivided_to = $specific_id
                          ORDER BY position";
$subdivided_titles_result = mysqli_query($conn, $subdivided_titles_sql);

$lot_number = explode(',', str_replace(' ', '', $land_title_row["lot_number"]));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
</head>
<body>
    <h1>Subdivided Land History for <?= $lot_number[0]." - ".$lot_number[1] ?></h1>

    <!-- back -->
    <?php
    if ($land_title_row["land_title_id"] == null) {
        echo "<a href='sub_history.php?cid=".$land_title_row["subdivided_to"]."'>back</a>";
    } elseif ($land_title_row["subdivided_to"] == null) {
        echo "<a href='history.php?id=".$land_title_row["land_title_id"]."'>back</a>";
    } else {
        echo "<a href='index.php'>back</a>";
    }
    ?>
    <!-- back end -->

    <h2>Land Title Details:</h2>
    <table border="1">
        <tr>
            <th>Lot Number</th>
            <th>Application No</th>
            <th>Date Filed</th>
            <th>Applicant Name</th>
            <th>Area</th>
            <th>Location</th>
        </tr>
        <tr>
            <td><?= $lot_number[0]." - ".$lot_number[1] ?></td>
            <td><?= $land_title_row["application_no"] ?></td>
            <td><?= $land_title_row["date_filed"] ?></td>
            <td><?= $land_title_row["applicant_name"] ?></td>
            <td><?= $land_title_row["area"] ?></td>
            <td><?= $land_title_row["location"] ?></td>
        </tr>
    </table>
    <br>
    <a href="sub_subdivide.php?id=<?= $_GET['cid'] ?>">add</a>
    <h2>Subdivided Titles:</h2>
    <table border="1">
        <tr>
            <th>Lot Number</th>
            <th>Application No</th>
            <th>Date Filed</th>
            <th>Applicant Name</th>
            <th>area</th>
            <th>Location</th>
            <th>Approved Date</th>
            <th>Remarks</th>
            <th>Action</th>
        </tr>
        <?php
        ?>
        <script type='text/javascript'>
        function confirmDelete(status) {
            if (status == 0) {
                return confirm('Are you sure you want to delete this record?');
            } else {
                alert('You cannot delete this record because it\'s already subdivided/titled. Please delete the subdivided entry first.');
                return false;
            }
        }

        function confirmSubdivide(status) {
            if (status == 'untitled') {
                return confirm('Are you sure you want to subdivide this record?');
            } else {
                alert('You cannot subdivided this record because it\'s already titled. Please edit the subdivided entry first. ');
                return false;
            }
        }
        </script>
        <?php
        if (mysqli_num_rows($subdivided_titles_result) > 0):
            while($row = mysqli_fetch_assoc($subdivided_titles_result)): ?>
                <tr>
                <?php
                    // Remove white spaces and split into an array by ","
                    $lot_number_parts = explode(',', str_replace(' ', '', $row["lot_number"]));
                    ?>
                    <td><?= isset($lot_number_parts[0]) ? $lot_number_parts[0] : '' ?> <?= isset($lot_number_parts[1]) ? " - ".$lot_number_parts[1] : '' ?></td>
                    <td><?= $row["application_no"] ?></td>
                    <td><?= $row["date_filed"] ?></td>
                    <td><?= $row["applicant_name"] ?></td>
                    <td><?= $row["area"] ?></td>
                    <td><?= $row["location"] ?></td>
                    <td><?= $row["date_approved"] ?></td>
                    <td>
                        <?php
                        if ($row["status"] == 2 || $row["remarks"] == "titled"):
                            echo "Titled";
                        elseif ($row["status"] == 0):
                            echo "Not Subdivided";
                        else:
                            echo "<a href='sub_history.php?cid=".$row["id"]."'>View</a>";
                        endif;
                        ?>
                    </td>
                    <td>
                        <a href='edit_subtitles.php?id=<?= $row["id"] ?>'>Edit</a> | 
                        <a href='sub_subdivide.php?id=<?= $row["id"] ?>' onclick='return confirmSubdivide("<?= $row["remarks"] ?>")'>Subdivide</a> |
                        <a href='delete_subtitles.php?id=<?= $row["id"] ?>&land_title_id=<?= $land_title_row["land_title_id"] ?>' onclick='return confirmDelete(<?= $row["status"] ?>)'>Delete</a>
                    </td>
                </tr>
            <?php endwhile;
        else:
            if($land_title_row["land_title_id"] == null):
                // update the status to = 0 (not subdivided)
                $id = $land_title_row['id'];
                $sql = "UPDATE subdivided_titles SET status = 0 WHERE id = $id";

                if (mysqli_query($conn, $sql)) {
                    echo "Status updated successfully.";
                } else {
                    echo "Error updating status: " . mysqli_error($conn);
                }
                // Redirect to sub_history.php
                header("Location: sub_history.php?cid=".$land_title_row["subdivided_to"]);
            elseif($land_title_row["subdivided_to"] == null):
                // update the status to = 0 (not subdivided)
                $id = $land_title_row['id'];
                $sql = "UPDATE subdivided_titles SET status = 0 WHERE id = $id";

                if (mysqli_query($conn, $sql)) {
                    echo "Status updated successfully.";
                } else {
                    echo "Error updating status: " . mysqli_error($conn);
                }
                // Redirect to history.php
                header("Location: history.php?id=".$land_title_row["land_title_id"]);
            else:
                echo "Ronnie is gay";
                header("Location: index.php");
            endif;

            echo "<tr><td colspan='5'>No subdivided titles found.</td></tr>";
        endif;
        ?>
    </table>
</body>
</html>
