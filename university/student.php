<?php
include 'db_connection.php';

// Handle Create Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $first_name = $_POST['first_name'];
    $middle_initial = $_POST['middle_initial'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $department_name = $_POST['department_name'];
    $email = $_POST['email'];

    // Check for duplicates before inserting
    $check_sql = "SELECT * FROM student WHERE first_name = '$first_name' AND last_name = '$last_name' AND email = '$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo '<div class="error">Error: A student with the same name and email already exists.</div>';
    } else {
        // Insert the new student
        $sql = "INSERT INTO student (first_name, middle_initial, last_name, date_of_birth, department_name, email) 
                VALUES ('$first_name', '$middle_initial', '$last_name', '$date_of_birth', '$department_name', '$email')";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="success">New student created successfully.</div>';  // Success message
        } else {
            echo '<div class="error">Error: ' . $conn->error . '</div>';  // Error message for insertion failure
        }
    }
}

// Handle Update Operation
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM student WHERE student_id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['student_id'];
    $first_name = $_POST['first_name'];
    $middle_initial = $_POST['middle_initial'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $department_name = $_POST['department_name'];
    $email = $_POST['email'];

    $sql = "UPDATE student SET 
        first_name='$first_name', 
        middle_initial='$middle_initial', 
        last_name='$last_name', 
        date_of_birth='$date_of_birth', 
        department_name='$department_name', 
        email='$email'
        WHERE student_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Student updated successfully.</div>';  // Success message
    } else {
        echo '<div class="error">Error updating record: ' . $conn->error . '</div>';  // Error message
    }
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM student WHERE student_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Student deleted successfully.</div>';  // Success message
    } else {
        echo '<div class="error">Error deleting record: ' . $conn->error . '</div>';  // Error message
    }
}

// Fetch all students for display
$sql = "SELECT * FROM student";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        /* Error and Success Message Styles */
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

        /* General Page Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #2c2c2c;
            color: #e0e0e0;
            text-align: center;
            margin: 20px;
        }

        h2, h3 {
            color: white;
            text-align: center;
        }

        .container {
            max-width: 900px;
            margin: auto;
            padding: 20px;
            background-color: #3c3c3c;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            text-align: left;
        }

        /* Button Styling */
        .back-button {
            display: flex;
            background-color: #007BFF;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            margin: 20px auto;
            width: fit-content;
            max-width: 150px;
            text-align: center;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #444;
            padding: 10px;
            text-align: center;
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

        /* Input and Submit Button Styling */
        input[type="text"], input[type="date"], input[type="email"], input[type="submit"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px 0;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #555;
            color: #e0e0e0;
        }

        input[type="submit"], input[type="button"] {
            background-color: #007BFF;
            width: 20%;
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

        /* Centering the Submit Button */
        .form-group {
            margin-top: 20px;
            text-align: center;
        }

        .student-form {
            background-color: #3c3c3c;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
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

    </style>
</head>
<body>

<div class="container">
    <h2>Student Management</h2>

    <!-- Back to University button -->
    <a href="university.php" class="back-button">Back</a>

    <!-- Create Form -->
    <h3>Add New Student</h3>
        <form method="POST" class="student-form">
            <input type="hidden" id="student_id" name="student_id" value="<?php echo isset($row) ? $row['student_id'] : ''; ?>">
            <label>First Name:</label>
            <input type="text" id="first_name" name="first_name" required value="<?php echo isset($row) ? $row['first_name'] : ''; ?>">
            <label>Middle Initial:</label>
            <input type="text" id="middle_initial" name="middle_initial" maxlength="1" value="<?php echo isset($row) ? $row['middle_initial'] : ''; ?>">
            <label>Last Name:</label>
            <input type="text" id="last_name" name="last_name" required value="<?php echo isset($row) ? $row['last_name'] : ''; ?>">
            <label>Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required value="<?php echo isset($row) ? $row['date_of_birth'] : ''; ?>">
            <label>Department Name:</label>
            <input type="text" id="department_name" name="department_name" required value="<?php echo isset($row) ? $row['department_name'] : ''; ?>">
            <label>Email:</label>
            <input type="email" id="email" name="email" required value="<?php echo isset($row) ? $row['email'] : ''; ?>">
            <div class="form-group">
                <input type="submit" name="<?php echo isset($row) ? 'update' : 'create'; ?>" value="<?php echo isset($row) ? 'Update Student' : 'Create Student'; ?>">
                <input type="button" class="submit-button" value="Clear" onclick="clearForm()">
            </div>
        </form>

    <!-- Read Table -->
    <h3>Student List</h3>
    <table>
        <tr>
            <th>Student ID</th>
            <th>First Name</th>
            <th>Middle Initial</th>
            <th>Last Name</th>
            <th>Date of Birth</th>
            <th>Department Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['student_id']}</td>
                    <td>{$row['first_name']}</td>
                    <td>{$row['middle_initial']}</td>
                    <td>{$row['last_name']}</td>
                    <td>{$row['date_of_birth']}</td>
                    <td>{$row['department_name']}</td>
                    <td>{$row['email']}</td>
                    <td>
                        <a href='student.php?edit={$row['student_id']}'>Edit</a> | 
                        <a href='student.php?delete={$row['student_id']}'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No students found</td></tr>";
        }
        ?>
    </table>
</div>

    <script>
        function clearForm() {
            document.getElementById('student_id').value = '';
            document.getElementById('first_name').value = '';
            document.getElementById('middle_initial').value = '';
            document.getElementById('last_name').value = '';
            document.getElementById('date_of_birth').value = '';
            document.getElementById('department_name').value = '';
            document.getElementById('email').value = '';
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
