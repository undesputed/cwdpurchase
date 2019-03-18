<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cash_voucher extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_journalEntry');			
	}


	public function index(){

		$data['view'] = $this->load->view("accounting/cash_voucher/index","",true);
		$this->lib_auth->title = "Cash Voucher";
		$this->lib_auth->build = "procurement/transaction_list/sidebar";
		$this->lib_auth->render($data);

	}



}