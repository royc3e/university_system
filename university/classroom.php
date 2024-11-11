<?php
include 'db_connection.php';

// Handle Create Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $building = $_POST['building'];
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];

    // Check for duplicates before inserting
    $check_sql = "SELECT * FROM classroom WHERE building = '$building' AND room_number = '$room_number'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo '<div class="error">Error: A classroom in the same building with the same room number already exists.</div>';
    } else {
        // Insert the new classroom
        $sql = "INSERT INTO classroom (building, room_number, capacity) VALUES ('$building', '$room_number', $capacity)";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="success">New classroom created successfully.</div>';  // Success message
        } else {
            echo '<div class="error">Error: ' . $conn->error . '</div>';  // Error message for insertion failure
        }
    }
}

// Handle Update Operation
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM classroom WHERE classroom_id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['classroom_id'];
    $building = $_POST['building'];
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];

    $sql = "UPDATE classroom SET 
        building='$building', 
        room_number='$room_number', 
        capacity=$capacity
        WHERE classroom_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Classroom updated successfully.</div>';  // Success message
    } else {
        echo '<div class="error">Error updating record: ' . $conn->error . '</div>';  // Error message
    }
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM classroom WHERE classroom_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Classroom deleted successfully.</div>';  // Success message
    } else {
        echo '<div class="error">Error deleting record: ' . $conn->error . '</div>';  // Error message
    }
}

// Fetch distinct buildings from the department table
$building_sql = "SELECT DISTINCT building FROM department";
$building_result = $conn->query($building_sql);

// Fetch all classrooms for display
$sql = "SELECT * FROM classroom";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Classroom Management</title>
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

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #3c3c3c; /* Darker container background */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5); /* Shadow effect */
            text-align: left;
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

        .classroom-form {
            background-color: #3c3c3c;
            padding: 20px;
            width: 500px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
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

        .submit-button:hover {
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
    <h2>Classroom Management</h2>
    <a href="university.php" class="back-button">Back</a>

    <!-- Create Form -->
    <h3>Add New Classroom</h3>
    <form method="POST" class="classroom-form">
        <input type="hidden" name="classroom_id" value="<?php echo isset($row) ? $row['classroom_id'] : ''; ?>">
        <label for="building">Building:</label> <br>
        <input type="text" id="building" name="building" value="<?php echo isset($row) ? htmlspecialchars($row['building']) : ''; ?>" placeholder="Enter Building Name"><br>

        <label>Room Number:</label> <input type="text" id="room_number" name="room_number" required value="<?php echo isset($row) ? $row['room_number'] : ''; ?>">
        <label>Capacity:</label> <input type="number" id="capacity" name="capacity" required value="<?php echo isset($row) ? $row['capacity'] : ''; ?>">
    <div class="form-group">
        <input type="submit" name="<?php echo isset($row) ? 'update' : 'create'; ?>" value="<?php echo isset($row) ? 'Update Classroom' : 'Create Classroom'; ?>">
        <input type="button" class="submit-button" value="Clear" onclick="clearForm()">
    </div>
    </form>

    <!-- Read Table -->
    <h3>Classroom List</h3>
    <table>
        <tr>
            <th>Building</th>
            <th>Room Number</th>
            <th>Capacity</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['building']}</td>
                    <td>{$row['room_number']}</td>
                    <td>{$row['capacity']}</td>
                    <td>
                        <a href='classroom.php?edit={$row['classroom_id']}' class='action-link'>Edit</a> | 
                        <a href='classroom.php?delete={$row['classroom_id']}' class='action-link'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No classrooms found</td></tr>";
        }
        ?>
    </table>

</div>
    <script>
        function clearForm() {
            document.getElementById('building').value = '';
            document.getElementById('room_number').value = '';
            document.getElementById('capacity').value = '';
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
