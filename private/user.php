<?php

interface IUser
{
	function uploadImage($img, $main);
	function deleteImage($img);
	function setProfileImage($img, $main);
	function addFriend($user);
	function confirmFriendship($user);
	function denyFriendship($user);
	function unfriend($user);
	function sendMessage($user, $txt);
	function readMessage($user);
}

class User implements IUser
{
	public $id = "";
	public $name = "";
	public $surname = "";
	public $age = "";
	public $email = "";

	function __construct($n,$s,$a,$e,$i){
		$this->name = $n;
		$this->surname = $s;
		$this->age = $a;
		$this->email = $e;
		$this->id = $i;
	}

	function uploadImage($img, $main=0){
		global $db;
		$db->setTable("user_image");

		if ($main) $db->update(array("main"=>0), array("user_id"=>$this->id));

		$type = explode("/", $img["type"]);
		$img["name"] = uniqid().".".$type[1];

		if (!file_exists("uploads")) mkdir("uploads");

		$path = "uploads/".$img["name"];
		move_uploaded_file($img["tmp_name"], $path);

		$path = "private/".$path;
		date_default_timezone_set("Asia/Yerevan");
		$date =  date("Y-m-d H:i:s");
		$db->insert(array("image"=>$path, "user_id"=>$this->id, "main"=>$main, "date"=>$date));
		return $path;
	}

	function deleteImage($img){
		global $db;
		$db->setTable("user_image");
		$m = $db->getVal("main", array("image"=>$img));
		$db->delete(array("image"=>$img));
		$img = substr($img, strpos($img, "uploads"));
		unlink($img);
		return $m;
	}

	function setProfileImage($img, $main){
		global $db;
		$db->setTable("user_image");
		$db->update(array("main"=>"0"),array("user_id"=>$this->id,"main"=>"1"));
		if($main == 0){
			return 0;
		}
		else{
			$db->update(array("main"=>"1"),array("image"=>$img));
			return 1;
		}
	}

	function addFriend($user){
		global $db;
		$db->setTable("friend");
		$res = $db->find(array("user1"=>$this->id, "user2"=>$user));
		if(count($res) == 0){
			$res = $db->find(array("user1"=>$user, "user2"=>$this->id));
			if(count($res)){
				return false;
			}
			else{
				$db->setTable("friend_request");
				$db->insert(array("user1"=>$this->id, "user2"=>$user));
			}
		}
		else return false;
	}

	function confirmFriendship($user){
		global $db;
		$db->setTable("friend_request");
		$db->update(array("status"=>"1"),array("user1"=>$user, "user2"=>$this->id));

		$db->setTable("friend");
		date_default_timezone_set("Asia/Yerevan");
		$date = date("Y-m-d H:i:s");
		$db->insert(array("user1"=>$user, "user2"=>$this->id, "date"=>$date));

		$db->setTable("user");
		return $db->getVal("status", array("id"=>$user));
	}

	function denyFriendship($user){
		global $db;
		$db->setTable("friend_request");
		$db->update(array("status"=>"0"),array("user1"=>$user, "user2"=>$this->id));
	}

	function unfriend($user){
		global $db;
		$db->deleteFriend($this->id, $user);
	}

	function sendMessage($user, $txt){
		global $db;
		$db->setTable("message");
		$time = time();
		$db->insert(array("user1"=>$this->id, "user2"=>$user, "txt"=>$txt, "time"=>$time, "status"=>"0"));
		return $time;
	}

	function readMessage($user){
		global $db;
		$db->setTable("message");
		$db->update(array("status"=>"1"), array("user1"=>$user, "user2"=>$this->id, "status"=>"0"));
	}
}

?>