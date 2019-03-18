<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_cashflow extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_cashflow($arg){

		if($arg['view_type']=="monthly"){
			$date = $arg['year']."-".$arg['month'].'-01';
			$from = date('Y-m-01',strtotime($date));
			$to   = date('Y-m-t',strtotime($date));
		}else{
			$from = $arg['date_from'];
			$to   = $arg['date_to'];
		}

		$sql = "CALL CashFlow_display_Main_new('".$arg['location']."','".$from."','".$to."','".$this->session->userdata('Proj_Main')."');";				
		$result = $this->db->query($sql);		
		$this->db->close();
		return $result->result_array();


		$sql = "

		

SELECT
journal_main1.`journal_id`,
journal_main1.`trans_date`,
journal_detail1.`amount`,
journal_detail1.`dr_cr`,
account_setup.account_description,
sub_classification_setup.sub_classification_name
FROM journal_main1
  INNER JOIN journal_detail1
    ON (journal_main1.journal_id = journal_detail1.journal_id)
     INNER JOIN account_setup
      ON (account_setup.`account_id` = journal_detail1.`account_id`)
     INNER JOIN sub_classification_setup
      ON (sub_classification_setup.sub_classification_id = account_setup.`sub_class_code`)
	INNER JOIN (
		SELECT
		journal_main1.`journal_id`,
		journal_main1.`trans_date`,
		journal_detail1.`amount`,
		journal_detail1.`dr_cr`,
		account_setup.account_description,
		sub_classification_setup.sub_classification_name
		FROM journal_main1
		  INNER JOIN journal_detail1
		    ON (journal_main1.journal_id = journal_detail1.journal_id)
		     INNER JOIN account_setup
		      ON (account_setup.`account_id` = journal_detail1.`account_id`)
		     INNER JOIN sub_classification_setup
		      ON (sub_classification_setup.sub_classification_id = account_setup.`sub_class_code`)
		WHERE 
		journal_main1.trans_date BETWEEN '2015-11-01' AND '2015-11-30' 
		AND journal_main1.status = 'POSTED' 
		AND sub_classification_name = 'RECEIVABLE' AND journal_detail1.`dr_cr` = 'CREDIT'
		) a 
	ON (a.journal_id = journal_main1.journal_id)
WHERE journal_detail1.`dr_cr` ='DEBIT' AND sub_classification_setup.sub_classification_name = 'CASH'



		";


	}



}