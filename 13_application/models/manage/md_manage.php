<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_manage extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function save(){


	/*	$fullname = $this->input->post('lastname').', '.$this->input->post('firstname');
		$insert = array(
			'UserName_User'=>$this->input->post('username'),
			'Password_User'=>$this->input->post('password'),
			'UserFull_Name'=>$fullname,
			'Type_User'=>$this->input->post('usertype'),
		);

		$this->db->insert('users',$insert);		
		return true;*/

		$insert = array(
			'username'=>$this->input->post('username'),
			'password'=>$this->input->post('password'),
			'firstname'=>$this->input->post('firstname'),
			'lastname'=>$this->input->post('lastname'),
			'position'=>$this->input->post('usertype'),
			'department'=>'admin',
		);

		$this->db->insert('web_users',$insert);
		return true;

	}

	public function block($id){
		$sql = "select * from web_users where id = '".$id."'";
		$result = $this->db->query($sql);
		$result = $result->row_array();

		$status = ($result['status'] == 'ACTIVE')? 'INACTIVE': 'ACTIVE' ;		

		$insert = array(
			'status'=>$status,
		);
		$this->db->where('id',$id);
		$this->db->update('web_users',$insert);	
	}



	public function update($id){

		$fullname = $this->input->post('lastname').', '.$this->input->post('firstname');
		$insert = array(
			'UserName_User'=>$this->input->post('username'),
			'Password_User'=>$this->input->post('password'),
			'UserFull_Name'=>$fullname,
			'Type_User'=>$this->input->post('usertype'),
		);
		$this->db->where('SysPK_User',$id);
		$this->db->update('users',$insert);		

	}

	public function update_position($id){
		
		$insert = array(
			'position'=>$this->input->post('usertype'),
		);
		$this->db->where('id',$id);
		$this->db->update('web_users',$insert);		

	}



	public function getUsers(){

		$sql = "SELECT * FROM web_users";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result;

	}

	public function getUserId($id){

		$sql = "SELECT * FROM web_users WHERE id = '$id'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}




}