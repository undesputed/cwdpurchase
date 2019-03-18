<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_project_operation extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	function operation(){

		$sql = "Select * from fvc_operation_setup where status ='ACTIVE'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}

	function insert_operation(){

		$insert = array(
			'operation_type'=>$this->input->post('description'),
			'code'=>$this->input->post('code'),
			'userid'   =>$this->session->userdata('user'),
			'location' =>$this->session->userdata('Proj_Code'),
			'title_id' =>$this->session->userdata('Proj_Main'),
			);		
		$this->db->insert('fvc_operation_setup',$insert);
		return true;

	}

	function update_operation(){

		$insert = array(
			'operation_type'=>$this->input->post('description'),
			'code'=>$this->input->post('code'),
			'userid'   =>$this->session->userdata('user'),
			'location' =>$this->session->userdata('Proj_Code'),
			'title_id' =>$this->session->userdata('Proj_Main'),
			);

		$this->db->where('operation_id',$this->input->post('id'));
		$this->db->update('fvc_operation_setup',$insert);
		return true;

	}


	/**Project Area**/

	function project_area(){

		$sql = "Select * from fvc_area_setup where status ='ACTIVE'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}

	function insert_project_area(){

		$insert = array(
			'area_description'=>$this->input->post('description'),
			'code'=>$this->input->post('code'),
			'userid'   =>$this->session->userdata('user'),
			'location' =>$this->session->userdata('Proj_Code'),
			'title_id' =>$this->session->userdata('Proj_Main'),
			);		
		$this->db->insert('fvc_area_setup',$insert);
		return true;

	}

	function update_project_area(){

		$insert = array(
			'area_description'=>$this->input->post('description'),
			'code'=>$this->input->post('code'),
			'userid'   =>$this->session->userdata('user'),
			'location' =>$this->session->userdata('Proj_Code'),
			'title_id' =>$this->session->userdata('Proj_Main'),
			);

		$this->db->where('area_id',$this->input->post('id'));
		$this->db->update('fvc_area_setup',$insert);
		return true;

	}



	/**AREA OF ACTIVITY**/


	function area_activity(){

		$sql = "SELECT * FROM fvc_assign_setup WHERE STATUS = 'ACTIVE'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}
	
	function insert_area_activity(){

		if($this->input->post('factor')){
			$with_factor = 'YES';
			$factor_formula = 'ACTIVITY * TRUCK FACTOR';
		}else{
			$with_factor = 'NO';
			$factor_formula = 'ACTIVITY * TRUCK FACTOR';
		}
				
		$insert = array(
			'assign_description'=>$this->input->post('description'),
			'code'=>$this->input->post('code'),
			'userid'   =>$this->session->userdata('user'),
			'location' =>$this->session->userdata('Proj_Code'),
			'title_id' =>$this->session->userdata('Proj_Main'),
			'with_factor' =>$with_factor,
			'factor_formula' =>$factor_formula,
			);		

		$this->db->insert('fvc_assign_setup',$insert);
		return true;

	}

	function update_area_activity(){

		if($this->input->post('factor')){
			$with_factor = 'YES';
			$factor_formula = 'ACTIVITY * TRUCK FACTOR';
		}else{
			$with_factor = 'NO';
			$factor_formula = 'ACTIVITY * TRUCK FACTOR';
		}


		$insert = array(
			'assign_description'=>$this->input->post('description'),
			'code'=>$this->input->post('code'),
			'userid'   =>$this->session->userdata('user'),
			'location' =>$this->session->userdata('Proj_Code'),
			'title_id' =>$this->session->userdata('Proj_Main'),
			'with_factor' =>$with_factor,
			'factor_formula' =>$factor_formula,
			);

		$this->db->where('assign_id',$this->input->post('id'));
		$this->db->update('fvc_assign_setup',$insert);
		return true;

	}

	
	/**Activity Scope**/
	
	function activity_scope(){

		$sql = "SELECT * FROM project_scope_type WHERE STATUS = 'ACTIVE'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}
	
	function insert_activity_scope(){

		
				
		$insert = array(
			'type'=>$this->input->post('description'),
			'code'=>$this->input->post('code'),
			'userid'   =>$this->session->userdata('user'),
			'location' =>$this->session->userdata('Proj_Code'),
			'title_id' =>$this->session->userdata('Proj_Main'),
			);		

		$this->db->insert('project_scope_type',$insert);
		return true;

	}

	function update_activity_scope(){


		$insert = array(
			'type'=>$this->input->post('description'),
			'code'=>$this->input->post('code'),
			'userid'   =>$this->session->userdata('user'),
			'location' =>$this->session->userdata('Proj_Code'),
			'title_id' =>$this->session->userdata('Proj_Main'),
			);

		$this->db->where('id',$this->input->post('id'));
		$this->db->update('project_scope_type',$insert);
		return true;

	}








}