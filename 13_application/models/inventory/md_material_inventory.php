<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_material_inventory extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function get_inventory($arg){

		switch($arg['category']){
			case"materials":
				$sql = "call _DISPLAY_INVENTORY_WH_MAT('".$arg['date']."')";
			break;

			case"lubes":
				$sql = "call _DISPLAY_INVENTORY_WH_LUBES('".$arg['date']."')";
			break;

			case"tires":
				$sql = "call _DISPLAY_INVENTORY_WH_TIRES('".$arg['date']."')";
			break;

		}
		
		$result = $this->db->query($sql);
		$this->db->close();

		return $result->result_array();
	}

}