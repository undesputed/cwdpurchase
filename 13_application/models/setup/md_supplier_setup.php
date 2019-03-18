<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_supplier_setup extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_cumulative(){
		$sql = "SELECT * FROM business_list WHERE type='BUSINESS' order by business_name asc";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	public function get_supplierMain($id){
		$sql = "SELECT * FROM business_list where business_number = '$id'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();
	}

	public function get_supplierDetails($id){

		$sql = "SELECT * FROM business_list_detail where business_number = '$id'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}
	


	public function save_supplier(){

		$insert = array(
			 'business_name'=>$this->input->post('business_name'),
			 'trade_name'=>$this->input->post('trade_name'),
			 'address'=>$this->input->post('address'),
			 'contact_no'=>$this->input->post('contact_no'),
			 'contact_person'=>$this->input->post('contact_person'),
			 'tin_number'=>$this->input->post('tin_number'),
			 'mode_delivery'=>$this->input->post('mode_of_delivery'),
			 'leadtime_delivery'=>$this->input->post('delivery_lead_time'),
			 'term_type'=>$this->input->post('payment_terms'),
			 'term_days'=>$this->input->post('term_days'),
			 'vat'=>$this->input->post('vat'),
			 'vat_percentage'=>$this->input->post('vat_percentage'),
			 'credit_limit'=>$this->input->post('credit_line_limit'),
			 'title_id'=>$this->session->userdata('Proj_Main'),
			);

		$this->db->insert('business_list',$insert);

		$details = $this->input->post('details');
		if(!empty($details)){
				foreach($details as $row){
					$insert_details = array(
							'business_number'=>$this->db->insert_id(),
							'type'=>$row['contact_type'],
							'tel_no'=>$row['contact_number'],
						);
					$this->db->insert('business_list_detail',$insert_details);

				}
		}		
		return true;

	}

	public function update_supplier(){

		$insert = array(
			 'business_name'=>$this->input->post('business_name'),
			 'trade_name'=>$this->input->post('trade_name'),
			 'address'=>$this->input->post('address'),
			 'contact_no'=>$this->input->post('contact_no'),
			 'contact_person'=>$this->input->post('contact_person'),
			 'tin_number'=>$this->input->post('tin_number'),
			 'mode_delivery'=>$this->input->post('mode_of_delivery'),
			 'leadtime_delivery'=>$this->input->post('delivery_lead_time'),
			 'term_type'=>$this->input->post('payment_terms'),
			 'term_days'=>$this->input->post('term_days'),
			 'vat'=>$this->input->post('vat'),
			 'vat_percentage'=>$this->input->post('vat_percentage'),
			 'credit_limit'=>$this->input->post('credit_line_limit'),
			 'title_id'=>$this->session->userdata('Proj_Main'),
			);
		$this->db->where('business_number',$this->input->post('id'));
		$this->db->update('business_list',$insert);
		$this->db->query("DELETE FROM business_list_detail WHERE business_number = '".$this->input->post('id')."'");

		$details = $this->input->post('details');
		if(!empty($details)){
				foreach($details as $row){
					$insert_details = array(
							'business_number'=>$this->input->post('id'),
							'type'=>$row['contact_type'],
							'tel_no'=>$row['contact_number'],
						);
					$this->db->insert('business_list_detail',$insert_details);

				}
		}		
		return true;

	}



	public function cancel($id){

		$sql = "UPDATE business_list SET `status`='INACTIVE' WHERE business_number = '$id'";
		$result = $this->db->query($sql);

		return true;

	}

	public function activate($id){

		$sql = "UPDATE business_list SET `status`='ACTIVE' WHERE business_number = '$id'";
		$result = $this->db->query($sql);

		return true;

	}


	public function select2($where){
		$sql = "SELECT * FROM business_list WHERE status = 'ACTIVE' AND {$where} ORDER BY business_name ASC";
		$result = $this->db->query($sql);				
		return $result->result_array();

	}


}