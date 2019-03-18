<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_chart_of_account extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_cumulative($location = "0"){
		
		$sql = "CALL accounting_display_title_accnt('".$this->session->userdata('Proj_Code')."')";
		$result = $this->db->query($sql);
		return $result;
		
	}
	

	public function save_changes(){
		
		$data = $this->input->post('data');

		$data = json_decode($data,true);


		

		$sql = "UPDATE setup_title_accounts SET `status` = 'INACTIVE' WHERE title_id = '".$this->session->userdata('Proj_Main')."'";
		$result = $this->db->query($sql);	


		if(empty($data)){		
			return false;		
		}
		$array = array();
		$insert_batch = array();
		$if_insert = false;
		foreach($data as $row){

			if($row['checked']=='true' && $row['status']==''){
				$if_insert = true;
				$insert_batch[] = array(
					'title_id'=>$this->session->userdata('Proj_Main'),
					'account_id'=>$row['account_id'],
					);

			
			}else if($row['status'] != "" ){
				$array[] = $row['account_id'];				
			}
		}

		if($if_insert){
			$this->db->insert_batch('setup_title_accounts',$insert_batch);
		}


		if(!empty($array))
		{
			$in = implode(',', $array);
			$sql    = "UPDATE setup_title_accounts SET `status` = 'ACTIVE' WHERE title_id = '".$this->session->userdata('Proj_Main')."' AND account_id IN ({$in})";
			$result = $this->db->query($sql);
		}
			
		return true;

		
	}


	public function delete($account_id){

		$this->db->where('account_id',$account_id);
		$this->db->delete('account_setup');

		return true;

	}


}