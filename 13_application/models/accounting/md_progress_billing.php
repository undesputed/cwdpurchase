<?php defined('BASEPATH') OR exit('No direct script access allowed');

class md_progress_billing extends CI_MODEL {

	public function __construct(){
		parent :: __construct();		
		
	}

	public function get_project_category(){
		$sql = "
			SELECT * FROM project_category;
		";
		
		$result = $this->db->query($sql);
		return $result->result_array();
	}


}
