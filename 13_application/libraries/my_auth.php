<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Auth{

	function __construct(){			
		$this->load->model('md_auth');		
		$this->load->helper('cookie');	
	}
	
	public function __get($var)
	{
		return get_instance()->$var;
	}
	
	public function logged_in(){
		return (bool) $this->session->userdata('user_id');
	}
	
	public function is_admin(){
		$user  = $this->md_auth->user()->row();
		if($user->position=='1'){
			return true;
		}
			return false;
	}
	
	public function logout(){
		$this->session->unset_userdata('user_id');		
		$this->session->sess_destroy();
		if (get_cookie('identity'))
		{
			delete_cookie('identity');
		}
		return true;
	}
	
	public function get_user_id(){
		$user_id = $this->session->userdata('user');	
		if (!empty($user_id))
		{
			return $user_id;
		}
		return null;
	}

	

}