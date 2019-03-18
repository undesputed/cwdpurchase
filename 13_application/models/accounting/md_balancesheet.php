<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_balancesheet extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}



	public function get_shortDesc(){

		$sql = "SELECT
				  short_description
				FROM classification_setup
				WHERE short_description <> 'INCOME'
				    AND short_description <> 'EXPENSES'
				GROUP BY short_description
				ORDER BY id";

		$result = $this->db->query($sql);
		$this->db->close();
		
		return $result->result_array();
	}

	
	public function get_fullDesc(){

		$sql = "SELECT short_description,full_description FROM classification_setup WHERE short_description <> 'INCOME' AND short_description <> 'EXPENSES'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
		
	}

	public function get_journal(){


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

			
		$sql = "CALL display_balanceSheet_report_summary('1900-01-01','".$dateT."','".$this->input->post('location')."','".$this->session->userdata('Proj_Main')."');";
		$result = $this->db->query($sql);		
		$this->db->close();
		return $result->result_array();

	}
		
	public function get_equity(){



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

		$sql = "CALL accounting_display_equity_list('1900-01-01','".$dateT."','".$this->input->post('location')."','".$this->session->userdata('Proj_Main')."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}
	

	public function get_balance_sheet($arg){

		$sql = "				
			SELECT
			account_id,
			`ACCOUNT CODE`,
			`DESCRIPTION`,
			t_account,
			short_description,
			full_description,
			(CASE
			   WHEN `DEBIT` <> 0 AND short_description = 'ASSETS' THEN `DEBIT` 
			   WHEN `CREDIT` <> 0 AND short_description = 'ASSETS' THEN CONCAT('-',`CREDIT`)
			   WHEN `CREDIT` <> 0 AND short_description = 'LIABILITIES' THEN `CREDIT`
			   WHEN `DEBIT` <> 0 AND short_description = 'EQUITY' THEN  CONCAT('-',`DEBIT`)
			   WHEN `CREDIT` <> 0 AND short_description = 'EQUITY' THEN `CREDIT` 
			END) 'AMOUNT'
			FROM (
			SELECT
			  z.account_id,
			  z.account_code 'ACCOUNT CODE',
			  z.account_description 'DESCRIPTION',
			  IF(z.DEBIT > z.CREDIT,z.DEBIT - z.CREDIT,'') 'DEBIT',  
			  IF(z.DEBIT < z.CREDIT,z.CREDIT - z.DEBIT,'') 'CREDIT',  
			  z.t_account,
			  short_description,
			  full_description
			FROM  
			  (SELECT 
			    account_setup.account_id,
			    account_setup.account_code,
			    account_setup.account_description,
			    IFNULL(SUM((CASE
				WHEN journal_detail1.dr_cr ='DEBIT' THEN journal_detail1.amount
			    END)),0)'DEBIT',
			    IFNULL(SUM((CASE
				WHEN journal_detail1.dr_cr ='CREDIT' THEN journal_detail1.amount
			    END)),0)'CREDIT',
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
			  WHERE journal_main1.location LIKE '%{$arg['location']}%' 
			    AND journal_main1.trans_date BETWEEN '{$arg['date_from']}' 
			    AND '{$arg['date_to']}' AND journal_main1.status = 'POSTED' AND short_description IN ('ASSETS','LIABILITIES')
			    GROUP BY journal_detail1.`account_id`
			    ) z 
			WHERE `z`.`DEBIT` <> 0 
			  OR `z`.`CREDIT` <> 0 
			GROUP BY z.account_id 
			#ORDER BY z.account_code 

			UNION 


			SELECT 
			account_id,
			`ACCOUNT CODE`,
			DESCRIPTION,
			' ' AS 'DEBIT', 
			SUM(CREDIT) - SUM(DEBIT)'CREDIT',
			t_account,
			short_description,
			full_description
			FROM ( 
			SELECT 
			  z.account_id, 
			  z.account_code 'ACCOUNT CODE', 
			  z.account_description 'DESCRIPTION', 
			  SUM(IF(z.DEBIT > z.CREDIT,z.DEBIT - z.CREDIT,'')) 'DEBIT',  
			  SUM(IF(z.DEBIT < z.CREDIT,z.CREDIT - z.DEBIT,''))'CREDIT',  
			  z.t_account, 
			  short_description, 
			  full_description 
			FROM  
			  (SELECT 
			    account_setup.account_id,
			    account_setup.account_code,
			    account_setup.account_description,
			    IFNULL(SUM((CASE
				WHEN journal_detail1.dr_cr ='DEBIT' THEN journal_detail1.amount
			    END)),0)'DEBIT',
			    IFNULL(SUM((CASE
				WHEN journal_detail1.dr_cr ='CREDIT' THEN journal_detail1.amount
			    END)),0)'CREDIT',
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
			  WHERE journal_main1.location LIKE '%{$arg['location']}%'
			    AND journal_main1.trans_date BETWEEN '{$arg['date_from']}' 
			    AND '{$arg['date_to']}' AND journal_main1.status = 'POSTED' AND short_description IN ('EQUITY','EXPENSES','INCOME')
			    GROUP BY journal_detail1.`account_id` 
			    ) z 
			    WHERE `z`.`DEBIT` <> 0 
			  OR `z`.`CREDIT` <> 0 
			  GROUP BY z.short_description 
			) m 
			) a
			
		";		
		$result = $this->db->query($sql);		
		return $result->result_array();
	}






}