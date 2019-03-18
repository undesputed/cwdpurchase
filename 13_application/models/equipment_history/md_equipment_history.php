<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_equipment_history extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	
	public function get_inhouse_equipment(){
		$sql = "
			SELECT	
			DISTINCT del.equipment_brand,
			del.equipment_itemno,
			sgd.description
			FROM db_equipmentlist del
			INNER JOIN setup_group_detail sgd
			ON (sgd.group_detail_id = del.equipment_itemno)		
			INNER JOIN inventory_main im
			ON (im.inventory_id = del.inventory_id)
			INNER JOIN project_scope_setup pss
			ON (pss.sub_con_id = im.supplier_id)
			INNER JOIN project_scope_equipment pse
			ON (pse.db_equipment_id = del.equipment_id)
			AND pss.type = 'FVC'
			AND del.active = 'YES'
		";
		
		$result = $this->db->query($sql);
		return $result;
		
	}

	public function get_inhouse_equipment_2(){
		$sql = "
			SELECT
			IF(sgd.description='HOWO DT','DUMP TRUCK',sgd.description)'description',		
			del.equipment_brand
			FROM db_equipmentlist del
			INNER JOIN setup_group_detail sgd
			ON (sgd.group_detail_id = del.equipment_itemno)
			INNER JOIN project_scope_equipment pse
			ON (pse.db_equipment_id = del.equipment_id)
			INNER JOIN project_scope_setup pss
			ON (pss.scope_id = pse.scope_id)
			WHERE 
			pse.status = 'ACTIVE'
			AND pss.type = 'FVC'
			AND pss.status = 'ACTIVE'
			ORDER BY description,SUBSTRING(equipment_brand,INSTR(equipment_brand,'-')+1)*1;
		";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function equipment_productivity($arg)
	{
		$sql = "
			SELECT 
			trans_date,
			COUNT(*) 'trips',
			SUM(truck_factor) 'wmt'
			FROM view_area_delivery
			WHERE 
			equipment_brand = '".$arg['unit']."'
			AND trans_date BETWEEN '".$arg['date_from']."'
			AND '".$arg['date_to']."'
			GROUP BY trans_date;
		";		
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function equipment_breakdown($arg){

		$year = $this->config->item('year');
		$sql = "
		SELECT 
		DATE_FORMAT(transaction_date,'%M') 'month',
		SUM(IF(pm_jo_master.job_status = 'COMPLETE',1,0)) 'complete',
		COUNT(*) 'total'
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
		WHERE 
		db_equipmentlist.equipment_brand = '".$arg['unit']."'
		AND YEAR(transaction_date) = '".$year."'
		AND  pm_jo_master.job_status <> 'CANCELLED'
		GROUP BY MONTH(transaction_date);
		";

		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function fixed_asset()
	{
		$sql = "CALL items_display_availability_far('%')";
		$result = $this->db->query($sql);
		$this->db->close();

		$group = array();
		$cnt = 0;
		foreach($result->result_array() as $row)
		{
			$group[$cnt] = $row;
			$group[$cnt]['cnt'] = $this->count_equipment($row['ITEM NO']);
			
			$cnt++;
		}		
		return $group;

	}
		

	public function get_equipment_list($arg){

		$sql = "
			SELECT
			IF(sgd.description='HOWO DT','DUMP TRUCK',sgd.description)'description',		
			del.equipment_brand,
			equipment_status
			FROM db_equipmentlist del
			INNER JOIN setup_group_detail sgd
			ON (sgd.group_detail_id = del.equipment_itemno)
			INNER JOIN project_scope_equipment pse
			ON (pse.db_equipment_id = del.equipment_id)
			INNER JOIN project_scope_setup pss
			ON (pss.scope_id = pse.scope_id)
			WHERE 
			pse.status = 'ACTIVE'
			AND pss.type = 'FVC'
			AND pss.status = 'ACTIVE'
			AND sgd.group_detail_id = '".$arg['id']."'
			ORDER BY description,SUBSTRING(equipment_brand,INSTR(equipment_brand,'-')+1)*1;
		";
		$result = $this->db->query($sql);

		return $result->result_array();

	}

	public function count_equipment($id)
	{
		$sql = "
				SELECT
				COUNT(*) 'cnt'	
				FROM db_equipmentlist del
				INNER JOIN setup_group_detail sgd
				ON (sgd.group_detail_id = del.equipment_itemno)
				INNER JOIN project_scope_equipment pse
				ON (pse.db_equipment_id = del.equipment_id)
				INNER JOIN project_scope_setup pss
				ON (pss.scope_id = pse.scope_id)
				WHERE 
				pse.status = 'ACTIVE'
				AND pss.type = 'FVC'
				AND pss.status = 'ACTIVE'
				AND sgd.group_detail_id = '".$id."'
				ORDER BY description,SUBSTRING(equipment_brand,INSTR(equipment_brand,'-')+1)*1
		";

		$result = $this->db->query($sql);
		$data = $result->row_array();
		return $data['cnt'];

	}
	
	







}