<?php

include_once "functions.php";


if($_SERVER["REQUEST_METHOD"] == "POST"){

	if(isset($_POST["action"])){
		
		if($_POST["action"] == "sign_up"){
			print Functions::signUp($_POST["name"], $_POST["surname"], $_POST["age"], $_POST["email"], $_POST["password"]);
		}
		else if($_POST["action"] == "log_in"){
			$u = Functions::logIn($_POST["email"], $_POST["password"]);
			if($u instanceof User) $u = json_encode($u);
			print $u;
		}
		else if($_POST["action"] == "log_out"){
			Functions::logOut();
		}
		else if($_POST["action"] == "add_profile_image"){
			$user = Functions::stdToUser(json_decode($_SESSION["user"]));
			print $user->uploadImage($_FILES["img"], $_POST["main"]);
		}
		else if($_POST["action"] == "load_main_image"){
			print Functions::loadMainImage($_SESSION["user"]);
		}
		else if($_POST["action"] == "load_images"){
			print json_encode(Functions::loadImages($_SESSION["user"]));
		}
		else if($_POST["action"] == "delete_image"){
			$user = Functions::stdToUser(json_decode($_SESSION["user"]));
			print $user->deleteImage($_POST["img"]);
		}
		else if($_POST["action"] == "set_profile_image"){
			$user = Functions::stdToUser(json_decode($_SESSION["user"]));
			print $user->setProfileImage($_POST["img"], $_POST["main"]);
		}
		else if($_POST["action"] == "search_user"){
			print Functions::searchUser($_POST["txt"]);
		}
		else if($_POST["action"] == "friend_request"){
			$user = Functions::stdToUser(json_decode($_SESSION["user"]));
			$user->addFriend($_POST["user"]);
		}
		else if($_POST["action"] == "check_request"){
			$u1 = json_decode($_SESSION["user"])->id;
			$u2 = $_POST["user"];
			print Functions::checkRequest($u1, $u2);
		}
		else if($_POST["action"] == "friend_confirm"){
			$user = Functions::stdToUser(json_decode($_SESSION["user"]));
			print $user->confirmFriendship($_POST["user"]);
		}
		else if($_POST["action"] == "friend_deny"){
			$user = Functions::stdToUser(json_decode($_SESSION["user"]));
			$user->denyFriendship($_POST["user"]);
		}
		else if($_POST["action"] == "unfriend"){
			$user = Functions::stdToUser(json_decode($_SESSION["user"]));
			$user->unfriend($_POST["user"]);
		}
		else if($_POST["action"] == "load_friends"){
			print Functions::loadFriends(json_decode($_SESSION["user"]));
		}
		else if($_POST["action"] == "check_friend"){
			$u1 = json_decode($_SESSION["user"])->id;
			$u2 = $_POST["user"];
			print Functions::checkFriend($u1,$u2);
		}
		else if($_POST["action"] == "load_notifications"){
			print Functions::loadNotifications(json_decode($_SESSION["user"]));
		}
		else if($_POST["action"] == "delete_notification"){
			Functions::deleteNotification($_POST["request_id"]);
		}
		else if($_POST["action"] == "load_messages"){
			$user1 = json_decode($_SESSION["user"])->id;
			$user2 = $_POST["user"];
			print Functions::loadMessages($user1, $user2);
		}
		else if($_POST["action"] == "send_message"){
			$user = Functions::stdToUser(json_decode($_SESSION["user"]));
			print $user->sendMessage($_POST["user"], $_POST["message"]);
		}
		else if($_POST["action"] == "read_message"){
			$user = Functions::stdToUser(json_decode($_SESSION["user"]));
			$user->readMessage($_POST["user"]);
		}
	}
}

?>