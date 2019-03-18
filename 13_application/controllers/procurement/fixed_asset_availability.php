<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fixed_asset_availability extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('equipment_history/md_equipment_history');	
	}

	public function index(){

		$this->lib_auth->title = "Fixed Asset Availability";		
		$this->lib_auth->build = "procurement/fixed_asset_availability/index";

		/*$data['equipment'] = $this->md_equipment_history->get_inhouse_equipment_2();*/

		$data['category'] = $this->md_equipment_history->fixed_asset();
		$this->lib_auth->render($data);
		
	}


	public function get_details(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$data['name'] = $arg['name'];
		$data['result'] = $this->md_equipment_history->get_equipment_list($arg);		
		$this->load->view('procurement/fixed_asset_availability/tbl_details',$data);


	}





}