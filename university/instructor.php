<?php
include 'db_connection.php';

// Fetch distinct departments
$departments_sql = "SELECT DISTINCT department_id, department_name FROM department";
$departments_result = $conn->query($departments_sql);
$departments = $departments_result->fetch_all(MYSQLI_ASSOC);

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
    $salary = $_POST['salary'];
    $department_id = $_POST['department_id'];
    $identification_number = $_POST['identification_number'];  // Added

    // Check for duplicate instructor
    $check_sql = "SELECT * FROM instructor WHERE first_name=? AND last_name=? AND date_of_birth=?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param('sss', $first_name, $last_name, $date_of_birth);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo '<div class="error">Error: An instructor with the same name and date of birth already exists.</div>';
    } else {
        // Handle department_id for insert
        $department_value = empty($department_id) ? NULL : $department_id;

        $sql = "INSERT INTO instructor (first_name, middle_initial, last_name, street_number, street_name, apt_number, city, state, postal_code, date_of_birth, department_id, salary, identification_number) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssssssssssd', $first_name, $middle_initial, $last_name, $street_number, $street_name, $apt_number, $city, $state, $postal_code, $date_of_birth, $department_value, $salary, $identification_number);

        if ($stmt->execute()) {
            echo '<div class="success">New instructor created successfully.</div>';
        } else {
            echo '<div class="error">Error: ' . $stmt->error . '</div>';
        }
    }
}

// Handle Update Operation
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM instructor WHERE instructor_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $salary = isset($row) ? $row['salary'] : '';
    $identification_number = isset($row) ? $row['identification_number'] : ''; // Added
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
    $salary = $_POST['salary'];
    $department_id = $_POST['department_id'];
    $identification_number = $_POST['identification_number'];  // Added

    // Handle department_id for update
    $department_value = empty($department_id) ? NULL : $department_id;

    $sql = "UPDATE instructor SET first_name=?, middle_initial=?, last_name=?, street_number=?, street_name=?, apt_number=?, city=?, state=?, postal_code=?, date_of_birth=?, department_id=?, salary=?, identification_number=? 
            WHERE instructor_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssssssdi', $first_name, $middle_initial, $last_name, $street_number, $street_name, $apt_number, $city, $state, $postal_code, $date_of_birth, $department_value, $salary, $identification_number, $id);

    if ($stmt->execute()) {
        echo '<div class="success">Instructor updated successfully.</div>';
    } else {
        echo '<div class="error">Error: ' . $stmt->error . '</div>';
    }
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM instructor WHERE instructor_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo '<div class="success">Instructor deleted successfully.</div>';
    } else {
        echo '<div class="error">Error deleting record: ' . $stmt->error . '</div>';
    }
}



// Fetch all instructors for display
$sql = "SELECT instructor.*, department.department_name 
        FROM instructor 
        LEFT JOIN department ON instructor.department_id = department.department_id";
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

        .error, .success {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            padding: 8px 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-size: 14px; /* Smaller text size */
            z-index: 9999; /* Ensure it's on top of other content */
            width: auto;
            max-width: 80%; /* Limit the width of the message */
            text-align: center;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            animation: fadeOut 5s forwards;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #333;
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
            width: 600px; /* Set the desired width for the form */
            margin: auto; /* Center the form horizontally */
        }

        .instructor-form input[type="text"], 
        .instructor-form input[type="number"], {
            width: calc(100% - 22px); /* Adjust to ensure proper padding */
            padding: 10px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #555;
            color: #e0e0e0;
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
            flex: 1;
            min-width: 200px;
            display: flex;
            flex-direction: column;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-actions {
            text-align: left;
        }

        .form-group input,
        .form-group select {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input[type="date"] {
            max-width: 250px;
        }

        select {
            background-color: #555;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            width: 95%;
            font-size: 16px;
            transition: border-color 0.3s;
            color: white;
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

        .instructor-list {
            text-align: left;
            max-width: 1050px;
            margin: auto;
            padding: 20px;
            background-color: #333;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
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

        <!-- Form Fields -->
        <div class="form-row">
            <div class="form-group">
                <label for="identification_number">Identification Number:</label>
                <input type="text" id="identification_number" name="identification_number" value="<?php echo isset($row) ? $row['identification_number'] : ''; ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" required value="<?php echo isset($row) ? $row['first_name'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="middle_initial">Middle Initial:</label>
                <input type="text" id="middle_initial" name="middle_initial" value="<?php echo isset($row) ? $row['middle_initial'] : ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required value="<?php echo isset($row) ? $row['last_name'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="street_number">Street Number:</label>
                <input type="text" id="street_number" name="street_number" value="<?php echo isset($row) ? $row['street_number'] : ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="street_name">Street Name:</label>
                <input type="text" id="street_name" name="street_name" value="<?php echo isset($row) ? $row['street_name'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="apt_number">Apt Number:</label>
                <input type="text" id="apt_number" name="apt_number" value="<?php echo isset($row) ? $row['apt_number'] : ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo isset($row) ? $row['city'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="state">Province:</label>
                <input type="text" id="state" name="state" value="<?php echo isset($row) ? $row['state'] : ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="postal_code">Postal Code:</label>
                <input type="text" id="postal_code" name="postal_code" value="<?php echo isset($row) ? $row['postal_code'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="date_of_birth">Date of Birth:</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo isset($row) ? $row['date_of_birth'] : ''; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="salary">Salary:</label>
                <input type="number" step="0.01" id="salary" name="salary" value="<?php echo isset($row) ? $row['salary'] : ''; ?>">
            </div>
            <div class="form-group">
                <label for="department_id">Department:</label>
                <select id="department_id" name="department_id" class="department">
                    <option value="">Select Department</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo htmlspecialchars($dept['department_id']); ?>"
                            <?php echo (isset($row) && $row['department_id'] == $dept['department_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dept['department_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <input type="submit" name="<?php echo isset($row) ? 'update' : 'create'; ?>" value="<?php echo isset($row) ? 'Update Instructor' : 'Create Instructor'; ?>"><br>
            <input type="button" class="submit-button" value="Clear" onclick="clearForm()">
        </div>
    </form>
</div>

<div class="instructor-list">
    <h3>Instructor List</h3>
    <table>
        <thead>
            <tr>
                <th>Identification Number</th>
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
                <th>Salary</th>
                <th>Department Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['identification_number']) . "</td>
                        <td>{$row['first_name']}</td>
                        <td>{$row['middle_initial']}</td>
                        <td>{$row['last_name']}</td>
                        <td>{$row['street_number']}</td>
                        <td>{$row['street_name']}</td>
                        <td>{$row['apt_number']}</td>
                        <td>{$row['city']}</td>
                        <td>{$row['state']}</td>
                        <td>{$row['postal_code']}</td>
                        <td>" . date('F j, Y', strtotime($row['date_of_birth'])) . "</td>
                        <td>" . htmlspecialchars($row['salary']) . "</td>   
                        <td>" . (is_null($row['department_name']) ? 'N/A' : htmlspecialchars($row['department_name'])) . "</td>
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
            document.getElementById('identification_number').value = '';
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
            document.getElementById('salary').value = '';
            document.getElementById('department_id').value = '';
        }
    </script>
    
</body>
</html>

<?php
$conn->close();
?>
