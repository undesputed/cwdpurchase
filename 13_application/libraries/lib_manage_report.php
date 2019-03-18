<?php 


Class lib_manage_report {


	public function __construct(){

		$this->load->model(array('manage_report/md_manage_report'));

	}

	public function __get($var)
	{
		return get_instance()->$var;
	}

	
	public function get_type($type){
		
		return $this->md_manage_report->get_type($type);
		
	}


	public function get_reports(){
		return $this->md_manage_report->get_report();
	}



}


 ?>