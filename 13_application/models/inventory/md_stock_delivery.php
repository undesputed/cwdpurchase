<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_stock_delivery extends CI_model {

	public function __construct(){
		parent :: __construct();		
	}

	
	function get_cumulative($location){
		$sql = "CALL display_DR_main1('".$location."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	function get_dispatch($location){
		$sql = "CALL display_dispatch_list('".$location."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	function get_itemDetails($id){
		$sql = "CALL display_dispatch_details_new('".$id."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	function save_delivery(){
		$this->db->trans_begin();

		$insert = array(
			'dispatch_no'=>$this->input->post('dispatch_no'),
			'issued_by'=>$this->input->post('issued_by'),
			'requested_by'=>$this->input->post('issued_by'),
			'approved_by'=>$this->input->post('approved_by'),
			'date_created'=>$this->input->post('date_created'),
			'remarks'=>$this->input->post('remarks'),
			'to_location'=>$this->input->post('to_location'),
			'from_location'=>$this->input->post('from_location'),
			'request_id'=>$this->input->post('request_id'),
			'from_title_id'=>$this->input->post('from_title_id'),
			'delivered_by'=>$this->input->post('delivered_by'),
			'inside'=>$this->input->post('inside'),
			'dispatch_type'=>$this->input->post('dispatch_type'),
		);
		$this->db->insert('dispatch_main',$insert);


		foreach($this->input->post('details') as $key => $value){
			$insert_details = array(
				'dispatch_main'=>$this->db->insert_id(),
				'request_id'=>$value['equipment_request_id'],
				'equipment_id'=>$value['equipment_id'],
				'location_id'=>$value['to_location'],
				'date_created'=>$this->input->post('date_created'),
				'remarks'=>$this->input->post('remarks'),
				'item_no'=>$value['item_no'],
				'quantity'=>$value['quantity'],
			);			
			$this->db->insert('dispatch_detail',$insert_details);

			$sql = "UPDATE equipment_request_main SET request_status = 'DISPATCHED' WHERE equipment_request_id = '".$value['equipment_request_id']."'";
            $this->db->query($sql);

         	$sql = "UPDATE db_equipmentlist SET equipment_status = 'DISPATCHED', equipment_statuscode = '6' WHERE equipment_id = '".$value['equipment_id']."'";
         	$this->db->query($sql);         	
		}

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


	function get_details($id){

		$sql = "SELECT
		  `dispatch_detail`.`item_no`,
		  `setup_group_detail`.`description`,
		  `dispatch_detail`.`quantity`
		  FROM `dispatch_detail`
		  INNER JOIN `setup_group_detail`
		  ON (`dispatch_detail`.`item_no` = `setup_group_detail`.`group_detail_id`)
		  WHERE dispatch_main = '".$id."'";
		  $result = $this->db->query($sql);
		  $this->db->close();
		  return $result;

	}

	function get_mainData($id){

		$sql = "SELECT * FROM dispatch_main WHERE dispatch_id = '".$id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}

	function get_detailsData($id){		
		$sql = "
				 SELECT
			     dispatch_detail.dispatch_main 'equipment_request_id',
			     dispatch_detail.equipment_id,
			     setup_group_detail.description 'item_description',
			     dispatch_detail.quantity,
			     dispatch_detail.item_no,
			     dispatch_detail.location_id 'to_location',
			      dispatch_detail.quantity 'withdrawn_qty'
			FROM `dispatch_detail`
			INNER JOIN `setup_group_detail`
			ON (`dispatch_detail`.`item_no` = `setup_group_detail`.`group_detail_id`)
			WHERE dispatch_main = '".$id."';
		";
		$result =  $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}


	function update_delivery(){
		$this->db->trans_begin();

		$insert = array(
			'dispatch_no'=>$this->input->post('dispatch_no'),
			'issued_by'=>$this->input->post('issued_by'),
			'requested_by'=>$this->input->post('issued_by'),
			'approved_by'=>$this->input->post('approved_by'),
			'date_created'=>$this->input->post('date_created'),
			'remarks'=>$this->input->post('remarks'),
			'to_location'=>$this->input->post('to_location'),
			'from_location'=>$this->input->post('from_location'),
			'request_id'=>$this->input->post('request_id'),
			'from_title_id'=>$this->input->post('from_title_id'),
			'delivered_by'=>$this->input->post('delivered_by'),
			'inside'=>$this->input->post('inside'),
			'dispatch_type'=>$this->input->post('dispatch_type'),
		);
		
		$this->db->where('dispatch_id',$this->input->post('dispatch_id'));
		$this->db->update('dispatch_main',$insert);
		
		$sql = "DELETE FROM dispatch_detail where dispatch_main = '".$this->input->post('dispatch_id')."'";
		$this->db->query($sql);

		foreach($this->input->post('details') as $key => $value){
			$insert_details = array(
				'dispatch_main'=>$this->input->post('dispatch_id'),
				'request_id'=>$value['equipment_request_id'],
				'equipment_id'=>$value['equipment_id'],
				'location_id'=>$value['to_location'],
				'date_created'=>$this->input->post('date_created'),
				'remarks'=>$this->input->post('remarks'),
				'item_no'=>$value['item_no'],
				'quantity'=>$value['quantity'],
			);

			$this->db->insert('dispatch_detail',$insert_details);

			$sql = "UPDATE equipment_request_main SET request_status = 'DISPATCHED' WHERE equipment_request_id = '".$value['equipment_request_id']."'";
            $this->db->query($sql);

         	$sql = "UPDATE db_equipmentlist SET equipment_status = 'DISPATCHED', equipment_statuscode = '6' WHERE equipment_id = '".$value['equipment_id']."'";
         	$this->db->query($sql);         	
		}

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