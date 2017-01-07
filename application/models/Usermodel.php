<?php
class Usermodel extends CI_Model {
	private $userTableName = 'user';

	public function GetInternalID($userID) {
		$sql = "SELECT id FROM user WHERE userid = ?";
		$result = $this->db->query($sql, array($userID))->result_array();
		$id = isset($result[0]["id"]) ? $result[0]["id"] : 0;
		return $id;
	}

	public function IsLogined() {
		$internalID = $this->session->userdata("internal_id");
		return isset($internalID);
	}

	# need to revise
	public function Login($username, $md5Pw) {
		try {
			$sql = "SELECT uid FROM " . $this->userTableName . " WHERE username = ? AND password = ?";
			$result = $this->db->query($sql, array($username, $md5Pw))->result();
			$uid = isset($result[0]->uid) ? $result[0]->uid : 0;
			$this->SetUid($uid);
			return isset($result[0]->uid);
		} catch (Exception $e) {
			echo '.............';
			show_error('...........');
		}

	}

	public function Logout() {
		$unsetKeys = array(
			"email"
			, "provider"
			, "user_id"
			, "nickname"
			, "username"
			, "picture"
			, "internal_id",
		);
		$this->session->unset_userdata($unsetKeys);
	}

	public function Register($userID, $provider = "bookmarker") {
		$this->db->trans_start();
		try {
			$sql = "INSERT INTO user(userid,provider,updatedtime,createdtime) VALUE(?,?,NOW(),NOW())";
			$this->db->query($sql, array($userID, $provider));
			if ($this->db->affected_rows() == 0) {
				throw new Exception("Error occur when insert a user.");
			}
		} catch (Exception $ex) {
			$this->db->trans_rollback();
		}
		$this->db->trans_complete();
	}

	public function SignIn($data = array()) {
		$this->session->set_userdata($data);
	}

	public function __construct() {
		parent::__construct();
		$this->load->library('session');
		$this->load->database();
	}
}

?>