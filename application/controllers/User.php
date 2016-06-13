<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends BWTV_Controller {

	public function index($message = '')
	{
		if($this->Usermodel->IsLogined()){
			redirect('/bookmark');
		}else{
			$this->loadJS('js/plugin/md5.min.js');
			$this->loadJS('js/bookmarker_session.js');
			$this->setBlock('main','loginForm',array('err_msg'=>$message));
			$this->display();
		}
	}
	public function Login(){
		$username = $this->input->post('username');
		$md5pw = $this->input->post('password');
		if(empty($username) || empty($md5pw)){
			show_error('Your operation is not allowed.');
		}
		$this->Usermodel->Login($username,$md5pw);
		if($this->Usermodel->IsLogined()){
			redirect('/bookmark');
		}else{
			$this->index('Your username or password is wrong.');
		}

	}
	public function Logout(){
		$this->Usermodel->Logout();
		redirect('/');
	}
	public function __construct(){
		parent::__construct();
		$this->load->model('Usermodel');
		//$this->load->library('session');
		/*$this->loadJS('js/jquery-1.10.2.js');
		$this->loadJS('js/login.js');//if.....
		$this->loadJS('js/formChecker.js');
		$this->loadJS('js/bootstrap.js');
		$this->loadJS('js/bootstrap.min.js');

		$this->loadCSS('css/layout.css');
		$this->loadCSS('css/bootstrap-theme.css');
		$this->loadCSS('css/bootstrap-theme.min.css');
		$this->loadCSS('css/bootstrap.css');
		$this->loadCSS('css/bootstrap.min.css');

		$this->setBlock('layout/header','header');
		$this->setBlock('menu/main_menu','menu');
		$this->setBlock('session','menu');
		$this->setBlock('layout/footer','footer');*/
	}
}
