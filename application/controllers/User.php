<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends BWTV_Controller {
	public function InternalLogin() {
		$code = $this->input->get("code");
		$userinfo = $this->Auth0UserModel->GetUser();
		if (empty($userinfo)) {
			//echo "error";
		} else {
			$fetchedData = array(
				"email" => $userinfo['email']
				, "provider" => $userinfo['identities'][0]['provider']
				, "user_id" => $userinfo['identities'][0]['user_id']
				, "nickname" => $userinfo['nickname']
				, "username" => $userinfo['username']
				, "picture" => $userinfo['picture'], // url;
			);
			$internalID = $this->Usermodel->GetInternalID($fetchedData["user_id"]);
			if ($internalID == 0) {
				$internalID = $this->Usermodel->Register($fetchedData["user_id"], $fetchedData["provider"]);
			}
			$fetchedData["internal_id"] = $internalID;
			$this->Usermodel->SignIn($fetchedData);

			redirect("/bookmark");
		}
	}

	public function Logout() {
		$this->Usermodel->Logout(); // remove internal session
		$this->Auth0UserModel->Logout(); // remove auth0 session and data
		redirect('/');
	}

	public function __construct() {
		parent::__construct();
		$this->load->library("ComposerLoader");
		$this->load->library("session");
		$this->load->helper("url");
		$this->load->model('Usermodel');
		$this->load->model("Auth0UserModel");
	}

	public function index() {
		if ($this->Usermodel->IsLogined()) {
			redirect('/bookmark');
		} else {
			$this->loadJS('js/plugin/md5.min.js');
			$this->loadJS('js/bookmarker_session.js');
			$this->setBlock('main', 'auth0loginform', array('config' => $this->Auth0UserModel->GetConfig()));
			$this->display();
		}
	}
}
