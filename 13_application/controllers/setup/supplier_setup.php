<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_setup extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('setup/md_supplier_setup');

	}

	public function index(){

		$this->lib_auth->title = "Supplier Setup";		
		$this->lib_auth->build = "setup/supplier_setup/index";				
		$this->lib_auth->render();

	}


	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$result = $this->md_supplier_setup->get_cumulative();

		$show = array(
				array('data'=>'business_id','style'=>'display:none'),
			    'Business Name',
				'Address',
				'Contact No',
				'Contact Person',
				'Tin Number',
				'Mode Delivery',
				'Action'
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
				$contact_person = (empty($value['contact_person']))? '' : $value['contact_person'] ;
				$tin_number     = (empty($value['tin_number']))? '' : $value['tin_number'] ;
				$mode_delivery  = (empty($value['mode_delivery']))? '' : $value['mode_delivery'] ;

				$row_content[] = array('data'=>$value['business_number'],'class'=>'id','style'=>'display:none');
				$row_content[] = array('data'=>$value['business_name'],'class'=>'business_name','style'=>'');
				$row_content[] = array('data'=>$value['address'],'class'=>'address','style'=>'');
				$row_content[] = array('data'=>$contact_no,'class'=>'contact_no','style'=>'');
				$row_content[] = array('data'=>$contact_person,'class'=>'contact_person');
				$row_content[] = array('data'=>$tin_number,'class'=>'tin_number');
				$row_content[] = array('data'=>$mode_delivery,'class'=>'mode_delivery');								
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

		$this->load->view('setup/supplier_setup/create');		

	}


	public function save_supplier(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo $this->md_supplier_setup->save_supplier();
		
	}

	public function update_supplier(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo $this->md_supplier_setup->update_supplier();
		
	}

	public function update(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['main'] = $this->md_supplier_setup->get_supplierMain($this->input->post('id'));
		$data['details'] = $this->md_supplier_setup->get_supplierDetails($this->input->post('id'));		
		$this->load->view('setup/supplier_setup/edit',$data);

	}

	public function cancel(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_supplier_setup->cancel($this->input->post('id'));
	}


	public function activate(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_supplier_setup->activate($this->input->post('id'));
	}

	public function get_supplier(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
				
		$q = $this->input->get('q');		
		$query_strings = preg_split('/\s+/', $q);
		$where = array();		
		foreach($query_strings as $row){
			 $where[] = "business_name LIKE '%{$this->db->escape_str($row)}%'";
		}		
		$where = implode(' AND ', $where);
		$result = $this->md_supplier_setup->select2($where);
		echo json_encode($result);
	}	

}