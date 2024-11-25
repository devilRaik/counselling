<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: index.php");
    exit;
}

// Allow only admins
if ($_SESSION['role'] != 'admin') {
    header("location: dataform.php");
    exit;
}

$username = $_SESSION['username'] ?? 'Guest';
include '_dbconnect.php';

$showAlert = false;
$showError = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add City
    if (isset($_POST['add_state'])) {
        $state_name = $_POST['state_name'];
        $existState = "SELECT * FROM states WHERE state_name='$state_name'";

        $select_query_state = mysqli_query($conn, $existState);
        $rowCountState = mysqli_num_rows($select_query_state);
        if ($rowCountState > 0) {
            $showError = "State is already Exist In the List";
        } else {
            $query = "INSERT INTO states (state_name) VALUES ('$state_name')";
            $stateQuery = mysqli_query($conn, $query);
            if ($stateQuery) {
                $showAlert = "State Saved In List";
            } else {
                $showError = "Functional Error";
            }
        }
    }
    if (isset($_POST['add_city'])) {
        $city_name = $_POST['city_name'];
        $state_id = $_POST['state_id'] ?? null;

        // Debugging Output
        // if (!$state_id) {
        //     die("Error: State ID is not selected.");
        // }
        $existcity = "SELECT * FROM cities WHERE city_name='$city_name'";
        $select_query_city = mysqli_query($conn, $existcity);
        $rowCountCity = mysqli_num_rows($select_query_city);
        if ($rowCountCity > 0) {
            $showError = "Dublicate City Name Exist";
        } else {
            $stmt = $conn->prepare("INSERT INTO cities (city_name, state_id) VALUES (?, ?)");
            $stmt->bind_param("si", $city_name, $state_id);
            if ($stmt->execute()) {
                $showAlert = "City Added In the Dropdown";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    // Add School
    if (isset($_POST['add_school'])) {
        $school_name = $_POST['school_name'];
        $city_id = $_POST['city_id'] ?? null;

        // // Debugging Output
        // if (!$city_id) {
        //     die("Error: City ID is not selected.");
        // }
        $exist = "SELECT * FROM schools WHERE school_name='$school_name'";
        $select_query = mysqli_query($conn, $exist);
        $rowCount = mysqli_num_rows($select_query);
        if ($rowCount > 0) {
            $showError = "Dublicate School Exist";
        } else {

            $stmt = $conn->prepare("INSERT INTO schools (school_name, city_id) VALUES (?, ?)");
            $stmt->bind_param("si", $school_name, $city_id);
            if ($stmt->execute()) {
                $showAlert = "Added School In Dropdown";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }
    if (isset($_POST['empty'])) {
        $selectTable = "SELECT * FROM states";

        $query = mysqli_query($conn, $selectTable);
        if ($query) {
            $empty = "SET FOREIGN_KEY_CHECKS = 0";
            if (mysqli_query($conn, $empty)) {
                $truncate = "TRUNCATE TABLE states";
                if (mysqli_query($conn, $truncate)) {
                    $reset = "SET FOREIGN_KEY_CHECKS = 1";
                    if (mysqli_query($conn, $reset)) {
                        $showAlert = "Empty State Table";
                    }
                }
            }
        }
    }
}

// Fetch cities and states for dropdowns
$cities = $conn->query("SELECT * FROM cities");
$states = $conn->query("SELECT * FROM states");

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/toastr.min.css">
    <link rel="stylesheet" href="lib/datatable/dataTables.css">
</head>

<body>
    <?php include 'components/_navbar.php'; ?>
    <?php
    if ($showAlert) {
        echo '<div id="overlay-alert" class="alert alert-primary alert-dismissible fade show position-absolute" style="top: 8%; left: 34%; z-index: 1050;">
            <strong>' . $showAlert . '</strong>.
          </div>';
    }

    if ($showError) {
        echo '<div id="overlay-alert" class="alert alert-warning alert-dismissible fade show position-absolute" style="top: 8%; left: 40%; z-index: 1050;">
            <strong>' . $showError . '</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    }
    ?>
    <!-- <h1 class="text-center mt-3">Welcome <?php echo ucfirst($username); ?></h1> -->
    <div class="d-flex justify-content-center align-items-center">
        <div class="container">
            <div class=" my-2">
                <div class="card-body">
                    <div class="row">

                        <!-- State Name Form -->
                        <div class="card p-3 bg-light my-2 mb-2">
                            <form method="POST" action="">
                                <input class="form-control" type="text" id="state_name" name="state_name" placeholder="Enter State Name" required>
                                <button class="btn btn-warning mt-2" type="submit" name="add_state">Add State</button>
                                <button class="btn btn-warning mt-2" type="button" name="empty" id="emptyButton">Empty</button>
                            </form>
                        </div>

                        <!-- City Name Form -->
                        <div class="card p-3 my-2 bg-light mb-2">
                            <form method="POST" action="">
                                <input class="form-control mb-2" type="text" id="city_name" name="city_name" placeholder="Enter City Name" required>
                                <select class="form-control" id="state_id" name="state_id" required>
                                    <option value="" disabled selected>Select State</option>
                                    <?php while ($row = $states->fetch_assoc()) { ?>
                                        <option value="<?= $row['state_id'] ?>"><?= $row['state_name'] ?></option>
                                    <?php } ?>
                                </select>
                                <button class="btn btn-primary mt-2" type="submit" name="add_city">Add City</button>
                            </form>
                        </div>

                        <!-- School Name Form -->
                        <div class="card p-3 my-2 bg-light mt-2">
                            <form method="POST" action="">
                                <input class="form-control mb-2" type="text" id="school_name" name="school_name" placeholder="Enter the School" required>
                                <select class="form-control" id="city_id" name="city_id" required>
                                    <option value="" disabled selected>Select City</option>
                                    <?php while ($row = $cities->fetch_assoc()) { ?>
                                        <option value="<?= $row['city_id'] ?>"><?= $row['city_name'] ?></option>
                                    <?php } ?>
                                </select>
                                <button class="btn btn-success mt-2" type="submit" name="add_school">Add School</button>
                                <a class="btn btn-success mt-2" href="">Empty School Table</a>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Table View of Data entered by users -->
    <div class="container">

        <div class="container my-2 bg-light card">
            <table id="myTable" class="table table-striped table-bordered dt-responsive">
                <thead>
                    <tr>
                        <th>S.No</th> <!-- Index Column -->
                        <th>Entry From</th>
                        <th>Student</th>
                        <th>Fathers Name</th>
                        <th>Contact 1</th>
                        <th>Contact 2</th>
                        <th>Category</th>
                        <th>Subject Stream</th>
                        <th>States</th>
                        <th>Cities</th>
                        <th>Schools</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Example PHP code to fetch data (use your database here)
                    // Database connection
                    include '_dbconnect.php';

                    // Query to fetch data
                    // $sql = "SELECT * FROM `enquiry` WHERE entery_type=$etype, sname='$sname', fname='$fname', contact1='$mob1', contact2='$mob2', ccategory='$ccat', subject_stream='$substr', school_name='$schoolname', city='$city', tehsil='$tehsil', Village='$village'";
                    $sql = "SELECT * FROM `enquiry`";
                    $result = mysqli_query($conn, $sql);

                    // Check if we have data and display it
                    $index = 1; // Start index from 1
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='text-center'>" . $index++ . "</td>"; // Display index number
                            echo "<td class='text-center'>" . $row['entry_type'] . "</td>";
                            echo "<td class='text-center'>" . $row['sname'] . "</td>";
                            echo "<td class='text-center'>" . $row['fname'] . "</td>";
                            echo "<td class='text-center'>" . $row['contact1'] . "</td>";
                            echo "<td class='text-center'>" . $row['contact2'] . "</td>";
                            echo "<td class='text-center'>" . $row['ccategory'] . "</td>";
                            echo "<td class='text-center'>" . $row['subject_stream'] . "</td>";
                            echo "<td class='text-center'>" . $row['states'] . "</td>";
                            echo "<td class='text-center'>" . $row['cities'] . "</td>";
                            echo "<td class='text-center'>" . $row['schools'] . "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <script src="lib/jquery/jquery-3.7.1.min.js"></script>
        <script src="lib//datatable/dataTables.js"></script>
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable();
            });
        </script>
        <script>
            // Automatically hide alert after 5 seconds
            setTimeout(() => {
                const overlayAlert = document.getElementById('overlay-alert');
                if (overlayAlert) {
                    overlayAlert.classList.remove('show'); // Triggers fade out
                    overlayAlert.addEventListener('transitionend', () => overlayAlert.remove()); // Removes from DOM
                }
            }, 2000);
        </script>
        <script>
            document.getElementById('emptyButton').addEventListener('click', function() {
                // Create a hidden input to mimic the 'empty' button being clicked
                const form = this.closest('form');
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'empty';
                hiddenInput.value = 'true';
                form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            });
        </script>

</body>
<script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

</html>
</div>
</body>

</html>