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
  }

  .sidenav {
    position: relative;
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
    text-align:right;
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

  .styled-table {
    background-color: white;
    border-collapse: collapse;
    font-size: 0.9em;
    min-width: 400px;
  }

  .styled-table {
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
  }

  .styled-table thead tr {
    background-color: #D9D9D9;
    color: black;
    text-align: left;
  }

  .styled-table th,
  .styled-table td {
      padding: 12px 15px;
      text-align: center;
  }

  .styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
  }



  .styled-table tbody tr:nth-of-type(even) {
      background-color: #f3f3f3;
  }

  .styled-table tbody tr:last-of-type {
      border-bottom: 2px solid #D9D9D9;
  }

  #main-content{
    position: relative;
    display: flex;
    flex-direction: column;
    text-align: center;
    align-items: center;
    justify-content: center;
  }

  #delete-btn{
    padding: 10px 20px;
    border: 1px solid red;
    color: red;
    background-color: white;
    border-radius: 10px;
    font-weight: 800;
    transition: all 0.1s;
  }

  #delete-btn:hover{
    background-color: red;
    color: white;
  }

  #add-btn{
    position: relative;
    margin-left: 1000px;
    padding: 10px 20px;
    border: 1px solid green;
    color: green;
    background-color: white;
    border-radius: 50px;
    font-weight: 800;
    margin-bottom: 10px;
    transition: all 0.1s;
  }

  #add-btn:hover{
    background-color: green;
    color: white;
  }



  @media screen and (max-height: 450px) {
    .sidenav {padding-top: 15px;}
    .sidenav a {font-size: 18px;}
  }
  </style>
</head>
<body>
  <div id="mySidenav" class="sidenav">
    <a href="."> <img src="assets\images\homeicon.png" alt="Home" style="width:40px;height:40px;"></a>
  </div>

  <div id="myTopnav" class="topnav">
  <button class="button buttonSignout">Sign out</button>

  <div id="main-content">
    <h1>AWS Clients List</h1>
    <button id="add-btn">Add Client</button>
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

              echo "<tr onclick={$clicklocation}>";
              echo "<td>{$row['customer_id']}</td>";
              echo "<td>{$row['first_name']}</td>";
              echo "<td>{$row['last_name']}</td>";
              echo "<td>{$row['email']}</td>";
              echo "<td>{$row['phone_number']}</td>";
              echo "<td>{$row['risk_profile']}</td>";
              echo "<td>{$row['portfolio_value']}</td>";
              echo "<td>{$assessed}</td>";
              echo "<td><button id='delete-btn'>Delete</button></td>";
              echo "</tr>";
            }
            ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
