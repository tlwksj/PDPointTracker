
<!DOCTYPE html>
<html>
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
                    <li><a href="add_student.php" class="active"> Students </a></li>
                    <li><a href="add_events.php"> Events </a></li>
                </ul>
              </div>
          </nav>
          <h1>Students</h1>
    </header>
    <body>
<form method="GET" class="search">
<h2> Search for a student</h2>
    <label for="search_name">Search by Name:</label>
    <input type="text" id="search_name" name="search_name" placeholder="Enter student name">
    <button type="submit" id="search_name">Search</button>
</form>
    <?php
    include 'config.php';
    if (isset($_GET['search_name'])) {
    $search_name = $_GET['search_name'];
    
    
    $stmt = $conn->prepare("SELECT PDstudent.name, year, PDstudent.PDworth, group_concat(class_code) AS classes, group_concat(PDevents.name) AS events FROM PDstudent INNER JOIN student_class USING(student_id) INNER JOIN PDclasses USING(class_code) LEFT JOIN eventattendance USING(student_id) LEFT JOIN PDevents USING(event_id) WHERE PDstudent.name LIKE ? GROUP BY student_id");
    $search_name_param = "%" . $search_name . "%";
    $stmt->bind_param('s', $search_name_param);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    <table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Year</th>
      <th>Classes</th>
      <th>Events</th>
      <th>PD points worth</th>
    </tr>
  </thead>
  <tbody>
    <?php
  if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["year"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["classes"]) . "</td>";
          echo "<td>" . htmlspecialchars($row["events"]) . "</td>";
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

<br>
<!-- divider -->
    <?php
    include 'config.php';
    $result = $conn->query("SELECT * FROM PDclasses");

if ($_SERVER["REQUEST_METHOD"] == "POST"&& isset($_POST['submit2'])) {
    $name = $_POST['name'];
    $year = $_POST['year'];

    $sql = "INSERT INTO PDstudent (name, year, PDworth) VALUES (?,?,0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $year);
    $stmt->execute();
    if ($stmt->execute()) {
        $student_id = $stmt->insert_id;
    }
    $classes = $_POST['classes'];

    // Enroll student in selected classes
    if (!empty($classes)) {
        $stmt = $conn->prepare("INSERT INTO student_class (student_id, class_code) VALUES (?, ?)");
        foreach ($classes as $class_code) {
            $stmt->bind_param('is', $student_id, $class_code);
            $stmt->execute();
        }
    }

    echo "Student added successfully and enrolled in selected classes!";
}

?>
    <form method="POST" action="">
    <h2> Register a new student</h2>
        <input type="text" name="name" placeholder="Student Name" required>
        <label for="year">Year:</label>
        <select name="year" id="year" required>
            <option value="">--Select Year--</option>
            <option value="Freshman">Freshman</option>
            <option value="Sophomore">Sophomore</option>
            <option value="Junior">Junior</option>
            <option value="Senior">Senior</option>
        </select><br><br>

        <h4>Select Classes:</h4>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <label>
            <input type="checkbox" name="classes[]" value="<?php echo $row['class_code']; ?>">
            <?php echo $row['name']; ?>
        </label>
    <?php } ?>

    <button type="submit" name="submit2">Add Student</button>
    
    </form>
    <br>

    <!-- divider -->
    
    <?php
    include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"&& isset($_POST['classbutton'])) {
    $name = $_POST['name'];
    $code = $_POST['code'];
    $Semester = $_POST['semester'];
    $points = $_POST['PDpoints'];

    if(filter_var($points, FILTER_VALIDATE_INT) !== false){
    $sql = "INSERT INTO PDclasses (class_code,name, semester, PDreq) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $code,$name,$Semester,$points);
    $stmt->execute();
    }
    else{
        echo "Invalid input";
    }
}

?>
<br>
<br>
<br>
<br>
<br>
<br>
<br>



    <form method="POST" action="" class="left">
    <h2> Register a class </h2>
    <label for="type">Class code:</label>
        <input type="text" name="code" placeholder="Class code" required><br>
        <label for="type">Class name:</label>
        <input type="text" name="name" placeholder="Class name" required><br>
        <label for="semester">Semester:</label>
        <select name="semester" id="type" required>
            <option value="">--Select Semester--</option>
            <option value="Spring 2025">Spring 2025</option>
        </select><br><br>
        <label for="pd">PD point worth:</label>
        <input type="text" name="PDpoints" placeholder="PD point worth" required> <br>

    <button type="submit" name="classbutton">Add class</button>
    
    </form>
    <br>
</body>
</html>
