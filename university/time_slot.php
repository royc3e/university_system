<?php
include 'db_connection.php';

// Handle Create Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Check for duplicates before inserting
    $check_sql = "SELECT * FROM time_slot WHERE day_of_week = '$day_of_week' AND start_time = '$start_time' AND end_time = '$end_time'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo '<div class="error">Error: A time slot with the same day, start time, and end time already exists.</div>';
    } else {
        // Insert the new time slot
        $sql = "INSERT INTO time_slot (day_of_week, start_time, end_time) VALUES ('$day_of_week', '$start_time', '$end_time')";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="success">New time slot created successfully.</div>';  // Success message
        } else {
            echo '<div class="error">Error: ' . $conn->error . '</div>';  // Error message for insertion failure
        }
    }
}

// Handle Update Operation
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM time_slot WHERE time_slot_id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['time_slot_id'];
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $sql = "UPDATE time_slot SET 
        day_of_week='$day_of_week', 
        start_time='$start_time', 
        end_time='$end_time'
        WHERE time_slot_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Time slot updated successfully.</div>';  // Success message
    } else {
        echo '<div class="error">Error updating record: ' . $conn->error . '</div>';  // Error message
    }
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM time_slot WHERE time_slot_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Time slot deleted successfully.</div>';  // Success message
    } else {
        echo '<div class="error">Error deleting record: ' . $conn->error . '</div>';  // Error message
    }
}

// Fetch all time slots for display
$sql = "SELECT * FROM time_slot";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Time Slot Management</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
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

        .back-button:hover {
            background-color: #0056b3;
        }

        .time_slot-form {
            background-color: #3c3c3c;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }

        h2, h3 {
            color: white; /* White headings */
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Time Slot Management</h2>

        <!-- Back to University button -->
        <a href="university.php" class="back-button">Back</a>
        
        <!-- Create Form -->
        <h3>Add New Time Slot</h3>
        <form method="POST" class="time_slot-form">
            <input type="hidden" name="time_slot_id" value="<?php echo isset($row) ? $row['time_slot_id'] : ''; ?>">
            <label>Day of Week:</label> 
            <input type="text" name="day_of_week" required value="<?php echo isset($row) ? $row['day_of_week'] : ''; ?>">
            <label>Start Time:</label> 
            <input type="time" name="start_time" required value="<?php echo isset($row) ? $row['start_time'] : ''; ?>">
            <label>End Time:</label> 
            <input type="time" name="end_time" required value="<?php echo isset($row) ? $row['end_time'] : ''; ?>"><br><br>
            <input type="submit" name="<?php echo isset($row) ? 'update' : 'create'; ?>" value="<?php echo isset($row) ? 'Update Time Slot' : 'Create Time Slot'; ?>">
        </form>

        <!-- Read Table -->
        <h3>Time Slot List</h3>
        <table>
            <tr>
                <th>Time Slot ID</th>
                <th>Day of Week</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['time_slot_id']}</td>
                        <td>{$row['day_of_week']}</td>
                        <td>{$row['start_time']}</td>
                        <td>{$row['end_time']}</td>
                        <td>
                            <a href='time_slot.php?edit={$row['time_slot_id']}'>Edit</a> | 
                            <a href='time_slot.php?delete={$row['time_slot_id']}'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No time slots found</td></tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>

<?php
$conn->close();
?>
