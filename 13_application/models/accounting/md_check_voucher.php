<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_check_voucher extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_cumulative($date){

		$sql = "CALL display_setup_cv('".$date."','".$this->session->userdata('Proj_Main')."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}


	public function save_check_voucher(){
		$cv_id = "0";
		if($this->input->post('cv_id')==""){

			$insert = array(
				'serial_no_from'=>"CV #".$this->input->post('year')."-".$this->input->post('serial_no_from'),
				'serial_no_to'=>"CV #".$this->input->post('year')."-".$this->input->post('serial_no_to'),
				'quantity'=>$this->input->post('quantity'),
				'date_issued'=>$this->input->post('date_issued'),
				'remarks'=>$this->input->post('remarks'),
				'employee_id'=>$this->input->post('employee_id'),
				'title_id'=>$this->session->userdata('Proj_Code'),
				'year'=>$this->input->post('year'),
			);	
			$this->db->insert('setup_cv',$insert);

			$cv_id = $this->db->insert_id();

		}else{

			$insert = array(
				'serial_no_from'=>"CV #".$this->input->post('year')."-".$this->input->post('serial_no_from'),
				'serial_no_to'=>"CV #".$this->input->post('year')."-".$this->input->post('serial_no_to'),
				'quantity'=>$this->input->post('quantity'),
				'date_issued'=>$this->input->post('date_issued'),
				'remarks'=>$this->input->post('remarks'),
				'employee_id'=>$this->input->post('employee_id'),
				'title_id'=>$this->session->userdata('Proj_Code'),
				'year'=>$this->input->post('year'),
			);
			$this->db->where('cv_id',$this->input->post('cv_id'));
			$this->db->update('setup_cv',$insert);

			$sql = "DELETE FROM setup_cv_dtl WHERE cv_id = '".$this->input->post('cv_id')."'";
			$this->db->query($sql);

			$cv_id = $this->input->post('cv_id');
		}
			
		$insert_all = array();
		$i   = ltrim($this->input->post('serial_no_from'),'0');
		$cnt = ltrim($this->input->post('serial_no_to'),'0');



		for ($i; $i <= $cnt ; $i++){

			$insert = array(
				'cv_id'=>$cv_id,
				'cv_no'=>'CV #'.$this->input->post('year').'-'.str_pad($i,6, '0', STR_PAD_LEFT),
			);

			
			$insert_all[] = $insert;

		}

		$this->db->insert_batch('setup_cv_dtl',$insert_all);

		return true;
	}



		


}