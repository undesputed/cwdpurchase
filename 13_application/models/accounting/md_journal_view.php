<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_journal_view extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_cumulative($arg){



		if($arg['view_type']=='date_range'){
			$from = $arg['date_from'];
			$to   = $arg['date_to'];
		}else{
			$from  = date('Y-m-01',strtotime($arg['year'].'-'.$arg['month'].'-01'));
			$to    = date('Y-m-t',strtotime($arg['year'].'-'.$arg['month'].'-01'));	
		}

		

		/*switch($arg['display_by']){
			case "today":
				$from = $arg['date'];
				$to   = $arg['date'];
			break;
			case "month":
				$date = explode('-',$arg['date']);				
				$from = $date[0].'-'.$date[1].'-'.'01';
				$to   = date('Y-m-t',strtotime($arg['date']));
			break;

			case "year":
				$date = explode('-',$arg['date']);	
				$from = $date[0].'-01-01';
				$to   = $date[0].'-12-31';
			break;
		}
		*/

		$sql = "
			SELECT 
			journal_main.trans_date 'Date',				
			(IF(`type`='TRANSFER',CONCAT(journal_main.trans_type,'-',(SELECT project_name FROM setup_project WHERE project_id=journal_main.location)) ,journal_main.trans_type)) 'Trans Type',			
			'' AS 'Account Titles and Explanation',
			'' AS 'Debit',
			'' AS 'Credit',
			journal_main.status AS 'Status',
			location,
			journal_main.journal_id
			FROM journal_main
			WHERE journal_main.location LIKE '{$arg['location']}'
			AND journal_main.trans_date BETWEEN '{$from}' AND '{$to}'
			AND status = 'POSTED'
		";
				
		/*$sql = "CALL accounting_display_JOURNALVIEW('".$arg['location']."','".$from."','".$to."');";*/
		$result = $this->db->query($sql);
		$this->db->close();
		$array = array();
		if($result->num_rows() > 0)
		{	

			$cnt = 0;
			foreach($result->result_array() as $row){

				$sql = "CALL accounting_display_JOURNALVIEW_dETAIL('".$row['location']."','".$row['journal_id']."')";
				$response = $this->db->query($sql);
				$this->db->close();
				
				$array[$cnt] = $row;
				$array[$cnt]['data'][] = $response->result_array();
				
				$cnt++;
			}

		}
		
		return $array;
		
	}



}