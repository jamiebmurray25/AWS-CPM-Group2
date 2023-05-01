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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      background-color: #F1F1F1;
      font-family: "Lato", sans-serif;
    }

    .main {
      margin-left: 120px;
      margin-right: 20px;
      display: flex;
      flex-direction: column;
    }

    .sidenav {
      height: 100%;
      width: 90px;
      position: fixed;
      vertical-align: middle;
      text-align: center;
      z-index: 1;
      top: 0;
      left: 0;
      background-color: #FFFFFF;
      overflow-x: hidden;
      padding-top: 20px;
    }

    .sidenav a {
      padding: 8px 8px 8px 32px;
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
      transition: 0.3s;
    }

    .sidenav a:hover {
      color: #f1f1f1;
    }

    .sidenav .closebtn {
      position: absolute;
      top: 0;
      right: 25px;
      font-size: 36px;
      margin-left: 50px;
    }

    .topnav {
      padding: 8px 32px 8px 8px;
      text-align: right;
      background-color: #F1F1F1;
      overflow: hidden;
    }

    .customerprofile {
      padding: 12px 12px 12px 12px;
      background-color: #D9D9D9;
    }

    .customerreports {
      padding: 12px 12px 12px 12px;
      background-color: #F1F1F1;
    }

    .columnThree {
      float: left;
      width: 33.33%;
      padding: 18px;
    }

    .investmentTable {
      width: 100%;
    }

    .row:after {
      content: "";
      display: table;
      clear: both;
    }

    .button {
      border: none;
      color: black;
      padding: 12px 18px 12px 18px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      cursor: pointer;
    }

    .buttonSignout {
      background-color: #D9D9D9;
      border-radius: 8px;
    }

    .buttonEdit {
      background-color: #FFFFFF;
      border-radius: 8px;
    }

    .styled-table {
      background-color: #D9D9D9;
      border-collapse: collapse;
      margin: 25px 0;
      font-size: 0.9em;
      min-width: 400px;
    }

    .styled-table th,
    .styled-table td {
      padding: 6px 8px;
    }

    .styled-table tbody tr {
      border-bottom: 1px solid #dddddd;
    }

    .styled-table tbody tr:nth-of-type(even) {
      background-color: #f3f3f3;
    }

    .styled-table tbody tr:last-of-type {
      border-bottom: 2px solid #333;
    }

    @media screen and (max-height: 450px) {
      .sidenav {
        padding-top: 15px;
      }

      .sidenav a {
        font-size: 18px;
      }
    }
  </style>
</head>

<body>

  <div id="mySidenav" class="sidenav">
    <img src="assets\images\homeicon.png" alt="Home" style="width:40px;height:40px;">
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
        </tr>
        <?php
        foreach ($stocks as $key => $asset) {
          echo "<tr>";
          echo "<td>" . $key + 1 . "</td>";
          echo "<td>" . $asset["asset_name"] . "</td>";
          echo "<td>" . $asset["date_accuired"] . "</td>";
          echo "<td>" . $asset["asset_initial_value"] . "</td>";
          echo "<td>" . $asset["asset_current_value"] . "</td>";
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
        </tr>
        <?php
        foreach ($properties as $key => $asset) {
          echo "<tr>";
          echo "<td>" . $key + 1 . "</td>";
          echo "<td>" . $asset["asset_name"] . "</td>";
          echo "<td>" . $asset["date_accuired"] . "</td>";
          echo "<td>" . $asset["asset_initial_value"] . "</td>";
          echo "<td>" . $asset["asset_current_value"] . "</td>";
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
        </tr>
        <?php
        foreach ($commodities as $key => $asset) {
          echo "<tr>";
          echo "<td>" . $key + 1 . "</td>";
          echo "<td>" . $asset["asset_name"] . "</td>";
          echo "<td>" . $asset["date_accuired"] . "</td>";
          echo "<td>" . $asset["asset_initial_value"] . "</td>";
          echo "<td>" . $asset["asset_current_value"] . "</td>";
          echo "</tr>";
        }
        ?>
      </table>
    </div>
  </div>
  </div>

  </div>

</body>

</html>