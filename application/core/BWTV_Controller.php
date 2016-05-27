<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
define('sessionKey_Uid','uid');
//新增核心程式，注意：檔名&類別名一致，且格式為：
//前置字串(config中可設定)+CI類別名稱
class BWTV_Controller extends CI_Controller{
	//for layout
	//const WAN='DisColor';

	private $contents=array(
		'header'=>''
		,'main'=>''
		,'menu'=>''
		,'L_side'=>''
		,'R_side'=>''
		,'footer'=>''
		,'css_files'=>array()
		,'js_files'=>array()
		,'pageTitle'=>'此頁面未設定標題'
		);
	//版型
	private $template='';
	//-------方法------------
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		$this->template='BWTV_Layout/template';
		

		
	}
	//載入CSS
	public function loadCSS($name){
		if(isset($this->contents['css_files'])){
		array_push($this->contents['css_files'],$name);

		}
	}
	//載入JS
	public function loadJS($name){
		if(isset($this->contents['js_files'])){
		array_push($this->contents['js_files'],$name);
		}
	}
	public function setTitle($title){
		$this->contents['pageTitle']=$title;
	}
	//在某區塊中顯示view
	public function setBlock($pos,$name,$args=array()){
		//name 檔名
		//pos 顯示位置
		//args 給view顯示的資料
		if($pos=='css_files'||$pos=='js_files'){
			echo '請使用(loadCSS)(loadJS) 載入檔案';
			return ;
		}
		if(isset($this->contents[$pos])){
			//載入檔案後轉成字串
			$this->contents[$pos].= $this->load->view($name,$args,TRUE);
		}else{
			echo "{$pos} 未定義";
		}
	}
	public function display(){
		$this->load->view($this->template,$this->contents);
	}
}

