<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Received_purchase extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('procurement/md_received_purchase');

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);
	}

	public function index(){

		$this->lib_auth->title = "Received Purchase";		
		$this->lib_auth->build = "procurement/received_purchase/index";
		$this->lib_auth->render();

	}

	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$result = $this->md_received_purchase->cumulative_main($this->input->post('location'));
		$show = array(
					'RR No.',
					'RR Date',
					'Po No.',
					'Po Date',
					'Supplier Name',
					'Supplier Invoice',
					'Received By',
			 		);
		$header = $show;		
		foreach($result->result_array() as $key => $value){
			$row_content = array();
			$header = array();
			foreach($value as $key1 => $value1){
					$value1 = (isset($value1))? $value1 : '';
					if(in_array($key1,$show)){
						switch($key1){
							case "APPROVED":
							case "Status":
								$row_content[] = array('data'=>$this->extra->label($value1));
							break;
							default:
								$row_content[] = array('data'=>$value1,'class'=>$key1);
							break;
						}						
						$header[] = array('data'=>$key1);
					}else{
						$row_content[] = array('data'=>$value1,'style'=>'display:none','class'=>$key1);
						$header[] = array('data'=>$key1,'style'=>'display:none');
					}
			}
			$this->table->add_row($row_content);			
		}

		$this->table->set_heading($header);
		echo $this->table->generate();

	}

	


	public function get_po(){

		$tmpl = array ( 'table_open'  => '<table class="table po_table table-striped">' );
		$this->table->set_template($tmpl);

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
				
		$result = $this->md_received_purchase->get_po($this->input->post('location'));

		$show = array(
					'po_number',
					'po_date',
					'status',
					'Supplier',
			 		);
		$header = $show;

		foreach($result->result_array() as $key => $value){
			$row_content = array();
			$header = array();
			foreach($value as $key1 => $value1){
					$value1 = (isset($value1))? $value1 : '';
					if(in_array($key1,$show)){
						switch($key1){
							case "APPROVED":
							case "Status":
								$row_content[] = array('data'=>$this->extra->label($value1));
							break;
							default:
								$row_content[] = array('data'=>$value1,'class'=>$key1);
							break;
						}						
						$header[] = array('data'=>$key1);
					}else{
						$row_content[] = array('data'=>$value1,'style'=>'display:none','class'=>$key1);
						$header[] = array('data'=>$key1,'style'=>'display:none');
					}
			}
			$this->table->add_row($row_content);			
		}

		$this->table->set_heading($header);
		echo $this->table->generate();

	}


	public function get_po_details(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['item'] = $this->md_received_purchase->get_po_details($this->input->post('po_id'));
		$data['location'] = $this->input->post('location');
		$data['project'] = $this->input->post('project');		

		$this->load->view('procurement/received_purchase/received',$data);

	}

	public function get_cumulative_details(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_received_purchase->cumulative_details($this->input->post('rr_id'));

		// $main = $this->md_purchase_order->get_main($this->input->post('po_id'));
		// $result = $this->md_purchase_order->cumulative_details($this->input->post('po_id'));

		$options = array(
			'result'     =>$result,
			'hide'       =>array(
						'item_quantity_ordered',
						'item_quantity_actual',
						'item_name_ordered',
						'discrepancy',
						'discrepancy_remarks'),
			'bool'       =>true,
			'table_class'=>array('table','table-striped'),
		);

		$data['type']     = 'RR';
		$data['id']       = $this->input->post('rr_id');		
		$data['table']    = $this->extra->generate_table($options);
		$data['edit_url'] = 'procurement/received_purchase/edit_form';
		$this->load->view('template/cumulative_4',$data);

	}


	public function edit_form(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_received_purchase->cumulative_details($this->input->post('rr_id'));
		$result = $result->row_array();


		$data['content'] = $this->md_received_purchase->get_rr_main($this->input->post('rr_id'));
		$data['item'] = $this->md_received_purchase->get_po_details_edit($result['po_id']);		
		$data['location'] = $this->input->post('location');
		$data['project'] = $this->input->post('project');
		$data['id'] = $this->input->post('rr_id');
		
		$this->load->view('procurement/received_purchase/edit_form',$data);		
	}


	public function save_receiving(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_received_purchase->save_receiving();

	}


	public function save_receiving_2(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_received_purchase->save_receiving_2();
	}



	public function update_receiving(){		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo $this->md_received_purchase->update_receiving();
	}


	public function rr_info(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();		
		$data['main_data']    = $this->md_purchase_order->get_po_main($arg['po']);

		$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
		$data['details_data'] = $this->md_purchase_order->get_po_details($data['main_data']['po_id']);
		$data['rr_main']    = $this->md_received_purchase->get_po_received_rr_id($arg['id']);
		$data['rr_details'] = $this->md_received_purchase->get_rr_details($arg['id']);
		$this->load->view('procurement/transaction_list/rr_info_view',$data);

		
		
		
	}






}