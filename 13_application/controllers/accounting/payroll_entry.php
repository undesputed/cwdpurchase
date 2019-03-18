<?php defined('BASEPATH') OR exit('No direct script access allowed');

class payroll_entry extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_payroll_entry');			
		$this->load->model('md_project');
	}


	public function index(){		
		$this->lib_auth->title = "Payroll Entry";
		$this->lib_auth->build = "accounting/payroll_entry/index";				
		$data['project'] = $this->md_project->get_profit_center();
		$data['project_category'] = $this->md_project->get_project_category();

		$this->lib_auth->render($data);

	}

	public function save(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo $this->md_payroll_entry->save($arg);
		
	}

	public function delete(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo $this->md_payroll_entry->delete($arg);
	}


	public function get_data(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$result = $this->md_payroll_entry->get_data();		
		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);		
		$show = array(
					array('data'=>'id','style'=>'display:none'),
					'Project Type',
					'Project Name',
					'Payroll Period',
					'Payroll Amount',
					'Action',
					);

		foreach($result as $key => $value){

			$value['project_type'] =  (isset($value['project_type']))? $value['project_type'] : "";

			$row_content = array();
			    $row_content[] = array('data'=>$value['id'],'class'=>'id','style'=>'display:none','data-project_id'=>$value['project_id'],'data-project_type'=>$value['project_type_id']);
			    $row_content[] = array('data'=>$value['project_type'],'class'=>'project_name');
				$row_content[] = array('data'=>$value['project_name'],'class'=>'project_name');
				$row_content[] = array('data'=>$value['payroll_date'],'class'=>'payroll_date');
				$row_content[] = array('data'=>$this->extra->number_format($value['payroll_amount']),'class'=>'payroll_amount');
				$row_content[] = array('data'=>'<span class="event"><span class="btn-link event update_class">Update</span> |<span class="btn-link event delete_class">Delete</span></span>','class'=>'');
			$this->table->add_row($row_content);

		}		
		$this->table->set_heading($show);
		echo $this->table->generate();

	}





}