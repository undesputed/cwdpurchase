<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_order extends CI_Controller {

	public function __construct(){
		parent :: __construct();	
		$this->load->model('procurement/md_purchase_order');	
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);
	}

	public function index(){		
		redirect('procurement/purchase_order/cumulative','refresh');
	}

	public function cumulative(){
		$this->lib_auth->title = "Purchase Order";		
		$this->lib_auth->build = "procurement/purchase_order/cumulative";
		
		$this->lib_auth->render();		
	}


	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_purchase_order->get_cumulative();

		$show = array(
					'PR Number',
					'PO Number',
					'PO Date',					
					'Supplier',
					'Type',
					'Payment Term',
					'Del. Terms',					
					'Del. DATE',
					'ServeStatus',
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
								$row_content[] = array('data'=>$this->extra->label($value1));
							break;
							case "ServeStatus":
								$row_content[] = array('data'=>$this->extra->label($value1));
							break;
							case "PO Date":
								$row_content[] = array('data'=>date('F d,Y',strtotime($value1)));
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

	public function create_form(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		//$data['approved_pr'] =  $this->md_purchase_order->get_approved_pr();		
		$data['location'] = $this->input->post('location');
		$this->load->view('procurement/purchase_order/selection',$data);
		
	}

	public function proceed(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$details = $this->input->post('details');
		$data['main'] = $this->input->post('main');
		$result = $this->md_purchase_order->get_supplier($details[0]);	
		
		$data['supplier'] = $result;
		$data['item'] = $this->md_purchase_order->get_items($data['main'][0],$result['business_number']);
			
		$this->load->view('procurement/purchase_order/create',$data);

	}

	public function canvass_selection(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		if(isset($_POST)){
			switch($_POST['type']){
				case"month":
					$result = $this->md_purchase_order->canvass_display_canvass_prz_supplier_month($_POST['title_id'],$this->input->post('date'));	
					$options = array(
						'result'=>$result,
						'hide'=>array(
								'CANVASS #',
								'CANVASS DATE',
								'PR #',
								'PR DATE',
								'PROJECT',
							),
						'bool'=>true,
						'table_class'=>array('table','selection-table'),
						);
					echo $this->extra->generate_table($options);

				break;

				case"all":
					$result = $this->md_purchase_order->canvass_display_canvass_prz_supplier($_POST['title_id']);
					$options = array(
						'result'=>$result,
						'hide'=>array(
								'CANVASS #',
								'CANVASS DATE',
								'PR #',
								'PR DATE',
								'PROJECT',
							),
						'bool'=>true,
						'table_class'=>array('table','selection-table'),
						);
					echo $this->extra->generate_table($options);

				break;
			}			 
		}

	}


	public function canvass_selection_details(){
		if(!$this->input->is_ajax_request()){
				exit(0);
		}	

		$result = $this->md_purchase_order->canvass_display_canvass_prz_dtls($this->input->post('id'));
		
		$options = array(
						'result'=>$result,
						'hide'=>array(
								'SUPPLIER',
								'ITEM DESCRIPTION',
								'UNIT',
								'UNIT PRICE',
								'QTY',
								'TOTAL',
								'REMARKS',
							),
						'bool'=>true,
						'table_class'=>array('table','selection-details-table'),
						);

		echo $this->extra->generate_table($options);

	}


	public function save_purchaseOrder(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo $this->md_purchase_order->save_purchaseOrder();		
	}


	public function save_purchaseOrder2(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo $this->md_purchase_order->save_purchaseOrder2();
		
	}



	public function save_purchaseOrder3(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo $this->md_purchase_order->save_purchaseOrder3();
		
	}

	

	public function get_cumulative_detail(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$main = $this->md_purchase_order->get_main($this->input->post('po_id'));
		$result = $this->md_purchase_order->cumulative_details($this->input->post('po_id'));
		$options = array(
			'result'=>$result,
			'hide'  =>array('po_id'),
			'table_class'=>array('table','table-striped'),
		);		
		$data['type']     = 'PO';
		$data['id']       = $this->input->post('po_id');
		$data['status']   = $main['status'];
		$data['table']    = $this->extra->generate_table($options);
		$data['edit_url'] = 'procurement/purchase_order/edit_form';
		
		$this->load->view('template/cumulative_3',$data);
		
	}

	public function edit_form(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['main'] = $this->md_purchase_order->get_main($this->input->post('po_id'));
		
		// $details = $this->input->post('details');
		// $data['main']  = $this->input->post('main');
		$data['supplier'] = $this->md_purchase_order->get_supplier($data['main']['supplierID']);	
		$data['canvass']  = $this->md_purchase_order->get_canvassByPo($this->input->post('po_id'));
		
		$data['item'] = $this->md_purchase_order->get_items($data['canvass']['can_id'],$data['supplier']['business_number']);
		
		$this->load->view('procurement/purchase_order/edit',$data);

	}


	public function update_purchaseOrder(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_purchase_order->update_purchaseOrder();

	}


	public function edit_po(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$arg = $this->input->post();
		$this->md_purchase_order->edit_po($arg);


		$event['type']    = 'Purchase Order';
		$event['transaction_no'] = $arg['transaction_no'];
		$event['transaction_id'] = $arg['po_id'];
		$event['remarks'] = '';
		$event['action']  = 'EDIT';

		echo $this->md_event_logs->create($event);

	}

}