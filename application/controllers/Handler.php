<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Handler extends BWTV_Controller {
	public function index(){
		echo "There was no html output in this controller!";
	}

	public function bookmark($action){
		$this->load->model('Bookmarkmodel');
		$response = '';
		switch($action){
			//load all of bookmark data 
			case 'loading':
			$uid = $this->Usermodel->GetUid();
			$result = $this->Bookmarkmodel->GetBookmarksJsonByID($uid);
			$response = json_encode($result);
			break;
			//create a new bookmark
			case 'bcr':
			break;
			//update a bookmark
			case 'bu':
				$result = array();
				$data = array(
					$this->input->post('bid')
					,$this->Usermodel->GetUid()
					,$this->input->post('title')
					,$this->input->post('url')
					,$this->input->post('title')
					,$this->input->post('url')
					);
				$tags = $this->input->post('tags');
				$bid = $this->Bookmarkmodel->SetBookmark($data);
				$bid = $bid == 0 ? $this->input->post('bid') : $bid;

				if($bid != 0 && count($tags) != 0){
					$this->Bookmarkmodel->DeleteTagon('bid',$bid);
					$this->Bookmarkmodel->SetTagon($bid,$tags);
				}
				$result['bid'] = $bid;
				$response = json_encode($result);
			break;
			case 'bd'://delete a bookmark
				$data = array(
					$this->input->post('bid')
					);
				$bid = $this->Bookmarkmodel->DeleteBookmark($data);
				$bid = $bid == 0 ? false : true;

				if($bid != 0){
					$this->Bookmarkmodel->DeleteTagon('bid',$bid);
				}
				$result = array(
					'bid' => $this->input->post('bid')
					,'success' => $bid
					);
				$response = json_encode($result);
			break;
			//create a tag
			case 'tcr':
			break;
			//update a tag
			case 'tu':
			//if response 0 for false
				$data = array(
					$this->input->post('tid')
					,$this->Usermodel->GetUid()
					,$this->input->post('title')
					,$this->input->post('font_color')
					,$this->input->post('bg_color')
					,$this->input->post('title')
					,$this->input->post('font_color')
					,$this->input->post('bg_color')
					);
				$tid = $this->Bookmarkmodel->SetTag($data);
				$tid = $tid == 0 ? $this->input->post('tid') : $tid;
				$result = array('tid' => $tid);
				$response = json_encode($result);
			break;
			//delete a tag
			case 'td':
				//return 0 for false ,1 for true
				$data = array(
					$this->input->post('tid')
					);
				$sqlresult = $this->Bookmarkmodel->DeleteTag($data);
				$sqlresult = $sqlresult == 0 ? false : true;
				$result = array(
					'tid' => $this->input->post('tid')
					,'success' => $sqlresult
					);
				$response = json_encode($result);
			break;
		}
		echo $response;
	}

	public function __construct(){
		parent::__construct();
		$this->load->model('Usermodel');
	}
}