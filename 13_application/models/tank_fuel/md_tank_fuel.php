<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_tank_fuel extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	
	public function fuel_monthly($arg){

		$sql = "CALL _display_fuel_monthly('".$arg['date_from']."','".$arg['date_to']."');";
		$result = $this->db->query($sql);
		$this->db->close();
		$row = $result->row_array();
		if(count($row)>0){				
				$result = $this->db->query($row['RANS']);		
				return $result;
		}else{
				return false;
		}

	}




	public function get_equipment_fuel($arg){

		$sql = "CALL _EQUIPMENT_AVE_FUEL ('".$arg['date_from']."','".$arg['date_to']."','%%','%%','%');";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function get_equipment_fuel_detail($arg){

		$sql = "CALL _EQUIPMENT_AVE_FUEL_dtl('".$arg['date_from']."','".$arg['date_to']."','".$arg['body_no']."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}



	public function get_production_equipment($arg){
		$sql = "SELECT 
				DATE_FORMAT(trans_date,'%M-%d') 'date',
				production,
				SUM(truck_factor) 'wmt'
				FROM view_area_delivery
				WHERE equipment_brand = '".$arg['body_no']."'
				    AND trans_date BETWEEN ('".$arg['date_from']."' - INTERVAL 1 DAY) AND '".$arg['date_to']."'
				    GROUP BY production,trans_date
				    ORDER BY trans_date ASC;";

		$result = $this->db->query($sql);		
		$this->db->close();
		return $result->result_array();

	}


	public function get_fuel_withdrawal($arg){

		$sql = "CALL _DISPLAY_FUEL_WITHDRAWAL ('".$arg['date_from']."','".$arg['date_to']."','%%%%','%%','%%','%%','%');";
		$result = $this->db->query($sql);		
		$this->db->close();
		return $result->result_array();

	}

	public function get_total($arg){

		/*
		$output = array();
		$sql = "CALL _GET_FWS('".$arg['date_from']."','".$arg['date_to']."')";
		$result = $this->db->query($sql);
		$result->row_array();		
		$this->db->close();

		$sql = "CALL _GET_FTS('".$arg['date_from']."','".$arg['date_to']."')";
		$result = $this->db->query($sql);
		$output['FTS'] = $result->row_array();
		$this->db->close();

		$sql = "CALL _GET_FTD('".$arg['date_from']."','".$arg['date_to']."')";
		$result = $this->db->query($sql);
		$output['FTD'] = $result->row_array();
		$this->db->close();

		$sql = "CALL _GET_ALL_FUEL('".$arg['date_from']."','".$arg['date_to']."')";
		$result = $this->db->query($sql);
		$output['ALL'] = $result->row_array();
		$this->db->close();
		*/

		$sql = "
			SELECT 
			IFNULL(SUM((CASE
			  WHEN `REQUISITION NO.` LIKE '%FWS%' THEN QTY
			END)),0) 'FWS',
			IFNULL(SUM((CASE
			  WHEN `REQUISITION NO.` LIKE '%FTS%' THEN QTY
			END)),0) 'FTS',
			IFNULL(SUM((CASE
			  WHEN `REQUISITION NO.` LIKE '%FTD%' THEN QTY
			END)),0) 'FTD',
			IFNULL(SUM(QTY),0) 'TOTAL'
			FROM (
			SELECT
			    `setup_tank`.`tank_description`'TANK DESCRIPTION'
			    , `requisition_table`.`requisition_number`'REQUISITION NO.'
			    , `requisition_table`.`request_date`'DATE WITHDRWAN'
			    , IF(IF(`business_list`.`business_name`='PLATINUM GROUP METALS CORPORATION','INHOUSE',`business_list`.`business_name`)='FRASEC VENTURE CORPORATION','INHOUSE',IF(`business_list`.`business_name`='PLATINUM GROUP METALS CORPORATION','INHOUSE',`business_list`.`business_name`))AS'OWNER'
			     , `db_equipmentlist`.`equipment_brand`'UNIT'
			    , `requisition_details`.`tank_withdrawn_amount`'QTY'
			    , `requisition_table`.`shift`'SHIFT'
			    ,requisition_table.EXISTING_SMR AS'SMR'
			    ,requisition_table.EXISTING_KMR AS'KMR'
			    ,requisition_table.reference AS'REF NO.'
			    , IF(IF(IF(`business_list`.`business_name`='PLATINUM GROUP METALS CORPORATION','INHOUSE',`business_list`.`business_name`)='FRASEC VENTURE CORPORATION','INHOUSE',IF(`business_list`.`business_name`='PLATINUM GROUP METALS CORPORATION','INHOUSE',`business_list`.`business_name`))!='INHOUSE','SUBCON',IF(IF(`business_list`.`business_name`='PLATINUM GROUP METALS CORPORATION','INHOUSE',`business_list`.`business_name`)='FRASEC VENTURE CORPORATION','INHOUSE',IF(`business_list`.`business_name`='PLATINUM GROUP METALS CORPORATION','INHOUSE',`business_list`.`business_name`)))AS'TYP'
			    ,requisition_table.remarks AS'REMARKS'	
			FROM
			    `qpsii_constsystem`.`requisition_details`
			    INNER JOIN `qpsii_constsystem`.`setup_tank` 
			        ON (`requisition_details`.`tank_id` = `setup_tank`.`tank_id`)
			    INNER JOIN `qpsii_constsystem`.`requisition_table` 
			        ON (`requisition_table`.`requisition_id` = `requisition_details`.`requisition_id`)
			    INNER JOIN `qpsii_constsystem`.`db_equipmentlist` 
			        ON (`db_equipmentlist`.`equipment_id` = `requisition_table`.`equipment_id`)
			    INNER JOIN `qpsii_constsystem`.`inventory_main` 
			        ON (`db_equipmentlist`.`inventory_id` = `inventory_main`.`inventory_id`)
			    INNER JOIN `qpsii_constsystem`.`business_list` 
			        ON (`business_list`.`business_number` = `inventory_main`.`supplier_id`)
			        WHERE `requisition_table`.`request_date` 
			        BETWEEN '".$arg['date_from']."' AND '".$arg['date_to']."'        
			        GROUP BY requisition_table.REQUISITION_ID ) A
		
		";

		$result = $this->db->query($sql);
		$output = $result->row_array();
		return $output;

	}



	public function tank_fuel_monitoring($arg){

		$sql = "CALL _DISPLAY_FUEL_TANK_INVENTORYperday('".$arg['date']."');";
		$result = $this->db->query($sql);
		return $result->result_array();			
		
	}


	public function get_tank_fuel_monitoring($arg){
		$sql = " 
			SELECT 
			IF(setup_tank.team='10',CONCAT(`setup_tank`.`tank_description`,' (Petron)'),IF(setup_tank.team='12',CONCAT(`setup_tank`.`tank_description`,' (Phoenix)'),CONCAT(`setup_tank`.`tank_description`,' (FuelTruck)'))) AS 'TANK DESCRIPTION'
			,FORMAT(MAR2.BR - MAR3.BW ,2) AS 'BEGINING'
			#,FORMAT(MAR2.BR ,2) AS 'BEGINING_received'
			  # ,FORMAT(MAR3.BW ,2) AS 'BEGINING_WITHDRAWN'
			     ,FORMAT(IFNULL(mar1.RCD,0),2)AS'RECEIVED'
			   ,FORMAT(IFNULL(MAR.WD,0),2) AS 'WITHDRAWN'
			  # ,FORMAT(IF(`setup_tank`.`tank_description` LIKE '%FT-05%',`setup_tank_details`.`received_quantity`- MAR.WD,IFNULL((MAR2.BR - MAR3.BW),0)+(SUM(`setup_tank_details`.`received_quantity`))- MAR.WD),2) AS 'BALANCE'
			  ,FORMAT((IFNULL(MAR2.BR,0) - IFNULL(MAR3.BW,0) +IFNULL((mar1.RCD),0)- IFNULL(MAR.WD,0) ),2)AS 'ENDING_BALANCE'
			 			 
			FROM setup_tank  INNER JOIN `qpsii_constsystem`.`setup_tank_details` 
			        ON (`setup_tank`.`tank_id` = `setup_tank_details`.`tank_id`)
			LEFT JOIN(
			SELECT
			setup_tank.tank_id 'ID'
			     ,SUM(`setup_tank_details`.`received_quantity` )AS'RCD'
			     FROM
			    `qpsii_constsystem`.`setup_tank`
			    INNER JOIN `qpsii_constsystem`.`setup_tank_details` 
			        ON (`setup_tank`.`tank_id` = `setup_tank_details`.`tank_id`)   WHERE `setup_tank_details`.`date_saved` = '".$arg['date']."' GROUP BY setup_tank.tank_id
			        
			     )   AS mar1 ON mar1.ID=setup_tank.tank_id
			       
			     LEFT JOIN( SELECT
			   `setup_tank`.`tank_id` 'ID'
			    ,IFNULL(SUM(`setup_tank_details`.`received_quantity`),0)AS'BR'
			     FROM
			    `qpsii_constsystem`.`setup_tank`
			    INNER JOIN `qpsii_constsystem`.`setup_tank_details` 
			        ON (`setup_tank`.`tank_id` = `setup_tank_details`.`tank_id`)  WHERE DATE(`setup_tank_details`.`date_saved`) < DATE('".$arg['date']."') GROUP BY setup_tank.tank_id
			        )AS MAR2 ON MAR2.ID=setup_tank.tank_id  
			        
			        
			        LEFT JOIN(SELECT
			   `setup_tank`.`tank_id` 'ID'
			,IFNULL(SUM(`requisition_details`.`tank_withdrawn_amount`),0)AS'WD'
			FROM
			    `qpsii_constsystem`.`requisition_table`
			    INNER JOIN `qpsii_constsystem`.`requisition_details` 
			        ON (`requisition_table`.`requisition_id` = `requisition_details`.`requisition_id`)
			    INNER JOIN `qpsii_constsystem`.`setup_tank` 
			        ON (`requisition_details`.`tank_id` = `setup_tank`.`tank_id`) WHERE  
			     DATE(`requisition_table`.`request_date`) = DATE('".$arg['date']."')  AND requisition_details.type!='truck' GROUP BY setup_tank.tank_id  
			        ) AS MAR ON MAR.ID=setup_tank.tank_id
			        
			           
			        LEFT JOIN(SELECT
			   `setup_tank`.`tank_id` 'ID'
			,IFNULL(SUM(`requisition_details`.`tank_withdrawn_amount`),0)AS'BW'
			FROM
			    `qpsii_constsystem`.`requisition_table`
			    INNER JOIN `qpsii_constsystem`.`requisition_details` 
			        ON (`requisition_table`.`requisition_id` = `requisition_details`.`requisition_id`)
			    INNER JOIN `qpsii_constsystem`.`setup_tank` 
			        ON (`requisition_details`.`tank_id` = `setup_tank`.`tank_id`) WHERE  
			       DATE(`requisition_table`.`request_date`) < DATE('".$arg['date']."')  AND requisition_details.type!='truck' GROUP BY setup_tank.tank_id	
			        ) AS MAR3 ON MAR3.ID=setup_tank.tank_id
			    WHERE( team='10' OR team='12' OR team='13') GROUP BY setup_tank.tank_id 
           ";

           $result = $this->db->query($sql);
		   return $result->result_array();


	}

	public function get_tank_fuel_total($arg){

		$sql = "
			SELECT 
			IF(setup_tank.team='10','PETRON',IF(setup_tank.team='12','PHOENIX','FUEL TRUCK')) AS 'TANK DESCRIPTION'
			,FORMAT((MAR2.BR - MAR3.BW),2) AS 'BEGINING'
			   #,FORMAT(MAR3.BW + MAR.WD,2) AS 'BEGINING_WITHDRAWN'
			     ,FORMAT(IFNULL(mar1.RCD,0),2)AS'MONTH_RECEIVED'
			   ,FORMAT(IFNULL(MAR.WD,0),2) AS 'MONTH_WITHDRAWN'
			  # ,FORMAT(IF(`setup_tank`.`tank_description` LIKE '%FT-05%',`setup_tank_details`.`received_quantity`- MAR.WD,IFNULL((MAR2.BR - MAR3.BW),0)+(SUM(`setup_tank_details`.`received_quantity`))- MAR.WD),2) AS 'BALANCE'
			   ,FORMAT((IFNULL(MAR2.BR,0) - IFNULL(MAR3.BW,0) +IFNULL((mar1.RCD),0)- IFNULL(MAR.WD,0)),2)AS 'BALANCE'
			FROM setup_tank 
			LEFT JOIN(
			SELECT
			setup_tank.team 'ID'
			     ,IFNULL(SUM(`setup_tank_details`.`received_quantity`),0)AS'RCD'
			     FROM
			    `qpsii_constsystem`.`setup_tank`
			    INNER JOIN `qpsii_constsystem`.`setup_tank_details` 
			        ON (`setup_tank`.`tank_id` = `setup_tank_details`.`tank_id`)   
			        WHERE DATE(`setup_tank_details`.`date_saved`) = DATE('".$arg['date']."') GROUP BY setup_tank.team
			        
			     )   AS mar1 ON mar1.ID=setup_tank.team
			        			        
			        LEFT JOIN( SELECT
			   `setup_tank`.`team` 'ID'
			    ,IFNULL(SUM(`setup_tank_details`.`received_quantity`),0)AS'BR'
			     FROM
			    `qpsii_constsystem`.`setup_tank`
			    INNER JOIN `qpsii_constsystem`.`setup_tank_details` 
			        ON (`setup_tank`.`tank_id` = `setup_tank_details`.`tank_id`)  WHERE DATE(`setup_tank_details`.`date_saved`) < DATE('2014-09-17') GROUP BY setup_tank.team
			        )AS MAR2 ON MAR2.ID=setup_tank.team 
			        
			        
			        LEFT JOIN(SELECT
			   `setup_tank`.`team` 'ID'
			,IFNULL(SUM(`requisition_details`.`tank_withdrawn_amount`),0)AS'WD'
			FROM
			    `qpsii_constsystem`.`requisition_table`
			    INNER JOIN `qpsii_constsystem`.`requisition_details` 
			        ON (`requisition_table`.`requisition_id` = `requisition_details`.`requisition_id`)
			    INNER JOIN `qpsii_constsystem`.`setup_tank` 
			        ON (`requisition_details`.`tank_id` = `setup_tank`.`tank_id`) WHERE  
			      DATE(`requisition_table`.`request_date`) = DATE('".$arg['date']."')  AND requisition_details.type!='truck' GROUP BY setup_tank.team  
			        ) AS MAR ON MAR.ID=setup_tank.team
			        			           
			        LEFT JOIN(SELECT
			   `setup_tank`.`team` 'ID'
			,IFNULL(SUM(`requisition_details`.`tank_withdrawn_amount`),0)AS'BW'
			FROM
		    `qpsii_constsystem`.`requisition_table`
		    INNER JOIN `qpsii_constsystem`.`requisition_details` 
		        ON (`requisition_table`.`requisition_id` = `requisition_details`.`requisition_id`)
		    INNER JOIN `qpsii_constsystem`.`setup_tank` 
		        ON (`requisition_details`.`tank_id` = `setup_tank`.`tank_id`) WHERE  
		       DATE(`requisition_table`.`request_date`) < DATE('".$arg['date']."')  AND requisition_details.type!='truck' GROUP BY setup_tank.team
		                
		        ) AS MAR3 ON MAR3.ID=setup_tank.team
		           #   WHERE MONTH(`setup_tank_details`.`date_saved`) = MONTH('2014-08-31') GROUP BY setup_tank.team;
		     WHERE( team='10' OR team='12' OR team='13') GROUP BY setup_tank.team
     				
		";		
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function fuel_transaction($arg){

		$sql = "CALL _DISPLAY_FUEL_WITHDRAWAL ('".$arg['date']."','".$arg['date']."','%','%','%','%','%')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function get_received_fuel($arg){

		$sql = "CALL display_tank_delivery('".$arg['date']."','".$arg['date']."','%');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
		
	}





}