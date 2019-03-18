<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Item_issuance extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('procurement/md_item_issuance');		
		
	}

	public function index(){

		redirect(base_url(),'refresh');
	}

	public function save_issuance(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo  $this->md_item_issuance->save_issuance();

	}




}
	