<?php
$servername = "localhost";
$username = "root";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, null, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM customer";

$result = $conn->query($sql);
?>


<ol>
  <?php
  while ($row = $result->fetch_assoc()) {
    echo "<li>" .  $row["first_name"] . "</li>";
  }
  ?>
</ol>