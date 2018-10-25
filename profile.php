<?php
session_start();
if (!isset($_SESSION["user"])) header("Location:login.php");
$user = json_decode($_SESSION["user"]);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/navbar.css">
	<link rel="stylesheet" type="text/css" href="css/messenger.css">
	<link rel="stylesheet" type="text/css" href="css/profile.css">
	 	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700">
	<link rel="stylesheet" type="text/css" href="css/chat.css">
	<title>Profile</title>
</head>
<body>

	<div class="navbar-fixed row">
		<nav class="nav-extended indigo accent-3" id="top">
			<div class="nav-wrapper">
				<a href="#" class="brand-logo col s2">
					<img id="logo" src="img/logo.png" class="col s5 valign-wrapper responsive-img">
					<span id="brand" class="col s6 right-align">MySocial</span>
				</a>
				<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
					<li><a href="#" id="log-out"><i class="material-icons">exit_to_app</i>LogOut</a></li>
				</ul>
				<ul class="side-nav" id="mobile-demo">
					<li><a href="#" id="log-out"><i class="material-icons">exit_to_app</i>LogOut</a></li>
				</ul>
			</div>
			<div class="nav-content col s12">
				<ul class="tabs tabs-transparent col s9">
					<li class="tab"><a href="#profile" class="active">Profile</a></li>
					<li class="tab"><a href="#photos" id="showPhotos">Photos</a></li>
					<li class="tab disabled"><a href="#newsfeed">News Feed</a></li>
					<li class="tab"><a href="#friends">Friends</a></li>
					<li class="tab disabled"><a href="#settings">Settings</a></li>
					<li class="tab"><a href="#notifications">Notifications <span class="new badge hide orange lighten-1"></span></a></li>
					<li class="tab hide"><a href="#guest">Guest</a></li>
				</ul>
			</div>
		</nav>
	</div>

	<div id="messenger"></div>
	<div id="chat-area"></div>

	<div id="_body" class="row">

		<div class="col s10" id="profile" data-user="<?= $user->id; ?>">
			<div class="col s3" id="main-img"></div>
			<div class="col s3 userInfo">
				<p class="txt">Name: <span id="myname"><?= $user->name; ?></span></p>
				<p class="txt">Surname: <span id="mysurname"><?= $user->surname; ?></span></p>
			</div>
			<div class="input-field col s4 offset-s1" id="srch">
				<input id="search" type="text" placeholder="search" class="col s12">
				<label class="label-icon" for="search"><i class="material-icons">search</i></label>
				<div id="search-result">
					<ul class="collection">
					</ul>
				</div>
			</div>
		</div>


		<div class="col s9" id="photos">
			<div id="gallery" class="col s12">
				<div class="photo col s3 blue accent-2 valign-wrapper">
					<a href="#add-image" class="col s12 center-align">
						<i class="material-icons">add_a_photo</i>
					</a>
				</div>
			</div>
		</div>


		<div class="col s9" id="newsfeed">	
		</div>


		<div class="col s9" id="friends">
		</div>


		<div class="col s9" id="settings">
		</div>


		<div class="col s9" id="guest">
			<div class="col s3" id="g_main-img"></div>
			<div class="col s3 userInfo">
				<p class="txt">Name: </p>
				<p class="txt">Surname: </p>
				<p><button class="btn indigo accent-2 mbtn send-message"><span>Message </span><i class="material-icons">send</i></button></p>
			</div>
		</div>


		<div class="col s9" id="notifications">
			<ul class="collection col s6">
				<label>No new notifications</label>
			</ul>
		</div>



		<div id="add-image" class="modal indigo accent-3 row">
			<div class="modal-content col s12">
				<form id="img-form" enctype="multipart/form-data">
					<div class="file-field input-field">
						<div class="btn">
							<span>Browse image</span>
							<input type="file" name="img" id="upload">
						</div>
						<div class="file-path-wrapper">
							<input class="file-path validate" type="text">
						</div>
					</div>

					<p>
						<input class="m_radio" name="main" type="radio" id="a1" value="0" checked="checked">
						<label for="a1">not main</label>
					</p>
					<p>
      					<input class="m_radio" name="main" type="radio" id="a2" value="1">
      					<label for="a2">main</label>
					</p>
						
					<img src="" class="hide col s6 offset-s3">
				</form>
			</div>
			<div class="modal-footer indigo accent-2 col s12">
				<button id="save" class="modal-action modal-close waves-effect waves-green btn red lighten-1" disabled="">Upload</button>
				<button id="cancel" class="modal-action modal-close waves-effect waves-green btn orange lighten-1">Cancel</button>
			</div>
		</div>


		<div id="delete-image" class="modal indigo accent-3 row">
			<div class="modal-content col s12">
				<h5>Are you sure you want to delete this photo?</h5>
				<div class="row">
					<img class="col s8 offset-s2" src="">
				</div>
			</div>
			<div class="modal-footer indigo accent-2 col s12">
				<button id="delete" class="modal-action modal-close waves-effect waves-green btn red lighten-1">Yes</button>
				<button class="modal-action modal-close waves-effect waves-green btn orange lighten-1">No</button>
			</div>
		</div>


		<div id="zoom-image" class="modal indigo accent-3 row">
			<div class="modal-content">
				<img src="" class="col s12 modal-close">
			</div>
		</div>


		<div id="profile-image" class="modal indigo accent-3 row">
			<div class="modal-content col s12">
				<h5>Set as profile picture: 
						<span>
							<input name="set_main" type="radio" id="mn1" value="0" class="m_radio" checked="checked">
							<label for="mn1">No</label>	
						</span>
						<span>
							<input name="set_main" type="radio" id="mn2" value="1" class="m_radio">
      						<label for="mn2">Yes</label>
						</span>
				</h5>
				<h6></h6>
				<div class="row">
					<img class="col s8 offset-s2" src="">
				</div>
			</div>
			<div class="modal-footer indigo accent-2 col s12">
				<button id="setMain" class="modal-action modal-close waves-effect waves-green btn green lighten-1">Yes</button>
				<button class="modal-action modal-close waves-effect waves-green btn orange lighten-1">No</button>
			</div>
		</div>

	</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
<script type="text/javascript" src="js/profile.js"></script>
<script type="text/javascript" src="js/gallery.js"></script>
<script type="text/javascript" src="js/messenger.js"></script>
<script type="text/javascript" src="js/guest.js"></script>
<script type="text/javascript" src="js/search.js"></script>
<script type="text/javascript" src="js/notifications.js"></script>
</html>