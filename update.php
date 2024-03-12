<?php
include "config.php";

// Initialize variables with empty values
$category = "";
$cadastral = "";
$case = "";
$location = "";
$range = "";
$no_records = "";

// Check if ID parameter is present in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve the record from the database
    $sql = "SELECT * FROM boxes WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $category = $row['category'];
                $cadastral = $row['cadastral'];
                $case = $row['case_number'];
                $location = $row['location'];
                $location = explode(", ", $row['location']);

                $range = $row['range_val'];
                $no_records = $row['no_records'];
            } else {
                echo "No record found with ID: " . $id;
                exit();
            }
        } else {
            echo "Error retrieving record: " . mysqli_error($conn);
            exit();
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit();
    }
} else {
    echo "ID parameter is missing.";
    exit();
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $category = $_POST["category"];
    $cadastral = $_POST["cadastral"];
    $case = $_POST["case"];
    $location = $_POST["rack"] . ", " . $_POST["layer"] . ", " . $_POST["box"];
    $range = $_POST["range"];
    $no_records = $_POST["no_record"];

    // Update query
    $sql = "UPDATE boxes SET category=?, cadastral=?, case_number=?, location=?, range_val=?, no_records=? WHERE id=?";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind parameters to the prepared statement
        mysqli_stmt_bind_param($stmt, "ssssssi", $category, $cadastral, $case, $location, $range, $no_records, $id);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to index page
            header("location: edit_box.php");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <img src="logo.png" alt="Logo" class="header-logo">
        <a class="navbar-brand" href="#"> DENR CENRO Record Tracer</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="edit_box.php">Edit Data</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Search</a>
                </li>
                <!-- Add more navigation links here if needed -->
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Edit Record</h4>
        </div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Category:</label>
                            <input type="text" name="category" class="form-control" value="<?php echo $category; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Cadastral:</label>
                            <input type="text" name="cadastral" class="form-control" value="<?php echo $cadastral; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Case Number:</label>
                            <input type="text" name="case" class="form-control" value="<?php echo $case; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Rack:</label>
                            <input type="text" name="rack" class="form-control" value="<?php echo $location[0]; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Layer:</label>
                            <input type="text" name="layer" class="form-control" value="<?php echo $location[1]; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Box:</label>
                            <input type="text" name="box" class="form-control" value="<?php echo $location[2]; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Range:</label>
                            <input type="text" name="range" class="form-control" value="<?php echo $range; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">No. of Records:</label>
                            <input type="text" name="no_record" class="form-control" value="<?php echo $no_records; ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
