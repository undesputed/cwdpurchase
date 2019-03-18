<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Material_requisition extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model(array('procurement/md_material_requisition'));

	}

	public function index(){

		$this->lib_auth->title = "Material Requisition";		
		$this->lib_auth->build = "procurement/material_requisition/index";

		$this->lib_auth->render();
		
	}

	public function get_material_requisition(){

		$arg    = $this->input->post();
		$result = $this->md_material_requisition->get_material_requisition($arg);

		$tmpl = array ( 'table_open'  => '<table class="table table-condensed table-hover myTable table-striped">' );
		$this->table->set_template($tmpl);

		$show = array(
					'',
					'MRS DATE',										
					'MRS NO',
					'DEPARTMENT',
					'RECOMMENDED BY',
					'Action'
			 		);
			foreach($result as $key => $value){
				$row_content = array();

				$row_content[] = array('data'=>$this->extra->label($value['APPROVED']));
				$row_content[] = array('data'=>$this->extra->format_date($value['MRS DATE']));
				$row_content[] = array('data'=>$value['MRS NO']);
				$row_content[] = array('data'=>(isset($value['DEPARTMENT'])? $value['DEPARTMENT'] :'-'));			
				$row_content[] = array('data'=>$value['RECOMMENDED BY']);
				$row_content[] = array('data'=>'<a href="javascript:void(0)" onclick="view_details('.$value['pr_id'].')">view</a>');

				$this->table->add_row($row_content);			
			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();

	}


	public function get_request_details(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$data['id'] = $this->input->post('id');
		$data['result'] = $this->md_material_requisition->get_request_details($data['id']);

		$this->load->view('procurement/material_requisition/details',$data);

	}


	public function approved(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo $this->md_material_requisition->approved($arg['id']);

	}


	public function mis_report(){


		
	}







}