<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payable_list extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_payable_list');
		$this->load->model('md_project');
		
	}

	public function index(){

		$this->lib_auth->title = "Payable List";
		$this->lib_auth->build = "accounting/payable_list/payable_report";
	
		$data['project']       = $this->md_project->get_profit_center();
		$data['supplier']      = $this->md_payable_list->get_supplier();		
		$data['bank_setup']    = $this->md_payable_list->get_bank_setup();
		$data['get_affiliate'] = $this->md_payable_list->get_affiliate();

		$this->lib_auth->render($data);		
		
	}
		
	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$arg = $this->input->post();		
		$result = $this->md_payable_list->get_payable_list($arg);
		
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-condensed">');
		$this->table->set_template($tmpl);
			
			$show = array(
					'PO #',
					'Date',
					'Amount',
					'Project',
					'Supplier',
					array('data'=>'SI #','style'=>'width:30px'),
					'SI Date',
					'Check#',
					'SI Amount',
					'Due Date',
					'Status',
			 		);

				$total_debit  = 0;
				$total_credit = 0;

				$po_list = array();
				$total_cost = 0;
				$total_balance = 0;
				foreach($result as $key => $value){			

					$row_content = array();
					$project_requestor = isset($value['from_name'])? $value['from_name'] : '' ;

					if(!isset($po_list[$value['reference_no']])){
							$po_list[$value['reference_no']] = '';
							$row_content[] = array('data'=>$value['reference_no'],'class'=>'po_no');
							$row_content[] = array('data'=>$value['po_date'],'class'=>'po_date');
							$row_content[] = array('data'=>$this->extra->number_format($value['total_cost']),'class'=>'total_amount');
							$row_content[] = array('data'=>$project_requestor);
							$row_content[] = array('data'=>$value['Supplier']);
							$balance = $value['total_cost'];
							$total_cost = $total_cost + $value['total_cost'];
					}else{
							$row_content[] = array('data'=>'<span style="display:none">'.$value['reference_no'].'</span>','class'=>'po_no');
							$row_content[] = array('data'=>'<span style="display:none">'.$value['po_date'].'</span>','class'=>'po_date');
							$row_content[] = array('data'=>'<span style="display:none">'.$this->extra->number_format($value['total_cost']).'</span>','class'=>'total_amount');
							$row_content[] = array('data'=>'');
							$row_content[] = array('data'=>'');
					}
					
					$balance = $balance - $value['si_amount'];
					
					$total_balance = $total_balance + $value['paid_amount'];
					
					$value['supplier_invoice'] = str_replace(',',', ', $value['supplier_invoice']);
					$value['supplier_invoice'] = wordwrap($value['supplier_invoice'], 20, "<br />\n");
					$row_content[] = array('data'=>(isset($value['supplier_invoice'])? $value['supplier_invoice'] : '' ),'class'=>'word-break');

					$row_content[] = array('data'=>(isset($value['invoice_date'])? $value['invoice_date'] : ''));
					$row_content[] = array('data'=>(isset($value['bank'])? $value['bank']." ".$value['_check_no'] : ''));
					$row_content[] = array('data'=>$this->extra->number_format($value['si_amount']));
					$row_content[] = array('data'=>(isset($value['check_date'])? $value['check_date'] : ''));
					/*$row_content[] = array('data'=>(isset($value['rr_date'])? $value['rr_date'] : ''));*/
					/*$row_content[] = array('data'=>$this->extra->number_format($balance));*/	
					if($value['paid_status'] == 'paid')
					{

						$row_content[] = array('data'=>'<label class="label label-success" data-value="paid" data-id="'.$value['po_id'].'">paid</label>');
						/*$row_content[] = array('data'=>'<label class="label label-success update" data-value="paid" data-id="'.$value['po_id'].'">paid</label>');*/
						/*
						$row_content[] = array('data'=>'
								<div class="btn-group font-10">
								  <button class="btn btn-default btn-xs btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    Paid <span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu pull-right">
								  	<li><a href="javascript:void(0)" class="update" data-id="'.$value['po_id'].'">Add Invoice</a></li>
								  	<li class="divider"></li>
								    <li><a href="javascript:void(0)" class="edit" data-id="'.$value['po_id'].'" data-receipt_id="'.$value['receipt_id'].'" data-journal_id="'.$value['journal_id'].'">Edit</a></li>
								    <li><a href="javascript:void(0)" class="delete" data-id="'.$value['po_id'].'" data-receipt_id="'.$value['receipt_id'].'" data-journal_id="'.$value['journal_id'].'">Delete</a></li>
								  </ul>
								</div>
							','style'=>'width:90px');					

							*/
					}else{
						$row_content[] = array('data'=>'<label class="label label-danger" data-value="paid" data-id="'.$value['po_id'].'">Unpaid</label>');
						/*
						$row_content[] = array('data'=>'
									<div class="btn-group font-10">																		
									  <button class="btn btn-default btn-xs btn-warning dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									    Unpaid <span class="caret"></span>
									  </button>									
									  <ul class="dropdown-menu pull-right">									  										   
										<li><a href="javascript:void(0)" class="update" data-id="'.$value['po_id'].'" data-value="unpaid" data-receipt_id="'.$value['receipt_id'].'" data-journal_id="'.$value['journal_id'].'">Pay Payable</a></li>
										<li class="divider"></li>
									    <li><a href="javascript:void(0)" class="delete" data-id="'.$value['po_id'].'" data-receipt_id="'.$value['receipt_id'].'" data-journal_id="'.$value['journal_id'].'">Delete</a></li>									    
									  </ul>
									</div>
							');

							*/
					}
					$this->table->add_row($row_content);
				}

				$row_content = array();
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'Total','style'=>'font-weight:bold');
				$row_content[] = array('data'=>$this->extra->number_format($total_cost),'style'=>'font-weight:bold;');
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'');		
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'');

				$this->table->add_row($row_content);		

		$this->table->set_heading($show);
		echo $this->table->generate();
		/*$this->load->view('accounting/payable_list/cumulative',);*/
		
	}

	public function update_status(){
		if(!$this->input->is_ajax_request()){
					exit(0);
		}
		
		$arg = $this->input->post();




		if($this->md_payable_list->check_multiple_rr($arg) || $arg['proceed'] =='true')
		{
			echo $this->md_payable_list->update_status($arg);
		}else
		{
			if($this->md_payable_list->check_multiple_journal($arg))
			{
				echo $this->md_payable_list->update_status($arg);				
			}else{
				echo "exist";
			}
			
		}
		
		
	}

	public function edit(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo json_encode($this->md_payable_list->edit($arg));

	}

	public function do_edit(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();		
		$this->md_payable_list->do_edit($arg);
		
	}

	public function delete(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$this->md_payable_list->delete($arg);
		
	}



}
