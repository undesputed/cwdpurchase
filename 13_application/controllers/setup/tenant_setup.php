<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tenant_setup extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('setup/md_tenant_setup');
	}

	public function index(){

		$this->lib_auth->title = "Tenant Setup";		
		$this->lib_auth->build = "setup/tenant_setup/index";				
		$this->lib_auth->render();

	}


	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$result = $this->md_tenant_setup->get_cumulative();

		$show = array(
				array('data'=>'id','style'=>'display:none'),
			    'Tenant Name',
				'Address',
				'Contact No',
				'Contract Amount',				
				'Action',
			 	);
				
			foreach($result->result_array() as $key => $value){


				if($value['status'] == "ACTIVE"){
					$status = "Cancel";
					$status_class = "cancel_class";
				}else{
					$status = "Activate";
					$status_class = "activate_class";
				}

				$row_content = array();

				$contact_no     = (empty($value['contact_no']))? '' : $value['contact_no'] ;				
				$contract_amount     = (empty($value['contract_amount']))? '' : $value['contract_amount'] ;

				$row_content[] = array('data'=>$value['id'],'class'=>'id','style'=>'display:none');
				$row_content[] = array('data'=>$value['name'],'class'=>'name','style'=>'');
				$row_content[] = array('data'=>$value['address'],'class'=>'address','style'=>'');
				$row_content[] = array('data'=>$contact_no,'class'=>'contact_no','style'=>'');
				$row_content[] = array('data'=>$this->extra->number_format($contract_amount),'class'=>'contract_amount','style'=>'');
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span> <span class="event">|</span> <span class="btn-link event '.$status_class.'">'.$status.'</span>','class'=>'');
				$this->table->add_row($row_content);
				
				
			}
			
			$this->table->set_heading($show);
			echo $this->table->generate();

	}

		
	public function create_supplier(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$this->load->view('setup/tenant_setup/create');		

	}


	public function save(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
			
		echo $this->md_tenant_setup->save();
		
	}

	public function update_action(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo $this->md_tenant_setup->update();
		
	}

	public function update(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$data['main']    = $this->md_tenant_setup->get_main($this->input->post('id'));
		$data['details'] = $this->md_tenant_setup->get_details($this->input->post('id'));

		$this->load->view('setup/tenant_setup/edit',$data);

	}

	public function cancel(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_tenant_setup->cancel($this->input->post('id'));
	}


	public function activate(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_tenant_setup->activate($this->input->post('id'));
	}



}