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

function deleteInvestment($conn, $id){
  if (mysqli_query($conn, "DELETE FROM investment WHERE investment_id = {intval($id)}")){
  }
  else{
    echo mysqli_error($conn);
  }
}

if (isset($_POST["action"])){ 
  switch ($_POST["action"]) {
    case "delete":
      deleteInvestment($conn,$_POST["investmentId"]);
      break;
   // Add edit and any other actions here.
    default:
      break;
  }
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

<!-- CODE FOR THE ADD INVESTMENT MODAL -->
<div class="modal" id="add-form">
	<div class="modal-content" id="form-content">
		<h2>New Investment Information</h2>
			<form action="." method="POST">
				<input type="hidden" name="action" value="add">
				<label for="investment-type">Investment Type:</label>
				<select id="investment-type" name="investment-type" required>
					<option value="">--Select--</option>
					<option value="Stocks">Stocks</option>
					<option value="Properties">Properties</option>
					<option value="Commodities">Commodities</option>
				</select>
				
				<label for="asset_name">Name:</label>
				<input type="text" id="asset_name" name="asset_name" required>
				
				<label for="date_acquired">Date Acquired:</label>
				<input type="date" id="date_acquired" name="date_acquired" required>
				
				<label for="asset_initial_value">Initial Value:</label>
				<input type="number" id="asset_initial_value" name="asset_initial_value" required>
				
				<label for="asset_current_value">Current Value:</label>
				<input type="number" id="asset_current_value" name="asset_current_value" required>
				
				<button type="submit">Submit</button>
			</form>
	</div>
</div>

<!-- CODE FOR THE EDIT INVESTMENT MODAL -->
<div class="modal" id="edit-form">
	<div class="modal-content" id="form-content">
		<h2>Edit Investment Entry Information</h2>
    <form action="." method="POST" id="investment-form">
      <input type="hidden" name="action" value="update">
      <label>Investment field to update</label>
      <select id="field-to-update" name="field-to-update" required>
        <option value="">--Select--</option>
        <option value="asset_name">Name</option>
        <option value="date_acquired">Date Acquired</option>
        <option value="asset_initial_value">Initial Value</option>
        <option value="asset_current_value">Current Value</option>
      </select>

      <div id="additional-inputs"></div>

      <button type="submit">Submit</button> 
    </form>
	</div>
</div>

<div class="modal" id="delete-confirmation">
  <div class="modal-content" id = "confirmation-content">
    <h2>Are you sure ?</h2>
    <h3>You are going to delete an asset.</h3>
    <form method="POST" onsubmit="setTimeout(function(){window.location.reload();},10);">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="investmentId" value="">
      <button id="submit-btn" type="submit">Yes</button>
    </form>
  </div>
</div>
  
<div id="overlay"></div>

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
      <button class="button buttonAdd" onclick='openAddForm()'>Add Investment</button>
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
          echo "<td><button id='edit-btn' type='submit' onclick='openEditForm()'>Edit</button></td>";
          echo "<td><button id='delete-btn' type='submit' onclick='openDeleteConfirmation({$asset['investment_id']})'>Delete</button></td>";
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
          echo "<td><button id='edit-btn' type='submit' onclick='openEditForm()'>Edit</button></td>";
          echo "<td><button id='delete-btn' type='submit' onclick='openDeleteConfirmation({$asset['investment_id']})'>Delete</button></td>";
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
          echo "<td><button id='edit-btn' type='submit' onclick='openEditForm()'>Edit</button></td>";
          echo "<td><button id='delete-btn' type='submit' onclick='openDeleteConfirmation({$asset['investment_id']})'>Delete</button></td>";
          echo "</tr>";
        }
        ?>
      </table>
    </div>
  </div>

</body>

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

  // EDIT FORM JAVASCRIPT

  // Get the form and the div where the additional inputs will be added
	const investmentForm = document.getElementById('investment-form');
	const additionalInputsDiv = document.getElementById('additional-inputs');

	// Listen for changes to the selected option
	const fieldToUpdateSelect = document.getElementById('field-to-update');
	fieldToUpdateSelect.addEventListener('change', () => {
    // Get the selected option value
    const selectedOption = fieldToUpdateSelect.value;

    // Clear any existing additional inputs
    additionalInputsDiv.innerHTML = '';

    // Add additional inputs based on the selected option
    if (selectedOption === 'asset_name') {
      additionalInputsDiv.innerHTML = '<label>Proposed value for Name:</label><input type="text" name="asset_name" required>';
    } else if (selectedOption === 'date_acquired') {
      additionalInputsDiv.innerHTML = '<label>Proposed value for Date Acquired:</label><input type="date" name="date_acquired" required>';
    } else if (selectedOption === 'asset_initial_value') {
      additionalInputsDiv.innerHTML = '<label>Proposed value for Intial Value:</label><input type="number" name="asset_initial_value" required>'; 
    } else if (selectedOption === 'asset_current_value') {
      additionalInputsDiv.innerHTML = '<label>Proposed value for Current Value:</label><input type="number" name="asset_current_value" required>'; 
    }
  }); 

  function openEditForm() {
    // Show the modal and overlay
    document.querySelector("#edit-form").style.display = "block";
    document.querySelector("#overlay").style.display = "block";

    // Disable scrolling on the background
    document.body.style.overflow = "hidden";
  }

  function closeEditForm() {
    // Hide the modal and overlay
    document.querySelector("#edit-form").style.display = "none";
    document.querySelector("#overlay").style.display = "none";

    // Enable scrolling on the background
    document.body.style.overflow = "auto";
  }

  function openDeleteConfirmation(id) {
    // Show the modal and overlay
    document.querySelector("#delete-confirmation").style.display = "block";
    document.querySelector('input[name="investmentId"]').value = id;
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
  document.getElementById("overlay").addEventListener("click", closeEditForm);
  document.getElementById("overlay").addEventListener("click", closeDeleteConfirmation);
</script>

</html>