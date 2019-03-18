<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_maintenance extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function get_equipment(){
		$sql = "
		  SELECT DISTINCT
		  (SELECT
		     group_detail_id
		   FROM setup_group_detail
		   WHERE group_detail_id = (SELECT
		                              equipment_itemno
		                            FROM db_equipmentlist
		                            WHERE equipment_id = db_equipment_id)) AS 'ID',
		  (SELECT
		     description
		   FROM setup_group_detail
		   WHERE group_detail_id = (SELECT
		                              equipment_itemno
		                            FROM db_equipmentlist
		                            WHERE equipment_id = db_equipment_id)) AS 'Equipment'
		FROM pm_jo_master
		#WHERE bd_start_date BETWEEN '2014-03-01'
		 #   AND '2014-06-19'
		ORDER BY `Equipment`
		";

		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function get_pending_jo_monthly(){

		$sql = "
			SELECT COUNT(*) 'cnt',DATE_FORMAT(transaction_date,'%M') 'date'
			FROM pm_jo_inhouse
			WHERE job_status = 'PENDING' 
			AND STATUS = 'ACTIVE'
			AND YEAR(transaction_date) = '2014'
			GROUP BY MONTH(transaction_date)
			UNION ALL 
			SELECT COUNT(*),'Total'
			FROM pm_jo_inhouse
			WHERE job_status = 'PENDING'
			AND YEAR(transaction_date) = '2014'
			AND STATUS = 'ACTIVE';
		";		
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function get_breakdown_history($arg){

		$sql = "
			SELECT
				transaction_date,
				COUNT(*) 'cnt'
				FROM (pm_jo_inhouse)
				INNER JOIN pm_jo_master
				ON (pm_jo_inhouse.jo_inhouse_id = pm_jo_master.jo_id)
				INNER JOIN pm_inhouse_scope
				ON (pm_inhouse_scope.pm_inhouse_id = pm_jo_inhouse.jo_inhouse_id)
				INNER JOIN pm_breakdown_setup
				ON (pm_breakdown_setup.id = pm_inhouse_scope.service_order)
				INNER JOIN db_equipmentlist
				ON (db_equipmentlist.equipment_id = pm_jo_master.db_equipment_id)
				INNER JOIN setup_group_detail
				ON (setup_group_detail.group_detail_id = db_equipmentlist.equipment_itemno)
				WHERE setup_group_detail.description = '".$arg['equipment']."'
				AND YEAR(transaction_date) = '2014'
				AND  pm_jo_master.job_status = 'COMPLETE'
				GROUP BY MONTH(transaction_date);
		";

		$result = $this->db->query($sql);
		return $result->result_array();
	}

		





	public function get_body_no($id){
		$sql = "SELECT equipment_id,equipment_brand FROM db_equipmentlist WHERE equipment_itemno = '".$id."'";
		$result = $this->db->query($sql);
		return $result->result_array();
	}

	
	public function get_jo(){

		$sql = "
				SELECT
		  pm_inhouse_scope.jo_inhouse_no   AS 'JO NUMBER',
		  transaction_date                 AS 'TRANS DATE',
		  (SELECT
		     equipment_brand
		   FROM db_equipmentlist
		   WHERE equipment_id = db_equipment_id) AS 'BODY NUMBER',
		  pm_jo_master.bd_start_time       AS 'BD START TIME',
		  pm_jo_master.bd_start_date       AS 'BD START DATE',
		  IF(pm_jo_master.job_status = 'PENDING','',pm_jo_master.bd_end_time) AS 'BD END TIME',
		  IF(pm_jo_master.job_status = 'PENDING','',pm_jo_master.bd_end_date) AS 'BD END DATE',
		  pm_jo_inhouse.requestor_complain AS 'REPAIR DESCRIPTION',
		  pm_breakdown_setup.type          AS 'COMPONENT TYPE',
		  pm_jo_master.job_status          AS 'STATUS',
		  pm_jo_inhouse.remarks            AS 'REMARKS'
		FROM (pm_jo_inhouse)
		  INNER JOIN pm_jo_master
		    ON (pm_jo_inhouse.jo_inhouse_id = pm_jo_master.jo_id)
		  INNER JOIN pm_inhouse_scope
		    ON (pm_inhouse_scope.pm_inhouse_id = pm_jo_inhouse.jo_inhouse_id)
		  INNER JOIN pm_breakdown_setup
		    ON (pm_breakdown_setup.id = pm_inhouse_scope.service_order)
		WHERE transaction_date BETWEEN '2014-06-18'
		    AND '2014-06-19'
		    AND pm_jo_master.job_status <> 'CANCELLED'
		    AND db_equipment_id LIKE '%'
		    AND pm_breakdown_setup.type LIKE '%'
		GROUP BY pm_jo_inhouse.jo_inhouse_id
		ORDER BY `TRANS DATE` DESC,`JO NUMBER`,`BODY NUMBER`,`COMPONENT TYPE`;
		";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result;
	}	



	public function get_jo_summary($arg){

		$sql = "CALL _jo_monitoring_graph_all('2','".$arg['from']."','".$arg['to']."','','".$arg['equipment']."%')";
		$result = $this->db->query($sql);				
		$this->db->close();
		return $result->result_array();

	}

	public function get_jo_breakdown($arg){
		$arg['body_no'] = ($arg['body_no']=="ALL")? '' : $arg['body_no'] ;

		$sql = "CALL _JO_GRAPH_COM ('".$arg['from']."','".$arg['to']."','".$arg['body_no']."%','".$arg['equipment']."','%')";
		$result =  $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
		
	}

	public function get_jo_pending(){
		$date = date('Y-m-d');
		$sql = "CALL display_jo_monitoring_search('2','2014-03-01','".$date."','%','%%','PENDING','1','4','%')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}


	public function count_jo_pending(){
		$result = $this->get_jo_pending();		
		return count($result->result_array());
	}


	public function get_mech_availability($date){

		//$sql = "CALL display_maintenance_report5('".$date."');";
		$sql = "CALL display_maintenance_ben('".$date."');";		
		$result = $this->db->query($sql);			
		$this->db->close();
		return $result->result_array();

	}

	public function get_range_mech(){
		$sql = "CALL display_maintenance_ben('2014-06-25');";
	}



	/****/

	public function get_totalcomplete_jo(){		
		$sql = "
				SELECT 
				COUNT(*) 'cnt'
				FROM 
				pm_jo_master
				WHERE job_status = 'COMPLETE';";
		$result = $this->db->query($sql);
		$row = $result->row_array();
		return $row['cnt'];
	}

	public function get_today_complete($arg){
		$sql = "SELECT COUNT(*) 'cnt' FROM pm_jo_master WHERE job_status = 'COMPLETE' AND DATE(date_completed) = '".$arg['date']."'";
		$result = $this->db->query($sql);		
		$row = $result->row_array();
		return $row['cnt'];
	}
	
	public function get_maintenance($arg){
		
		$sql = "CALL display_maintenance_ben('".$arg['date']."');";
		$result = $this->db->query($sql);		
		$this->db->close();		
		return $result->result_array();
		
	}


	public function get_ma_ben($arg){

		$sql = "CALL get_ma_ben('".$arg['equipment']."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}




}