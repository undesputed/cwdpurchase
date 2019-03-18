<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_event_logs extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function create($arg){

		$insert = array(
			'type'=>$arg['type'],
			'transaction_no'=>$arg['transaction_no'],
			'transaction_id'=>$arg['transaction_id'],
			'remarks'=>$arg['remarks'],
			'action'=>$arg['action'],
			'emp_id'=>$this->session->userdata('emp_id'),
			'emp_fullname'=>$this->session->userdata('username'),
			'project_id'=>$this->session->userdata('Proj_Code'),
			'title_id'=>$this->session->userdata('Proj_Main'),
			'date_created'=>date("Y-m-d H:i:s"),
		);

		$this->db->insert('event_logs',$insert);

		return true;

	}

	public function get($arg){
		
		$sql = "
			SELECT * FROM event_logs WHERE transaction_id = '".$arg['transaction_id']."' AND transaction_no = '".$arg['transaction_no']."';
		";
		$result = $this->db->query($sql);
		return $result->result_array();
	}


	/*
	type
	transaction_no
	remarks
	action
	*/


}