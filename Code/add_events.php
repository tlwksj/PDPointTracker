<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Author: Trishelle Leal -->
        <link rel="stylesheet" type="text/css" href="pd_styling.css" media="screen,print" />
        <meta name="Author" content="Trishelle Leal">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="hamburguermenu.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title> PD Point Tracker </title>
    </head>
    <header>
        <nav class="menu">
              <a class="icon" onclick="hamburger()">
                <i class="fa fa-bars"></i>
              </a>
              <div id="sidebar">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="add_student.php"> Students </a></li>
                    <li><a href="add_events.php"  class="active"> Events </a></li>
                </ul> 
              </div>
          </nav>


    <h1> Events </h1>
</header>
    <body>
<form method="GET" class="search">
<h2> Search for a event</h2>
    <label for="search_name">Search by Name:</label>
    <input type="text" id="search_name" name="search_name" placeholder="Enter event name:">
    <button type="submit">Search</button>
</form>
<?php
    include 'config.php';
    if (isset($_GET['search_name'])) {
    $search_name = $_GET['search_name'];
    
    
    $stmt = $conn->prepare("SELECT PDevents.name, type, count(student_id) AS attendance, group_concat(PDstudent.name) AS students, PDevents.PDworth, date FROM PDevents LEFT JOIN eventattendance USING(event_id) LEFT JOIN PDstudent USING(student_id) WHERE PDevents.name LIKE ? GROUP BY event_id ");
    $search_name_param = "%" . $search_name . "%";
    $stmt->bind_param('s', $search_name_param);
    $stmt->execute();
    $result = $stmt->get_result();
?>
<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Type</th>
      <th>Attendance count</th>
      <th>Students in attendance</th>
      <th>PD points worth</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["type"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["attendance"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["students"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["PDworth"]) . "</td>";
  
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='3'>No events found</td></tr>";
      }
}
?>
</tbody>
</table>


<!-- divider -->

    <?php
    include 'config.php';
    $result = $conn->query("SELECT * FROM PDevents");
    $result2 = $conn->query("SELECT * FROM PDstudent INNER JOIN student_class USING(student_id)");


if ($_SERVER["REQUEST_METHOD"] == "POST"&& isset($_POST['submit2'])) {
    $event = $_POST['event'];
    $student = $_POST['student'];

    
    $sql = "INSERT INTO eventattendance (student_id, event_id) VALUES (?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii",$student, $event);
    $stmt->execute();
    
}
?>
    <form method="POST" action="">
    <h2> Register your attendance. </h2>
    <label for="event">Select Event:</label>
    <select name="event" id="event" required>
    <option value="">--Select Event--</option>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <option value="<?php echo $row['event_id']; ?>">
            <?php echo $row['name']; ?>
    <?php } ?>
    </select><br>
    <label for="student">Select student name:</label>
    <select name="student" id="student" required>
    <option value="">--Select Student--</option>
        <?php while ($row = $result2->fetch_assoc()) { ?>
            <option value="<?php echo $row['student_id']; ?>">
            <?php echo $row['name']; ?>
    <?php } ?>
    </select>
    <button type="submit" name="submit2">Add attendance</button>
    </form>


<!-- divider -->
    
    <?php
    include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"&& isset($_POST['submit'])) {
    $name = $_POST['name'];
    $Type = $_POST['type'];
    $year = $_POST['date'];
    $points = $_POST['PDpoints'];
    if(filter_var($points, FILTER_VALIDATE_INT) !== false){
    $sql = "INSERT INTO PDevents (name,Type, date, PDworth) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $Type,$year,$points);
    $stmt->execute();
    }
    else{
        echo "Invalid input";
    }
}

?>
    <form method="POST" action="" class="left2">
    <h2> Register a new event</h2>
    <label for="type">Event Name:</label>
        <input type="text" name="name" placeholder="Event Name" required>
        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="">--Select Type--</option>
            <option value="Talk">Talk</option>
            <option value="Club">Club</option>
        </select><br><br>
        <label for="pd">PD point worth:</label>
        <input type="text" name="PDpoints" placeholder="PD point worth" required>
        <label for="date">Event date:</label>
        <input type="date" name="date" placeholder="date" required>
    <button type="submit" name="submit">Add Event</button>
    
    </form>
    <br>
</body>
</html>