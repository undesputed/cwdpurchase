<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Work_sheet extends CI_Controller {

	public function __construct(){
		parent :: __construct();	
		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_worksheet');	
	}

	public function index(){

		$this->lib_auth->title = "Work Sheet";		
		$this->lib_auth->build = "accounting/work_sheet/index";
			
		$this->lib_auth->render();

	}


	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-hover">' );
		$this->table->set_template($tmpl);

		$heading = array(
			array('data'=>'No','rowspan'=>'2','style'=>'vertical-align:middle;text-align:left','class'=>'sub-total'),
			array('data'=>'Account Title','rowspan'=>'2','style'=>'vertical-align:middle;text-align:left','class'=>'sub-total'),
			array('data'=>'Trial Balance','colspan'=>'2','class'=>'sub-total'),
			array('data'=>'Adjustments','colspan'=>'2','class'=>'sub-total'),
			array('data'=>'Adjusted Trial Balance','colspan'=>'2','class'=>'sub-total'),
			array('data'=>'Income Statement','colspan'=>'2','class'=>'sub-total'),
			array('data'=>'Balance Sheet','colspan'=>'2','class'=>'sub-total'),
			);
		$this->table->add_row($heading);

		$heading2 = array(
			array('data'=>'DEBIT','class'=>'sub-total'),
			array('data'=>'CREDIT','class'=>'sub-total'),
			array('data'=>'DEBIT','class'=>'sub-total'),
			array('data'=>'CREDIT','class'=>'sub-total'),
			array('data'=>'DEBIT','class'=>'sub-total'),
			array('data'=>'CREDIT','class'=>'sub-total'),
			array('data'=>'DEBIT','class'=>'sub-total'),
			array('data'=>'CREDIT','class'=>'sub-total'),
			array('data'=>'DEBIT','class'=>'sub-total'),
			array('data'=>'CREDIT','class'=>'sub-total'),		
			);
	$this->table->add_row($heading2);		


		$result = $this->md_worksheet->get_worksheet();

		$total['TBAL_DEBIT'] = 0;
		$total['TBAL_CREDIT'] = 0;
		$total['ADJ_DEBIT'] = 0;
		$total['ADJ_CREDIT'] = 0;
		$total['ADJ_T_DEBIT'] = 0;
		$total['ADJ_T_CREDIT'] = 0;
		$total['INC_DEBIT'] = 0;
		$total['INC_CREDIT'] = 0;
		$total['BAL_DEBIT'] = 0;
		$total['BAL_CREDIT'] = 0;

		foreach($result as $result_row){

			$total['TBAL_DEBIT']   +=  $result_row['TBAL_DEBIT'];
			$total['TBAL_CREDIT']  +=  $result_row['TBAL_CREDIT'];
			$total['ADJ_DEBIT']    +=  $result_row['ADJ_DEBIT'];
			$total['ADJ_CREDIT']   +=  $result_row['ADJ_CREDIT'];
			$total['ADJ_T_DEBIT']  +=  $result_row['ADJ_T_DEBIT'];
			$total['ADJ_T_CREDIT'] +=  $result_row['ADJ_T_CREDIT'];
			$total['INC_DEBIT']    +=  $result_row['INC_DEBIT'];
			$total['INC_CREDIT']   +=  $result_row['INC_CREDIT'];
			$total['BAL_DEBIT']    +=  $result_row['BAL_DEBIT'];
			$total['BAL_CREDIT']   +=  $result_row['BAL_CREDIT'];


			$row_content = array(
				array('data'=>$result_row['account_code']),
				array('data'=>$result_row['DESCRIPTION']),
				array('data'=>$this->number_format($result_row['TBAL_DEBIT'])),
				array('data'=>$this->number_format($result_row['TBAL_CREDIT'])),
				array('data'=>$this->number_format($result_row['ADJ_DEBIT'])),
				array('data'=>$this->number_format($result_row['ADJ_CREDIT'])),
				array('data'=>$this->number_format($result_row['ADJ_T_DEBIT'])),
				array('data'=>$this->number_format($result_row['ADJ_T_CREDIT'])),
				array('data'=>$this->number_format($result_row['INC_DEBIT'])),
				array('data'=>$this->number_format($result_row['INC_CREDIT'])),
				array('data'=>$this->number_format($result_row['BAL_DEBIT'])),
				array('data'=>$this->number_format($result_row['BAL_CREDIT'])),
			);

			$this->table->add_row($row_content);

		}

		$footer = array(
			'',
				array('data'=>'PROFIT','class'=>'sub-total'),
				array('data'=>$this->number_format($total['TBAL_DEBIT']),'class'=>'sub-total'),
				array('data'=>$this->number_format($total['TBAL_CREDIT']),'class'=>'sub-total'),
				array('data'=>$this->number_format($total['ADJ_DEBIT']),'class'=>'sub-total'),
				array('data'=>$this->number_format($total['ADJ_CREDIT']),'class'=>'sub-total'),
				array('data'=>$this->number_format($total['ADJ_T_DEBIT']),'class'=>'sub-total'),
				array('data'=>$this->number_format($total['ADJ_T_CREDIT']),'class'=>'sub-total'),
				array('data'=>$this->number_format($total['INC_DEBIT']),'class'=>'sub-total'),
				array('data'=>$this->number_format($total['INC_CREDIT']),'class'=>'sub-total'),		
				array('data'=>$this->number_format($total['BAL_DEBIT']),'class'=>'sub-total'),
				array('data'=>$this->number_format($total['BAL_CREDIT']),'class'=>'sub-total'),
			);

		$this->table->add_row($footer);	
		$lblIDebit  = 0;
		$lblACredit = 0;
		$lblICredit = 0;
		$lblADebit  = 0;

		if($total['INC_CREDIT'] > $total['INC_DEBIT']){
			$lblIDebit  = $total['INC_CREDIT'] - $total['INC_DEBIT'];
			$lblACredit = $lblIDebit;
		}else if($total['INC_CREDIT'] > $total['INC_DEBIT']){
			$lblICredit = $this->number_format($total['INC_DEBIT'] - $total['INC_CREDIT']);
            $lblADebit  = $this->number_format($lblICredit);
		}


		$finalTotal['income_debit']   =   $lblIDebit  + $total['INC_DEBIT'];
		$finalTotal['income_credit']  =   $lblICredit + $total['INC_CREDIT'];
		$finalTotal['balance_debit']  =   $lblICredit + $total['BAL_DEBIT'];
		$finalTotal['balance_credit'] =   $lblACredit + $total['BAL_CREDIT'];

		$sub_footer = array(
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				array('data'=>$this->number_format($lblIDebit),'class'=>'sub-total'),
				array('data'=>$this->number_format($lblICredit),'class'=>'sub-total'),
				array('data'=>$this->number_format($lblADebit),'class'=>'sub-total'),
				array('data'=>$this->number_format($lblACredit),'class'=>'sub-total'),
			);
		$this->table->add_row($sub_footer);


		$sub_footer2 = array(
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				"",
				array('data'=>$this->number_format($finalTotal['income_debit']),'class'=>'sub-total'),
				array('data'=>$this->number_format($finalTotal['income_credit']),'class'=>'sub-total'),		
				array('data'=>$this->number_format($finalTotal['balance_debit']),'class'=>'sub-total'),
				array('data'=>$this->number_format($finalTotal['balance_credit']),'class'=>'sub-total'),
			);
		$this->table->add_row($sub_footer2);


		
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