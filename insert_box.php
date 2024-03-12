<?php
include "config.php";

$formSubmitted = isset($_SESSION['success']);

$categoryValue = isset($_SESSION['category']) ? htmlspecialchars($_SESSION['category']) : '';
// $rangeValue = isset($_SESSION['range']) ? htmlspecialchars($_SESSION['range']) : '';
$cadastralValue = isset($_SESSION['cadastral']) ? htmlspecialchars($_SESSION['cadastral']) : '';
$caseValue = isset($_SESSION['case']) ? htmlspecialchars($_SESSION['case']) : '';
$rackValue = isset($_SESSION['rack']) ? htmlspecialchars($_SESSION['rack']) : '';
$layerValue = isset($_SESSION['layer']) ? htmlspecialchars($_SESSION['layer']) : '';
$boxValue = isset($_SESSION['box']) ? htmlspecialchars($_SESSION['box']) : '';
$noRecordValue = isset($_SESSION['no_record']) ? htmlspecialchars($_SESSION['no_record']) : '';

if (isset($_POST['submit'])) {
    $category = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['category'])));
    $range = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['range'])));
    $cadastral = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['cadastral'])));
    $case = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['case'])));
    $rack = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['rack'])));
    $layer = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['layer'])));
    $box = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['box'])));
    $no_record = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['no_record'])));

    $_SESSION['category_e'] = '';
    $_SESSION['range_e'] = '';
    $_SESSION['cadastral_e'] = '';
    $_SESSION['case_e'] = '';
    $_SESSION['rack_e'] = '';
    $_SESSION['layer_e'] = '';
    $_SESSION['box_e'] = '';
    $_SESSION['no_record_e'] = '';
    $_SESSION['error'] = '';
    $_SESSION['success'] = '';


    if (empty($category)) {
        $_SESSION['category_e'] = "Please select to complete this field Category.";
    }

    if (empty($range)) {
        $_SESSION['range_e'] = "Please complete this mandatory field Range.";
    }

    if (empty($cadastral)) {
        $_SESSION['cadastral_e'] = "Please select to complete this field Cadastral.";
    }

    if (empty($case)) {
        $_SESSION['case_e'] = "Please complete this mandatory field Case.";
    }

    if (empty($rack)) {
        $_SESSION['rack_e'] = "Please complete this mandatory field Rack.";
    }

    if (empty($layer)) {
        $_SESSION['layer_e'] = "Please complete this mandatory field Layer.";
    }

    if (empty($box)) {
        $_SESSION['box_e'] = "Please complete this mandatory field Box.";
    } else {


        // Default optional
        $case = min($case, 15);
        $location = $rack . ", " . $layer . ", " . $box;
        $range = str_replace(' ', '', $range);


        $stmt = mysqli_prepare($conn, "INSERT INTO boxes (category, cadastral, case_number, location, range_val, no_records) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssss", $category, $cadastral, $case, $location, $range, $no_record);


        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Data successfully inserted!");</script>';
        } else {
            $_SESSION['error'] = "All Data is not submitted";
        }

        $_SESSION['category'] = '';
        $_SESSION['range'] = '';
        $_SESSION['cadastral'] = '';
        $_SESSION['case'] = '';
        $_SESSION['rack'] = '';
        $_SESSION['layer'] = '';
        $_SESSION['box'] = '';
        $_SESSION['no_record'] = '';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locator</title>
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
                        <a class="nav-link" href="edit_box.php">Edit Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Search</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Locator Form
            </div>
            <div class="card-body">
                <form class="row g-3" action="" method="POST">


                    <div class="col-md-6">
                        <label for="" class="form-label">Category
                            <?php if (isset($_SESSION['category_e'])) { ?>
                                <div class="text-danger" role="alert">
                                    <?php echo $_SESSION['category_e']; ?>
                                </div>
                                <?php unset($_SESSION['category_e']);
                            } ?>
                        </label><br>
                        <select class="search-bar" id="category" name="category" style="width:99%;padding:2px;">
                            
                            <option value="Lot Data Computation" <?php if (isset($_POST['category']) && $_POST['category'] === 'Lot Data Computation')
                                echo ' selected'; ?>>Lot Data Computation
                            </option>
                            <option value="Lot Description" <?php if (isset($_POST['category']) && $_POST['category'] === 'Lot Description')
                                echo ' selected'; ?>>Lot Description</option>
                        </select>
                    </div>



                    <div class="col-md-6">
                        <label for="" class="form-label">Range
                            <?php if (isset($_SESSION['range_e'])) { ?>
                                <div class="text-danger" role="alert">
                                    <?php echo $_SESSION['range_e']; ?>
                                </div>
                                <?php unset($_SESSION['range_e']);
                            } ?>
                        </label>
                        <input type="text" class="form-control" id="range" name="range">
                    </div>




                    <div class="col-md-6">
                        <label for="cadastral" class="form-label">Cadastral map
                            <?php if (isset($_SESSION['cadastral_e'])) { ?>
                                <div class="text-danger" role="alert">
                                    <?php echo $_SESSION['cadastral_e']; ?>
                                </div>
                                <?php unset($_SESSION['cadastral_e']);
                            } ?>
                        </label><br>
                        <select class="" id="" name="cadastral" style="width:99%;padding:2px;">
                            
                            <option value="Agno" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Agno')
                                echo ' selected'; ?>>Agno</option>
                            <option value="Alaminos" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Alaminos')
                                echo ' selected'; ?>>Alaminos</option>
                            <option value="Asingan" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Asingan')
                                echo ' selected'; ?>>Asingan</option>
                            <option value="Balungao" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Balungao')
                                echo ' selected'; ?>>Balungao</option>
                            <option value="Bani" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Bani')
                                echo ' selected'; ?>>Bani</option>
                            <option value="Basista" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Basista')
                                echo ' selected'; ?>>Basista</option>
                            <option value="Bayambang" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Bayambang')
                                echo ' selected'; ?>>Bayambang</option>
                            <option value="Binalonan" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Binalonan')
                                echo ' selected'; ?>>Binalonan</option>
                            <option value="Binmaley" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Binmaley')
                                echo ' selected'; ?>>Binmaley</option>
                            <option value="Bolinao" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Bolinao')
                                echo ' selected'; ?>>Bolinao</option>
                            <option value="Bugallon" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Bugallon')
                                echo ' selected'; ?>>Bugallon</option>
                            <option value="Burgos" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Burgos')
                                echo ' selected'; ?>>Burgos</option>
                            <option value="Calasiao" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Calasiao')
                                echo ' selected'; ?>>Calasiao</option>
                            <option value="Dasol" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Dasol')
                                echo ' selected'; ?>>Dasol</option>
                            <option value="Infanta" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Infanta')
                                echo ' selected'; ?>>Infanta</option>
                            <option value="Labrador" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Labrador')
                                echo ' selected'; ?>>Labrador</option>
                            <option value="Laoac" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Laoac')
                                echo ' selected'; ?>>Laoac</option>
                            <option value="Lingayen" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Lingayen')
                                echo ' selected'; ?>>Lingayen</option>
                            <option value="Mabini" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Mabini')
                                echo ' selected'; ?>>Mabini</option>
                            <option value="Malasiqui" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Malasiqui')
                                echo ' selected'; ?>>Malasiqui</option>
                            <option value="Manaoag" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Manaoag')
                                echo ' selected'; ?>>Manaoag</option>
                            <option value="Mangaldan" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Mangaldan')
                                echo ' selected'; ?>>Mangaldan</option>
                            <option value="Mangatarem" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Mangatarem')
                                echo ' selected'; ?>>Mangatarem</option>
                            <option value="Mapandan" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Mapandan')
                                echo ' selected'; ?>>Mapandan</option>
                            <option value="Natividad" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Natividad')
                                echo ' selected'; ?>>Natividad</option>
                            <option value="Pozorrubio" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Pozorrubio')
                                echo ' selected'; ?>>Pozorrubio</option>
                            <option value="Rosales" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Rosales')
                                echo ' selected'; ?>>Rosales</option>
                            <option value="San Carlos City" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'San Carlos City')
                                echo ' selected'; ?>>San Carlos City</option>
                            <option value="San Fabian" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'San Fabian')
                                echo ' selected'; ?>>San Fabian</option>
                            <option value="San Jacinto" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'San Jacinto')
                                echo ' selected'; ?>>San Jacinto</option>
                            <option value="San Manuel" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'San Manuel')
                                echo ' selected'; ?>>San Manuel</option>
                            <option value="San Nicolas" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'San Nicolas')
                                echo ' selected'; ?>>San Nicolas</option>
                            <option value="San Quintin" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'San Quintin')
                                echo ' selected'; ?>>San Quintin</option>
                            <option value="Santa Barbara" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Santa Barbara')
                                echo ' selected'; ?>>Santa Barbara</option>
                            <option value="Santa Maria" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Santa Maria')
                                echo ' selected'; ?>>Santa Maria</option>
                            <option value="Santo Tomas" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Santo Tomas')
                                echo ' selected'; ?>>Santo Tomas</option>
                            <option value="Sison" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Sison')
                                echo ' selected'; ?>>Sison</option>
                            <option value="Sual" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Sual')
                                echo ' selected'; ?>>Sual</option>
                            <option value="Tayug" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Tayug')
                                echo ' selected'; ?>>Tayug</option>
                            <option value="Umingan" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Umingan')
                                echo ' selected'; ?>>Umingan</option>
                            <option value="Urbiztondo" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Urbiztondo')
                                echo ' selected'; ?>>Urbiztondo</option>
                            <option value="Villasis" <?php if (isset($_POST['cadastral']) && $_POST['cadastral'] === 'Villasis')
                                echo ' selected'; ?>>Villasis</option>
                        </select>
                    </div>





                    <div class="col-md-6">
                        <label for="" class="form-label">Case
                            <?php if (isset($_SESSION['case_e'])) { ?>
                                <div class="text-danger" role="alert">
                                    <?php echo $_SESSION['case_e']; ?>
                                </div>
                                <?php unset($_SESSION['case_e']);
                            } ?>
                        </label>
                        <input type="number" class="form-control" id="case" name="case"
                            value="<?php echo isset($_POST['case']) ? htmlspecialchars($_POST['case']) : $caseValue; ?>">
                    </div>




                    <div class="col-md-6">
                        <label for="" class="form-label">File Rack No#
                            <?php if (isset($_SESSION['rack_e'])) { ?>
                                <div class="text-danger" role="alert">
                                    <?php echo $_SESSION['rack_e']; ?>
                                </div>
                                <?php unset($_SESSION['rack_e']);
                            } ?>
                        </label>
                        <input type="number" class="form-control" id="rack" name="rack"
                            value="<?php echo isset($_POST['rack']) ? htmlspecialchars($_POST['rack']) : $rackValue; ?>">
                    </div>




                    <div class="col-md-6">
                        <label for="" class="form-label">Layer No#
                            <?php if (isset($_SESSION['layer_e'])) { ?>
                                <div class="text-danger" role="alert">
                                    <?php echo $_SESSION['layer_e']; ?>
                                </div>
                                <?php unset($_SESSION['layer_e']);
                            } ?>
                        </label>
                        <input type="number" class="form-control" id="layer" name="layer"
                            value="<?php echo isset($_POST['layer']) ? htmlspecialchars($_POST['layer']) : $layerValue; ?>">
                    </div>





                    <div class="col-md-6">
                        <label for="" class="form-label">Box No#
                            <?php if (isset($_SESSION['box_e'])) { ?>
                                <div class="text-danger" role="alert">
                                    <?php echo $_SESSION['box_e']; ?>
                                </div>
                                <?php unset($_SESSION['box_e']);
                            } ?>
                        </label>
                        <input type="number" class="form-control" id="box" name="box">
                    </div>




                    <div class="col-md-6">
                        <label for="" class="form-label">No Records</label>
                        <input type="text" class="form-control" id="no_record" name="no_record" value="">
                    </div><br>
                    <div class="col-md-6">
                        <button type="submit" name="submit" class="btn btn-primary mt-3">Submit</button>
                        <?php if (isset($_SESSION['message'])) { ?>
                            <div class="text-danger" role="alert">
                                <?php echo $_SESSION['message']; ?>
                            </div>
                            <?php unset($_SESSION['message']);
                        } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>