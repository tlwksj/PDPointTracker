<!DOCTYPE HTML>
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
    <div>
        <nav class="menu">
              <a class="icon" onclick="hamburger()">
                <i class="fa fa-bars"></i>
              </a>
              <div id="sidebar">
                <ul>
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="add_student.php"> Students </a></li>
                    <li><a href="add_events.php"> Events </a></li>
                </ul>
              </div>
          </nav>
          <h1> PD point tracker </h1>
          </header>
          <div id=".right">
          <h2> Past Events</h2>
          <?php
            include 'config.php';
          
            $sql = "SELECT * FROM PDevents";
            $result = $conn->query($sql);
          ?>
          <table>
  <thead>
    <tr>
      <th>Event Name</th>
      <th>Type</th>
      <th>Date</th>
      <th>PD points</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["type"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["PDworth"]) . "</td>";

        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='3'>No events found</td></tr>";
    }
    ?>
  </tbody>
</table>
  </div>
<div>
<h2> Students with the most PD points</h2>
          <?php
            include 'config.php';
          
            $sql = "SELECT PDstudent.name, year, PDstudent.PDworth FROM PDstudent INNER JOIN student_class USING(student_id) INNER JOIN PDclasses USING(class_code) LEFT JOIN eventattendance USING(student_id) LEFT JOIN PDevents USING(event_id)  GROUP BY student_id ORDER BY PDworth DESC LIMIT 5";
            $result = $conn->query($sql);
          ?>
<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Year</th>
      <th>PD points</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["year"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["PDworth"]) . "</td>";

        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='3'>No students found</td></tr>";
    }
    ?>
  </tbody>
</table>
</div>
    </body>
</html>
