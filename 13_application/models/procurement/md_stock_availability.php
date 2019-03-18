<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_stock_availability extends CI_model {

	public function __construct(){
		parent :: __construct();		
	}


	function get_all_items(){

		$sql = "
			SELECT
			  setup_group_detail.group_detail_id       'ITEM NO',
			  setup_group.group_id,
			  setup_group.group_description            'ITEM CATEGORY',
			  setup_group_detail.description           'ITEM DESCRIPTION',
			  setup_group_detail.unit_measure          'UNIT',
			  classification_setup.id                  'CLASSIFICATION ID',
			  classification_setup.code                'CLASS CODE',
			  classification_setup.full_description    'CLASS DESCRIPTION'
			FROM setup_group_detail
			  INNER JOIN setup_group
			    ON (setup_group.group_id = setup_group_detail.group_id)
			  INNER JOIN classification_setup
			    ON (setup_group_detail.classification = classification_setup.id)
			  INNER JOIN receiving_details
			    ON (setup_group_detail.group_detail_id = receiving_details.item_id)    
			WHERE
			   setup_group_detail.description LIKE '%%'
			   AND setup_group_detail.group_id1 <> 2
			GROUP BY setup_group_detail.group_detail_id
			ORDER BY setup_group_detail.description;
			";
		
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	function get_availability($item_desc = "",$type){
		if($type=='stock'){
			$sql = "CALL items_display_availability_item('%".$item_desc."%')";			
		}else{
			$sql = "CALL items_display_availability_far('%".$item_desc."%')";			
		}
		
		$result = $this->db->query($sql);			
		$this->db->close();
		return $result;
	}


	function get_details($item_no){
		$sql = "CALL items_display_stockonhand1('".$item_no."')";
		/*$sql = "CALL items_display_stockonhand2('".$item_no."')";*/
		/*$sql = "CALL items_display_stockonhand1('2')";*/
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
		
	}

	function get_items(){
		$sql = "
				SELECT
				  setup_group_detail.group_detail_id       'ITEM NO',
				  setup_group.group_id,
				  setup_group.group_description            'ITEM CATEGORY',
				  setup_group_detail.description           'ITEM DESCRIPTION',
				  setup_group_detail.unit_measure          'UNIT MEASURE',
				  classification_setup.id                  'CLASSIFICATION ID',
				  classification_setup.code                'CLASS CODE',
				  classification_setup.full_description    'CLASS DESCRIPTION',
				  IFNULL(qty.qty_onhand,0)                 'qty_onhand'
				FROM setup_group_detail
				  INNER JOIN setup_group
				    ON (setup_group.group_id = setup_group_detail.group_id)
				  INNER JOIN classification_setup
				    ON (setup_group_detail.classification = classification_setup.id)
				  INNER JOIN receiving_details
				    ON (setup_group_detail.group_detail_id = receiving_details.item_id)
			       LEFT JOIN (
							SELECT
							  receiving_details.item_id              'item_id',
							  setup_project.title_id		 'TITLE ID',
							  (SELECT title_name FROM project_title WHERE title_id = setup_project.title_id) AS 'TITLE NAME',
							  setup_project.project_id               'PROJECT ID',
							  setup_project.project                  'PROJECT',
							  setup_project.project_name             'PROJECT NAME',
							  SUM(receiving_details.item_quantity_actual) - SUM(IFNULL(withdraw_details.withdrawn_quantity,0))    'qty_onhand'  
							FROM
							    `receiving_details`
							    INNER JOIN `purchase_order_main` 
								ON (`receiving_details`.`po_id` = `purchase_order_main`.`po_id`)
							    INNER JOIN `setup_project` 
								ON (`purchase_order_main`.`project_id` = `setup_project`.`project_id`) 
							    LEFT JOIN `withdraw_details` 
								ON (`receiving_details`.`item_id` = `withdraw_details`.`item_no`)
								AND (`purchase_order_main`.`project_id` = `withdraw_details`.project_location_id)
							WHERE
							   purchase_order_main.project_id = '".$this->session->userdata('Proj_Code')."'
							GROUP BY receiving_details.item_id		
			          ) qty
			          ON (qty.item_id = setup_group_detail.group_detail_id)	    
				WHERE
				   setup_group_detail.description LIKE '%%'
				   AND setup_group_detail.group_id1 <> 2
				GROUP BY setup_group_detail.group_detail_id
				ORDER BY setup_group_detail.description;
		";

		$result = $this->db->query($sql);			
		$this->db->close();
		return $result->result_array();


	}


	
		

}