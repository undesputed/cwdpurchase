<?php 



class lib_upload {

	public $upload_path = "";
	public $url_path = "";

	public function __get($var)
	{
		return get_instance()->$var;
	}

	public function makeDir(){

		$date = date('Y-m');
		$this->upload_path = APPPATH.'../uploads/'.$date;
		$this->url_path = base_url().'uploads/'.$date;
		if (!file_exists($this->upload_path)) {
    		mkdir($this->upload_path, 0777, true);
    		$this->url_path = base_url().'uploads/'.$date;
		}
		
		
	}



}









?>