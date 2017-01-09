<?php

use Auth0\SDK\Auth0;

class Auth0UserModel extends CI_Model {
	private $_instance;

	public function GetUser() {
		return $this->_instance->getUser();
	}

	public function Logout() {
		$this->_instance->logout();
	}

	public function __construct() {
		parent::__construct();
		$this->load->config("auth0");
		$auth0Config = $this->config->item("auth0");
		$this->_instance = new Auth0(array(
			'domain' => $auth0Config["domain"]
			, 'client_id' => $auth0Config["client_id"]
			, 'client_secret' => $auth0Config["client_secret"]
			, 'redirect_uri' => $auth0Config["callback_url"],
		));
	}
}