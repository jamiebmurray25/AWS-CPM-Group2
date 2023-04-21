<?php
$servername = "localhost";
$username = "root";
$dbname = "myDB";

$conn = new mysqli($servername, $username, null, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM customer WHERE customer_id = 1";

$result = $conn->query($sql);

if ($result->num_rows) {
  $row = $result->fetch_assoc();
} else {
  echo "No customer found for that ID";
  die();
}

?>

<ol>
  <?php
  foreach ($row as $column) {
    echo "<li>" . $column . "</li>";
  }
  ?>
</ol>