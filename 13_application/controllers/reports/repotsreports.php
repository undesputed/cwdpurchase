<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
	}


	public function mine_operation(){

		$this->lib_auth->title = "Mine Operation";		
		$this->lib_auth->build = "reports/operation/mine_operation";

		$this->lib_auth->render();		
	}

	public function shipment_operation(){

		$this->lib_auth->title = "Mine Operation";		
		$this->lib_auth->build = "reports/operation/shipment_operation";

		$this->lib_auth->render();

	}



}