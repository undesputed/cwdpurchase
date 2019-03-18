<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_availability extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('procurement/md_stock_availability');
	}

	public function index(){

		$this->lib_auth->title = "Stock Availability";		
		$this->lib_auth->build = "procurement/Stock_availability/index";
		

		$this->lib_auth->render();
		
	}


	public function get_availability(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		$result = $this->md_stock_availability->get_availability('','stock');

		$option = array(
			'result'=>$result,
			'hide'=>array(
				'ITEM NO',
				'ITEM DESCRIPTION',
				),
			'bool'=>true,
			);

		echo $this->extra->generate_table($option);
	}


	public function get_details(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_stock_availability->get_details($this->input->post('item_no'));

		$option = array(
			'result'=>$result,
			'hide'=>array(
				'PROJECT',
				'PROJECT NAME',
				'QTY ON HAND',
				),
			'bool'=>true,
			'table_class'=>array('table','table-striped','get_details_tbl')
			);

		echo $this->extra->generate_table($option);

	}



}