<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profit_center extends CI_Controller {

	public function __construct(){

		parent :: __construct();		
		$this->load->model('setup/md_profit_center');
		$this->load->model('setup/md_project_setup');

	}
	

	public function index(){

		$data['project_list']  = $this->md_project_setup->get_all();
		$this->lib_auth->title = "Project Center";		
		$this->lib_auth->build = "setup/profit_center/index";		
		$this->lib_auth->render($data);

	}

	public function get_data(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
		
		$result = $this->md_profit_center->get_cumulative();

		$show = array(
			    array('data'=>'title_id','style'=>'display:none'),
				'ID',
				'Code',
				'Company',
				'Type',
				'Project Description',
				'Address',
				'Action',
				array('data'=>'project_duration','style'=>'display:none'),
				array('data'=>'date_started','style'=>'display:none'),
				array('data'=>'date_projected','style'=>'display:none'),
				array('data'=>'date_completed','style'=>'display:none'),
				array('data'=>'project_incharged','style'=>'display:none'),
				array('data'=>'project_managed','style'=>'display:none'),
			 	);
			
			foreach($result->result_array() as $key => $value){
				$date_started     = (empty($value['date_started']))? '' : $value['date_started'] ;
				$date_projected = (empty($value['date_projected']))? '' : $value['date_projected'] ;
				$date_completed     = (empty($value['date_completed']))? '' : $value['date_completed'] ;

				$row_content = array();
				$row_content[] = array('data'=>$value['title_id'],'class'=>'title_id','style'=>'display:none');
				$row_content[] = array('data'=>$value['project_id'],'class'=>'id');
				$row_content[] = array('data'=>$value['project_no'],'class'=>'code');
				$row_content[] = array('data'=>$value['title_name'],'class'=>'title_name');
				$row_content[] = array('data'=>$value['project'],'class'=>'project');
				$row_content[] = array('data'=>$value['project_name'],'class'=>'project_name');
				$row_content[] = array('data'=>$value['project_location'],'class'=>'project_location');
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
				$row_content[] = array('data'=>$value['project_duration'],'class'=>'project_duration','style'=>'display:none');
				$row_content[] = array('data'=>$date_started,'class'=>'date_started','style'=>'display:none');
				$row_content[] = array('data'=>$date_projected,'class'=>'date_projected','style'=>'display:none');
				$row_content[] = array('data'=>$date_completed,'class'=>'date_completed','style'=>'display:none');
				$row_content[] = array('data'=>$value['project_incharged'],'class'=>'project_incharged','style'=>'display:none');
				$row_content[] = array('data'=>$value['project_managed'],'class'=>'project_managed','style'=>'display:none');
				$this->table->add_row($row_content);

			}
			
			$this->table->set_heading($show);
			echo $this->table->generate();

	}

	public function save(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_profit_center->save();

	}




}