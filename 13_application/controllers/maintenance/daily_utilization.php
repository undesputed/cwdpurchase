<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_utilization extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('maintenance/md_daily_utilization');		
	}

	public function index(){

		$this->lib_auth->title = "Daily Utilization";		
		$this->lib_auth->build = "maintenance/daily_utilization/index";
		
		$this->lib_auth->render();
		
	}


	/**get_equip_util_no**/

	public function get_equip_util_no(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		

		echo  $this->md_daily_utilization->equip_util_no();

	}


	public function scope(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_daily_utilization->scope();

		echo json_encode($result);
	}


	public function unit_no(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_daily_utilization->unit_no();

		echo json_encode($result);

	}



}