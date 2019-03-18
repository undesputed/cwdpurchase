<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_setup extends CI_Controller {

	public function __construct(){

		parent :: __construct();		
		$this->load->model('setup/md_asset_setup');
		$this->load->model('setup/md_subsidiary_setup');
		$this->lib_auth->default = "default-accounting";

	}


	public function index(){

		$this->lib_auth->title = "Customer Setup";		
		$this->lib_auth->build = "setup/asset_setup/add_customer";
		
		$this->lib_auth->render();

	}


	public function save(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo $this->md_asset_setup->save_customer($arg);

	}

	public function get_data(){


		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);		
		$result = $this->md_subsidiary_setup->get_customer();
		$show = array(
					array('data'=>'bank_id','style'=>'display:none'),
					'Full Name',
					'Address',					
					'TIN',
					'Action',			 		
			 	);

			foreach($result as $key => $value){
				$row_content = array();

				$value['business_name']    = (isset($value['business_name']))? $value['business_name'] : '';
				$value['trade_name'] = (isset($value['trade_name']))? $value['trade_name'] : '';
				$value['address'] = (isset($value['address']))? $value['address'] : '';
				
				$row_content[] = array('data'=>$value['business_number'],'class'=>'id','style'=>'display:none');
				$row_content[] = array('data'=>$value['trade_name'],'class'=>'full_name');
				$row_content[] = array('data'=>$value['address'],'class'=>'address');
				$row_content[] = array('data'=>$value['tin_number'],'class'=>'tin_number');
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
				$this->table->add_row($row_content);
				
			}
	
		$this->table->set_heading($show);
		echo $this->table->generate();

	}



}