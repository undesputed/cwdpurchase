<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_checklist extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('maintenance/md_daily_checklist');

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);

	}

	public function index(){

		$this->lib_auth->title = "Daily Equipment Checklist";		
		$this->lib_auth->build = "maintenance/daily_checklist/index";
		
		$data['item_name'] = $this->md_daily_checklist->cbxName();
		$data['shift'] = $this->md_daily_checklist->cbxShift();
		
		$data['category'] = $this->md_daily_checklist->cbxCategory();
		$data['signatory'] = $this->md_daily_checklist->get_signatory();
		$this->lib_auth->render($data);
		
	}


	public function get_equipmentname(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		$result = $this->md_daily_checklist->cbxEquipment($this->input->post('id'));
		echo json_encode($result);

	}

	public function model_no(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_daily_checklist->model_no($this->input->post('equip_id'));		
		echo json_encode($result);		
	}


	public function get_checklist(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		$result = $this->md_daily_checklist->checklist($this->input->post('item_id'));

		$this->table->set_heading(array(
			array('data'=>'check_id','style'=>'display:none'),
			'Type',
			'Category',
			'<div class="checkbox-inline"><input type="checkbox" class="chk-head" id="ok"> Ok </div>',
			'<div class="checkbox-inline"><input type="checkbox" class="chk-head" id="no"> No </div>',
			'<div class="checkbox-inline"><input type="checkbox" class="chk-head" id="na"> N/A </div>',
			'Remarks'
			));

		$hide = array(
			'checklist_id',
			'utilization_main_id',
			'db_equipment_id',
			);

		foreach($result as $key => $value){
				$row_content = array();				
				$row_content[] = array('data'=>$value['checklist_id'],'style'=>'display:none');
				$row_content[] = $value['Type'];
				$row_content[] = array('data'=>$value['category'].'<b class="pull-right caret action"></b>','class'=>'editable-td');
				$row_content[] = $this->checkbox($value['ok'],'ok',$value['checklist_id']);
				$row_content[] = $this->checkbox($value['no'],'no',$value['checklist_id']);
				$row_content[] = $this->checkbox($value['na'],'na',$value['checklist_id']);
				$row_content[] = array('data'=>$value['remarks'],'class'=>'editable-td-input');
				$this->table->add_row($row_content);
		}

		echo $this->table->generate();

	}


	private function checkbox($value,$type,$name){
		return "<input type='checkbox' value='' name='".$name."' class='".$type."' >";
	}


	public function save(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		parse_str($this->input->post('form'),$form);
		if($this->md_daily_checklist->save($form,$this->input->post('list'))){
			echo '0';
			$this->session->set_flashdata(array(
				'message'=>'<strong>Successfully Save</strong>',
				'type'=>'alert-success',
			));
		}else{

			$this->session->set_flashdata(array(
				'message'=>'<strong>Failed to Saved :(</strong>',
				'type'=>'alert-danger',
			));
		}
				
	}





/***************
============================CUMULATIVE DATA=================================**************/


	public function cumulative(){

		$this->lib_auth->title = "Daily Equipment Checklist";		
		$this->lib_auth->build = "maintenance/daily_checklist/cumulative";

		$this->lib_auth->render();

	}


	public function cumulative_data(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$result = $this->md_daily_checklist->cumulative();

		$this->table->set_heading(array(		
		'Transaction Date',
		'Operator',
		'Shift',
		'Remarks',
		'Equipment Name',
		'Remarks',
		));
		$hide = array(
			'utilization_id',			
			'checked_by',
			'inspected_by',
			'approved_by',
			'location',
			'title',
			'shift_id',
			'Checked by',
			'Inspected by',
			'Approved by',
			);

		foreach($result as $key => $value){
			$row_content = array();

			foreach ($value as $key1 => $value1){
				if(in_array($key1,$hide)){
					$row_content[] = array('data'=>$value1,'style'=>'display:none');
				}else{
					$row_content[] = array('data'=>$value1);
				}					
			}
			$this->table->add_row($row_content);
		}

		echo $this->table->generate();

	}


	public function cumulative_details(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$this->table->set_heading(array(
				'Type',
				'Category',
				'Ok',
				'No',
				'N/A',
		));
		

		$result = $this->md_daily_checklist->cumulative_details($this->input->get('id'));

		foreach ($result as $key => $value){
			$row_content = array();
			$row_content[] = array('data'=>$value['checklist_id'],'style'=>'display:none');	
			$row_content[] = array('data'=>$value['Type']);	
			$row_content[] = array('data'=>$value['category']);	
			$row_content[] = array('data'=>$this->check($value['ok']));	
			$row_content[] = array('data'=>$this->check($value['no']));
			$row_content[] = array('data'=>$this->check($value['na']));
			$this->table->add_row($row_content);

		}

		echo $this->table->generate();



	}

	private function check($value){
		return ($value=="FALSE")? '<i class="fa fa-check-circle color1"></i>': "-";
	}




}