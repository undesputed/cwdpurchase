<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Balance_sheet extends CI_Controller {

	public function __construct(){
		parent :: __construct();	
		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_balancesheet');	
	}

	public function index(){

		$this->lib_auth->title = "Balance Sheet";		
		$this->lib_auth->build = "accounting/balance_sheet/single_view";
		$data['project'] = $this->md_project->get_profit_center();
		$this->lib_auth->render($data);

	}


	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-hover">' );
		$this->table->set_template($tmpl);

		$data = array();
			
			$show = array(
						'Accounts',
						'Previous',
						'Current',
				 		);

				$data        = $this->md_balancesheet->get_shortDesc();
				$fullDesc    = $this->md_balancesheet->get_fullDesc();
				$get_journal = $this->md_balancesheet->get_journal();
				$get_equity  = $this->md_balancesheet->get_equity();

				$total_debit   = 0;
				$total_credit  = 0;

				$subTotalPrev = 0;				
				$subTotalCurrent = 0;
				
				$Liabilities_Equity_Prev = 0;
				$Liabilities_Equity_Current = 0;

				$include = false;
				$cnt = count($data);
				$increment = 0;
				foreach($data as $key => $value){

						$grandPrevious = 0;
						$grandCurrent = 0;


						$this->table->add_row(array('data'=>$value['short_description'],'class'=>'sub-total'),'','');
												
						foreach($fullDesc as $fullDesc_row){
						
							if($value['short_description'] == $fullDesc_row['short_description']){
								$load = false;

								foreach($get_journal as $journal_row2){
										
										if($journal_row2['full_description'] == $fullDesc_row['full_description']){
											$this->table->add_row(array('data'=>$fullDesc_row['full_description'],'style'=>'padding-left:2em;','class'=>'sub-total'),'','');
											$load = true;
											break;
										}

								}

								$subTotalPrev    = 0;
								$subTotalCurrent = 0;

								foreach($get_journal as $journal_row){
																		
									if($journal_row['full_description'] == $fullDesc_row['full_description']){

										 $prevAmount    =  $journal_row['PREVIOUS'];
										 $currentAmount =  $journal_row['CURRENT'];

										if($fullDesc_row['full_description']=='EQUITY'){
											foreach($get_equity as $equity_row){												
												$prevAmount    = $equity_row['Previous'];
												$currentAmount = $equity_row['Current'];
												$subTotalPrev    += ($equity_row['dr_cr']=="DEBIT")? ($prevAmount < 0)? $prevAmount  : -$prevAmount  : $prevAmount;
												$grandPrevious   += ($equity_row['dr_cr']=="DEBIT")? -($prevAmount < 0)? $prevAmount  : -$prevAmount  : $prevAmount;
												$subTotalCurrent += ($equity_row['dr_cr']=="DEBIT")? ($currentAmount < 0)? $currentAmount  : -$currentAmount  : $currentAmount;
												$grandCurrent    += ($equity_row['dr_cr']=="DEBIT")? ($currentAmount < 0)? $currentAmount  : -$currentAmount  : $currentAmount;	
											}
											
											if($fullDesc_row['short_description']=='EQUITY'){
												$Liabilities_Equity_Prev += abs($grandPrevious);
												$Liabilities_Equity_Current += abs($grandCurrent);
											}

										}else{
											
											$this->table->add_row(array('data'=>$journal_row['account_code']." ".$journal_row['account_description'],'style'=>'padding-left:5em;'),$this->number_format($prevAmount),$this->number_format($currentAmount));

											$subTotalPrev    += $prevAmount;
				                            $grandPrevious   += $prevAmount;
				                            $subTotalCurrent += $currentAmount;
				                            $grandCurrent    += $currentAmount;

				                            if($fullDesc_row['short_description']=="LIABILITIES"){
				                            	$Liabilities_Equity_Prev    += abs($grandPrevious);
												$Liabilities_Equity_Current += abs($grandCurrent);
				                            }

										}

									}


								}

								if($load){
										$this->table->add_row(
											array('data'=>'Total    '.$fullDesc_row['full_description']." : ",'style'=>'padding-left:7em;',),
											$this->number_format($subTotalPrev),
											$this->number_format($subTotalCurrent)
										);										
								}
								$load = false;
																
							}

						}


						$this->table->add_row(
									array('data'=>'Total '.$value['short_description'],'style'=>'','class'=>'sub-total'),
									array('data'=>$this->number_format($grandPrevious),'class'=>'sub-total'),
									array('data'=>$this->number_format($grandCurrent),'class'=>'sub-total')
								);


						
						if(++$increment === $cnt){
   							$this->table->add_row(
									array('data'=>'Total LIABILITIES AND EQUITY','style'=>''),
									$this->number_format($Liabilities_Equity_Prev),
									$this->number_format($Liabilities_Equity_Current)
								);
  						}											
				}

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


	public function get_balance_sheet(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg    = $this->input->post();		
		$result = $this->md_balancesheet->get_balance_sheet($arg);

		$a = array();	
		foreach($result as $row){
			$a[$row['short_description']][$row['full_description']][] = $row;
		}
		
		$data['data'] = $a;
		$this->load->view('accounting/balance_sheet/tbl_current',$data);

	}

}