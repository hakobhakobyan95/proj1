<?php

class Database{
	private $db;
	private $table;


	public function __construct($db_name){
		$this->db = new mysqli("localhost","root","",$db_name);
		$this->db->query("SET NAMES UTF8");
	}

	public function setTable($table_name){
		$this->table = $table_name;
	}

	public function findAll(){
		$hraman = "SELECT * FROM $this->table";
		return $this->db->query($hraman)->fetch_all(MYSQLI_ASSOC);
	}

	public function find(Array $arr, Array $keys = array()){
		$hraman = "SELECT ";
		if(count($keys)){
			foreach ($keys as $k){
				$hraman .= $k.",";
			}
			$hraman = substr($hraman, 0, -1);
		}
		else{
			$hraman .= "*";
		}
		$hraman .= " FROM $this->table WHERE ";
		foreach ($arr as $k => $v) {
			$hraman .= $k." = '".$v."' and ";
		}
		$hraman = substr($hraman, 0, -4);
		return $this->db->query($hraman)->fetch_all(MYSQLI_ASSOC);
	}

	public function delete(Array $arr){
		$hraman = "DELETE FROM $this->table WHERE ";
		foreach ($arr as $k => $v) {
			$hraman .= $k." = '".$v."' and ";
		}
		$hraman = substr($hraman, 0, -4);
		$this->db->query($hraman);
	}

	public function update(Array $arr1, Array $arr2 = array()){
		$hraman = "UPDATE $this->table SET ";
		foreach ($arr1 as $k => $v) {
			$hraman .= $k." = '".$v."',";
		}
		$hraman = substr($hraman, 0, -1);
		if(count($arr2) != 0){
			$hraman .= " WHERE ";
			foreach ($arr2 as $k => $v) {
				$hraman .= $k." = '".$v."' and ";	
			}
			$hraman = substr($hraman, 0, -4);
		}
		$this->db->query($hraman);
	}

	public function insert(Array $arr){
		$hraman = "INSERT INTO $this->table(";
		foreach ($arr as $k => $v) {
			$hraman .= $k.",";
		}
		$hraman = substr($hraman, 0, -1).") VALUES(";
		foreach ($arr as $k => $v) {
			$hraman .= "'".$v."',";
		}
		$hraman = substr($hraman, 0, -1).")";
		$this->db->query($hraman);

		return $this->db->insert_id;
	}

	public function getVal($key, Array $arr){
		if(count($arr) == 0) return $arr;
		$hraman = "SELECT $key FROM $this->table WHERE ";
		foreach ($arr as $k => $v) {
			$hraman .= $k." = '".$v."' and ";	
		}
		$hraman = substr($hraman, 0, -4);
		$result = $this->db->query($hraman)->fetch_all(MYSQLI_ASSOC);
		return count($result) ? $result[0][$key] : $result;
	}

	public function m_query($que){
		return $this->db->query($que)->fetch_all(MYSQLI_ASSOC);
	}

	public function getFriends($user_id){
		$hraman  = "SELECT user.id,name,surname,email,image,user.status FROM ";
		$hraman .= "(SELECT user2 as user_id FROM friend WHERE user1 = $user_id UNION ";
		$hraman .= "SELECT user1 as user_id FROM friend WHERE user2 = $user_id) as friends ";
		$hraman .= "LEFT JOIN (SELECT user_id,image FROM user_image WHERE main = 1) as images ";
		$hraman .= "on images.user_id = friends.user_id ";
		$hraman .= "join user on user.id = friends.user_id";
		return $this->db->query($hraman)->fetch_all(MYSQLI_ASSOC);
	}

	public function deleteFriend($user_id, $unfriend_id){
		$hraman  = "SELECT id FROM ";
		$hraman .= "(SELECT id,user2 as user_id FROM friend WHERE user1 = $user_id UNION ";
		$hraman .= "SELECT id,user1 as user_id FROM friend WHERE user2 = $user_id) as friends ";
		$hraman .= "WHERE user_id = $unfriend_id";
		$id = $this->db->query($hraman)->fetch_all(MYSQLI_ASSOC)[0]["id"];
		$this->setTable("friend");
		$this->delete(array("id"=>$id));
	}

	public function checkFriend($u1, $u2){
		$hraman  = "SELECT id as user_id FROM friend WHERE user1 = $u1 AND user2 = $u2 UNION ";
		$hraman .= "SELECT id as user_id FROM friend WHERE user1 = $u2 AND user2 = $u1";
		$res = $this->db->query($hraman)->fetch_all(MYSQLI_ASSOC);
		return count($res) ? 1 : 0;
	}

	public function getNotifications($user_id){
		$hraman = "SELECT * FROM requestinfo WHERE user1 = $user_id";
		return $this->db->query($hraman)->fetch_all(MYSQLI_ASSOC);
	}

	public function getMessages($u1, $u2){
		$hraman = "SELECT * FROM message WHERE user1 = $u1 AND user2 = $u2 OR user1 = $u2 AND user2 = $u1";
		return $this->db->query($hraman)->fetch_all(MYSQLI_ASSOC);
	}
}

?>