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
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<title>Login</title>
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
				<li><a href="signup.php">Sigup</a></li>
			</ul>
		</div>
	</nav>


	<div id="_body" class="row">

		<h2 class="center-align">Log In</h2>

		<form id="login" class="col s12">

			<div class="row">
        		<div class="input-field col s4 offset-s4">
          			<input id="email" type="email" class="validate" name="email">
          			<label for="email" data-error="wrong" data-success="right">Email</label>
        		</div>
     		</div>

     		<div class="row">
        		<div class="input-field col s4 offset-s4">
          			<input id="pwd" type="password" name="pwd">
          			<label for="pwd">Password</label>
        		</div>
     		</div>

     		<div class="col s12 center-align">
     			<button id="sbm" class="btn waves-effect waves-light amber accent-3" type="button">Login</button>
     		</div>

		</form>
		
		<div class="col s4 offset-s4 red hide" id="error"></div>

	</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
<script type="text/javascript" src="js/login.js"></script>
</html>