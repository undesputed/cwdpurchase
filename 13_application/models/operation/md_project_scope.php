<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_project_scope extends CI_model {

	public function __construct(){
		parent :: __construct();		
	}


	function all_scope(){

		$sql = "SELECT * FROM project_scope_type WHERE `status` = 'ACTIVE'";
		$result = $this->db->query($sql);	
		$this->db->close();
		return $result->result_array();

	}



	function get_scope(){

		$active = ($this->input->post('active'))? '%': 'ACTIVE';

		$sql    = "CALL display_project_scope('".$active."','".$this->input->post('location')."')";	
		$result = $this->db->query($sql);				
		$this->db->close();

		return $result->result_array();
	}


	function get_dataScope(){
		$sql = "SELECT
				  `scope_id`,
				  `type_id`,
				  `type_desc`,
				  `remarks`,
				  `type_code`
				FROM project_scope_setup
				WHERE STATUS = 'ACTIVE'
				    AND location = '".$this->input->post('location')."'
				ORDER BY `type_code` ASC;";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result;	
	}


	function insert_scope_setup(){		
		$data = json_decode($this->input->post('data'));
		
		$this->db->trans_begin();

		$sql = "SELECT COUNT(*) as cnt FROM project_scope_setup WHERE location = '".$data[0]->{'location'}."'";
		$result = $this->db->query($sql);

		$cnt = $result->row_array();

		$sql = "UPDATE project_scope_setup SET `status` = 'INACTIVE' WHERE location = '".$data[0]->{'location'}."'";		
		$this->db->query($sql);

		for ($i=0; $i <count($data) ; $i++){ 

			if($cnt['cnt']==0){
					$insert = array(
						'type_id'  =>$data[$i]->{'id'},
						'type_code'=>$data[$i]->{'type'},
						'type_desc'=>$data[$i]->{'title'},
						'userid'   =>$this->session->userdata('user'),
						'location' =>$data[$i]->{'location'},
						'title_id' =>$data[$i]->{'project'},
					);
					$this->db->insert('project_scope_setup',$insert);		

			}else{
				$sql = "UPDATE project_scope_setup SET type_id = '".$data[$i]->{'id'}."',type_desc='".$data[$i]->{'title'}."',`status`='ACTIVE' WHERE location = '".$data[$i]->{'location'}."' AND type_code = '".$data[$i]->{'type'}."'";
				$this->db->query($sql);

				if($this->db->affected_rows()==0){
					$insert = array(
						'type_id'  =>$data[$i]->{'id'},
						'type_code'=>$data[$i]->{'type'},
						'type_desc'=>$data[$i]->{'title'},
						'userid'   =>$this->session->userdata('user'),
						'location' =>$data[$i]->{'location'},
						'title_id' =>$data[$i]->{'project'},
						);
					$this->db->insert('project_scope_setup',$insert);	
				}
			}

				
		}
			

		if($this->db->trans_status()===TRUE){
			$this->db->trans_commit();
			return true;
		}else{
			$this->db->trans_rollback();
		}
		
		
	}




}