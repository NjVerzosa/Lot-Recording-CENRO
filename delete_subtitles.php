<?php
// Include config.php
include "config.php";

$id = $_GET["id"];

// Initialize variables to store fetched data
$deletedData = [];

// Attempt to fetch the record before deletion
$query = "SELECT * FROM subdivided_titles WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Get result set
        $result = mysqli_stmt_get_result($stmt);

        // Fetch data into an array
        $deletedData = mysqli_fetch_assoc($result);

        // Free result set
        mysqli_free_result($result);
    } else {
        echo "Error fetching record: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Attempt to delete the record
$sql = "DELETE FROM subdivided_titles WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Bind parameters to the prepared statement
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        echo "Record deleted successfully.<br>";
        // Redirect based on land_title_id and subdivided_to
        if ($deletedData['land_title_id'] == null) {
            // echo "sub: " . $deletedData['subdivided_to'];
            // Redirect to sub_history.php with cid=subdivided_to
            header("Location: sub_history.php?cid=" . $deletedData['subdivided_to']);
        } elseif ($deletedData['subdivided_to'] == null) {
            echo "land id: " . $deletedData['land_title_id'];
            // Redirect to history.php with id=land_title_id
            header("Location: history.php?id=" . $deletedData['land_title_id']);
        } else {
            echo "Neither land_title_id nor subdivided_to is null.";
        }
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($conn);
?>
