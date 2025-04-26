<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    $sql = "INSERT INTO pdstudents (name, year, PDworth) VALUES (?,?,0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name, $year);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Register Student </title>
</head>
<body>
    <h1> Register a new student</h1>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Student Name" required>
        <label for="year">Year:</label>
        <select name="year" id="year" required>
            <option value="">--Select Year--</option>
            <option value="Freshman">Freshman</option>
            <option value="Sophomore">Sophomore</option>
            <option value="Junior">Junior</option>
            <option value="Senior">Senior</option>
        </select><br><br>
        <button type="submit">Add Student</button>
    </form>

    <br>
    <a href="index.php">Back to Home</a>
</body>
</html>
