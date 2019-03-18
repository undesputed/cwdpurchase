<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_inventory_master extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function received($arg){

		/**
			 $arg['receive_qty']
			 $arg['item_no']
			 $arg['location_id']
		**/

		if($this->check_if_exist($arg)){			
			$sql = "
				UPDATE inventory_master SET receive_qty = (receive_qty + ".$arg['receive_qty']."),
				current_qty =(receive_qty - withdraw_qty) WHERE item_no = '".$arg['item_no']."' AND location_id = '".$arg['location_id']."';				
			";
			$this->db->query($sql);

		}else
		{
			$insert = array(
				'item_no'=>$arg['item_no'],
				'location_id'=>$arg['location_id'],
				'receive_qty'=>$arg['receive_qty'],
				'withdraw_qty'=>'0',
				'current_qty'=>$arg['receive_qty'],
				'project_id'=>$arg['project_id'],
				);
			$this->db->insert('inventory_master',$insert);	
		}
		
	}

	public function withdrawal($arg){

		/**

			 $arg['withdraw_qty']
			 $arg['item_no']
			 $arg['location_id']

		**/

		if($this->check_if_exist($arg)){	
			$sql ="
				UPDATE inventory_master SET withdraw_qty = (withdraw_qty + ".$arg['withdraw_qty']."),
				current_qty =(receive_qty - withdraw_qty) WHERE item_no = '".$arg['item_no']."' AND location_id = '".$arg['location_id']."';
			";			
			$this->db->query($sql);
		}
	}

	public function insert($arg){
		$insert = array(
			'location_id'=>$arg['location_id'],
			'received_qty'=>$arg['received_qty'],
			'item_no'=>$arg['item_no'],
			);		
		$this->db->insert('inventory_master',$insert);
	}

	public function check_if_exist($arg){

		$sql = "
			SELECT * FROM inventory_master WHERE item_no = '".$arg['item_no']."' AND location_id = '".$arg['location_id']."';
		";

		$result = $this->db->query($sql);
		if($result->num_rows() > 0)
		{
			return true;
		}else
		{
			return false;
		}

	}

	public function undo($arg){
		
		/**
			$arg['receive_qty']
			$arg['item_no']
			$arg['location_id']
		**/

		$sql = "
				UPDATE inventory_master SET receive_qty = ".$arg['receive_qty'].",
				current_qty =({$arg['receive_qty']} - withdraw_qty) WHERE item_no = '".$arg['item_no']."' AND location_id = '".$arg['location_id']."';				
			";
			
		$result = $this->db->query($sql);		
		echo $this->db->last_query();
		
	}

}