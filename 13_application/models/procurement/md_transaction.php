<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_transaction extends CI_Model {

	public function __construct(){
		parent :: __construct();		
		
	}
	
	public function has_canvass_pr($pr_id){

		$sql = "SELECT * FROM purchaserequest_main WHERE pr_id = '".$pr_id."' AND canvass_no <> '';";
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	public function has_canvass_pr_2($pr_id){
		$sql = "SELECT * FROM canvass_main WHERE pr_id = '".$pr_id."'";
		$result = $this->db->query($sql);
		return $result->row_array();
	}



	public function has_po_pr($pr_id){
		
		$sql = "SELECT * FROM purchase_order_main WHERE pr_id = '".$pr_id."';";
		$result = $this->db->query($sql);
		if($result->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}

	public function has_rr($pr_id){

		$sql = "SELECT * FROM purchase_order_main WHERE pr_id = '".$pr_id."' AND (STATUS ='COMPLETE' OR STATUS = 'PARTIAL') LIMIT 1;";

		$result = $this->db->query($sql);
		$row = $result->row_array();
		$status = '';

		if($result->num_rows() > 0){
				if($row['status'] == 'COMPLETE')
				{	
					$status = "label-success";
				}else{
					$status = "label-info";
				}
		
			return array('title'=>$row['status'],'status'=>$status);			
		}else{
			return false;
		}

	}

	public function incoming(){

		$branch_type = $this->session->userdata('branch_type');
		$where = '';
		switch($branch_type){
			case "MAIN OFFICE":
				 $where = "
				   		   to_projectMain = '".$this->session->userdata('Proj_Main')."'
				 ";
			break;
			case "PROFIT CENTER":
				$where = " ( to_projectCode = '".$this->session->userdata('Proj_Code')."'
							AND to_projectMain = '".$this->session->userdata('Proj_Main')."') ";
			break;			
			default:
				$where = " ( to_projectCode = '".$this->session->userdata('Proj_Code')."'
							AND to_projectMain = '".$this->session->userdata('Proj_Main')."') ";
			break;

		}


		$sql = "
			SELECT count(*) 'cnt'
			FROM transaction_history
			WHERE ".$where."
			AND TYPE = 'Purchase Request'
			AND STATUS = 'PENDING'
		";

		$result = $this->db->query($sql);				
		$row = $result->row_array();				
		$row['cnt'];
		if(isset($row['cnt']) && $row['cnt'] > 0){
			return "<span class='badge badge-pos'>".$row['cnt']."</span>";
		}else{
			return "";
		}

	}


	public function outgoing(){

		$sql = "
			SELECT count(*) 'cnt'
			FROM transaction_history
			WHERE from_projectCode = '".$this->session->userdata('Proj_Code')."'
			AND from_projectMain = '".$this->session->userdata('Proj_Main')."'
			AND TYPE = 'Purchase Request'
			AND STATUS = 'PENDING'
		";

		$result = $this->db->query($sql);				
		$row = $result->row_array();				
		$row['cnt'];
		if(isset($row['cnt']) && $row['cnt'] > 0){
			return "<span class='badge badge-pos'>".$row['cnt']."</span>";
		}else{
			return "";
		}

	}


}