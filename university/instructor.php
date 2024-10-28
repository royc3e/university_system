<?php
include 'db_connection.php';

// Handle Create Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $first_name = $_POST['first_name'];
    $middle_initial = $_POST['middle_initial'];
    $last_name = $_POST['last_name'];
    $street_number = $_POST['street_number'];
    $street_name = $_POST['street_name'];
    $apt_number = $_POST['apt_number'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $date_of_birth = $_POST['date_of_birth'];
    $department_id = $_POST['department_id'];

    // Check for duplicate instructor
    $check_sql = "SELECT * FROM instructor WHERE first_name='$first_name' AND last_name='$last_name' AND date_of_birth='$date_of_birth'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo '<div class="error">Error: An instructor with the same name and date of birth already exists.</div>';
    } else {
        // Insert the new instructor
        $sql = "INSERT INTO instructor (first_name, middle_initial, last_name, street_number, street_name, apt_number, city, state, postal_code, date_of_birth, department_id) 
                VALUES ('$first_name', '$middle_initial', '$last_name', '$street_number', '$street_name', '$apt_number', '$city', '$state', '$postal_code', '$date_of_birth', '$department_id')";
        
        if ($conn->query($sql) === TRUE) {
            echo '<div class="success">New instructor created successfully.</div>';
        } else {
            echo '<div class="error">Error: ' . $conn->error . '</div>';
        }
    }
}

// Handle Update Operation
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM instructor WHERE instructor_id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['instructor_id'];
    $first_name = $_POST['first_name'];
    $middle_initial = $_POST['middle_initial'];
    $last_name = $_POST['last_name'];
    $street_number = $_POST['street_number'];
    $street_name = $_POST['street_name'];
    $apt_number = $_POST['apt_number'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $date_of_birth = $_POST['date_of_birth'];
    $department_id = $_POST['department_id'];

    $sql = "UPDATE instructor SET 
        first_name='$first_name', middle_initial='$middle_initial', last_name='$last_name',
        street_number='$street_number', street_name='$street_name', apt_number='$apt_number',
        city='$city', state='$state', postal_code='$postal_code', date_of_birth='$date_of_birth', 
        department_id='$department_id'
        WHERE instructor_id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Instructor updated successfully.</div>';
    } else {
        echo '<div class="error">Error:Error updating record: </div>'. $conn->error;
    }
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM instructor WHERE instructor_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Instructor deleted successfully;.</div>';
    } else {
        echo '<div class="error">Error:Error deleting record: </div>'. $conn->error;
    }
}

// Fetch all instructors for display
$sql = "SELECT * FROM instructor";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Instructor Management</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        /* Shared styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #2c2c2c; /* Dark background */
            color: #e0e0e0; /* Light text color */
            margin: 20px;
            text-align: center;
        }

        h2, h3 {
            color: white; /* White headings */
            margin-bottom: 10px;
            text-align: center;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            animation: fadeOut 5s forwards; 
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            animation: fadeOut 3s forwards;
        }

        @keyframes fadeOut {
            0% { opacity: 1; }
            99% { opacity: 1; }
            100% { opacity: 0; display: none; }
        }

        .submit-button {
            margin-left: 10px; /* Adds spacing to the left */
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .back-button {
            display: flex;
            text-align: center; /* Center text within the button */
            background-color: #007BFF;
            color: white;
            padding: 8px 12px; /* Padding can be adjusted as needed */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            margin: 20px auto; /* Center the button with auto margin on left and right */
            margin-top: 4px;
            width: fit-content; /* Button width fits its content */
            max-width: 150px; /* Set a maximum width for the button */
        }


        .back-button:hover, .submit-button:hover {
            background-color: #0056b3;
        }

        .container {
            text-align: left;
            max-width: 1000px;
            margin: auto;
            padding: 20px;
            background-color: #3c3c3c;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #3c3c3c;
        }

        th, td {
            border: 1px solid #444;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #4a4a4a;
        }

        tr:hover {
            background-color: #555;
        }

        .action-link {
            color: #007bff;
            text-decoration: none;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        .instructor-form {
            background-color: #3c3c3c;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }

        .instructor-list table {
            width: 50%;
            background-color: #3c3c3c;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .instructor-list th, .instructor-list td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .instructor-list th {
            background-color: #007bff;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #e0e0e0;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #555;
            color: #e0e0e0;
        }

        input[type="submit"], input[type="button"] {
            background-color: #007BFF;
            display: flex;
            align-items: center;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #0056b3;
        }

        .form-group {
            display: flex;                  /* Use flexbox */
            justify-content: center;        /* Center the button horizontally */
            margin-top: 20px;              /* Space above the button */
        }
    </style>
</head>
<body>
    
<div class="container">
    <h2>Instructor Management</h2>

    <!-- Back to University button -->
    <a href="university.php" class="back-button">Back</a>

    <!-- Create Form -->
    <h3>Add New Instructor</h3>
        <form method="POST" class="instructor-form">
            <input type="hidden" id="instructor_id" name="instructor_id" value="<?php echo isset($row) ? $row['instructor_id'] : ''; ?>">
            <label>First Name:</label> <input type="text" id="first_name" name="first_name" required value="<?php echo isset($row) ? $row['first_name'] : ''; ?>">
            <label>Middle Initial:</label> <input type="text" id="middle_initial" name="middle_initial" value="<?php echo isset($row) ? $row['middle_initial'] : ''; ?>">
            <label>Last Name:</label> <input type="text" id="last_name" name="last_name" required value="<?php echo isset($row) ? $row['last_name'] : ''; ?>">
            <label>Street Number:</label> <input type="text" id="street_number" name="street_number" value="<?php echo isset($row) ? $row['street_number'] : ''; ?>">
            <label>Street Name:</label> <input type="text" id="street_name" name="street_name" value="<?php echo isset($row) ? $row['street_name'] : ''; ?>">
            <label>Apt Number:</label> <input type="text" id="apt_number" name="apt_number" value="<?php echo isset($row) ? $row['apt_number'] : ''; ?>">
            <label>City:</label> <input type="text" id="city" name="city" value="<?php echo isset($row) ? $row['city'] : ''; ?>">
            <label>State:</label> <input type="text" id="state" name="state" value="<?php echo isset($row) ? $row['state'] : ''; ?>">
            <label>Postal Code:</label> <input type="text" id="postal_code" name="postal_code" value="<?php echo isset($row) ? $row['postal_code'] : ''; ?>">
            <label>Date of Birth:</label> <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo isset($row) ? $row['date_of_birth'] : ''; ?>">
            <label>Department ID:</label> <input type="number" id="department_id" name="department_id" value="<?php echo isset($row) ? $row['department_id'] : ''; ?>">
            <div class="form-group">
                <input type="submit" name="<?php echo isset($row) ? 'update' : 'create'; ?>" value="<?php echo isset($row) ? 'Update Instructor' : 'Create Instructor'; ?>">
                <input type="button" class="submit-button" value="Clear" onclick="clearForm()">
            </div>
        </form>
    </div>
<div class="container instructor-list">
    <h3>Instructor List</h3>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Middle Initial</th>
                <th>Last Name</th>
                <th>Street Number</th>
                <th>Street Name</th>
                <th>Apt Number</th>
                <th>City</th>
                <th>State</th>
                <th>Postal Code</th>
                <th>Date of Birth</th>
                <th>Department ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['instructor_id']}</td>
                        <td>{$row['first_name']}</td>
                        <td>{$row['middle_initial']}</td>
                        <td>{$row['last_name']}</td>
                        <td>{$row['street_number']}</td>
                        <td>{$row['street_name']}</td>
                        <td>{$row['apt_number']}</td>
                        <td>{$row['city']}</td>
                        <td>{$row['state']}</td>
                        <td>{$row['postal_code']}</td>
                        <td>{$row['date_of_birth']}</td>
                        <td>{$row['department_id']}</td>
                        <td>
                            <a href='instructor.php?edit={$row['instructor_id']}' class='action-link'>Edit</a> | 
                            <a href='instructor.php?delete={$row['instructor_id']}' class='action-link'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='13'>No instructors found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
    <script>
        function clearForm() {
            document.getElementById('first_name').value = '';
            document.getElementById('middle_initial').value = '';
            document.getElementById('last_name').value = '';
            document.getElementById('street_number').value = '';
            document.getElementById('street_name').value = '';
            document.getElementById('apt_number').value = '';
            document.getElementById('city').value = '';
            document.getElementById('state').value = '';
            document.getElementById('postal_code').value = '';
            document.getElementById('date_of_birth').value = '';
            document.getElementById('department_id').value = '';
        }
    </script>
    
</body>
</html>

<?php
$conn->close();
?>
