<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Md_auth extends CI_Model {

	function __construct(){	
		parent::__construct();
		$this->load->model('md_project');		
	}
	


	/*function login($username,$password,$remember = false){
		$sql   = "SELECT * FROM users WHERE UserName_User = ? AND Password_User = ? LIMIT 1";		
		$query = $this->db->query($sql,array($username,$password));
		
		if($query->num_rows > 0){

			$user = $query->row();
			//$LABEL_SUB_NAME = $this->DataObject("SELECT title_name FROM project_title  WHERE title_id = '".$user->Proj_Main."'");
			$this->session->set_userdata(array('user'=>$user->SysPK_User,'username'=>$user->UserFull_name,'Proj_Main'=>$user->Proj_Main,'Proj_Code'=>$user->Proj_Code,'person_code'=>$user->Person_code,'employee_id'=>$user->Employee_id,'type_user'=>$user->Type_User));
			//$project = $this->md_project->get_projects($user->Proj_Code);
			//$this->session->set_userdata(array('project'=>$project));
			

			if($remember){
					$expire = (60*60*24*365*2);
					set_cookie(array(
						'name'=>'identity',
						'value'=>$user->SysPK_User,
						'expire'=>$expire					
					));
			}
			return true;
			
		}else{
			return false;			
		}
					
	}*/

	function login($username,$password,$remember = false){

		$sql   = "SELECT * FROM users WHERE UserName_User = ? AND Password_User = ? LIMIT 1";
		$query = $this->db->query($sql,array($username,$password));

		$msg['status'] = false;
		$msg['msg'] = "Invalid Login";
		
		if($query->num_rows > 0){

			$user = $query->row();

			if($user->status == 'INACTIVE')
			{
				$msg['msg'] = "This Account is Blocked!";
				return $msg;
			}

			$sql = "SELECT CONCAT('(',project,')',' - ',project_name) AS 'Project_F',project FROM setup_project WHERE project_id = '".$user->Proj_Code."'";
			$result = $this->db->query($sql);
			$row = $result->row_array();

			$title['profit_center'] = '';
			
			$title['profit_center'] = $row['Project_F'];
			$title['branch_type']   = $row['project'];

			$sql = "SELECT title_name FROM project_title  WHERE title_id = '".$user->Proj_Main."'";
			$result = $this->db->query($sql);
			$row = $result->row_array();

			$title['project'] = '';
			foreach($row as $data){
				$title['project'] = $data;
			}
						
			//$fullname = $user->firstname.", ".$user->lastname;
			$fullname = $user->UserFull_name;
			$this->session->set_userdata(
				array('user'=>$user->SysPK_User,
					  'username'=>$fullname,
					  'type_user'=>$user->Type_User,
					  'department'=>$user->Dept_Code,
					  'project_main'=>$user->Proj_Main,
					  'Proj_Main'=>$user->Proj_Main,
					  'Proj_Code'=>$user->Proj_Code,
					  'lblpersoncode'=>$user->Employee_id,
					  'title_profitcenter'=>$title['profit_center'],
					  'title_project'=>$title['project'],
					  'branch_type'=>$title['branch_type'],
					  'emp_id'=>$user->Employee_id,
					  'privileges'=>explode(',',$user->privileges),
					  ));

			if($remember){
					$expire = (60*60*24*365*2);
					set_cookie(array(
						'name'=>'identity',
						'value'=>$user->SysPK_User,
						'expire'=>$expire					
					));
			}
			$msg['status'] = true;
			return $msg;
						
		}else{
			$msg['status'] = false;
			return $msg;			
		}
	}

	function login_old($username,$password,$remember = false){
		$sql   = "SELECT * FROM web_users WHERE username = ? AND password = ? LIMIT 1";		
		$query = $this->db->query($sql,array($username,$password));

		$msg['status'] = false;
		$msg['msg'] = "Invalid Login";
		
		if($query->num_rows > 0){

			$user = $query->row();

			if($user->status == 'INACTIVE')
			{
				$msg['msg'] = "This Account is Blocked!";
				return $msg;
			}

			$fullname = $user->firstname.", ".$user->lastname;
			$this->session->set_userdata(array('user'=>$user->id,
				 'username'=>$fullname,
				 'type_user'=>$user->position,
				 'department'=>$user->department));


			


			if($remember){
					$expire = (60*60*24*365*2);
					set_cookie(array(
						'name'=>'identity',
						'value'=>$user->id,
						'expire'=>$expire					
					));
			}
			$msg['status'] = true;
			return $msg;
			
		}else{
			$msg['status'] = false;
			return $msg;			
		}
	}


	public function update_user(){

		/*
		$update = array(
			'username'=>$this->input->post('username'),
			'password'=>$this->input->post('password'),
			'firstname'=>$this->input->post('firstname'),
			'lastname'=>$this->input->post('lastname'),
			'lastname'=>$this->input->post('lastname'),
			);
		*/
		
		$fullname = $this->input->post('lastname').', '.$this->input->post('firstname').', '.$this->input->post('middlename');
		$update = array(
			'UserName_User'=>$this->input->post('username'),
			'Password_User'=>$this->input->post('password'),
			'UserFull_name'=>$fullname,
			);

		$this->db->where('SysPK_User',$this->session->userdata('user'));
		$this->db->update('users',$update);		
		$this->session->set_userdata('username', $fullname);

		return "Successfully Update";

	}

	
	
	public function DataObject($sql = ""){
		$result = $this->db->query($sql)->row_array();
		$this->db->close();
		$result = array_values($result);
		return $result[0];
	}
	
	
	public function user($id = false){

		$id || $id = $this->session->userdata('user');
		
		return $this->db->select('*')
			      ->from('users')
				  ->where('SysPK_User',$id)
				  ->get();
	
	}
	
	public function users(){
		return $this->db->select('*')
				->from('users')
				->get();
	}
	
		
}