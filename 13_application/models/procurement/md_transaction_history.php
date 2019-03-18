<?php defined('BASEPATH') OR exit('No direct script access allowed');

class md_transaction_history extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function insertion($arg){

		$insert = array(
			'from_projectCode'=>$this->session->userdata('Proj_Code'),
			'from_projectMain'=>$this->session->userdata('Proj_Main'),
			'to_projectCode'=>$arg['to_projectCode'],
			'to_projectMain'=>$arg['to_projectMain'],
			'type'=>$arg['type'],
			'status'=>$arg['status'],
			'reference_id'=>$arg['reference_id'],
			);

		$this->db->insert('transaction_history',$insert);		
		return true;
	}

	public function update_status($arg){

		if(!isset($arg['bool']))
		{
			$arg['bool'] = 'TRUE';
		}

		$update = array(
			'status'=>$arg['status'],
			'remarks'=>$arg['remarks'],
			'is_open'=>$arg['bool'],
			);
		$this->db->where('id',$arg['id']);
		$this->db->update('transaction_history',$update);
		return false;
	}

	public function po_history($arg){

		$sql    = "SELECT * FROM transaction_history WHERE reference_id = '".$arg['id']."' AND TYPE = 'Purchase Request' AND STATUS='APPROVED';";
		$result = $this->db->query($sql);		
		$row    = $result->row_array();	

			

		if(count($row) == 0){
			return false;
		}

		$update = array(
		'status'=>$arg['status'],
		'is_open'=>$arg['bool'],
		);

		$this->db->where('id',$row['id']);
		$this->db->update('transaction_history',$update);
		
		$insert = array(
		'from_projectCode'=>$row['from_projectCode'],
		'from_projectMain'=>$row['from_projectMain'],
		'to_projectCode'=>$row['to_projectCode'],
		'to_projectMain'=>$row['to_projectMain'],
		'type'=>'Purchase Order',
		'status'=>$arg['status'],
		'reference_id'=>$arg['id'],
		'is_open'=>'TRUE',
		);
		
		$this->db->insert('transaction_history',$insert);
		return true;
		
	}

	public function get_po_ref_id($id){
		$sql = "
			SELECT * FROM transaction_history WHERE TYPE = 'Purchase Order' AND reference_id = '".$id."'
		";			
		$result = $this->db->query($sql);
		return $result->row_array();
	}

	public function get_transaction_main($id){
		//$sql = "select * from transaction_history where id = '".$id."'";


		$sql = "
				SELECT
					*,
					notify,
					(CASE
					  WHEN notify = 'creator' THEN (SELECT CONCAT('(',project,')',' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = from_projectCode)
					  ELSE (SELECT CONCAT('(',project,')',' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = to_projectCode)
					END) 'title_projectCode',
					(CASE
					  WHEN notify = 'creator' THEN (SELECT title_name FROM project_title  WHERE title_id = from_projectMain)
					  ELSE (SELECT title_name FROM project_title  WHERE title_id = to_projectMain)
					END) 'title_projectMain'
						FROM (SELECT
						*,
						(CASE
						   WHEN (to_projectCode = ".$this->session->userdata('Proj_Code')." AND to_projectMain = ".$this->session->userdata('Proj_Main').") THEN 'reciever'
						   WHEN (from_projectCode = ".$this->session->userdata('Proj_Code')." AND from_projectMain = ".$this->session->userdata('Proj_Main').") THEN 'creator'
						END)'notify'
						FROM transaction_history 
						where id = '".$id."'
						ORDER BY id DESC
				) AS tbl
		";

		$result = $this->db->query($sql);		
		$this->db->close();
		return $result->row_array();
	}

	public function get_transaction_main_ref_id($id){
		//$sql = "select * from transaction_history where id = '".$id."'";

		$sql = "
				SELECT
					*,
					notify,
					(CASE
					  WHEN notify = 'creator' THEN (SELECT CONCAT('(',project,')',' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = from_projectCode)
					  ELSE (SELECT CONCAT('(',project,')',' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = to_projectCode)
					END) 'title_projectCode',
					(CASE
					  WHEN notify = 'creator' THEN (SELECT title_name FROM project_title  WHERE title_id = from_projectMain)
					  ELSE (SELECT title_name FROM project_title  WHERE title_id = to_projectMain)
					END) 'title_projectMain'
						FROM (SELECT
						*,
						(CASE
						   WHEN (to_projectCode = ".$this->session->userdata('Proj_Code')." AND to_projectMain = ".$this->session->userdata('Proj_Main').") THEN 'reciever'
						   WHEN (from_projectCode = ".$this->session->userdata('Proj_Code')." AND from_projectMain = ".$this->session->userdata('Proj_Main').") THEN 'creator'
						END)'notify'
						FROM transaction_history 
						where reference_id = '".$id."'
						AND TYPE = 'Purchase Request'
						ORDER BY id DESC
				) AS tbl
		";
		

		$result = $this->db->query($sql);		
		$this->db->close();
		return $result->row_array();
	}
	

	public function get_notification(){
		
/*		$sql    = "
					SELECT 
					id,
					(SELECT CONCAT('(',project,')',' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = from_projectCode) 'from_projectCode',
					(SELECT title_name FROM project_title  WHERE title_id = from_projectMain) 'from_projectMain',
					(SELECT CONCAT('(',project,')',' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = to_projectCode) 'to_projectCode',
					(SELECT title_name FROM project_title  WHERE title_id = to_projectMain) 'to_projectMain',
					(SELECT purchaseNo FROM purchaserequest_main WHERE pr_id = reference_id) 'purchaseNo',
					type,
					status,
					reference_id,
					date_created,
					is_open
					FROM transaction_history WHERE (to_projectCode = ? AND to_projectMain = ?) OR (from_projectCode = ? AND from_projectMain = ?) AND is_open = 'FALSE'
					ORDER BY id desc
					";*/



		$sql1 = "

					SELECT
					*,
					(SELECT purchaseNo FROM purchaserequest_main WHERE pr_id = reference_id) 'purchaseNo',
					(CASE
					  WHEN notify = 'creator' THEN (SELECT CONCAT('(',project,')',' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = from_projectCode)
					  ELSE (SELECT CONCAT('(',project,')',' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = to_projectCode)
					END) 'title_projectCode',
					(CASE
					  WHEN notify = 'creator' THEN (SELECT title_name FROM project_title  WHERE title_id = from_projectMain)
					  ELSE (SELECT title_name FROM project_title  WHERE title_id = to_projectMain)
					END) 'title_projectMain'
						FROM (SELECT
						*,
						(CASE
						   WHEN (to_projectCode = ".$this->session->userdata('Proj_Code')." AND to_projectMain = ".$this->session->userdata('Proj_Main').") THEN 'creator'
						   WHEN (from_projectCode = ".$this->session->userdata('Proj_Code')." AND from_projectMain = ".$this->session->userdata('Proj_Main').") THEN 'reciever'
						END)'notify'
						FROM transaction_history WHERE (to_projectCode = ".$this->session->userdata('Proj_Code')." AND to_projectMain = ".$this->session->userdata('Proj_Main').") OR (from_projectCode = ".$this->session->userdata('Proj_Code')." AND from_projectMain = ".$this->session->userdata('Proj_Main').") AND is_open = 'FALSE'
						ORDER BY id DESC
						) AS tbl						
		        ";

		$result = $this->db->query($sql1);		
		return $result->result_array();

	}


	public function get_approved_pr(){		
		$sql = "
				SELECT *,
				SUM((CASE `type`
				   WHEN 'Purchase Order' THEN 1
				   ELSE 0
				END))'uid'
				FROM purchaserequest_main pm
				INNER JOIN transaction_history th
				ON (pm.pr_id = th.reference_id)
				WHERE (th.status = 'APPROVED' OR th.status = 'CANCELLED' OR th.status = 'CLOSED')
				AND to_projectCode = '".$this->session->userdata('Proj_Code')."'
				AND to_projectMain = '".$this->session->userdata('Proj_Main')."'
				GROUP BY reference_id;
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function get_approved_po(){		
		$sql = "				
				
				SELECT 
				po.po_id,
				po.po_number,
				po.po_date,
				po.status 'po_status',
				th.status 'th_status',
				SUM((CASE `type`
				   WHEN 'Purchase Order' THEN 1
				   ELSE 0
				END))'uid'
				FROM purchase_order_main po
				INNER JOIN transaction_history th
				ON (po.pr_id = th.reference_id)
				WHERE 
				(th.status = 'APPROVED' OR th.status = 'CANCELLED' OR th.status = 'CLOSED')
				AND to_projectCode = '".$this->session->userdata('Proj_Code')."'
				AND to_projectMain = '".$this->session->userdata('Proj_Main')."'
				GROUP BY reference_id;				
		";
		
		$result = $this->db->query($sql);
		return $result->result_array();

	}





}