<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Check_voucher extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_check_voucher');

	}

	public function index(){

		$this->lib_auth->title = "Check Voucher";		
		$this->lib_auth->build = "accounting/check_voucher/index";
		

		$this->lib_auth->render();
		
	}


	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped tbl-event table-hover">' );
		$this->table->set_template($tmpl);

		$result = $this->md_check_voucher->get_cumulative($this->input->post('date'));		

		$show = array(
					array('data'=>'cv_id','style'=>'display:none'),
					array('data'=>'cv_id','style'=>'display:none'),
					'Serial From',
					'Serial To',
					'Quantity',
					'Date Issued',
					'Remarks',
					'Employee Name',
					'Year',
					'Action',
			 	);

			foreach($result->result_array() as $key => $value){
				$row_content = array();
							
				$row_content[] = array('data'=>$value['cv_id'],'class'=>'cv_id','style'=>'display:none');
				
				$row_content[] = array('data'=>$value['employee_id'],'class'=>'employee_id','style'=>'display:none');


				/*
				$row_content[] = array('data'=>$value['Date Issued'],'class'=>'date','style'=>'display:none');
				$row_content[] = array('data'=>$value['Remarks'],'class'=>'remarks','style'=>'display:none');
				$row_content[] = array('data'=>$value['asset_dtlID'],'class'=>'asset_dtlID','style'=>'display:none');
				*/

				$row_content[] = array('data'=>$this->is_isset($value['Serial From']),'class'=>'serial_from','style'=>'');
				$row_content[] = array('data'=>$this->is_isset($value['Serial To']),'class'=>'serial_to');
				$row_content[] = array('data'=>$this->is_isset($value['Quantity']),'class'=>'quantity');
				$row_content[] = array('data'=>$this->is_isset($value['Date Issued']),'class'=>'date');				
				$row_content[] = array('data'=>$this->is_isset($value['Remarks']),'class'=>'remarks');
				$row_content[] = array('data'=>$this->is_isset($value['Employee Name']),'class'=>'employee');
				$row_content[] = array('data'=>$this->is_isset($value['Year']),'class'=>'year');

				$row_content[] = array('data'=>'<span class="event btn-link update">Update</span>','class'=>'');
				
				$this->table->add_row($row_content);

			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();

	}

	private function is_isset($type){
		return (isset($type))? $type : '-';
	}

	public function save_check_voucher(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo $this->md_check_voucher->save_check_voucher();		

	}
	


}