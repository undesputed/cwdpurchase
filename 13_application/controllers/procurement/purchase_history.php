<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_history extends CI_Controller {

	public function __construct(){
		parent :: __construct();	
		$this->load->model('procurement/md_purchase_history');	
	}

	public function index(){

		$this->lib_auth->title = "Purchase History";		
		$this->lib_auth->build = "procurement/purchase_history/index";
		

		$this->lib_auth->render();
		
	}

	public function get_data(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		if($this->input->post('type')=='item'){
			$result = $this->md_purchase_history->item();
			$options = array(
				'result'=>$result,
				'hide'=>array(
					'ITEM DESCRIPTION',
					'ITEM NO',
					),
				'bool'=>true
			);

		}else{
			$result = $this->md_purchase_history->supplier();
			$options = array(
				'result'=>$result,
				'hide'=>array(
					'SUPPLIER',
					'TYPE',
					),
				'bool'=>true
			);	
		}		
		echo $this->extra->generate_table($options);

	}


	public function get_details(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		if($this->input->post('type')=='item'){
			$result = $this->md_purchase_history->get_itemDetails($this->input->post('item_no'));
			$options = array(
			'result'=>$result,
			'hide'=>array('po_id'),
			'table_class'=>array('table','table-striped'),
			);
		}else{
			$result = $this->md_purchase_history->get_supplierDetails($this->input->post('supplier_id'),$this->input->post('b_type'));
			$options = array(
			'result'=>$result,
			'hide'=>array('po_id'),
			'table_class'=>array('table','table-striped'),
			);
		}		
				
		echo $this->extra->generate_table($options);		
	}

		



}