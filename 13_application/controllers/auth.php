<?php defined('BASEPATH') OR exit('No direct script access allowed');
session_start();
class Auth extends CI_Controller {

	

	public function __construct(){
		parent ::__construct();
		$this->load->model(array('md_auth'));
		$this->lib_auth->default = "login";
		$this->lib_auth->redirect = false;
		
	}

	function login(){		
		$this->validation();
		
		$this->lib_auth->title = "Log In";
		$this->lib_auth->build = 'auth/login';	
		
		$this->lib_auth->render();		
	}

	function validation(){
		
		$this->form_validation->set_rules('username','Username','required|xss_clean');
		$this->form_validation->set_rules('password','Password','required|xss_clean');
		
		if($this->form_validation->run() == true){
						
			$remember = (bool) $this->input->post('remember');
			$msg =  $this->md_auth->login($this->input->post('username'),$this->input->post('password'),$remember);
			if($msg['status']){
				$this->lib_auth->themeplate();

				
				redirect(base_url(), 'refresh');
			}else{
				
				$this->session->set_flashdata(array('message'=>'<div class="alert alert-danger">'.$msg['msg'].'</div>','type'=>'alert-danger'));
				redirect('auth/login', 'refresh');
			}
			/*	
			if($this->ion_auth->login($this->input->post('username'),$this->input->post('password'),$remember)){
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('/', 'refresh');
				
			}else{
				$this->session->set_flashdata('message', $this->ion_auth->errors());
				redirect('auth/login', 'refresh');
			}
			*/
			
		}else{
				$this->template->set('message',(validation_errors())? validation_errors() : $this->session->flashdata('message'));				
			}
	}


	public function settings(){
		$data['message'] = "";

		$this->lib_auth->default = 'default-2';

		$this->lib_auth->title = "Account Settings";
		$this->lib_auth->build = 'auth/settings';
		
		$result = $this->md_auth->user();		
		$data['user'] = $result->row_array();
		
		$this->form_validation->set_rules('firstname','First Name','required|xss_clean');
		$this->form_validation->set_rules('middlename','middlename','xss_clean');
		$this->form_validation->set_rules('lastname','Last Name','required|xss_clean');

		$this->form_validation->set_rules('username','Username','required|xss_clean');
		$this->form_validation->set_rules('password','Password','required|xss_clean|matches[conf_password]');
		$this->form_validation->set_rules('conf_password','Confirm Password','required|xss_clean');
		
		if($this->form_validation->run() == true){
			 $this->md_auth->update_user();
			 $this->session->set_flashdata(array('message'=>'<div class="alert alert-success">Successfully Save</div>','type'=>'alert-danger'));
			 redirect('auth/settings', 'refresh');
		}else{
			$data['message'] = validation_errors();			
		}

		$data['message'] = (validation_errors())? validation_errors() : $this->session->flashdata('message');

		$this->lib_auth->render($data);

	}


	function _settings(){
		if(!$this->my_auth->logged_in()){
			redirect(base_url(),'refresh');
		}
			

		$user_id = $this->session->userdata('user');
		$result = $this->md_auth->user($user_id);
		$data = $result->result_array();
		$name = $data[0]['UserFull_name'];
		$this->template->set('name',$name);

		$this->form_validation->set_rules('name','Name','required|xss_clean');
		$this->form_validation->set_rules('username','Username','required|xss_clean');
		$this->form_validation->set_rules('new_password','Password','required|matches[conf_password]|xss_clean');
		$this->form_validation->set_rules('conf_password','Password Confirmation','required');

		if($this->form_validation->run() == true){
			$data = array(
					'UserName_User'=>$this->input->post('username'),
					'Password_User'=>$this->input->post('new_password'),
					'UserFull_name'=>$this->input->post('name'),
				);
			
			if($this->md_auth->update_user($user_id,$data)){
				$this->session->set_flashdata('message','<div class="alert alert-success"> <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a> Successfully Change </div>');
				
			}else{
				$this->session->set_flashdata('message','<div class="alert alert-danger"> <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a> Saving Failed... </div>');
			}

				redirect('auth/settings', 'refresh');


		}else{

			$this->template->set('message',(validation_errors())? validation_errors('<div class="alert alert-danger">  <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
', '</div>') : $this->session->flashdata('message'));

		}


		$this->template->title('Settings','Financial Report')
		->set_layout('default')
		->build('settings');

	}





	function signup(){
		$this->title = "Create Account";
		$this->build = 'auth/signup';
		$this->layout = "login";
					

			$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'required|matches[conf_password]|xss_clean');
			$this->form_validation->set_rules('conf_password', 'Password Confirmation', 'required|xss_clean');
			$this->form_validation->set_rules('username', 'Username', 'required|xss_clean|is_unique[tbl_users.username]');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean|is_unique[tbl_users.email]');

			$this->form_validation->set_message('is_unique','Already Taken');

			if ($this->form_validation->run() == TRUE){
					$this->md_auth->create_user();
					$this->session->set_flashdata(array('message'=>'<strong>Your Account is being Verified.</strong> Please Wait for a moment','type'=>'alert-info'));	
					redirect('auth/login','refresh');
			}
		
			$this->lib_auth->render();

	}

	
	function logout(){
		
		$this->lib_auth->logout();
		$this->session->set_flashdata(array('message'=>'<div class="alert alert-success">Successfully Logout</div>','type'=>'alert-success'));
		redirect('auth/login', 'refresh');		

	}
			
}