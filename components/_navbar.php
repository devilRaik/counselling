<?php
$loggedin = false;
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
}

echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">';
        if (basename($_SERVER['PHP_SELF']) == 'dataform.php') {
            echo '<form class="d-flex">
                    <a class="btn btn-warning" href="dataform.php">Reset Form</a>
                  </form>';
        }
        if ((basename($_SERVER['PHP_SELF']) == 'add_city.php')) {
            echo '<form class="d-flex">
                    <a class="btn btn-danger" href="admin.php">Back</a>
                  </form>';
        }
        echo '<p class="navbar-brand mx-auto mb-2">Welcome '.ucfirst($_SESSION["username"]).'</p>';
        
                echo '</div>
                <form class="d-flex">
                    <a class="btn btn-success ml-5" href="logout.php">Logout</a>
                </form>
        </div>
    </nav>';
