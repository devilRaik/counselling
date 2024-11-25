<?php
$loggedin = false;
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: index.php");
    exit;
}

echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">';
                if(basename($_SERVER['PHP_SELF']) == 'admin.php'){
                echo   '<ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="#">Link</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link bg-dark dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    City & School
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="\">Add Cities</a></li>
                                    <li><a class="dropdown-item" href="\">Add Schools</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                            </li>
                        </ul>';
                }
                if (basename($_SERVER['PHP_SELF']) == 'dataform.php') {
                    echo '<form class="d-flex mx-3">
                            <a class="btn btn-warning" href="dataform.php">Reset Form</a>
                          </form>';
                }

                echo '<form class="d-flex">
                    <a class="btn btn-success" href="logout.php">Logout</a>
                </form>
                <a class="navbar-brand mx-auto " href="#">Welcome ' . ucfirst($_SESSION["username"]) . '</a></div>
            </div>
        </div>
    </nav>';
