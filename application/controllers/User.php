<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends BWTV_Controller {
	public function Auth0Login() {
		$this->load->view("auth0loginform");
	}

	public function Auth0LoginCallback() {
		var_dump($this->input->get);
		//$auth0->getUser();
	}

	public function Login() {
		$username = $this->input->post('username');
		$pw = $this->input->post('password');
		if (empty($username) || empty($pw)) {
			show_error('Your operation is not allowed.');
		}
		$result = $this->curl->Post("oauth/ro",
			array(
				"client_id" => "vx2b0X6B0uSNyS3Y4O1PG0EtiKmHnUy2"
				,"username" => "$username"
				,"password" => "$pw"
				,"connection" => "DB"
				,"grant_type" => "password"
				,"scope" => "openid"
				)
		);
		var_dump($this->curl->GetResponse());
		var_dump($this->curl->GetError());
	}

	public function Logout() {
		$this->Usermodel->Logout();
		redirect('/');
	}

	public function __construct() {
		parent::__construct();
		$this->load->library("ComposerLoader");
		$this->load->library("Curl", array("host" => "https://bwtv.au.auth0.com"));
		$this->load->helper("url");
		$this->load->model('Usermodel');
	}

	public function index($message = '') {
		if ($this->Usermodel->IsLogined()) {
			redirect('/bookmark');
		} else {
			$this->loadJS('js/plugin/md5.min.js');
			$this->loadJS('js/bookmarker_session.js');
			$this->setBlock('main', 'loginForm', array('err_msg' => $message));
			$this->display();
		}
	}
}
