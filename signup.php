<?php 
session_start();
require_once("mysqlconnect.php");
//unset($_SESSION["message"]);
?>
<html>
	<head>
		<title>Registration</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" href="resource/dlsulogo.png" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<style>
		/* Remove the navbar's default margin-bottom and rounded borders */ 
		.navbar {
		  margin-bottom: 0;
		  border-radius: 0;
		}

		/* Set height of the grid so .sidenav can be 100% (adjust as needed) */
		.row.content {height: 450px}

		/* Set gray background color and 100% height */
		.sidenav {
		  padding-top: 20px;
		  background-color: #f1f1f1;
		  height: 100%;
		}

		/* Set black background color, white text and some padding */
		footer {
		  background-color: #555;
		  color: white;
		  padding: 15px;
		}

		/* On small screens, set height to 'auto' for sidenav and grid */
		@media screen and (max-width: 767px) {
		  .sidenav {
			height: auto;
			padding: 15px;
		  }
		  .row.content {height:auto;} 
		}
		
		</style>
	</head>
	<body background="resource/green.jpg" style="background-attachment:fixed; background-repeat:no-repeat;">	
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>                        
				</button>
				<a class="img-fluid" href="home.html"><img align="middle" src="resource/logo.png"></a>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li class="active"><a href="#">Home</a></li>
					<li><a href="#">About</a></li>
					<li><a href="#">Projects</a></li>
					<li><a href="#">Contact</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span>Login</a></li>
					<li><a href="signup.php"><span class="glyphicon glyphicon-user"></span>Register</a></li>
				</ul>
			</div>
		  </div>
		</nav>
	
		<div style="padding-top:20px; padding-bottom: 20px;">
			<div align="center" margin="auto" class="container" style="background-color:#73CD6F; width:600px; padding-bottom:5px; border-radius: 25px; border: solid white">
			
				<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<h2 color="#332929">Register</h2>
					<div>
						<b><font size="1" color="#332929">First Name</font></b>
						<br>
						<input type="text" name="fname" placeholder="First Name" required>
					</div>
					<br>
					<div>
						<b><font size="1" color="#332929">Last Name</font></b>
						<br>
						<input type="text" name="lname" placeholder="Last Name" required>
					</div>
					<br>
					<div>
						<b><font size="1" color="#332929">Email</font></b>
						<br>
						<input type="email" name="email" placeholder="Email" required>
					</div>
					<br>
					<div>
						<b><font size="1" color="#332929">Number</font></b>
						<br>
						<input type="number" name="number" placeholder="Contact Number"  min = "0" max="99999999999" step="0"  required>
					</div>
					<br>
					<div>
						<b><font size="1" color="#332929">Username</font></b>
						<br>
						<input type="text" name="username" placeholder="User Name" required>
					</div>
					<br>
					<div>
						<b><font size="1" color="#332929">Password</font></b>
						<br>
						<input type="password" name="password" placeholder="Password" required>
					</div>
					<br>
					<div>
						<b><font size="1" color="#332929">Confirm Password</font></b>
						<br>
						<input type="password" name="cpassword" placeholder="Confirm Password" required>
					</div>
					<br>
					<div>
						<b><font size="1" color="#332929">User type:</font></b>
						<br>
							<select name="userType">
								<?php
									$query="select * from ref_userType;";
									$result=mysqli_query($dbc,$query);
									
									while($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
									echo "<option value='{$row['id']}'>{$row['description']}</option>";
									}
								?>
							</select>
					</div>
					<br>
					<button style="padding-bottom: 7px" type="submit" name ="submit" class="btn btn-outline-secondary">Submit</button>
				</form>
			</div>
		</div>
	</body>
</html>


<!-- Modal -->
<div id="successModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Success</h4>
      </div>
      <div class="modal-body">
        <p>From Submitted</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<?php

  $key = "Fusion";

  $passwordCheck = false;
  $userCheck = true;

if(isset($_POST['submit'])){

$_SESSION["message"]="";
  $pass = $_POST['password'];
  $cpass = $_POST['cpassword'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $userType = $_POST['userType'];
  $firstName = $_POST['fname'];
  $lastName = $_POST['lname'];
  $email = $_POST['email'];
  $contactNumber = $_POST['number'];

  if($pass != $cpass){
    $_SESSION["message"] .= "Passwords do not match. ";
  }

  if($_POST['userType'] == "0")$_SESSION["message"] .= "Please select a usertype. ";

  $checkQuery1 = "SELECT CONVERT(AES_DECRYPT(userName, '{$key}') USING utf8) FROM user WHERE username = AES_ENCRYPT('{$username}', '{$key}');";

  $checkResult1 = mysqli_query($dbc, $checkQuery1);

  if ($checkResult1->num_rows > 0)$_SESSION["message"] .= "Username already exists. ";
  
  $checkQuery2 = "SELECT CONVERT(AES_DECRYPT(email, '{$key}') USING utf8) FROM user WHERE email = AES_ENCRYPT('{$email}', '{$key}');";

  $checkResult2 = mysqli_query($dbc, $checkQuery2);

  if ($checkResult2->num_rows > 0)$_SESSION["message"] .= "Email already exists. ";

  if(($_SESSION["message"])==""){
    
    //SELECT  CONVERT(userName USING utf8) FROM bankdb.user;
    
    //Inserting onto Table
    $insertQuery = "INSERT INTO User(userName, password, userType, firstName, lastName, email, contactNo) VALUES 
    ( AES_ENCRYPT('{$username}', '{$key}'), AES_ENCRYPT('{$password}', '$key'), '{$userType}', AES_ENCRYPT('{$firstName}', '{$key}'), AES_ENCRYPT('{$lastName}', '{$key}'), AES_ENCRYPT('{$email}', '{$key}'), AES_ENCRYPT('{$contactNumber}', '{$key}'));";
    
    
    $insertResult = mysqli_query($dbc, $insertQuery);

     echo "<script>$('#successModal').modal('show')</script>"; // Show modal
          
  }
  else{

   echo "<script type='text/javascript'>alert('{$_SESSION["message"]}');</script>";

   
  }

  }
?>