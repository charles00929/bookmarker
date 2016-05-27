<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookmark extends BWTV_Controller {
	private $uid = 0;
	public function index2(){
		$this->setBlock('header','BWTV_Layout/header');
		$this->setBlock('footer','BWTV_Layout/footer');
		$mainData = array('bookmarks' => $this->Bookmarkmodel->GetBookmarksByID($this->user_id));
		$this->setBlock('main','main',$mainData);
		$this->setBlock('L_side','tagform');
		$this->setBlock('L_side','taglist');
		$this->setBlock('L_side','bookmarkform');
		$this->setBlock('L_side','searchform');
		$this->setBlock('L_side','profileform');
		$this->display();
	}

	public function index(){
		if(!$this->Usermodel->IsLogined()){
			redirect('/');
		}
		$this->setBlock('header','BWTV_Layout/header');
		$this->setBlock('footer','BWTV_Layout/footer');
		$this->setBlock('main','main json');
		$this->setBlock('L_side','tagform');
		$this->setBlock('L_side','bookmarkform');
		$this->setBlock('L_side','searchform');
		$this->setBlock('L_side','profileform');
		$this->setBlock('L_side','taglist');
		$this->setBlock('L_side','deleteForm');
		$this->loadJS('js/bookmarker.js');
		$this->loadJs('js/colpick.js');
		$this->loadCSS('css/BTTemplate.css');
		$this->loadCSS('css/BTStyles.css');
		$this->loadCSS('css/bootstrap.min.css');
		$this->loadCSS('css/colpick.css');
		$this->display();
	}
	public function __construct(){
		parent::__construct();
		$this->load->model("Bookmarkmodel");
		$this->load->model('Usermodel');
	}

	function debug($s){
		echo (string)$s.'<br>';

	}
}
