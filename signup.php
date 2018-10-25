<?php 
session_start(); 
if (isset($_SESSION["user"])) header("Location:profile.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/navbar.css">
	<link rel="stylesheet" type="text/css" href="css/signup.css">
	<title>SignUp</title>
</head>
<body>

	<nav>
		<div class="nav-wrapper indigo accent-3 row">
			<a href="index.php" class="brand-logo col s2">
				<img id="logo" src="img/logo.png" class="col s5 valign-wrapper responsive-img">
				<span id="brand" class="col s6 right-align">MySocial</span>
				</a>
			<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li><a href="index.php">Home</a></li>
				<li><a href="login.php">Login</a></li>
			</ul>
		</div>
	</nav>


	<div id="_body" class="row">

		<h2 class="center-align">Sign Up</h2>

		<form id="signup" class="col s12">

			<div class="row">
        		<div class="input-field col s4 offset-s4">
          			<input id="name" type="text" name="name">
          			<label for="name">Name</label>
        		</div>
     		</div>

     		<div class="row">
        		<div class="input-field col s4 offset-s4">
          			<input id="surn" type="text" name="surn">
          			<label for="surn">Surame</label>
        		</div>
     		</div>

     		<div class="row">
        		<div class="input-field col s4 offset-s4">
          			<input id="age" type="number" name="age">
          			<label for="age">Age</label>
        		</div>
     		</div>

			<div class="row">
        		<div class="input-field col s4 offset-s4">
          			<input id="email" type="email" class="validate" name="email">
          			<label for="email" data-error="wrong" data-success="right">Email</label>
        		</div>
     		</div>

     		<div class="row">
        		<div class="input-field col s4 offset-s4">
          			<input id="pwd1" type="password" class="validate" name="pwd1">
          			<label for="pwd1">Password</label>
        		</div>
     		</div>

     		<div class="row">
        		<div class="input-field col s4 offset-s4">
          			<input id="pwd2" type="password" class="validate" name="pwd2">
          			<label for="pwd2">Password confirm</label>
        		</div>
     		</div>

     		<div class="col s12 center-align">
     			<button id="sbm" class="btn waves-effect waves-light amber accent-3" type="button">Signup</button>
     		</div>

		</form>

    <div class="col s4 offset-s4 red hide" id="error"></div>
	</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
<script type="text/javascript" src="js/signup.js"></script>
</html>