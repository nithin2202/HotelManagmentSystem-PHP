<?php  
session_start();  
if(!isset($_SESSION["user"])) {
    header("location:index.php");
    exit;
}
?> 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GRAND SINDHU HOTEL</title>
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
    <style>
        .modal-header, .modal-footer {
            background-color: #f5f5f5;
        }
        .modal-title {
            color: #333;
            font-weight: bold;
        }
        .form-control {
            margin-bottom: 10px;
        }
        .printable-newsletter {
            padding: 20px;
            border: 1px solid #ddd;
            margin-top: 20px;
            white-space: pre-wrap; /* Preserve whitespace and line breaks */
        }
        .printable-newsletter h4 {
            margin-top: 0;
        }
        .jumbotron {
            margin-bottom: 30px;
        }
        .jumbotron h3 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <nav class="navbar navbar-default top-navbar" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="home.php"><?php echo $_SESSION["user"]; ?></a>
        </div>

        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="usersetting.php"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
                    <li><a href="settings.php"><i class="fa fa-gear fa-fw"></i> Settings</a></li>
                    <li class="divider"></li>
                    <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <nav class="navbar-default navbar-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="main-menu">
                <li><a href="home.php"><i class="fa fa-dashboard"></i> Status</a></li>
                <li><a class="active-menu" href="messages.php"><i class="fa fa-desktop"></i> News Letters</a></li>
                <li><a href="roombook.php"><i class="fa fa-bar-chart-o"></i>Room Booking</a></li>
                <li><a href="Payment.php"><i class="fa fa-qrcode"></i> Payment</a></li>
                <li><a href="profit.php"><i class="fa fa-qrcode"></i> Profit</a></li>
                <li><a href="index1.php"><i class="fa fa-qrcode"></i>Online Payment</a></li>
                <li><a href="employee.php"><i class="fa fa-qrcode"></i>add employee</a></li>
                <li><a href="emp_details.php"><i class="fa fa-qrcode"></i>View employee</a></li>
                <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
            </ul>
        </div>
    </nav>
    <div id="page-wrapper">
        <div id="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="page-header">Newsletters <small>panel</small></h1>
                </div>
            </div>
            <?php
            include('connection.php');
            $mail = "SELECT * FROM `contact`";
            $rew = mysqli_query($con, $mail);
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="jumbotron">
                        <h3>Send The News Letters to Followers</h3>
                        <button class="btn btn-primary btn" data-toggle="modal" data-target="#myModal">Send New News Letters</button>
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title" id="myModalLabel">Compose News Letter - GRAND SINDHU</h4>
                                    </div>
                                    <form id="newsletterForm">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input name="title" class="form-control" placeholder="Enter Title">
                                            </div>
                                            <div class="form-group">
                                                <label>Subject</label>
                                                <input name="subject" class="form-control" placeholder="Enter Subject">
                                            </div>
                                            <div class="form-group">
                                                <label for="comment">News</label>
                                                <textarea name="news" class="form-control" rows="5" id="comment" placeholder="Enter News"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary" onclick="printNewsletter()">Print</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $sql = "SELECT * FROM `contact`";
            $re = mysqli_query($con, $sql);
            ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone Number</th>
                                            <th>Email</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Approval</th>
                                            <th>Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($row = mysqli_fetch_array($re)) {
                                            $id = $row['id'];
                                            $email = $row['email'];
                                            $emailLink = "<a href='mailto:$email'>$email</a>";
                                            $class = ($id % 2 == 1) ? 'gradeC' : 'gradeU';
                                            echo "<tr class='$class'>
                                                    <td>".$row['fullname']."</td>
                                                    <td>".$row['phoneno']."</td>
                                                    <td>".$emailLink."</td>
                                                    <td>".$row['cdate']."</td>
                                                    <td>".$row['approval']."</td>
                                                    <td><a href='newsletter.php?eid=$id' class='btn btn-primary'>Permission</a></td>
                                                    <td><a href='newsletterdel.php?eid=$id' class='btn btn-danger'>Delete</a></td>
                                                </tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/jquery.metisMenu.js"></script>
<script src="assets/js/dataTables/jquery.dataTables.js"></script>
<script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
<script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });

    function printNewsletter() {
        var title = document.querySelector('input[name="title"]').value;
        var subject = document.querySelector('input[name="subject"]').value;
        var news = document.querySelector('textarea[name="news"]').value;

        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Newsletter</title>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h1>GRAND SINDHU HOTEL</h1>'); // Displaying hotel name
        printWindow.document.write('<h3>Title: ' + title + '</h3>');
        printWindow.document.write('<h4>Subject: ' + subject + '</h4>');
        printWindow.document.write('<p>' + news + '</p>');
        printWindow.document.write('</body></html>');

        printWindow.document.close();
        printWindow.print();
        printWindow.close();
    }
</script>
</body>
</html>
