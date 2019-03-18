<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Journal_view extends CI_Controller {

	public function __construct(){
		parent :: __construct();	
		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_journal_view');	
		$this->load->model('md_project');
	}

	public function index(){

		$this->lib_auth->title = "Journal View";
		$this->lib_auth->build = "accounting/journal_view/index";
		$data['project'] = $this->md_project->get_profit_center();
		$this->lib_auth->render($data);
		
	}


	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

	

		$data = array();

		$arg = $this->input->post();		
		$result = $this->md_journal_view->get_cumulative($arg);		



		$general  = array();
		$purchase = array();
		$payments = array();
		$sales    = array();
		$receipt  = array();

		foreach($result as $row){

			switch(strtoupper($row['Trans Type'])){
				case "ENTER PAYMENT":
				case "ENTER PAYABLE":
					$purchase[] = $row;
				break;

				case "BEGINNING":
				case "JOURNAL":
				case "ADJUSTMENT":
				case "JOURNAL ENTRY":
				case "ADJUSTMENT ENTRY":				
					$general[] = $row;
				break;

				case "PAY PAYABLE":				
					$payments[] = $row;
				break;

				case "RECEIVED PAYMENT":
				case "ENTER INVOICE":
					$sales[] = $row;
				break;

				case "ENTER RECEIPT":
					$receipt[] = $row;
				break;

			}

		}

		$output = array(
			'all'=>$this->_loop($result),
			'purchases'=>$this->_loop($purchase),
			'general'=>$this->_loop($general),
			'payments'=>$this->_loop($payments),
			'sales'=>$this->_loop($sales),
			'receipt'=>$this->_loop($receipt),			
			);

		echo json_encode($output);
		
	}


	public function _loop($result){
		$tmpl = array ( 'table_open'  => '<table class="table myTable no-border-table table-striped table-condensed">' );
		$this->table->set_template($tmpl);
		$show = array(
					'Date',
					'Account Title and Explanation',
					'Debit',
					'Credit',					
			 		);

				$total_debit  = 0;
				$total_credit = 0;

				foreach($result as $key => $value){

					$row_content = array();

					$row_content[] = array('data'=>$value['Date']);
					$row_content[] = array('data'=>$value['Trans Type']);
					$row_content[] = array('data'=>'');
					$row_content[] = array('data'=>'');

					/*$row_content[] = array('data'=>$this->extra->label($value['Status']));*/

					$this->table->add_row($row_content);					
					foreach($value['data'] as $row){
						foreach($row as $row1){
								$row_content   = array();

								if($row1['Debit'] != 0){
									$padding = "padding-left:1em;";
								}else{
									$padding = "padding-left:2em;";
								}
							
								$row_content[] = array('data'=>'');
								$row_content[] = array('data'=>'<small>'.$row1['Account Titles and Explanation'].'</small>','style'=>$padding);	
								if($row1['Debit']==0)
								{
									$row_content[] = array('data'=>'-');
								}else{
									$row_content[] = array('data'=>$this->extra->number_format($row1['Debit']));
								}

								if($row1['Credit']==0){
									$row_content[] = array('data'=>'-');
								}else{
									$row_content[] = array('data'=>$this->extra->number_format($row1['Credit']));
								}
																
								$this->table->add_row($row_content);

								$total_debit  = $total_debit  + $row1['Debit'];
								$total_credit = $total_credit + $row1['Credit'];

						}					
					}
				
				}

				$this->table->add_row('',array('data'=>'<strong>TOTAL</strong>'),"<strong>".$this->extra->number_format($total_debit)."</strong>","<strong>".$this->extra->number_format($total_credit)."</strong>");

			/*
			$this->table->add_row(array('data'=>'Total Income','style'=>'padding-left:3em;'),'0','0');
			$this->table->add_row('','','');

			$this->table->add_row('COST OF REVENUES','','');
			
			$this->table->add_row(array('data'=>'Total Cost of Revenues','style'=>'padding-left:3em;'),'0','0');
			$this->table->add_row('','','');

			$this->table->add_row('Gross Profit','','');

			$this->table->add_row('EXPENSES','','');		
			$this->table->add_row(array('data'=>'Total Expense','style'=>'padding-left:3em;'),'0','0');
			$this->table->add_row('NET PROFIT','0','0');
			*/
		
		$this->table->set_heading($show);		
		return  $this->table->generate();		
	}




}