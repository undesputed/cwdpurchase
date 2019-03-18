<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Check_number extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_check_setup');
	}



	public function index(){

		$this->lib_auth->title = "Check Number Setup";		
		$this->lib_auth->build = "accounting/check_setup/index";
		
		$data['get_bankName'] = $this->md_check_setup->get_bankName($this->session->userdata('Proj_Main'));

		$this->lib_auth->render($data);
		
	}


	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped tbl-event table-hover">' );
		$this->table->set_template($tmpl);

		$result = $this->md_check_setup->get_cumulative();		

		$show = array(
					array('data'=>'check_id','style'=>'display:none'),
					array('data'=>'bank_id','style'=>'display:none'),
					array('data'=>'date','style'=>'display:none'),
					array('data'=>'remarks','style'=>'display:none'),
					array('data'=>'asset_dtlID','style'=>'display:none'),
					'Account No.',
					'Account Name',
					'Bank Name',
					'Stub No',
					'Serial No(From)',
					'Serial No(To)',
					'Quantity',
					'Action',
			 	);

			foreach($result->result_array() as $key => $value){
				$row_content = array();
								
				$row_content[] = array('data'=>$value['check_id'],'class'=>'check_id','style'=>'display:none');
				$row_content[] = array('data'=>$value['bank_id'],'class'=>'bank_id','style'=>'display:none');
				$row_content[] = array('data'=>$value['Date Issued'],'class'=>'date','style'=>'display:none');
				$row_content[] = array('data'=>$value['Remarks'],'class'=>'remarks','style'=>'display:none');
				$row_content[] = array('data'=>$value['asset_dtlID'],'class'=>'asset_dtlID','style'=>'display:none');
				$row_content[] = array('data'=>$value['Account No.'],'class'=>'account_no','style'=>'');
				$row_content[] = array('data'=>$value['Account Name'],'class'=>'account_name');
				$row_content[] = array('data'=>$value['Bank Name'],'class'=>'bank_name');
				$row_content[] = array('data'=>$value['Stub No'],'class'=>'stub_no');				
				$row_content[] = array('data'=>$value['Serial No(From)'],'class'=>'serial_no_from');
				$row_content[] = array('data'=>$value['Serial No(To)'],'class'=>'serial_no_to');
				$row_content[] = array('data'=>$value['Quantity'],'class'=>'quantity');
				$row_content[] = array('data'=>'<span class="event btn-link update">Update</span>','class'=>'');
				
				$this->table->add_row($row_content);

			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();

	}


	public function get_account(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_check_setup->get_account($this->input->post('bank_id'));


		$data['account_no'] = array();
		$data['account_name'] = array();
		foreach($result as $row){

				$data['account_no'][]   = array('dtl_id'=>$row['dtl_id'],'account_no'=>$row['account_no']);
				$data['account_name'][] = array('dtl_id'=>$row['dtl_id'],'account_name'=>$row['account_name']);

		}

		echo json_encode($data);

	}


	public function save_check(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		
		echo $this->md_check_setup->save_check();
	}







}