<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Auth0\SDK\API\Authentication;
use Auth0\SDK\Auth0;
use Auth0\SDK\JWTVerifier;

class User extends BWTV_Controller {
	public function Auth0LoginCallback() {
		$code = $this->input->get("code");
		$this->load->config("auth0");
		$auth0Config = $this->config->item("auth0");
		$auth0 = new Auth0(array(
			'domain' => $auth0Config["domain"]
			, 'client_id' => $auth0Config["client_id"]
			, 'client_secret' => $auth0Config["client_secret"]
			, 'redirect_uri' => $auth0Config["callback_url"],
		));
		$userinfo = $auth0->getUser();
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

	public function Login() {
		$username = $this->input->post('username');
		$pw = $this->input->post('password');
		if (empty($username) || empty($pw)) {
			show_error('Your operation is not allowed.');
		}
		$this->load->config("auth0");
		$auth0Config = $this->config->item("auth0");

		$auth0Api = new Authentication($auth0Config["domain"], $auth0Config["client_id"], $auth0Config["client_secret"]);
		$token = $auth0Api->authorize_with_ro($username, $pw, "openid", "DB");

		$verifier = new JWTVerifier([
			'valid_audiences' => [$auth0Config["client_id"]],
			'client_secret' => $auth0Config["client_secret"],
		]);

		$decoded = $verifier->verifyAndDecode($token["id_token"]);
	}

	public function Logout() {
		$this->Usermodel->Logout();
		redirect('/');
	}

	public function __construct() {
		parent::__construct();
		$this->load->library("ComposerLoader");
		$this->load->library("session");
		$this->load->helper("url");
		$this->load->model('Usermodel');
	}

	public function index($message = '') {
		if ($this->Usermodel->IsLogined()) {
			redirect('/bookmark');
		} else {
			$this->loadJS('js/plugin/md5.min.js');
			$this->loadJS('js/bookmarker_session.js');
			$this->setBlock('main', 'auth0loginform', array('err_msg' => $message));
			$this->display();
		}
	}
}
