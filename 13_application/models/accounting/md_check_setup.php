<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_check_setup extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_bankName($location){

		$sql = "CALL display_bank_by_projCenter('".$location."','%')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function get_cumulative(){

		$sql = "CALL display_check_main('".$this->session->userdata('Proj_Main')."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
		
	}


	public function get_account($bank_id){

		$sql = "SELECT
				  dtl_id,
				  account_no,
				  account_name
				FROM setup_asset_details
				  INNER JOIN setup_asset
				    ON (setup_asset_details.asset_id = setup_asset.asset_id)
				WHERE setup_asset.status <> 'CANCELLED'
				    AND bank_id = '".$bank_id."'
				    AND proj_profit_center = '".$this->session->userdata('Proj_Main')."'";

		$result = $this->db->query($sql);
		return $result->result_array();

	}
		

	public function save_check(){

		$this->db->trans_begin();
	
		if($this->input->post('check_id')==""){

			$insert = array(
				'bank_id'=>$this->input->post('bank_id'),
				'stub_no'=>$this->input->post('stub_no'),
				'serial_no_from'=>$this->input->post('serial_no_from'),
				'serial_to_to'=>$this->input->post('serial_to_to'),
				'quantity'=>$this->input->post('quantity'),
				'date_issued'=>$this->input->post('date_issued'),
				'remarks'=>$this->input->post('remarks'),
				'employee_id'=>$this->input->post('employee_id'),
				'asset_dtlID'=>$this->input->post('asset_dtlID'),
				'title_id'=>$this->session->userdata('Proj_Main'),
			);
			$this->db->insert('setup_check',$insert);

		}else{	

			$insert = array(
				'bank_id'=>$this->input->post('bank_id'),
				'stub_no'=>$this->input->post('stub_no'),
				'serial_no_from'=>$this->input->post('serial_no_from'),
				'serial_to_to'=>$this->input->post('serial_to_to'),
				'quantity'=>$this->input->post('quantity'),
				'date_issued'=>$this->input->post('date_issued'),
				'remarks'=>$this->input->post('remarks'),
				'employee_id'=>$this->input->post('employee_id'),
				'asset_dtlID'=>$this->input->post('asset_dtlID'),
				'title_id'=>$this->session->userdata('Proj_Main'),
			);

			$this->db->where('check_id',$this->input->post('check_id'));
			$this->db->update('setup_check',$insert);

			$sql = "DELETE FROM setup_check_dtl WHERE check_id='".$this->input->post('check_id')."'";
			$this->db->query($sql);

		}
		
		$insert_all = array();
		$i   = ltrim($this->input->post('serial_no_from'),'0');
		$cnt = ltrim($this->input->post('serial_to_to'),'0');

		for ($i; $i <= $cnt ; $i++){ 
			$insert = array(
				'check_id'=>$this->db->insert_id(),
				'check_no'=>str_pad($i,6, '0', STR_PAD_LEFT),
			);
			$insert_all[] = $insert;
		}

		$this->db->insert_batch('setup_check_dtl',$insert_all);


		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}
		

	}



	


}