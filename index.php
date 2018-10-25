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
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<title>social</title>
</head>
<body>
	<nav>
		<div class="nav-wrapper indigo accent-3 row">
			<a href="#" class="brand-logo col s2">
				<img id="logo" src="img/logo.png" class="col s5 valign-wrapper responsive-img">
				<span id="brand" class="col s6 right-align">MySocial</span>
				</a>
			<ul id="nav-mobile" class="right hide-on-med-and-down">
				<li><a href="login.php">Login</a></li>
				<li><a href="signup.php">Sigup</a></li>
			</ul>
		</div>
	</nav>
	<a href="signup.php"><img src="img/signup_now.png" id="reg" class="responsive-img col s2"></a>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
</html>