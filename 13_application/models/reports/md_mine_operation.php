<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_mine_operation extends CI_Model {


	public function get_operation($arg){

		$sql = "CALL display_production3_ben('".$arg['date_from']."','".$arg['date_to']."','".$arg['owner']."','%');";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result->result_array();

	}

	public function get_barging($arg){

		$sql = "CALL display_barging3_ben('".$arg['date_from']."','".$arg['date_to']."','".$arg['owner']."','%');";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result->result_array();

	}







}