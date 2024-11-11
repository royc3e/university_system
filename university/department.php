<?php
include 'db_connection.php';

// Handle Create Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $department_name = $_POST['department_name'];
    $building = $_POST['building'];
    $budget = !empty($_POST['budget']) ? $_POST['budget'] : null; // Optional budget

    // Check for duplicates
    $check_sql = "SELECT * FROM department WHERE department_name='$department_name' AND building='$building' AND budget='$budget'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        echo '<div class="error">Error: A department with the same name, building, and budget already exists.</div>';
    } else {
        // Insert the new department
        $sql = "INSERT INTO department (department_name, building, budget) VALUES ('$department_name', '$building', '$budget')";
        
        if ($conn->query($sql) === TRUE) {
            echo '<div class="success">New department created successfully.</div>';
        } else {
            echo '<div class="error">Error: ' . $sql . "<br>" . $conn->error;
        }
    }
}

// Handle Update Operation
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM department WHERE department_id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['department_id'];
    $department_name = $_POST['department_name'];
    $building = $_POST['building'];
    $budget = $_POST['budget'];

    // Update the department
    $sql = "UPDATE department SET department_name='$department_name', building='$building', budget='$budget' WHERE department_id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Department updated successfully.</div>';
    } else {
        echo '<div class="error">Error updating record: ' . $conn->error;
    }
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM department WHERE department_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Department deleted successfully.</div>';
    } else {
        echo '<div class="error">Error deleting record: ' . $conn->error;
    }
}

// Fetch all departments for display
$sql = "SELECT * FROM department";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Department Management</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c2c2c; /* Dark background */
            color: #e0e0e0; /* Light text color */
            margin: 20px;
            text-align: center;
            display: flex;
            justify-content: center;
        }

        h2, h3 {
            color: white; /* White headings */
            margin-bottom: 10px;
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
            0% {
                opacity: 1;
            }
            99% {
                opacity: 1;
            }
            100% {
                opacity: 0;
                display: none;
            }
        }

        .submit-button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            margin-bottom: 20px;
        }

        .back-button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            margin-bottom: 20px;
        }

        .back-button:hover, .submit-button:hover {
            background-color: #0056b3;
        }

        .container {
            max-width: 800px;
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

        .department-form {
            background-color: #3c3c3c;
            padding: 20px;
            width: 500px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
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

        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            margin-top: 10px;
            width: 50%;
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
        <h2>Department Management</h2>
        <a href="university.php" class="back-button">Back</a><br><br>

        <!-- Create Form -->
        <h3>Add New Department</h3>
        <form method="POST" class="department-form">
            <input type="hidden" name="department_id" value="<?php echo isset($row) ? $row['department_id'] : ''; ?>">
            <div class="form-group">
                <label for="department_name">Department Name:</label>
                <input type="text" id="department_name" name="department_name" required value="<?php echo isset($row) ? $row['department_name'] : ''; ?>">
            </div>

            <div class="form-group">
                <label for="building">Building:</label>
                <select id="building" name="building" required>
                    <option value="">Select Building</option>
                    <?php
                    // Fetch building names from the classroom table
                    $building_sql = "SELECT DISTINCT building FROM classroom";
                    $building_result = $conn->query($building_sql);

                    if ($building_result->num_rows > 0) {
                        while ($building_row = $building_result->fetch_assoc()) {
                            // Check if this building is selected in the form (for editing)
                            $selected = (isset($row) && $row['building'] === $building_row['building']) ? 'selected' : '';
                            echo "<option value='{$building_row['building']}' $selected>{$building_row['building']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="budget">Budget:</label>
                <input type="number" id="budget" name="budget" step="0.01" value="<?php echo isset($row) ? $row['budget'] : ''; ?>">
            </div>

            <input type="submit" class="submit-button" name="<?php echo isset($row) ? 'update' : 'create'; ?>" value="<?php echo isset($row) ? 'Update Department' : 'Create Department'; ?>">
            <input type="button" class="submit-button" value="Clear" onclick="clearForm()">
        </form>

        <!-- Read Table -->
        <h3>Department List</h3>
        <table>
            <tr>
                <th>Department Name</th>
                <th>Building</th>
                <th>Budget</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['department_name']}</td>
                        <td>{$row['building']}</td>
                        <td>" . (is_null($row['budget']) ? 'NULL' : $row['budget']) . "</td>
                        <td>
                            <a href='department.php?edit={$row['department_id']}' class='action-link'>Edit</a> |
                            <a href='department.php?delete={$row['department_id']}' class='action-link'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No departments found</td></tr>";
            }
            ?>
        </table>
    </div>
            
    <script>
        function clearForm() {
            document.getElementById('department_name').value = '';
            document.getElementById('building').value = '';
            document.getElementById('budget').value = '';
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
