<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Subsidiary_setup extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->lib_auth->default = "default-accounting";
		$this->load->model(array('setup/md_subsidiary_setup'));

	}

	public function index(){

		$data['account_ledger'] = $this->md_subsidiary_setup->account_ledger();	

		$this->lib_auth->title = "Subsidiary Setup";
		$this->lib_auth->build = "setup/subsidiary_setup/subsidiary_form";
		$this->lib_auth->render($data);
		
	}


	public function get_data(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		


		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$type = $this->input->post('type');
		$result = $this->md_subsidiary_setup->get_data($type);

		$show = array(
					array('data'=>'bank_id','style'=>'display:none'),
					'Title/Name',
					'Address/Location',					
					'Reference No/ Tin',
					'Type',
					'Action',			 		
			 	);

			foreach($result as $key => $value){
				$row_content = array();

				$value['business_name']    = (isset($value['business_name']))? $value['business_name'] : '';
				$value['trade_name'] = (isset($value['trade_name']))? $value['trade_name'] : '';
				$value['address'] = (isset($value['address']))? $value['address'] : '';
				
				$row_content[] = array('data'=>$value['business_number'],'class'=>'id','style'=>'display:none');
				$row_content[] = array('data'=>$value['trade_name'],'class'=>'title');
				$row_content[] = array('data'=>$value['address'],'class'=>'address');
				$row_content[] = array('data'=>$value['tin_number'],'class'=>'ref_no');
				$row_content[] = array('data'=>$value['type'],'class'=>'tin_number');
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
				$this->table->add_row($row_content);
				
			}
	
		$this->table->set_heading($show);
		echo $this->table->generate();

	}

	public function save(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_subsidiary_setup->save();


	}


}