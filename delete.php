<?php
include "config.php";

// Check if ID parameter is present in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare a delete statement
    $sql = "DELETE FROM boxes WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Record deleted successfully, redirect to index page
            header("location: edit_box.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
} else {
    echo "ID parameter is missing.";
}

// Close connection
mysqli_close($conn);
?>
