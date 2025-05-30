<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GRANDSINDHU HOTEL</title>
    <!-- Bootstrap Styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Morris Chart Styles -->
    <!-- You can add other stylesheets as needed -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tr:hover {
            background-color: #ddd;
        }
        .action-buttons a {
            margin-right: 10px;
            text-decoration: none;
            color: #007BFF;
        }
        .action-buttons a:hover {
            text-decoration: underline;
        }
        .edit-form {
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php
    include 'connection.php';

    // Handle delete request
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $sql_delete = "DELETE FROM employees WHERE id = $delete_id";
        if (mysqli_query($con, $sql_delete)) {
            echo "<p>Employee deleted successfully</p>";
        } else {
            echo "<p>Error deleting employee: " . mysqli_error($con) . "</p>";
        }
    }

    // Handle edit form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_id'])) {
        $edit_id = $_POST['edit_id'];
        $name = $_POST['name'];
        $position = $_POST['position'];
        $salary = $_POST['salary'];
        $email = $_POST['email'];
        $phoneno = $_POST['phoneno'];
        $shift = $_POST['shift'];

        $sql_update = "UPDATE employees SET name='$name', position='$position', salary='$salary', email='$email', phoneno='$phoneno', shift='$shift' WHERE id=$edit_id";
        if (mysqli_query($con, $sql_update)) {
            echo "<p>Employee details updated successfully</p>";
        } else {
            echo "<p>Error updating employee details: " . mysqli_error($con) . "</p>";
        }
    }

    // Query to retrieve all employees
    $sql = "SELECT * FROM employees";
    $result = mysqli_query($con, $sql);

    // Check if there are any results
    if (mysqli_num_rows($result) > 0) {
        echo "<h2>Employee Details</h2>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Name</th><th>Position</th><th>Salary</th><th>Email</th><th>Phone Number</th><th>Shift</th><th>Actions</th></tr>";
        
        // Output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row["id"]."</td>";
            echo "<td>".$row["name"]."</td>";
            echo "<td>".$row["position"]."</td>";
            echo "<td>₹".$row["salary"]."</td>"; // Assuming salary is stored as numeric
            echo "<td>".$row["email"]."</td>";
            echo "<td>".$row["phoneno"]."</td>"; // Assuming 'phoneno' is the column name for phone number
            echo "<td>".$row["shift"]."</td>";
            echo "<td class='action-buttons'>";
            echo "<a href='?edit_id=".$row["id"]."'>Edit</a>";
            echo "<a href='?delete_id=".$row["id"]."' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>No employees found</p>";
    }

    // Check if edit_id is set
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];

        // Fetch employee details
        $sql_edit = "SELECT * FROM employees WHERE id = $edit_id";
        $result_edit = mysqli_query($con, $sql_edit);
        $row_edit = mysqli_fetch_assoc($result_edit);
        
        // Display edit form
        if ($row_edit) {
            ?>
            <h2>Edit Employee</h2>
            <div class="edit-form">
                <form action="emp_details.php" method="post">
                    <input type="hidden" name="edit_id" value="<?php echo $row_edit['id']; ?>">
                    <label for="name">Name:</label>
                    <input type="text" name="name" value="<?php echo $row_edit['name']; ?>"><br>
                    <label for="position">Position:</label>
                    <input type="text" name="position" value="<?php echo $row_edit['position']; ?>"><br>
                    <label for="salary">Salary:</label>
                    <input type="text" name="salary" value="<?php echo $row_edit['salary']; ?>"><br>
                    <label for="email">Email:</label>
                    <input type="text" name="email" value="<?php echo $row_edit['email']; ?>"><br>
                    <label for="phoneno">Phone Number:</label>
                    <input type="text" name="phoneno" value="<?php echo $row_edit['phoneno']; ?>"><br>
                    <label for="shift">Shift:</label>
                    <input type="text" name="shift" value="<?php echo $row_edit['shift']; ?>"><br>
                    <input type="submit" value="Update">
                </form>
            </div>
            <?php
        } else {
            echo "<p>Employee not found</p>";
        }
    }

    mysqli_close($con);
    ?>
</body>
</html>
