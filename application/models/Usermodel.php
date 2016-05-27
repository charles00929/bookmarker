<?php
	class Usermodel extends CI_Model{
		private $userTableName = 'user';

		public function IsLogined(){
			return $this->GetSessionUid() > 0;
		}

		public function Login($username,$md5Pw){
			try{
			$sql = "SELECT uid FROM ". $this->userTableName ." WHERE username = ? AND password = ?";
			$result = $this->db->query($sql,array($username,$md5Pw))->result();
			$uid = isset($result[0]->uid)? $result[0]->uid:0;
			$this->SetSessionUid($uid);
			return isset($result[0]->uid);
			}catch(Exception $e){
				echo '.............';
				show_error('...........');
			}

		}
		public function Logout(){
			
		}
		public function SetSessionUid($uid){
			$_SESSION[sessionKey_Uid] = $uid;
		}
		public function GetSessionUid(){
			return isset($_SESSION[sessionKey_Uid]) ? $_SESSION[sessionKey_Uid] : 0;
		}
		public function __construct(){
			parent::__construct();
			$this->load->library('session');
			$this->load->database();
		}

	}

?>