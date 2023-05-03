<?php
$servername = "localhost";
$username = "root";
$dbname = "myDB";

$customer_id = $_GET["id"];

$conn = new mysqli($servername, $username, null, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Look up customer using their id and retreive their details
$customer_sql = "SELECT * FROM customer WHERE customer_id = $customer_id";

$result = $conn->query($customer_sql);

$customer = NULL;
if ($result->num_rows) {
  $customer = $result->fetch_assoc();
} else {
  echo "No customer found for that ID";
  die();
}

if ($customer["portfolio_assessed"] == 0) {
  $customer["advice_date"] = "N/A";
  $customer["advice_description"] = "N/A";
}


// Fetch customers investments using their id
$assets_sql = "SELECT * FROM investment WHERE customer_id = $customer_id";
$assets = [];
$result = $conn->query($assets_sql);

if ($result->num_rows) {
  for ($i = 0; $i < $result->num_rows; $i++) {
    if ($row = $result->fetch_assoc()) {
      $assets[$i] = $row;
    }
  }
}

// Sort assets by asset_type and calculate portfolio value
$stocks = [];
$commodities = [];
$properties = [];
$portfolio_value = 0.00;

foreach($assets as $asset) {
  if ($asset["asset_type"] == "Stocks"){
    $stocks[] = $asset;
  }

  if ($asset["asset_type"] == "Property"){
    $properties[] = $asset;
  }

  if ($asset["asset_type"] == "Commodities"){
    $commodities[] = $asset;
  }

  $portfolio_value += $asset["asset_current_value"];
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer</title>
  <link rel="stylesheet" href="assets/css/customers.css">
</head>

<body>

<div id="mySidenav" class="sidenav">
    <a href="."><img src="assets\images\homeicon.png" alt="Home" style="width:40px;height:40px;"></a>
  </div>

  <div id="myTopnav" class="topnav">
    <button class="button buttonSignout">Sign out</button>
  </div>

  <div class="main">
    <div id="myCustomerProfile" class="customerprofile">
      <h1>Customer Profile</h1>
      <button class="button buttonEdit">Edit profile</button>
      <hr>
      <div class="row">
        <div class="columnThree">
          <h2>Personal Information</h2>
          <p>Name: <?php echo $customer["first_name"] . " " . $customer["last_name"] ?></p>
          <p>Email: <?php echo $customer["email"] ?></p>
          <p>Phone: <?php echo $customer["phone_number"] ?></p>
        </div>
        <div class="columnThree">
          <h2>Financial Overview</h2>
          <h1 style="font-size:48px;">$<?php echo sprintf("%0.2f",$portfolio_value); ?> </h1>
          <p>Risk Profile: <?php echo $customer["risk_profile"] ?></p>
          <p>Last advice date: <?php echo $customer["advice_date"] ?></p>
        </div>
        <div class="columnThree">
          <img src="assets\images\adviceplaceholder.png" alt="Advice graph" style="max-width:100%;height:auto;">
        </div>
      </div>
    </div>
    <div class="investmentTable">
      <h2>Stocks</h2>
      <table class="styled-table" style="width:100%">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Date Acquired</th>
          <th>Initial Value</th>
          <th>Current Value</th>
          <th></th>
        </tr>
        <?php
        foreach ($stocks as $key => $asset) {
          echo "<tr>";
          echo "<td>" . $key + 1 . "</td>";
          echo "<td>" . $asset["asset_name"] . "</td>";
          echo "<td>" . $asset["date_acquired"] . "</td>";
          echo "<td>" . $asset["asset_initial_value"] . "</td>";
          echo "<td>" . $asset["asset_current_value"] . "</td>";
          echo "<td><button id='edit-btn'>Edit</button></td>";
          echo "</tr>";
        }
        ?>
      </table>
    </div>
    <div class="investmentTable">
      <h2>Properties</h2>
      <table class="styled-table" style="width:100%">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Date Acquired</th>
          <th>Initial Value</th>
          <th>Current Value</th>
          <th></th>
        </tr>
        <?php
        foreach ($properties as $key => $asset) {
          echo "<tr>";
          echo "<td>" . $key + 1 . "</td>";
          echo "<td>" . $asset["asset_name"] . "</td>";
          echo "<td>" . $asset["date_acquired"] . "</td>";
          echo "<td>" . $asset["asset_initial_value"] . "</td>";
          echo "<td>" . $asset["asset_current_value"] . "</td>";
          echo "<td><button id='edit-btn'>Edit</button></td>";
          echo "</tr>";
        }
        ?>
      </table>
    </div>
    <div class="investmentTable">
      <h2>Commodities</h2>
      <table class="styled-table" style="width:100%">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Date Acquired</th>
          <th>Initial Value</th>
          <th>Current Value</th>
          <th></th>
        </tr>
        <?php
        foreach ($commodities as $key => $asset) {
          echo "<tr>";
          echo "<td>" . $key + 1 . "</td>";
          echo "<td>" . $asset["asset_name"] . "</td>";
          echo "<td>" . $asset["date_acquired"] . "</td>";
          echo "<td>" . $asset["asset_initial_value"] . "</td>";
          echo "<td>" . $asset["asset_current_value"] . "</td>";
          echo "<td><button id='edit-btn'>Edit</button></td>";
          echo "</tr>";
        }
        ?>
      </table>
    </div>
  </div>

</body>

</html>