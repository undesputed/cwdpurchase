<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_change_in_equity extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_equity2($arg){

		switch($this->input->post('display_by')){
			case"today":
				$dateT = $this->input->post('date');
			break;
			case"month":
				$time = strtotime($this->input->post('date'));
				$final = date("Y-m-d", strtotime("+1 month", $time));
				$dateT = $final;
			break;
			case"year":
				$date =  explode('-',$this->input->post('date'));
				$dateT = $date[0].'-12-31';
			break;

		}


		$where = "";
		$where1 = "";		

		if($arg['view_type']=="monthly"){
			$date = $arg['year']."-".$arg['month'].'-01';
			$from = date('Y-m-01',strtotime($date));
			$to   = date('Y-m-t',strtotime($date));
		}else{
			$from = $arg['date_from'];
			$to   = $arg['date_to'];
		}

		$sql = "CALL accounting_display_equity_list_new('{$from}','{$to}','".$arg['location']."','".$this->session->userdata('Proj_Main')."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function get_equity($arg){

		

		$sql = "
				SELECT 
			      account_setup.account_id,
			      account_setup.account_code,
			      account_setup.account_description,
			      IFNULL(
			        SUM(
			          (
			            CASE
			              WHEN journal_detail1.dr_cr = 'DEBIT' 
			              THEN journal_detail1.amount 
			            END
			          )
			        ),
			        0
			      ) 'DEBIT',
			      IFNULL(
			        SUM(
			          (
			            CASE
			              WHEN journal_detail1.dr_cr = 'CREDIT' 
			              THEN journal_detail1.amount 
			            END
			          )
			        ),
			        0
			      ) 'CREDIT',
			      account_setup.t_account,
			      classification_setup.short_description,
			      classification_setup.full_description,
			      journal_main1.trans_date 
			    FROM
			      journal_main1 
			      INNER JOIN journal_detail1 
			        ON (
			          journal_main1.journal_id = journal_detail1.journal_id
			        ) 
			      INNER JOIN account_setup 
			        ON (
			          account_setup.account_id = journal_detail1.account_id
			        ) 
			      INNER JOIN classification_setup 
			        ON (
			          classification_setup.id = account_setup.classification_code
			        ) 
			      INNER JOIN setup_project 
			        ON (
			          journal_main1.location = setup_project.project_id
			        ) 
			    WHERE journal_main1.location LIKE '%1%' 
			      AND journal_main1.trans_date BETWEEN '{$arg['date_from']}' AND '{$arg['date_to']}' 
			      AND journal_main1.status = 'POSTED' 
			      AND short_description IN ('EQUITY', 'EXPENSES', 'INCOME') 
			    GROUP BY journal_detail1.`account_id`  
		";
		$result = $this->db->query($sql);
		$result = $result->result_array();

		$short_desc = array();
		foreach($result as $row){
			$short_desc[$row['short_description']][] = $row;
		}

		$credit_income = 0;
		$debit_income  = 0;
		if(!empty($short_desc['INCOME'])){
			foreach($short_desc['INCOME'] as $row){
				$credit_income = $credit_income + $row['CREDIT'];
				$debit_income  = $debit_income + $row['DEBIT'];
			}
		}

		$credit_income = $credit_income - $debit_income;

		$credit_exp = 0;
		$debit_exp  = 0;
		if(!empty($short_desc['EXPENSES'])){
			foreach($short_desc['EXPENSES'] as $row){
				$credit_exp = $credit_exp + $row['CREDIT'];
				$debit_exp  = $debit_exp + $row['DEBIT'];
			}
		}

		$debit_exp = $debit_exp - $credit_exp;

		$profit = $credit_income - $debit_exp;

		if($profit > 0){
			$a = array('DEBIT'=>'0','CREDIT'=>$profit,'account_description'=>' Add : Profit','position'=>'right');
		}else{
			$a = array('CREDIT'=>'0','DEBIT'=>$profit,'account_description'=>'Less : Loss','position'=>'right');
		}

		$date_start = date('m/d/Y',strtotime($arg['date_from']));
		$date_end   = date('m/d/Y',strtotime($arg['date_to']));

		$account = array();
		if(!empty($short_desc['EXPENSES'])){
			foreach($short_desc['EQUITY'] as $row){			
				if($row['CREDIT'] > $row['DEBIT']){
					$row['account_description'] = "Owner's Equity, ".$date_start;				
					$account[] = $row;
					$account[] = $a;
				}else{
					$account[] = $row;
				}
			}
		}

		return array(
				'data'=>$account,
				'date'=>array('from'=>$date_start,'to'=>$date_end),
			);
	}







}