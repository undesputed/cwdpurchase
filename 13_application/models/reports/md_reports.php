<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_reports extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function create($file){

		$insert = array(
			'submission_date'=>$this->input->post('date'),
			'subject'=>$this->input->post('subject'),
			'caption'=>$this->input->post('caption'),
			'file'=>$file,
			);

		$this->db->insert("tbl_report",$insert);

	}

	public function view($date = ""){

		$date = date('Y-m',strtotime($date));		
		$sql = "SELECT * FROM tbl_report WHERE STATUS = 'ACTIVE' AND  submission_date  LIKE '".$date."%' ORDER BY submission_date desc";
		$result = $this->db->query($sql);
		return $result;
		
	}

	public function generate_date(){
		
		$sql = "SELECT submission_date FROM tbl_report GROUP BY SUBSTRING(submission_date,1,7);";
		$result = $this->db->query($sql);
		return $result->result_array();
		
	}

	public function get_row($id){

		$sql = "SELECT * FROM tbl_report WHERE id ='$id'";
		$result = $this->db->query($sql);
		return $result->row_array();

	}

	public function update($id,$file){

		$insert = array(
			'submission_date'=>$this->input->post('date'),
			'subject'=>$this->input->post('subject'),
			'caption'=>$this->input->post('caption'),
			'file'=>$file,
			);
		$this->db->where('id',$id);
		$this->db->update("tbl_report",$insert);

	}

		
	
}