<?php

class Curl{
	private $_curl;
	private $_target;//host
	private $_response;
	private $_err;
	public function __construct($config = array()){
		$this->Initialize($config);
	}

	public function Initialize($config = array()){
		$this->_curl = curl_init();
		$this->SetOption(CURLOPT_RETURNTRANSFER,TRUE);
		$this->SetOption(CURLOPT_TIMEOUT,10);
		$this->_target = $config["host"] ?? "";
		
	}
	function __destruct(){
		curl_close($this->_curl);
	}
	public function GetResponse(){
		$response = $this->_response;
		$this->_response = NULL;
		return $response;
	}
	public function GetError(){
		return $this->_err;
	}
	private function SetOption($option,$value){
		curl_setopt($this->_curl,$option, $value);
	}
	public function Post($uri,$params = array()){
		
		$json = json_encode($params);
		$this->SetOption(CURLOPT_URL, "$this->_target/$uri");
		// $this->SetOption(CURLOPT_CUSTOMREQUEST,"POST");
		$this->SetOption(CURLOPT_RETURNTRANSFER,TRUE);
		$this->SetOption(CURLOPT_POST,TRUE);
		$this->SetOption(CURLOPT_POSTFIELDS,$json);
		$this->SetOption(CURLOPT_HTTPHEADER,array("content-type: application/json"));
		$this->_response = curl_exec($this->_curl);
		$this->_err = curl_error($this->_curl);
	}
	public function Get($uri,$params = array()){
		$urlParams = http_build_query($params);
		$this->SetOption(CURLOPT_RETURNTRANSFER,TRUE);
		$this->SetOption(CURLOPT_URL, "$this->_target/$uri?$urlParams");
		//get method dont set option

	}
	public function Put($uri,$params = array()){
		$this->SetOption(CURLOPT_URL, "$this->_target/$uri");
		$this->SetOption(CURLOPT_POST,TRUE);
	}
	public function Delete($uri,$params = array()){
		$this->SetOption(CURLOPT_URL, "$this->_target/$uri");
		$this->SetOption(CURLOPT_POST,TRUE);
	}
	private function BeforeCall($uri){
//		$this->SerOption(CURLOPT_URL, "$this->_target/$uri");
	}
}