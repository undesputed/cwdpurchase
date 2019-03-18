<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_delivery extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('procurement/md_purchase_delivery');

	}

	public function index(){

		$this->lib_auth->title = "Purchase Delivery";		
		$this->lib_auth->build = "procurement/purchase_delivery/index";
		
		$this->lib_auth->render();
		
	}

	public function get_data(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$tmpl = array ( 'table_open'  => '<table class="table table-main table-striped">' );
		$this->table->set_template($tmpl);

		$result = $this->md_purchase_delivery->get_data($this->input->post('location'));

			$header = array(
					array('data'=>'pr_id','style'=>'display:none'),
					array('data'=>'po_id','style'=>'display:none'),
					'PR Number',
					'PO Number',
					'PO Date',
					'Supplier',
					'Payment Terms',
					'Delivery Date',
					'Delivery Status',
					'PO Status',
				);
			foreach($result->result_array() as $key => $value){
				$row_content = array();

				$row_content[] = array('data'=>$value['pr_id'],'style'=>'display:none','class'=>'pr_id');
				$row_content[] = array('data'=>$value['po_id'],'style'=>'display:none','class'=>'po_id');
				$row_content[] = $value['PR Number'];
				$row_content[] = $value['PO Number'];
				$row_content[] = $value['PO Date'];
				$row_content[] = $value['Supplier'];
				$row_content[] = $value['Payment Terms'];
				$row_content[] = $value['Delivery Date'];
				$row_content[] = $value['Delivery Status'];
				$row_content[] = $value['PO Status'];				

				$this->table->add_row($row_content);			
			}
	
			$this->table->set_heading($header);
			echo $this->table->generate();

	}


	public function get_data_details(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		

		$result = $this->md_purchase_delivery->rr_details($this->input->post('po_id'));
		$result1 = $this->md_purchase_delivery->po_details($this->input->post('po_id'));

		$option = array(
			'result'=>$result,
			'hide'=>array('po_id','receipt_id'),
		);
		$data['rr'] = $this->extra->generate_table($option);

		$option2 = array(
			'result'=>$result1,
		);
		$data['po'] = $this->extra->generate_table($option2);
		
		echo json_encode($data);

	}

}