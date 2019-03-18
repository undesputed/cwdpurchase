<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_income_statement extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}



	public function get_cumulative($from,$to,$project,$check,$pay_item){

		// SELECT id,short_description, full_description FROM classification_setup WHERE short_description = 'INCOME';

		// SELECT id,short_description, full_description FROM classification_setup WHERE short_description = 'EXPENSES';

		// CALL incomestatement_display_income_newacc('1900-01-01','2014-04-18','%','2')

		// CALL incomestatement_display_revenues_newacc('1900-01-01','2014-04-18', '%', '2')

		// CALL incomestatement_display_expenses_newacc('1900-01-01','2014-04-18', '%', '2')

		/*
		//Income
		$sql = "SELECT id,short_description, full_description FROM classification_setup WHERE short_description = 'INCOME'";
		$result = $this->db->query($sql);
		//Expense
		$sql2 = "SELECT id,short_description, full_description FROM classification_setup WHERE short_description = 'EXPENSES'";
		$result2 = $this->db->query($sql2);
		//Stored Income
		$sql3 = "CALL incomestatement_display_income_1('".$from."','".$to."','".$project."','".$check."')";
		$result3 = $this->db->query($sql3);
		$this->db->close();
		//Stored Expense
		$sql4 = "CALL incomestatement_display_expenses_1('".$from."','".$to."', '".$project."', '".$check."')";
		$result4 = $this->db->query($sql4);
		$this->db->close();
		//Stored Revenue
		$sql5 = "CALL incomestatement_display_revenues_1('".$from."','".$to."', '".$project."', '".$check."')";
		$result5 = $this->db->query($sql5);
		$this->db->close();
		$data = array(
			'0' => $result->result_array(),
			'1' => $result2->result_array(),
			'2' => $result3->result_array(),
			'3' => $result4->result_array(),
			'4' => $result5->result_array(),
		);
		return $data;
		*/

		$sql_date ="
			SELECT 
			  YEAR(trans_date) 'DATE'
			  FROM 
			  journal_detail1
			  INNER JOIN journal_main1
					ON(journal_detail1.journal_id = journal_main1.journal_id)
			  INNER JOIN account_setup
					ON(journal_detail1.account_id = account_setup.account_id)
			  INNER JOIN classification_setup
					ON(account_setup.classification_code = classification_setup.id)
			  WHERE 
			  journal_main1.status = 'POSTED'
			  AND journal_main1.location like '%{$project}%' 
			  AND journal_main1.title_id = '{$check}'
			  AND journal_main1.pay_item like '%{$pay_item}%'			  
			  GROUP BY YEAR(trans_date);
			  ";

		$result6 = $this->db->query($sql_date);		
		
		//Income
		$sql = "SELECT id,short_description, full_description FROM classification_setup WHERE short_description = 'INCOME'";
		$result = $this->db->query($sql);
		//Expense
		$sql2 = "SELECT id,short_description, full_description FROM classification_setup WHERE short_description = 'EXPENSES'";
		$result2 = $this->db->query($sql2);
		//Stored Income
		$sql3 = "CALL _display_incomestatement_ben('".$project."','INCOME','{$pay_item}','".$check."')";
		$result3 = $this->db->query($sql3);		
		$row = $result3->row_array();
		$this->db->close();
		$result3 = $this->db->query($row['RESULT']);			
		$this->db->close();
		//Stored Expense
		$sql4 = "CALL _display_incomestatement_ben('".$project."','EXPENSES','{$pay_item}','".$check."')";		
		$result4 = $this->db->query($sql4);		
		$this->db->close();
		$row = $result4->row_array();
		$result4 = $this->db->query($row['RESULT']);
		$this->db->close();
		//Stored Revenue
		$sql5 = "CALL _display_incomestatement_revenue_ben('".$project."','".$check."')";
		$result5 = $this->db->query($sql5);
		$this->db->close();
		$row = $result5->row_array();
		$result5 = $this->db->query($row['RESULT']);
		$this->db->close();

		$data = array(
			'0' => $result->result_array(),
			'1' => $result2->result_array(),
			'2' => $result3->result_array(),
			'3' => $result4->result_array(),
			'4' => $result5->result_array(),
			'5' => $result6->result_array(),
		);
		
		return $data;
		
	}





}