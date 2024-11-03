<?php
include 'db_connection.php';

// Fetch departments for dropdown
$departments_sql = "SELECT DISTINCT department_name FROM department";
$departments_result = $conn->query($departments_sql);

// Handle Create Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $course_name = $_POST['course_name'];
    $department_name = $_POST['department_name'];
    $credits = $_POST['credits'];

    // Check for duplicates before inserting
    $check_sql = "SELECT * FROM course WHERE course_name = '$course_name' AND department_name = '$department_name'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo '<div class="error">Error: A course with the same name and department already exists.</div>';
    } else {
        // Insert the new course
        $sql = "INSERT INTO course (course_name, department_name, credits) VALUES ('$course_name', '$department_name', $credits)";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="success">New course created successfully.</div>';  // Success message
        } else {
            echo '<div class="error">Error: ' . $conn->error . '</div>';  // Error message for insertion failure
        }
    }
}

// Handle Update Operation
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM course WHERE course_id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['course_id'];
    $course_name = $_POST['course_name'];
    $department_name = $_POST['department_name'];
    $credits = $_POST['credits'];

    $sql = "UPDATE course SET 
        course_name='$course_name', 
        department_name='$department_name', 
        credits=$credits
        WHERE course_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Course updated successfully.</div>';  // Success message
    } else {
        echo '<div class="error">Error updating record: ' . $conn->error . '</div>';  // Error message
    }
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM course WHERE course_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Course deleted successfully.</div>';  // Success message
    } else {
        echo '<div class="error">Error deleting record: ' . $conn->error . '</div>';  // Error message
    }
}

// Fetch all courses for display
$sql = "SELECT * FROM course";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Management</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
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
            background-color: #d4edda;  /* Light green background */
            color: #155724;  /* Dark green text color */
            border: 1px solid #c3e6cb;  /* Light green border */
            padding: 10px;  /* Padding around the text */
            margin-bottom: 20px;  /* Margin below the success message */
            border-radius: 5px;  /* Rounded corners */
            animation: fadeOut 3s forwards;
        }

        @keyframes fadeOut {
            0% {
                opacity: 1;
            }
            99% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                display: none; /* This will hide the element */
            }
        }

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

        /* Container Styling */
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #3c3c3c; /* Darker container background */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5); /* Shadow effect */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #3c3c3c; /* Darker background for the table */
        }

        th, td {
            border: 1px solid #444; /* Darker borders */
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff; /* Blue header for contrast */
            color: white; /* White text in header */
        }

        tr:nth-child(even) {
            background-color: #4a4a4a; /* Darker even rows for contrast */
        }

        tr:hover {
            background-color: #555; /* Highlight row on hover */
        }

        .action-link {
            color: #007bff; /* Blue links for actions */
            text-decoration: none; /* Remove underline */
        }

        .action-link:hover {
            text-decoration: underline; /* Underline on hover */
        }

        .course-form {
            background-color: #3c3c3c; /* Form background */
            padding: 20px; /* Padding around form */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Shadow effect */
            margin-bottom: 20px; /* Margin below the form */
        }

        .form-group {
            margin-bottom: 15px; /* Space between form groups */
            text-align: left; /* Align text to the left */
        }

        label {
            display: block; /* Make label block-level */
            margin-bottom: 5px; /* Space below label */
            color: #e0e0e0; /* Label color */
        }

        input[type="text"],
        input[type="number"] {
            width: 100%; /* Full width inputs */
            padding: 10px; /* Padding inside inputs */
            border: 1px solid #444; /* Dark border */
            border-radius: 5px; /* Rounded corners */
            background-color: #555; /* Dark background for inputs */
            color: #e0e0e0; /* Light text color */
        }

        input[type="submit"] {
            background-color: #007BFF; /* Button background */
            color: white; /* Button text color */
            padding: 10px 15px; /* Padding for button */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners for button */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        input[type="button"] {
            background-color: #007BFF; /* Button background */
            color: white; /* Button text color */
            padding: 10px 15px; /* Padding for button */
            border: none; /* Remove border */
            border-radius: 5px; /* Rounded corners for button */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }

        input[type="button"]:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            width: 25%;
            font-size: 16px;
            background-color: #555;
            color: white;
            transition: border-color 0.3s;
        }

        select:focus {
            border-color: #007BFF; /* Change border color on focus */
            outline: none; /* Remove default outline */
        }

        select option {
            padding: 10px; /* Padding for options */
        }

        /* Adding a hover effect for better UX */
        select:hover {
            border-color: #007BFF; /* Change border color on hover */
        }

    </style>
</head>
<body>

        <div class="container">
            <h2>Course Management</h2>
            <a href="university.php" class="back-button">Back</a>

            <!-- Create Form -->
            <h3>Add New Course</h3>
            <form method="POST" class="course-form">
            <input type="hidden" name="course_id" value="<?php echo isset($row) ? $row['course_id'] : ''; ?>">

            <div class="form-group">
                <label for="course_name">Course Name:</label>
                <input type="text" id="course_name" name="course_name" required value="<?php echo isset($row) ? $row['course_name'] : ''; ?>">
            </div>

            <div class="form-group">
                <label for="department_name">Department Name:</label>
                <select id="department_name" name="department_name" required>
                <option value="">Select Department</option>
                <?php
                if ($departments_result->num_rows > 0) {
                    while ($department = $departments_result->fetch_assoc()) {
                        $selected = (isset($row) && $row['department_name'] == $department['department_name']) ? 'selected' : '';
                        echo "<option value=\"{$department['department_name']}\" $selected>{$department['department_name']}</option>";
                    }
                }
                ?>
            </select>
            </div>

            <div class="form-group">
                <label for="credits">Credits:</label>
                <input type="number" id="credits" name="credits" value="<?php echo isset($row) ? $row['credits'] : ''; ?>"> <!-- Removed 'required' -->
            </div>

            <input type="submit" class="submit-button" name="<?php echo isset($row) ? 'update' : 'create'; ?>" value="<?php echo isset($row) ? 'Update Course' : 'Create Course'; ?>">
            <input type="button" class="submit-button" value="Clear" onclick="clearForm()">

        </form>


    <!-- Read Table -->
    <h3>Course List</h3>
    <table>
        <tr>
            <th>Course ID</th>
            <th>Course Name</th>
            <th>Department Name</th>
            <th>Credits</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['course_id']}</td>
                    <td>{$row['course_name']}</td>
                    <td>{$row['department_name']}</td>
                    <td>{$row['credits']}</td>
                    <td>
                        <a href='course.php?edit={$row['course_id']}'>Edit</a> | 
                        <a href='course.php?delete={$row['course_id']}'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No courses found</td></tr>";
        }
        ?>
    </table>
</div>


<script>
    function clearForm() {
        document.getElementById('course_name').value = '';
        document.getElementById('department_name').value = '';
        document.getElementById('credits').value = '';
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
