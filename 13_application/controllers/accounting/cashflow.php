<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cashflow extends CI_Controller {


	public function __construct(){
		parent :: __construct();	
		$this->lib_auth->default = "default-accounting";		
		$this->load->model('accounting/md_cashflow');
		$this->load->model('md_project');

	}

	public function index(){

		$this->lib_auth->title = "Cashflow";		
		$this->lib_auth->build = "accounting/cashflow/index";
			
		$data['project'] = $this->md_project->get_profit_center();
		$this->lib_auth->render($data);

	}


	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);

		$data = array();
					
			$show = array(
						'CASHFLOW',
						'PREVIOUS',
						'CURRENT',
				 		);

			$this->table->add_row('OPERATING EXPENSES','','');
			
			$arg = $this->input->post();
			$data = $this->md_cashflow->get_cashflow($arg);		
			

				$total_debit = 0;
				$total_credit = 0;
				foreach($data as $key => $value){
					$row_content = array();

					if($value['account_id']==""){
						$row_content[] = array('data'=>$value['account_description'],'style'=>'padding-left:3em;');
						$row_content[] = array('data'=>$this->number_format($value['PREVIOUS']));
						$row_content[] = array('data'=>$this->number_format($value['CURRENT']));

						$this->table->add_row($row_content);

						$total_debit  = $total_debit + $value['PREVIOUS'];
						$total_credit = $total_credit + $value['CURRENT'];

					}else if($value['full_description']=="DEFERRED CREDITS" || $value['short_description'] == "EXPENSES" || $value['full_description'] == "CURRENT ASSETS" || $value['trans_type'] == "ENTER PAYMENT" || $value['trans_type'] == "PAY PAYABLE"){
						

						$row_content[] = array('data'=>$value['account_description'],'style'=>'padding-left:3em;');						
						$previous = ($value['PREVIOUS'] < 0 )? $value['PREVIOUS'] : - $value['PREVIOUS'];
						$current  = ($value['CURRENT'] < 0 )?  $value['CURRENT']  : - $value['CURRENT'];
						
						$row_content[] = array('data'=>$this->number_format($previous));
						$row_content[] = array('data'=>$this->number_format($current));
						
						$this->table->add_row($row_content);
						
						$total_debit  = $total_debit  + $previous;
						$total_credit = $total_credit + $current;

					}

				}

			$this->table->add_row(
				 array('data'=>'TOTAL OPERATING ACTIVITIES','style'=>'padding-left:1em;','class'=>'sub-total')
				,array('data'=>$this->number_format($total_debit),'class'=>'sub-total')
				,array('data'=>$this->number_format($total_credit),'class'=>'sub-total'));			
			
			$this->table->add_row('INVESTING ACTIVITIES','','');

			$total_debit = 0;
			$total_credit = 0;
			foreach($data as $key => $value){
				$row_content = array();

				if($value['full_description']=="NON-CURRENT ASSETS"){

					$row_content[] = array('data'=>$value['account_description'],'style'=>'padding-left:3em;');						
					$previous = ($value['PREVIOUS'] < 0 )? $value['PREVIOUS'] : - $value['PREVIOUS'];
					$current  = ($value['CURRENT'] < 0 )?  $value['CURRENT']  : - $value['CURRENT'];
					
					$row_content[] = array('data'=>$this->number_format($previous));
					$row_content[] = array('data'=>$this->number_format($current));
					
					$this->table->add_row($row_content);
					
					$total_debit  = $total_debit  + $previous;
					$total_credit = $total_credit + $current;

				}

			}

			$this->table->add_row(
				 array('data'=>'TOTAL INVESTING ACTIVITIES','style'=>'padding-left:1em;','class'=>'sub-total')
				,array('data'=>$this->number_format($total_debit),'class'=>'sub-total')
				,array('data'=>$this->number_format($total_credit),'class'=>'sub-total'));
			
			$this->table->add_row('FINANCING ACTIVITIES','','');


			$total_debit = 0;
			$total_credit = 0;

			foreach($data as $key => $value){
					$row_content = array();

					if($value['full_description']=="CURRENT LIABILITIES" && $value['trans_type'] == "ENTER PAYABLE" || $value['trans_type'] == "PAY PAYABLE" || $value['full_description'] == "EQUITY"){
						$row_content[] = array('data'=>$value['account_description'],'style'=>'padding-left:3em;');
						$row_content[] = array('data'=>$this->number_format($value['PREVIOUS']));
						$row_content[] = array('data'=>$this->number_format($value['CURRENT']));

						$this->table->add_row($row_content);

						$total_debit  = $total_debit + $value['PREVIOUS'];
						$total_credit = $total_credit + $value['CURRENT'];


					}else if($value['full_description']=="CURRENT ASSETS"){

						$row_content[] = array('data'=>$value['account_description'],'style'=>'padding-left:3em;');
						$row_content[] = array('data'=>$this->number_format($value['PREVIOUS']));
						$row_content[] = array('data'=>$this->number_format($value['CURRENT']));

						$this->table->add_row($row_content);

						$total_debit  = $total_debit + $value['PREVIOUS'];
						$total_credit = $total_credit + $value['CURRENT'];

					}

			}

			$this->table->add_row(
				 array('data'=>'TOTAL FINANCING ACTIVITIES','style'=>'padding-left:1em;','class'=>'sub-total')
				,array('data'=>$this->number_format($total_debit),'class'=>'sub-total')
				,array('data'=>$this->number_format($total_credit),'class'=>'sub-total'));			

			$this->table->add_row('NET INCREASE(DECREASE) IN CASH','','');
			
						
		$this->table->set_heading($show);		
		echo $this->table->generate();

	}

	private function number_format($value){
		if(is_numeric($value)){
			return number_format($value,2,'.',',');
		}else{
			return '';
		}
	}




}