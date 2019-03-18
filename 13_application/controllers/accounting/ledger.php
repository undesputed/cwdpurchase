<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ledger extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->lib_auth->default = "default-accounting";		
		$this->load->model('accounting/md_ledger');	
		$this->load->model('md_project');	
	}


	public function index(){

		$this->lib_auth->title = "Ledger";
		$this->lib_auth->build = "accounting/ledger/index";		
		$data['project']  = $this->md_project->get_profit_center();
		$data['business'] = json_encode($this->md_ledger->business());		
		$data['person']   = json_encode($this->md_ledger->person());
		$this->lib_auth->render($data);

	}


	public function ledger_display($cmbAccountName='%%'){
		
		$post  = $this->input->post();

		if(empty($post['account_id'])){
			$post['account_id'] = 0;
		}

		$arg['from']         = date('Y-m-01',strtotime($post['year']."-".$post['month']."-01"));
		$arg['to']           = date('Y-m-t',strtotime($post['year']."-".$post['month']."-01"));
		$arg['identifier']   = $post['identifier'];
		$arg['location']     = $post['location'];
		$arg['param5']       = "BUSINESS";
		$arg['per_ledger']   = $post['per_ledger'];
		$arg['selectedAccount'] = $post['account_id'];
		$arg['str']          = $post['str'];
		$arg['supplier']     = $post['supplier'];
		
		if($arg['identifier']=='1'){
			$cmbAccountName = $post['account_id'];		
			$arg['view_all'] = "false";
		}else{
			$arg['view_all'] = "true";
		}

		$result = $this->md_ledger->ledger_display($cmbAccountName,$arg);

		if($result){
			$for_debit=0;$for_credit=0;$for_balance=0;$overall_for_debit=0;$overall_for_credit=0;$tr=null;
			$tr .="<table class='table table-condensed'>";
			$tr .="<thead>";
			$tr .="<tr>";
			$tr .="<th>Account Description</th>";
			$tr .="<th>Transaction date</th>";			
			$tr .="<th>JEV No</th>";
			$tr .="<th>Subsidiary Ledger</th>";
			$tr .="<th>Reference No</th>";
			$tr .="<th>Debit</th>";
			$tr .="<th>Credit</th>";
			$tr .="<th>Balance</th>";
			$tr .="</tr>";
			$tr .="</thead>";

			if($arg['view_all']=='true'){
				
				foreach($result[0] as $i){
					$for_debit  =0;
					$for_credit =0;
					$tr.="<tr class=''><td><strong>".$i['Account']."</strong></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
					foreach($result[1] as $ii){

						if($i['account_id']==$ii['account_id']){
							if($ii['DEBIT']!=""){
								$for_balance+=$ii['amount'];
								$for_debit+=$ii['amount'];
								$overall_for_debit+=$ii['amount'];
							}
							else{
								$for_balance-=$ii['amount'];
								$for_credit+=$ii['amount'];
								$overall_for_credit+=$ii['amount'];
							}
														
							$tr.='<tr>';
								$tr.="<td></td><td>".$ii['trans_date']."</td>";								
								$tr.="<td>".$ii['reference_no']."</td>";
								$tr.="<td>".$ii['subsidiary']."</td>";
								$tr.="<td>".$ii['memo']."</td>";
								$tr.="<td>".$this->extra->number_format($ii['DEBIT'])."</td>";
								$tr.="<td>".$this->extra->number_format($ii['CREDIT'])."</td>";
								$tr.="<td>".$this->extra->number_format($for_balance)."</td>";								
							$tr.='</tr>';
						}
					}
					$tr.='<tr><td>TOTAL '.$i['Account'].'</td><td></td><td></td><td></td><td></td><td><strong>'.number_format($for_debit,2,'.',',').'</strong></td><td><strong>'.number_format($for_credit,2,'.',',').'</strong></td><td><strong>'.number_format($for_balance,2,'.',',').'</strong></td></tr>';
				}
				$tr.='<tr><td><strong>OVERALL TOTAL</strong></td><td></td><td></td><td></td><td></td><td><strong>'.number_format($overall_for_debit,2,'.',',').'</strong></td><td><strong>'.number_format($overall_for_credit,2,'.',',').'</strong></td><td><strong>'.number_format($for_balance,2,'.',',').'</strong></td></tr>';								
			}
			else{
			
				foreach($result[2] as $iii){
					if($iii['t_account'] == "DEBIT"){
						if($iii['DEBIT']!=""){
							$for_balance+=$iii['amount'];
							$for_debit+=$iii['amount'];
							$overall_for_debit+=$iii['amount'];
						}
						else{
							$for_balance-=$iii['amount'];
							$for_credit+=$iii['amount'];
							$overall_for_credit+=$iii['amount'];
						}
					}
					else{
						if($iii['DEBIT']!=""){
							$for_balance-=$iii['amount'];
							$for_debit+=$iii['amount'];
							$overall_for_debit+=$iii['amount'];
						}
						else{
							$for_balance+=$iii['amount'];
							$for_credit+=$iii['amount'];
							$overall_for_credit+=$iii['amount'];
						}
					}
					if ($this->input->post('per_ledger')=='true'){
						$tr.='<tr>';
							$tr.="<td></td><td>".$iii['trans_date']."</td>";
							$tr.="<td>".$iii['trans_type']."</td>";
							$tr.="<td>".$iii['reference_no']."</td>";
							$tr.="<td>".$iii['memo']."</td>";
							$tr.="<td>".$this->extra->number_format($iii['DEBIT'])."</td>";
							$tr.="<td>".$this->extra->number_format($iii['CREDIT'])."</td>";
							$tr.="<td>".$this->extra->number_format($for_balance)."</td>";
						$tr.='</tr>';												
					}
					else{
						$tr.='<tr>';
							$tr.="<td></td><td>".$iii['trans_date']."</td>";
							$tr.="<td>".$iii['trans_type']."</td>";
							$tr.="<td>".$iii['reference_no']."</td>";
							$tr.="<td>".$iii['memo']."</td>";
							$tr.="<td>".$this->extra->number_format($iii['DEBIT'])."</td>";
							$tr.="<td>".$this->extra->number_format($iii['CREDIT'])."</td>";
							$tr.="<td>".$this->extra->number_format($for_balance)."</td>";
						$tr.='</tr>';
					}
				}
				$tr.='<tr><td><strong>OVERALL TOTAL</strong></td><td></td><td></td><td></td><td></td><td><strong>'.number_format($overall_for_debit,2,'.',',').'</strong></td><td><strong>'.number_format($overall_for_credit,2,'.',',').'</strong></td><td><strong>'.number_format($for_balance,2,'.',',').'</strong></td></tr>';								
			}

			$tr.="</table>";
			echo $tr;
		}
		else
			echo "<div>Empty Result</div>";
	}


	public function get_account(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$id = $this->input->get('project_id');

		$result = $this->md_ledger->display_ledger($id);
		$div = "";
		foreach($result as $row){
			$div.="<option value='{$row['account_id']}' data-code='{$row['account_code']}' data-classification_id ='{$row['sub_class_code']}'>{$row['account_description']}</option>";
		}

		echo $div;
	}
		


}