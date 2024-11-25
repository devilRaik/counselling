<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
    header("location: index.php"); // Redirect if not logged in
    exit;
}

// Restrict admin redirection if needed
// Admins can access this page, so no restriction is added here.



$showAlert = false;
$showError = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '_dbconnect.php';
    $etype = $_POST['entery_type'];
    $sname = $_POST['sname'];
    $fname = $_POST['fname'];
    $mob1 = $_POST['contact1'];
    $mob2 = $_POST['contact2'];
    $ccat = $_POST['ccategory'];
    $substr = $_POST['subject_stream'];

    $entry_mode = $_POST['entry_mode'];

    $states = ($_POST['entry_mode'] == 'select') ? $_POST['state'] : $_POST['manual_state'];
    $cities = ($_POST['entry_mode'] == 'select') ? $_POST['city'] : $_POST['manual_city'];
    $schools = ($_POST['entry_mode'] == 'select') ? $_POST['school'] : $_POST['manual_school'];

    $exist = "SELECT * FROM enquiry WHERE sname = '$sname' AND contact1 = '$mob1' AND contact2='$mob2'";
    // $exist = "SELECT `sname`, `contact1`, `contact2` WHERE `sname` = '$sname' AND `contact1` = $mod1 AND `contact2` = $mod2 ";
    $select_query = mysqli_query($conn, $exist);
    $rowCount = mysqli_num_rows($select_query);
    if ($rowCount > 0) {
        $showError = "Student Name and Mobile number are same like previous entery";
    } else {
        $insert = "INSERT INTO `enquiry`(`entery_type`, `sname`, `fname`, `contact1`, `contact2`, `ccategory`, `subject_stream`, `states`, `cities`, `schools`) VALUES ('$etype','$sname','$fname','$mob1','$mob2','$ccat','$substr','$states','$cities','$schools')";
        // $query = "INSERT INTO `enquiry` (`entery_type`,`sname`, `fname`, `contact1`, `contact2`, `ccategory`, `subject_stream`,`states`, `cities`, `schools`) VALUES ('$etype', '$sname','$fname','$mob1','$mob2','$ccat','$substr', '$states','$cities','$schools')";
        $insert_query = mysqli_query($conn, $insert);
        if ($insert_query) {
            $showAlert = true;
            // header("location:enteryForm.php");
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Entry Form</title>
    <!-- Bootstrap CSS -->
    <link href="bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .form-container {
        width: 140vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

<body>
    <?php include 'components/_navbar.php'; ?>
    <?php
    if ($showAlert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Student details is <strong>Successfully</strong> save into the list
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
    }
    ?>
    <?php
    if ($showError) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>' . $showError . '</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
    }
    ?>
    <div class="d-flex justify-content-center align-items-center">
        <div class="form-container">
            <div class="card my-5">
                <h2 class="text-center my-3">Student Entry Form</h2>
                <div class="card-body">

                    <form class="row g-2" action="dataform.php" method="POST">
                        <div>
                            <select name="entery_type" class="form-select" id="entery_type">
                                <option selected disabled>Select Form Type</option>
                                <option value="Enquire">Enquire Form</option>
                                <option value="list">List</option>
                            </select>
                        </div>
                        <!-- Student Name -->
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="sname" name="sname" placeholder="Enter The Student Name Here" required>
                        </div>

                        <!-- Father's Name -->
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter The Fathers Name Here" required>
                        </div>


                        <!-- Mobile Number -->
                        <div class="col-md-4 mt-2">
                            <input type="text" maxlength="10" class="form-control" id="contact1" name="contact1" placeholder="Enter Mobile Number" required>
                        </div>

                        <!-- Alternative Mobile Number -->
                        <div class="col-md-4 mt-2">
                            <input type="text" maxlength="10" class="form-control" id="contact2" name="contact2" placeholder="Enter Alternate Mobile Number">
                        </div>

                        <!-- Cast Category -->
                        <div class="col-md-4 mt-2">
                            <select class="form-control" id="ccategory" name="ccategory" required>
                                <option selected disabled>Select Cast Category</option>
                                <option value="General">General</option>
                                <option value="OBC">OBC</option>
                                <option value="SC">SC</option>
                                <option value="ST">ST</option>
                                <option value="OTHER">Other</option>
                            </select>
                        </div>

                        <div>
                            <select name="subject_stream" class="form-select" id="subject_stream">
                                <option selected disabled>Which Subject Stream You Are</option>
                                <option value="PCM">PCM</option>
                                <option value="PCB">PCB</option>
                                <option value="Commerce">Commerce</option>
                                <option value="Arts">Arts</option>
                            </select>
                        </div>
                        <!-- Selection or Manual Entry Switch -->
                        <div class=" mt-3 mb-2 d-flex">
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="entry_mode" id="select_mode" value="select" checked>
                                <label class="form-check-label" for="select_mode">Select from Dropdown</label>
                            </div>
                            <div class="form-check mx-3">
                                <input type="radio" class="form-check-input" name="entry_mode" id="manual_mode" value="manual">
                                <label class="form-check-label" for="manual_mode">Enter Manually</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- State Selection -->
                            <select class="form-control" id="state" name="state">
                                <option selected disabled>Select State</option>
                                <?php include 'states_options.php'; ?>
                            </select>
                            <input type="text" class="form-control" id="manual_state" name="manual_state" placeholder="Or enter state manually" style="display:none;">
                        </div>
                        
                        <div class="col-md-6">
                            <!-- City Selection -->
                            <select class="form-control" id="city" name="city">
                                <option selected disabled>Select City</option>
                                <?php include 'cities_options.php'; ?>
                            </select>
                            <input type="text" class="form-control" id="manual_city" name="manual_city" placeholder="Or enter city manually" style="display:none;">
                        </div>
                        
                        <div class="col-md-6">
                            <!-- School Selection -->
                            <select class="form-control" id="school" name="school">
                                <option selected disabled>Select School</option>
                                <?php include 'schools_options.php'; ?>
                            </select>
                            <input type="text" class="form-control" id="manual_school" name="manual_school" placeholder="Or enter school manually" style="display:none;">
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            const contact1 = document.getElementById('contact1').value;
            const contact2 = document.getElementById('contact2').value;

            const isValidContact = (contact) => /^\d{10}$/.test(contact);

            if (!isValidContact(contact1)) {
                alert('Primary contact must be a 10-digit number.');
                event.preventDefault();
            }

            if (contact2 && !isValidContact(contact2)) {
                alert('Secondary contact must be a 10-digit number or left blank.');
                event.preventDefault();
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            // Load states when the page loads
            loadStates();

            // State dropdown change
            $('#state').on('change', function() {
                const stateId = $(this).val();
                if (stateId) {
                    loadCities(stateId);
                } else {
                    $('#city').html('<option value="">Select City</option>');
                    $('#school').html('<option value="">Select School</option>');
                }
            });

            // City dropdown change
            $('#city').on('change', function() {
                const cityId = $(this).val();
                if (cityId) {
                    loadSchools(cityId);
                } else {
                    $('#school').html('<option value="">Select School</option>');
                }
            });

            function loadStates() {
                $.ajax({
                    url: 'get_data.php',
                    type: 'POST',
                    data: {
                        type: 'state'
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#state').html('<option value="">Select State</option>');
                        data.forEach(function(state) {
                            $('#state').append(`<option value="${state.id}">${state.state_name}</option>`);
                        });
                    }
                });
            }

            function loadCities(stateId) {
                $.ajax({
                    url: 'get_data.php',
                    type: 'POST',
                    data: {
                        type: 'city',
                        state_id: stateId
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#city').html('<option value="">Select City</option>');
                        data.forEach(function(city) {
                            $('#city').append(`<option value="${city.id}">${city.city_name}</option>`);
                        });
                    }
                });
            }

            function loadSchools(cityId) {
                $.ajax({
                    url: 'get_data.php',
                    type: 'POST',
                    data: {
                        type: 'school',
                        city_id: cityId
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#school').html('<option value="">Select School</option>');
                        data.forEach(function(school) {
                            $('#school').append(`<option value="${school.id}">${school.school_name}</option>`);
                        });
                    }
                });
            }
        });
    </script>
    <!-- Custom Script to toggle fields based on radio button -->
    <script>
        document.getElementById('select_mode').addEventListener('change', function() {
            toggleFields(true);
        });
        document.getElementById('manual_mode').addEventListener('change', function() {
            toggleFields(false);
        });

        function toggleFields(isSelectMode) {
            const stateSelect = document.getElementById('state');
            const stateManual = document.getElementById('manual_state');
            const citySelect = document.getElementById('city');
            const cityManual = document.getElementById('manual_city');
            const schoolSelect = document.getElementById('school');
            const schoolManual = document.getElementById('manual_school');

            stateSelect.style.display = isSelectMode ? 'block' : 'none';
            stateManual.style.display = isSelectMode ? 'none' : 'block';
            citySelect.style.display = isSelectMode ? 'block' : 'none';
            cityManual.style.display = isSelectMode ? 'none' : 'block';
            schoolSelect.style.display = isSelectMode ? 'block' : 'none';
            schoolManual.style.display = isSelectMode ? 'none' : 'block';
        }

        // Initial state setup
        toggleFields(true);
    </script>
</body>

</html>