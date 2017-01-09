<?php

use Auth0\SDK\Auth0;

class Auth0UserModel extends CI_Model {
	private $_instance;
	private $_config;
	public function GetUser() {
		return $this->_instance->getUser();
	}

	public function Logout() {
		$this->_instance->logout();
	}
	public function GetConfig(){
		return $this->_config;
	}
	public function __construct() {
		parent::__construct();
		$this->load->config("auth0");
		$this->_config = $this->config->item("auth0");
		$this->_instance = new Auth0(array(
			'domain' => $this->_config["domain"]
			, 'client_id' => $this->_config["client_id"]
			, 'client_secret' => $this->_config["client_secret"]
			, 'redirect_uri' => $this->_config["callback_url"],
		));
	}
}