<?php defined('BASEPATH') OR exit('No direct script access allowed');

class mine_operation extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model(array('reports/md_mine_operation'));	
	}

	public function index(){

		$this->lib_auth->title = "Production Report";		
		$this->lib_auth->build = "reports/operation/mine_operation";
		$this->lib_auth->render();
				
	}


	public function get_operation(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->get();





		switch($arg['type'])
		{
			case"mine":		

				switch($arg['owner'])
					{	
						case"%":
							$data['title'] = "Mine Production Report";
						break;
						case"INHOUSE":
							$data['title'] = "Mine Production Report <small>Inhouse</small>";
						break;
						case"SUBCON":
							$data['title'] = "Mine Production Report <small>Subcon</small>";
						break;
					}


				$data['result'] = $this->md_mine_operation->get_operation($arg);
			break;

			case "barging":

				switch($arg['owner'])
					{	
						case"%":
							$data['title'] = "Shipment Operation Report";
						break;
						case"INHOUSE":
							$data['title'] = "Shipment Operation Report <small>Inhouse</small>";
						break;
						case"SUBCON":
							$data['title'] = "Shipment Operation Report <small>Subcon</small>";
						break;
					}


					$data['result'] = $this->md_mine_operation->get_barging($arg);

			break;

		}

		
		$this->load->view('reports/mine_operation/tbl_mine_operation',$data);


	}



}