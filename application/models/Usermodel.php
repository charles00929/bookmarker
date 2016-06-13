<?php
	class Usermodel extends CI_Model{
		private $userTableName = 'user';

		public function IsLogined(){
			return $this->GetUid() > 0;
		}

		public function Login($username,$md5Pw){
			try{
			$sql = "SELECT uid FROM ". $this->userTableName ." WHERE username = ? AND password = ?";
			$result = $this->db->query($sql,array($username,$md5Pw))->result();
			$uid = isset($result[0]->uid)? $result[0]->uid:0;
			$this->SetUid($uid);
			return isset($result[0]->uid);
			}catch(Exception $e){
				echo '.............';
				show_error('...........');
			}

		}
		public function Logout(){
			unset($_SESSION[sessionKey_Uid]);
			redirect('/');
		}
		public function SetUid($uid){
			$_SESSION[sessionKey_Uid] = $uid;
		}
		public function GetUid(){
			return isset($_SESSION[sessionKey_Uid]) ? $_SESSION[sessionKey_Uid] : 0;
		}
		public function __construct(){
			parent::__construct();
			$this->load->library('session');
			$this->load->database();
		}

	}

?>