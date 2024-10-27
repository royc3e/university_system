<!DOCTYPE html>
<html>
<head>
    <title>University Management System</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c2c2c; /* Dark background */
            color: #e0e0e0; /* Light text color */
            text-align: center;
            margin: 20px;
        }
        h1 {
            margin-bottom: 30px;
            color: white; /* White heading */
        }
        .container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            max-width: 800px; /* Set a max width for the container */
            margin: auto; /* Center the container */
            padding: 20px; /* Add some padding */
            background-color: #3c3c3c; /* Darker container background */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5); /* Shadow effect */
        }
        .button {
            display: block;
            width: 200px;
            padding: 15px;
            margin: 10px;
            background-color: #007BFF; /* Button color */
            color: white; /* Text color for buttons */
            text-decoration: none;
            font-size: 18px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #0056b3; /* Darker button on hover */
        }
    </style>
</head>
<body>

<h1>University Management System</h1>

<div class="container">
    <a href="instructor.php" class="button">Instructor</a>
    <a href="department.php" class="button">Department</a>
    <a href="course.php" class="button">Course</a>
    <a href="classroom.php" class="button">Classroom</a>
    <a href="time_slot.php" class="button">Time Slot</a>
    <a href="student.php" class="button">Student</a>
</div>

</body>
</html>
