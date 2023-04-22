<!-- Setting up connection with database and select, create, and delete function-->
<?php
$conn = null;
try{
    $conn = new mysqli("localhost", "root", "", "myDB");
    if (mysqli_connect_errno()){
        throw new Exception("Could not connect to database.");
    }
    }
catch (Exception $e){
    throw new Exception($e->getMessage());
}

function selectAll($conn){
  $results = $conn -> query("SELECT * FROM customer") -> fetch_all(MYSQLI_ASSOC);
  return $results;
}

?>

<!-- HTML css and js -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>

<!-- Add form -->
  <div class="modal" id="add-form">
    <div class="modal-content" id="form-content">
      <h2>New Client Information</h2>
        <form action="." method="POST">
          <input type="hidden" name="action" value="add">
          <label for="first-name">First Name:</label>
          <input type="text" id="first-name" name="first-name" required>

          <label for="last-name">Last Name:</label>
          <input type="text" id="last-name" name="last-name" required>

          <label for="email">Email:</label>
          <input type="email" id="email" name="email" required>

          <label for="phone">Phone Number:</label>
          <input type="tel" id="phone" name="phone" required>

          <label for="risk-profile">Risk Profile:</label>
          <select id="risk-profile" name="risk-profile" required>
            <option value="">--Select--</option>
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
          </select>

          <label for="portfolio-value">Portfolio Value:</label>
          <input type="number" id="portfolio-value" name="portfolio-value" required>

          <button type="submit">Submit</button>
        </form>
    </div>
  </div>

  <div class="modal" id="delete-confirmation">
    <div class="modal-content" id = "confirmation-content">
      <h2>Are you sure ?</h2>
      <h3>You are going to delete a client.</h3>
      <form action="." method="POST">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="customerId" value="">
        <button id="submit-btn" type="submit">Yes</button>
      </form>
    </div>
  </div>
  
  <div id="overlay"></div>
  
  <div id="mySidenav" class="sidenav">
    <a href="."> <img src="assets\images\homeicon.png" alt="Home" style="width:40px;height:40px;"></a>
  </div>

  <div id="myTopnav" class="topnav">
  <button class="button buttonSignout">Sign out</button>

  <div id="main-content">
    <h1>AWS Clients List</h1>
    <button id="add-btn" onclick="openAddForm()">Add Client</button>
    <div style="overflow: auto; max-height: 500px;">
      <table class="styled-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Risk Profile</th>
                <th>Portfolio Value</th>
                <th>Portfolio Assessed</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $results = selectAll($conn);
            foreach ($results as $row) {
              $clicklocation = "location.href='customers.php?id={$row['customer_id']}'";
              $assessed = "Not Yet";
              if ($row["portfolio_assessed"]){
                $assessed = "Done";
              }

              echo "<tr>";
              echo "<td onclick={$clicklocation}>{$row['customer_id']}</td>";
              echo "<td onclick={$clicklocation}>{$row['first_name']}</td>";
              echo "<td onclick={$clicklocation}>{$row['last_name']}</td>";
              echo "<td onclick={$clicklocation}>{$row['email']}</td>";
              echo "<td onclick={$clicklocation}>{$row['phone_number']}</td>";
              echo "<td onclick={$clicklocation}>{$row['risk_profile']}</td>";
              echo "<td onclick={$clicklocation}>{$row['portfolio_value']}</td>";
              echo "<td onclick={$clicklocation}>{$assessed}</td>";
              echo "<td><button id='delete-btn' onclick='openDeleteConfirmation({$row['customer_id']})'>Delete</button></td>";
              echo "</tr>";
            }
            ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<script>
  function openAddForm() {
    // Show the modal and overlay
    document.querySelector("#add-form").style.display = "block";
    document.querySelector("#overlay").style.display = "block";

    // Disable scrolling on the background
    document.body.style.overflow = "hidden";
  }

  function closeAddForm() {
    // Hide the modal and overlay
    document.querySelector("#add-form").style.display = "none";
    document.querySelector("#overlay").style.display = "none";

    // Enable scrolling on the background
    document.body.style.overflow = "auto";
  }

  function openDeleteConfirmation(id) {
    // Show the modal and overlay
    document.querySelector("#delete-confirmation").style.display = "block";
    document.querySelector('input[name="customerId"]').value = id;
    document.querySelector("#overlay").style.display = "block";

    // Disable scrolling on the background
    document.body.style.overflow = "hidden";
  }

  function closeDeleteConfirmation() {
    // Hide the modal and overlay
    document.querySelector("#delete-confirmation").style.display = "none";
    document.querySelector("#overlay").style.display = "none";

    // Enable scrolling on the background
    document.body.style.overflow = "auto";
  }


  // Close the modal when the user clicks outside of it
  document.getElementById("overlay").addEventListener("click", closeAddForm);
   document.getElementById("overlay").addEventListener("click", closeDeleteConfirmation);
</script>
</body>
</html>
