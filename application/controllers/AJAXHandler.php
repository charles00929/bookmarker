<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AJAXHandler extends BWTV_Controller {
	public function index(){
		echo "There was no html output in this controller!";
	}

	public function bookmark($action){
		$this->load->model('Bookmarkmodel');
		$response = '';
		switch($action){
			//load all of bookmark data 
			case 'loading':
			$uid = $this->Usermodel->GetSessionUid();
			$result = $this->Bookmarkmodel->GetBookmarksJsonByID($uid);
			$response = json_encode($result);
			break;
			//create a new bookmark
			case 'bcr':
			break;
			//update a bookmark
			case 'bu':
			break;
			//delete a bookmark
			case 'bd':
			break;
			//create a tag
			case 'tcr':
			break;
			//update a tag
			case 'tu':
			//if response 0 for false
			$tid = $this->post('tid');
			$title = $this->post('title');
			$font_color = $this->post('font_color');
			$bg_color = $this->post('bg_color');
			//t_id,u_id,title,font_color,bg_color


			$result = $this->Bookmarkmodel->SetTag();
			$response = json_encode($result);
			break;
			//delete a tag
			case 'td':
			break;
		}
		echo $response;
	}

	public function __construct(){
		parent::__construct();
		$this->load->model('Usermodel');
	}
}