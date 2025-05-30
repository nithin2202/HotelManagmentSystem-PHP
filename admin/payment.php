<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}
?> 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GRAND SINDHU</title>
    <!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- TABLE STYLES-->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
</head>
<body>
    <div id="wrapper">
        
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php"><?php echo $_SESSION["user"]; ?> </a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="usersetting.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="settings.php"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
        </nav>
        <!--/. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">

                    <li>
                        <a href="home.php"><i class="fa fa-dashboard"></i> Status</a>
                    </li>
                    <li>
                        <a  href="messages.php"><i class="fa fa-desktop"></i> News Letters</a>
                    </li>
                    <li>
                        <a href="roombook.php"><i class="fa fa-bar-chart-o"></i>Room Booking</a>
                    </li>
                    <li>
                        <a class="active-menu" href="payment.php"><i class="fa fa-qrcode"></i> Payment</a>
                    </li>
                    <li>
                        <a href="index1.php"><i class="fa fa-qrcode"></i>Online Payment</a>
                    </li>
                    <li>
                        <a  href="employee.php"><i class="fa fa-qrcode"></i>Add Employee</a>
                    </li>
                    <li>
                        <a  href="emp_details.php"><i class="fa fa-qrcode"></i>View Employee</a>
                    </li>
                    <li>
                        <a  href="profit.php"><i class="fa fa-qrcode"></i> Profit</a>
                    </li>
                    <li>
                        <a href="logout.php" ><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                    
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                           Payment Details<small> </small>
                        </h1>
                    </div>
                </div> 
                <!-- /. ROW  -->
                 
                <div class="row">
                    <div class="col-md-12">
                        <!-- Advanced Tables -->
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>Name</th>
                                                <th>Room type</th>
                                                <th>Bed Type</th>
                                                <th>Check in</th>
                                                <th>Check out</th>
                                                <th>No of Room</th>
                                                <th>Meal Type</th>
                                                <th>Room Rent</th>
                                                <th>Bed Rent</th>
                                                <th>Meals</th>
                                                <th>Gr.Total</th>
                                                <th>Print</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            include('db.php');
                                            $sql = "SELECT * FROM payment";
                                            $re = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_array($re)) {
                                                $id = $row['id'];
                                                $troom = $row['troom'];
                                                $tbed = $row['tbed'];
                                                $meal = $row['meal'];
                                                $nroom = $row['nroom'];
                                                $days = $row['noofdays'];

                                                // Calculate type_of_room based on troom
                                                $type_of_room = 0;
                                                if ($troom == "Superior Room") {
                                                    $type_of_room = 3200;
                                                } else if ($troom == "Deluxe Room") {
                                                    $type_of_room = 2200;
                                                } else if ($troom == "Guest House") {
                                                    $type_of_room = 1800;
                                                } else if ($troom == "Single Room") {
                                                    $type_of_room = 1500;
                                                }

                                                // Calculate type_of_bed based on tbed
                                                if ($tbed == "Single") {
                                                    $type_of_bed = $type_of_room * 1 / 100;
                                                } else if ($tbed == "Double") {
                                                    $type_of_bed = $type_of_room * 2 / 100;
                                                } else if ($tbed == "Triple") {
                                                    $type_of_bed = $type_of_room * 3 / 100;
                                                } else if ($tbed == "Quad") {
                                                    $type_of_bed = $type_of_room * 4 / 100;
                                                } else if ($tbed == "None") {
                                                    $type_of_bed = $type_of_room * 4 / 100;
                                                }

                                                // Calculate type_of_meal based on meal
                                                if ($meal == "Room only") {
                                                    $type_of_meal = $type_of_bed * 0;
                                                } else if ($meal == "Breakfast") {
                                                    $type_of_meal = $type_of_bed * 2;
                                                } else if ($meal == "Half Board") {
                                                    $type_of_meal = $type_of_bed * 4;
                                                } else if ($meal == "Full Board") {
                                                    $type_of_meal = $type_of_bed * 6;
                                                }

                                                // Calculate totals
                                                $ttot = $type_of_room * $nroom * $days;
                                                $btot = $type_of_bed * $nroom * $days;
                                                $mepr = $type_of_meal * $nroom * $days;
                                                $fintot = $ttot + $btot + $mepr;

                                                // Update the payment table with calculated values
                                                $update_query = "UPDATE payment SET ttot = $ttot, btot = $btot, mepr = $mepr, fintot = $fintot WHERE id = " . $row['id'];
                                                mysqli_query($con, $update_query);

                                                $id = $row['id'];
                                                $class = ($id % 2 == 1) ? 'gradeC' : 'gradeU';

                                                echo "<tr class='$class'>
                                                    <td>" . $id . "</td>
                                                    
                                                    <td>" . $row['title'] . " " . $row['fname'] . " " . $row['lname'] . "</td>
                                                    <td>" . $troom . "</td>
                                                    <td>" . $tbed . "</td>
                                                    <td>" . $row['cin'] . "</td>
                                                    <td>" . $row['cout'] . "</td>
                                                    <td>" . $nroom . "</td>
                                                    <td>" . $meal . "</td>
                                                    <td>" . $ttot . "</td>
                                                    <td>" . $btot . "</td>
                                                    <td>" . $mepr . "</td>
                                                    <td>" . $fintot . "</td>
                                                    <td><a href='print.php?pid=" . $id . "' class='btn btn-primary'> <i class='fa fa-print'></i> Print</a></td>
                                                </tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--End Advanced Tables -->
                    </div>
                </div>
                <!-- /. ROW  -->
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
        <!-- JS Scripts-->
        <!-- jQuery Js -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- Bootstrap Js -->
        <script src="assets/js/bootstrap.min.js"></script>
        <!-- Metis Menu Js -->
        <script src="assets/js/jquery.metisMenu.js"></script>
        <!-- DATA TABLE SCRIPTS -->
        <script src="assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function () {
                $('#dataTables-example').dataTable();
            });
        </script>
        <!-- Custom Js -->
        <script src="assets/js/custom-scripts.js"></script>
    </body>
</html>
