<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lib_sidebar{

	function __construct(){		
		$this->load->model('procurement/md_transaction_history');				
	}
	
	public function __get($var)
	{
		return get_instance()->$var;
	}

	public function sidebar(){

		$data['result'] = $this->md_transaction_history->get_approved_pr();
		$this->load->view('procurement/sidebar/list-approved-pr',$data);
	}


	public function po_sidebar(){

		$data['result'] = $this->md_transaction_history->get_approved_po();
		$this->load->view('procurement/sidebar/list-approved-po',$data);

	}

}