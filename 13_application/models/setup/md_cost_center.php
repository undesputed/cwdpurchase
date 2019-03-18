<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_cost_center extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_cumulative(){
		$sql = "SELECT id 'PK',itemno 'ID',itemdescription 'TYPE' FROM pay_item;";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	public function save(){

		if($this->input->post('id')==""){
			$sql    = "SELECT COUNT(id)+1 as max FROM pay_item WHERE title_id = '".$this->session->userdata('Proj_Main')."'";
			$result = $this->db->query($sql);
			$row = $result->row_array();

			$insert = array(
					'itemno'=>$row['max'],
					'itemdescription'=>$this->input->post('cost_center'),
					'title_id'=>$this->session->userdata('Proj_Main'),
				);
			$this->db->insert('pay_item',$insert);
		}else{
						
			$insert = array(					
					'itemdescription'=>$this->input->post('cost_center'),			
				);
			$this->db->where('id',$this->input->post('id'));
			$this->db->update('pay_item',$insert);

		}

	}


}