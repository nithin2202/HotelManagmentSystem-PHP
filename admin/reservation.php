<?php
include('connection.php');

$confirmationMessage = '';

// Function to check room availability via AJAX
if(isset($_POST['check_availability'])) {
    $troom = $_POST['troom'];
    $bed = $_POST['bed'];
    $cin = $_POST['cin'];
    $cout = $_POST['cout'];

    // Check room availability
    $check_availability_query = "SELECT COUNT(*) AS available_rooms FROM room WHERE type = '$troom' AND bedding = '$bed' AND place = 'Free'";
    $result = mysqli_query($con, $check_availability_query);

    if(!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    $row = mysqli_fetch_assoc($result);
    $available_rooms = $row['available_rooms'];

    if($available_rooms >= $_POST['nroom']) {
        echo json_encode(array('available' => true, 'message' => 'Rooms are available.'));
    } else {
        echo json_encode(array('available' => false, 'message' => 'Rooms not available for the selected type and dates.'));
    }
    exit;
}

// Process form submission
if(isset($_POST['submit'])) {
    $title = $_POST['title'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $nation = $_POST['nation'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];
    $troom = $_POST['troom'];
    $bed = $_POST['bed'];
    $nroom = $_POST['nroom'];
    $meal = $_POST['meal'];
    $cin = $_POST['cin'];
    $cout = $_POST['cout'];

    // Insert into roombook table
    $new = "Not Conform"; // Assuming this is your default status
    $nodays = (strtotime($cout) - strtotime($cin)) / (60 * 60 * 24); // Calculate number of days

    $newUser = "INSERT INTO roombook (Title, FName, LName, Email, National, Country, Phone, TRoom, Bed, NRoom, Meal, cin, cout, stat, nodays) 
                VALUES ('$title', '$fname', '$lname', '$email', '$nation', '$country', '$phone', '$troom', '$bed', '$nroom', '$meal', '$cin', '$cout', '$new', '$nodays')";

    if(mysqli_query($con, $newUser)) {
        // Redirect to prevent form resubmission on refresh
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<script>alert('Error adding user in database');</script>";
    }
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RESERVATION GRANDSINDHU HOTEL</title>
    <!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#checkAvailability').click(function() {
                var troom = $('#troom').val();
                var bed = $('#bed').val();
                var cin = $('#cin').val();
                var cout = $('#cout').val();
                var nroom = $('#nroom').val();

                $.ajax({
                    type: 'POST',
                    url: 'reservation.php',
                    data: {
                        check_availability: true,
                        troom: troom,
                        bed: bed,
                        cin: cin,
                        cout: cout,
                        nroom: nroom
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.available) {
                            $('#availabilityMessage').text(response.message).removeClass('text-danger').addClass('text-success');
                            $('#submitForm').prop('disabled', false);
                        } else {
                            $('#availabilityMessage').text(response.message).removeClass('text-success').addClass('text-danger');
                            $('#submitForm').prop('disabled', true);
                        }
                    },
                    error: function() {
                        alert('Error checking room availability.');
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li>
                        <a href="../index.php"><i class="fa fa-home"></i> Homepage</a>
                    </li>
                </ul>
            </div>
        </nav>
       
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            RESERVATION <small></small>
                        </h1>
                    </div>
                </div> 
                 
                <div class="row">
                    <div class="col-md-5 col-sm-5">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                PERSONAL INFORMATION
                            </div>
                            <div class="panel-body">
                                <form name="form" method="post">
                                    <div class="form-group">
                                        <label>Title*</label>
                                        <select name="title" class="form-control" required>
                                            <option value selected ></option>
                                            <option value="Dr.">Dr.</option>
                                            <option value="Miss.">Miss.</option>
                                            <option value="Mr.">Mr.</option>
                                            <option value="Mrs.">Mrs.</option>
                                            <option value="Prof.">Prof.</option>
                                            <option value="Rev .">Rev .</option>
                                            <option value="Rev . Fr">Rev . Fr .</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input name="fname" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input name="lname" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input name="email" type="email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Nationality*</label>
                                        <label class="radio-inline">
                                            <input type="radio" name="nation"  value="indian" checked="">Indian
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="nation"  value="Non indian">Non Indian 
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input name="phone" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Room Type*</label>
                                        <select id="troom" name="troom" class="form-control" required>
                                            <option value="" selected disabled>Select Room Type</option>
                                            <option value="Superior Room">Superior Room</option>
                                            <option value="Deluxe Room">Deluxe Room</option>
                                            <option value="Guest House">Guest House</option>
                                            <option value="Single Room">Single Room</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Bedding Type*</label>
                                        <select id="bed" name="bed" class="form-control" required>
                                            <option value="" selected disabled>Select Bedding Type</option>
                                            <option value="Single">Single</option>
                                            <option value="Double">Double</option>
                                            <option value="Triple">Triple</option>
                                            <option value="Quad">Quad</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Number of Rooms*</label>
                                        <select id="nroom" name="nroom" class="form-control" required>
                                            <option value="" selected disabled>Select Number of Rooms</option>
                                            <?php for($i = 1; $i <= 5; $i++) { ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Meal Plan</label>
                                        <select name="meal" class="form-control" required>
                                            <option value selected ></option>
                                            <option value="Room only">Room only</option>
                                            <option value="Breakfast">Breakfast</option>
                                            <option value="Half Board">Half Board</option>
                                            <option value="Full Board">Full Board</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Check-in</label>
                                        <input type="date" name="cin" id="cin" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Check-out</label>
                                        <input type="date" name="cout" id="cout" class="form-control" required>
                                    </div>
                                    <div id="availabilityMessage"></div>
                                    <button type="button" id="checkAvailability" class="btn btn-primary">Check Availability</button>
                                    <button type="submit" name="submit" id="submitForm" class="btn btn-success" disabled>Submit</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> 
                <!-- /. ROW  -->
                <footer><p>All right reserved. Template by: Grand Sindhu</p></footer>
            </div>
        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</body>
</html>
