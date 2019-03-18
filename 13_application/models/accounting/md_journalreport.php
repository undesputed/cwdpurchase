<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_journalReport extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}



	public function get_journal(){

		$sql = "
			SELECT 
				IF(journal_main.status = 'POSTED', 'True','False') AS 'Post?',
				IF(journal_main.status = 'POSTED',journal_main.status,'UNPOSTED') 'Status',
				journal_main.journal_id, 
				journal_main.reference_no 'Reference No.', 
				journal_main.trans_date 'Transaction Date', 
				journal_main.type 'Type', 
				journal_main.memo 'Memo',  
				journal_main._amount 'amount',  
				setup_project.project_name 'Location'
			FROM 
			journal_main
			INNER JOIN setup_project
			ON setup_project.project_id = journal_main.location
			WHERE journal_main.status = '{$this->input->post('status')}'
			AND journal_main.location LIKE '{$this->input->post('location')}'
		";
		
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function save_journal(){
		
		$details = $this->input->post('details');
		foreach($details as $row){			
			$sql    = "UPDATE journal_main SET `status` = 'POSTED' where journal_id = '".$row['journal_id']."'";
			$this->db->query($sql);

		}
		return true;		
	}



}