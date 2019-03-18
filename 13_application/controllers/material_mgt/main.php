<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('procurement/md_stock_availability');		
	}

	public function index(){

			$type = $this->session->userdata('type_user');
			if($type == 'ACCOUNTANT')
			{
				redirect(base_url().index_page().'/accounting','refresh');
			}else{
				redirect(base_url().index_page().'/transaction_list/purchase_request/incoming','refresh');	
			}
			
			
			
			$this->lib_auth->title = "Welcome";		
			$this->lib_auth->build = "material_mgt/index";
			$data['items'] = $this->md_stock_availability->get_items();		
			$this->lib_auth->render($data);
			
						
	}

}