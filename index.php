<?php
session_start();
include "config.php";

$cadastral = "";
$lot = "";
// $case = "";
$no_records = "";
$output = []; //this are where I put the output of the search

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cadastral = $_POST["cadastral"];
    $lot = $_POST["lot"];
    $_SESSION['cadastral'] = $cadastral;
    $_SESSION['lot'] = $cadastral;

    // $case = $_POST["case"];

    // Search Logic
    $sql = "SELECT * FROM boxes WHERE cadastral = '$cadastral'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $range = $row['range_val'];
            $case_no = $row['case_number'];
            $book = explode(",", $range);
            $books = array_map('trim', $book);
            $isLocated = "";
            $no_records = $row['no_records'];
            $location = explode(",", $row['location']);
            $isExisted = false;
            $isDeleted = false;
            $category = $row['category'];

            // Searching through range_val
            foreach ($books as $i => $book) {
                $temp_arr = explode("-", $book);

                if (count($temp_arr) == 2) {
                    if ($lot >= $temp_arr[0] && $lot <= $temp_arr[1]) {
                        $isLocated = ($i + 1);
                        $isExisted = true;
                        // existed
                    } else {
                        //not exist
                    }
                } elseif (count($temp_arr) == 1) {
                    if ($lot == $temp_arr[0]) {
                        $isLocated = ($i + 1);
                        $isExisted = true;
                    }
                }
            }

            // No record logic 
            $no_records = explode(",", $no_records);

            for ($i = 0; $i < count($no_records); $i++) {
                $temp_arr = explode("-", $no_records[$i]);

                if (count($temp_arr) == 2) {
                    if ($lot >= $temp_arr[0] && $lot <= $temp_arr[1]) {
                        $isDeleted = true;
                    } elseif ($lot <= $temp_arr[0] && $lot >= $temp_arr[1]) {
                        // Existed
                    }
                } elseif (count($temp_arr) == 1) {
                    if ($lot == $temp_arr[0]) {
                        $isDeleted = true;
                    }
                }
            }

            if ($isExisted && !$isDeleted) {
                $search_location = array(
                    'cadastre' => $cadastral,
                    'lot' => $lot,
                    'rack' => $location[0],
                    'layer' => $location[1],
                    'box' => $location[2],
                    'category' => $category,

                );
                array_push(
                    $output,
                    "
                <tr style='background:white;'><td><strong>Lot Number:</strong></td><td>{$search_location['lot']}</td></tr>
                <tr style='background:white;'><td><strong>Cadastre:</strong></td><td>{$search_location['cadastre']}</td></tr>
                <tr style='background:white;'><td><strong>Category:</strong></td><td>{$search_location['category']}</td></tr>
                <tr style='background:white;'><td><strong>File Rack:</strong></td><td>{$search_location['rack']}</td></tr>
                <tr style='background:white;'><td><strong>Layer:</strong></td><td>{$search_location['layer']}</td></tr>
                <tr style='background:white;'><td><strong>Box:</strong></td><td>{$search_location['box']}</td></tr>
                <tr class='jamark'><td><strong></strong></td><td></td></tr>
               
                "
                );


            } else {
                if (count($output) == 0) {
                    array_push($output, "<p style='color:white;'>No Result Found</p>");
                }
            }
        }
    } elseif (isset($_SESSION['cadastral']) && isset($_SESSION['lot'])) {
        // If there are previous search values, retain them
        $cadastral = $_SESSION['cadastral'];
        $lot = $_SESSION['lot'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <img src="logo.png" alt="Logo" class="header-logo">

            <a class="navbar-brand" href="#"> DENR CENRO Record Tracer</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="insert_box.php">Add Box</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="edit_box.php">Update Data</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <div class="search">
        <div class="container mt-5 pt-1">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <h4><label for="cadastral">Cadastral:</label></h4>
                <select name="cadastral" id="cadastral" class="form-control">
                    <?php
                    $sql = "SELECT DISTINCT cadastral FROM boxes";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $selected = $row["cadastral"] == $cadastral ? "selected" : "";
                            echo "<option value='" . $row["cadastral"] . "' $selected>" . $row["cadastral"] . "</option>";
                        }
                    }
                    ?>
                </select>
                <br>
                <input type="number" name="lot" id="lot" placeholder="Lot No." class="form-control"
                    value="<?php echo $lot; ?>">

                <button type="submit" class="btn btn-primary mt-3">Search</button>
            </form>

        </div>
    </div>
    <!-- Search Result Box -->
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8 ">
                <div class="card" style="background: rgb(39, 96, 253);  padding-bottom: 0px;">
                    <div class="card-header"><strong>Search Result</strong></div>
                    <div class="card-body">
                        <?php
                        if (!empty($output)) {
                            echo "<table class='table'>";
                            foreach ($output as $jhonmarc) {
                                echo $jhonmarc;
                            }
                            echo "</table>";
                        } else {
                            echo "<p style='color:white;'>No Result Found</p>";
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>