<?php

session_start();
include_once "database.php";
include_once "user.php";
$db = new Database("tk_social");


class Functions
{
	private function __construct(){ }

	static function stdToUser(stdClass $user){
		return new User($user->name,$user->surname,$user->age,$user->email,$user->id);
	}

	static function signUp($n, $s, $a, $e, $p){
		$arr = array("name"=>$n, "surname"=>$s, "age"=>$a, "email"=>$e, "password"=>$p);
		$error = self::validate($arr);
		if(empty($error)){
			self::saveUser($arr);
			self::logIn($e,$p);
		}
		return $error;
	}

	private static function validate(Array $arr){
		$error = "";
		foreach ($arr as $key => $value) {
			if(empty($value)) {
				$error = "Please fill all the fields.";
				return $error;
			}
			switch ($key) {
				case 'name':
					if(!preg_match("#^[aA-zZ0-9\-_'.]+$#",$value)){
						$error .= "<p>Name can only contain letters (a-z), numbers (0-9), dashes (-), underscores (_), apostrophes ('), and periods (.).</p>";
					}
				break;

				case 'surname':
					if(!preg_match("#^[aA-zZ0-9\-_'.]+$#",$value)){
						$error .= "<p>Surname can only contain letters (a-z), numbers (0-9), dashes (-), underscores (_), apostrophes ('), and periods (.).</p>";
					}
				break;

				case 'email':
					if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
						$true_email = filter_var($value, FILTER_SANITIZE_EMAIL);
						$error .= "<p>Wrong email!<br>Did you mean $true_email ?</p>";
					}
					else if(self::emailVerify($value)) {
						$error = "<p>$value is already registered to another account.</p>";
						if(strpbrk($value, "@") && strpbrk($value, ".") && strpos($value,".") > strpos($value, "@") + 1){
							$error .= "<p>You can use</p>";
						}
						$p = strrpos($value, "@");
						$end = strrchr($value, "@");
						$n = 1;
						$emails = array();
						while(count($emails) < 3){
							$email = substr($value, 0, $p).$n.$end;
							if(self::emailVerify($email) == false){
								$emails[] = $email;
								$error .= "<p class='email' style='cursor: pointer; color: white'>$email</p><br>";
							}
							$n++;	
						}
					}
				break;

				case 'age':
					if(!ctype_digit($value) || (int)$value<0 || (int)$value>150) $error .= "<br>The age must be greater or equal to 0 and less than 151.<br>";
				break;

				case 'password':
					if(strlen($value)<6 || strlen($value)>32
						|| strpbrk($value, "0123456789") == false
						|| strpbrk($value, "ABCDEFGHIJKLMNOPQRSTUVWXYZ") == false
						|| strpbrk($value, "abcdefghijklmnopqrstuvwxyz") == false){
						$error = $error."<br>Wrong password!<br>The password must contain numbers and both upper and lower case letters.<br>The length of the password must be at least 6 characters and not more than 32 characters.";
					}
				break;

				default:
					if(empty($value)) {
						$error = "Please fill all the fields.";
						return $error;
					}
				break;
			}
		}
		return $error;
	}

	private static function saveUser(Array $arr){
		global $db;
		$db->setTable("user");
		$pwd = password_hash($arr["password"], PASSWORD_DEFAULT);
		date_default_timezone_set("Asia/Yerevan");
		$reg_date =  date("Y-m-d H:i:s");

		return $db->insert(array("name"=>$arr["name"], "surname"=>$arr["surname"], "age"=>$arr["age"], "email"=>$arr["email"], "password"=>$pwd, "reg_date"=>$reg_date));
	}

	private static function emailVerify($e){
		global $db;
		$db->setTable("user");
		$user = $db->find(array("email"=>$e));
		return count($user) ? $user[0] : false;
	}

	static function logIn($e,$p){
		if(empty($e) || empty($p)){
			return "Please fill all the fields.";
		}
		$login = self::emailVerify($e);
		if($login){
			if (password_verify($p, $login["password"])) {
				$user = new User($login["name"], $login["surname"], $login["age"], $login["email"], $login["id"]);
				$_SESSION["user"] = json_encode($user);
				global $db;
				$db->setTable("user");
				$db->update(array("status"=>"1"), array("id"=>$user->id));
				return $user;
			}
			else return "Wrong password!";
		}
		else return $e." is not registered";
	}

	static function logOut(){
		$user = json_decode($_SESSION["user"]);
		global $db;
		$db->setTable("user");
		$db->update(array("status"=>"0"), array("id"=>$user->id));
		unset($_SESSION["user"]);
	}

	static function loadImages($user){
		$user = json_decode($user);
		global $db;
		$db->setTable("user_image");
		$images = $db->find(array("user_id"=>$user->id), array("image"));
		if(count($images)){
			$images_ = array();
			foreach ($images as $i) {
				$images_[] = $i["image"];
			}
			return $images_;
		}
		else return $images;
	}

	static function loadMainImage($user){
		global $db;
		$user = json_decode($user);
		$db->setTable("profileimage");
		$user_image = $db->getVal("image", array("id"=>$user->id));
		return empty($user_image) ? "0" : $user_image;
	}

	static function searchUser($txt){
		if(strlen($txt) == 0) return json_encode(array());
		$user = json_decode($_SESSION["user"]);
		global $db;
		$txt = strtolower(trim($txt));
		$query = "SELECT * FROM userInfo WHERE id != ".$user->id." AND (LCASE(name) LIKE '%".$txt."%' OR LCASE(surname) LIKE '%".$txt."%' OR CONCAT(LCASE(surname),' ',LCASE(name)) LIKE '%".$txt."%' OR CONCAT(LCASE(name),' ',LCASE(surname)) LIKE '%".$txt."%')";
		$res = $db->m_query($query);
		return json_encode($res);
	}

	static function checkRequest($u1, $u2){
		global $db;
		$db->setTable("friend_request");
		$res = $db->find(array("user1"=>$u1, "user2"=>$u2, "status"=>"0"));
		if(count($res) == 0){
			$res = $db->find(array("user1"=>$u2, "user2"=>$u1, "status"=>"0"));
			return count($res) ? 2 : 0;
		}
		else return 1;
	}

	static function loadFriends($user){
		global $db;
		return json_encode($db->getFriends($user->id));
	}

	static function checkFriend($u1, $u2){
		global $db;
		return $db->checkFriend($u1,$u2);
	}

	static function loadNotifications($user){
		global $db;
		return json_encode($db->getNotifications($user->id));
	}

	static function deleteNotification($request_id){
		global $db;
		$db->setTable("friend_request");
		$db->delete(array("id"=>$request_id));
	}

	static function loadMessages($u1, $u2){
		global $db;
		return json_encode($db->getMessages($u1,$u2));
	}
}

?>