<?php
    include "config.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search = $_POST['search'];

        // Search Logic
        $sql = "SELECT * FROM boxes WHERE cadastral = '$search'";
        $result = mysqli_query($conn, $sql);
    } else {
        // Select all records
        $sql = "SELECT * FROM boxes";
        $result = mysqli_query($conn, $sql);
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>editor</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        
        <link rel="stylesheet" href="css/style.css">
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
                    <li class="nav-item active">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="insert_box.php">Add Box</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Search</a>
                    </li>
        
                </ul>
            </div>
        </div>
    </nav>

    <div class="search_update">
    <div class="container mt-5 pt-1">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <select name="search" class="dropdown">
                <option value="">Select Cadastral</option>
                <?php
                $sql = "SELECT DISTINCT cadastral FROM boxes";
                $result_dropdown = mysqli_query($conn, $sql);

                if ($result_dropdown && mysqli_num_rows($result_dropdown) > 0) {
                    while ($row = mysqli_fetch_assoc($result_dropdown)) {
                        $selected = ($_POST['search'] == $row["cadastral"]) ? 'selected' : '';
                        echo "<option value='" . $row["cadastral"] . "' $selected>" . $row["cadastral"] . "</option>";
                    }
                }
                ?>
            </select>

            

            <button type="submit" class="btn btn-primary nav-link-btn">Search</button>
        </form>
    </div>
    </div>
    <div class="update_table">
        <table class='table'>
            <thead>
                <tr class="bg-primary" style="color: white;">
                    <th>ID</th>
                    <th>Category</th>
                    <th>Cadastral</th>
                    <th>Case Number</th>
                    <th>Location</th>
                    <th>Range</th>
                    <th>No Records</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['category'] . "</td>";
                        echo "<td>" . $row['cadastral'] . "</td>";
                        echo "<td>" . $row['case_number'] . "</td>";
                        echo "<td>" . $row['location'] . "</td>";
                        echo "<td>" . $row['range_val'] . "</td>";
                        echo "<td>" . $row['no_records'] . "</td>";
                        echo "<td>
                                <a href='update.php?id=" . $row['id'] . "' class='btn btn-primary'>Update</a>
                                <button class='btn btn-danger' onclick='confirmDelete(" . $row['id'] . ", \"" . $row['cadastral'] . "\");'>Delete</button>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No results found.</td></tr>";
                }

                ?>
            </tbody>
        </table>
    </div>

    <script>
        
        function confirmDelete(id, cadastral) {
            if (confirm("Are you sure you want to delete record ID: " + id + " with Cadastral: " + cadastral + "?")) {
                window.location.href = "delete.php?id=" + id;
            }
        }
        
    </script>

    </body>
    </html>