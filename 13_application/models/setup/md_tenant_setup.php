<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_tenant_setup extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_cumulative(){
		$sql = "
			SELECT *,
			(SELECT SUM(amount) FROM withdraw_tenant_details WHERE tenant_id = withdraw_tenant.id) 'contract_amount' 
			 FROM withdraw_tenant
			 WHERE withdraw_tenant.status = 'ACTIVE'
		";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}


	public function get_main($id){

		$sql = "SELECT * FROM withdraw_tenant where id = '$id'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}


	public function get_details($id){

		$sql    = "SELECT * FROM withdraw_tenant_details WHERE tenant_id = '$id'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function save(){
		
		$insert = array(
			'name'=>$this->input->post('tenant_name'),
			'address'=>$this->input->post('address'),
			'contact_no'=>$this->input->post('contact_no'),
			'project_id'=>$this->input->post('project_id'),
			'title_id'=>$this->session->userdata('Proj_Main'),
			);

		$this->db->insert('withdraw_tenant',$insert);
		$id   = $this->db->insert_id();
		$data = $this->input->post('data');

		$insert_details = array(
			'tenant_id'=>$id,
			'amount'=>$data['contract_amount'],
			'remarks'=>$data['remarks'],
			);
		$this->db->insert('withdraw_tenant_details',$insert_details);
		return true;

	}

	public function update(){

		$insert = array(
			'name'=>$this->input->post('tenant_name'),
			'address'=>$this->input->post('address'),
			'contact_no'=>$this->input->post('contact_no'),
			'project_id'=>$this->input->post('project_id'),
			);
		$this->db->where('id',$this->input->post('id'));
		$this->db->update('withdraw_tenant',$insert);
		$this->db->query("DELETE FROM withdraw_tenant_details WHERE tenant_id = '".$this->input->post('id')."'");

		$details = $this->input->post('details');
	
		$id   = $this->input->post('id');
		$data = $this->input->post('data');

		$insert_details = array(
			'tenant_id'=>$id,
			'amount'=>$data['contract_amount'],
			'remarks'=>$data['remarks'],
			);
		$this->db->insert('withdraw_tenant_details',$insert_details);
		return true;

	}

		
	public function cancel($id){

		$sql = "UPDATE withdraw_tenant SET `status`='INACTIVE' WHERE id = '$id'";
		$result = $this->db->query($sql);
		return true;		
	}

	public function activate($id){

		$sql = "UPDATE withdraw_tenant SET `status`='ACTIVE' WHERE id = '$id'";
		$result = $this->db->query($sql);

		return true;

	}





}