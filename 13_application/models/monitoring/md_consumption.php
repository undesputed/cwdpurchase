<?php defined('BASEPATH') OR exit('No direct script access allowed');

class md_consumption extends CI_MODEL {

	public function __construct(){
		parent :: __construct();		
	}




	public function display_heading(){
		$sql = "SELECT
				  withdraw_main.pay_item,
				  itemdescription
				FROM withdraw_main
				  INNER JOIN pay_item
				    ON (withdraw_main.pay_item = pay_item.id)
				WHERE withdraw_main.location = '".$this->input->post('location')."'
				    AND withdraw_main.date_withdrawn BETWEEN '".$this->input->post('date_from')."'
				    AND '".$this->input->post('date_to')."'
				GROUP BY withdraw_main.pay_item
				ORDER BY itemdescription;";
		$result = $this->db->query($sql);
		return $result->result_array();

	}



	public function display_amount(){

		$output = null;
		$sql = "SELECT withdraw_main.pay_item, itemdescription FROM withdraw_main INNER JOIN pay_item  ON (withdraw_main.pay_item = pay_item.id) WHERE withdraw_main.location = '".$this->input->post('location')."' AND withdraw_main.date_withdrawn BETWEEN '".$this->input->post('date_from')."' AND '".$this->input->post('date_to')."' GROUP BY withdraw_main.pay_item;";			
		$result = $this->db->query($sql);
		

		$sql = "CALL project_cost_monitoring_SQL('".$this->input->post('location')."','".$this->input->post('date_from')."','".$this->input->post('date_to')."')";
		$result = $this->db->query($sql);			
		$this->db->close();
		
		$row = $result->row_array();
		if(isset($row['RESULT'])){			

			$result = $this->db->query($row['RESULT']);			
			$this->db->close();
			$output = $result->result_array();

		}

		return $output;

	}

	public function display_quantity(){

		$output = null;
		$sql = "SELECT withdraw_main.pay_item, itemdescription,withdraw_details.item_no,withdraw_details.item_description FROM withdraw_main INNER JOIN pay_item  ON (withdraw_main.pay_item = pay_item.id) INNER JOIN withdraw_details ON (withdraw_main.withdraw_id = withdraw_details.withdraw_main_id)WHERE withdraw_main.location = '".$this->input->post('location')."' AND withdraw_main.date_withdrawn BETWEEN '".$this->input->post('date_from')."' AND '".$this->input->post('date_to')."' GROUP BY withdraw_main.pay_item ORDER BY itemdescription,withdraw_details.item_description";
		$result = $this->db->query($sql);
		

		$sql = "CALL project_consumption_monitoring_SQL('".$this->input->post('location')."','".$this->input->post('date_from')."','".$this->input->post('date_to')."')";
		$result = $this->db->query($sql);
		$this->db->close();
		
		$row = $result->row_array();
		if(isset($row['RESULT'])){			

			$result = $this->db->query($row['RESULT']);			
			$output = $result->result_array();

		}

		return $output;

	}
	


}