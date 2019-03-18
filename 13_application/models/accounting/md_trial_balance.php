<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_trial_balance extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}
	


	public function get_cumulative($arg){

		if($arg['location'] == 0){
			$arg['location'] = '';
		}

		$sql = "
			
			SELECT 
				  z.account_id,
				  z.account_code 'ACCOUNT CODE',
				  z.account_description 'DESCRIPTION',
				  IF(z.DEBIT > z.CREDIT,z.DEBIT - z.CREDIT,'') 'DEBIT',  
				  IF(z.DEBIT < z.CREDIT,z.CREDIT - z.DEBIT,'') 'CREDIT'  
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
				    AND '{$arg['date_to']}' 
				    AND journal_main1.status = 'POSTED' 
				    AND journal_main1.type <> 'ADJUSTMENT ENTRY'
				    GROUP BY journal_detail1.`account_id`
				    ) z 
				WHERE `z`.`DEBIT` <> 0 
				  OR `z`.`CREDIT` <> 0 
				GROUP BY z.account_id 
				ORDER BY z.account_code 
		
		";
		$result = $this->db->query($sql);				
		$this->db->close();
		return $result;

	}


}