<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_item_setup extends CI_Model {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('procurement/md_inventory_master');		
	}

	public function get_item_group(){
		$sql = "
			SELECT group_id, group_description FROM setup_group;
		";		
		$result = $this->db->query($sql);
		return $result->result_array();
	}

	public function get_items(){

		$sql = "
			SELECT 
			*,
			CONCAT(group_description ,' - ',description) 'item_description'
			FROM setup_group_detail sgd
			INNER JOIN setup_group sg
			ON (sg.group_id = sgd.group_id)
			WHERE item_status = 'ACTIVE';
		";
		
		$result = $this->db->query($sql);
		return $result->result_array();
	}


	public function save_item(){
		if($this->input->post('id')){
			$insert = array(
			 'group_id'=>$this->input->post('group_id'),
             'description'=>$this->input->post('description'),
             'quantity'=>$this->input->post('quantity'),             
             'unit_measure'=>$this->input->post('unit_measure'),             
             'title_id'=>$this->session->userdata('Proj_Main'),
             'account_id'=>$this->input->post('account_id'),
             'classification'=>$this->input->post('classification_id'),
			);
			$this->db->where('group_detail_id',$this->input->post('id'));
			$this->db->update('setup_group_detail',$insert);
					    
			/*$arg['item_no'] = $this->input->post('id');
			$arg['item_description'] = $this->input->post('description');
			$arg['item_measurement'] = $this->input->post('unit_measure');
			$arg['received_quantity'] = $this->input->post('quantity');	
			
			if($this->has_dr($arg)){

				$where['item_no'] = $this->input->post('id');				
				$this->save_inventory($arg);				
				$data = $this->return_dr($arg);

				$m['receive_qty'] = $data['balance'];
				$m['item_no'] = $this->input->post('id');
				$m['location_id'] = $this->session->userdata('Proj_Code');

				$this->md_inventory_master->undo($m);
				
			}else{

				$m['receive_qty'] = $arg['received_quantity'];
			 	$m['item_no']     = $arg['item_no'];
			 	$m['location_id'] = $this->session->userdata('Proj_Code');
				$this->md_inventory_master->received($m);
				$this->save_inventory($arg);
			}
			*/		
			
		}else{

			$insert = array(
			 'group_id'=>$this->input->post('group_id'),
             'description'=>$this->input->post('description'),
             'quantity'=>$this->input->post('quantity'),             
             'unit_measure'=>$this->input->post('unit_measure'),             
             'title_id'=>$this->session->userdata('Proj_Main'),
             'unit_cost'=>'0',
             'account_id'=>$this->input->post('account_id'),
             'classification'=>$this->input->post('classification_id'),
			);

			$this->db->insert('setup_group_detail',$insert);
			$last_id = $this->db->insert_id();

			/*$arg['item_no'] = $last_id;
			$arg['item_description'] = $this->input->post('description');
			$arg['item_measurement'] = $this->input->post('unit_measure');
			$arg['received_quantity'] = $this->input->post('quantity');	

			if($this->has_dr($arg)){

				$data = $this->return_dr($arg);
				$where['item_no'] = $this->input->post('id');

				$m['receive_qty'] = $data['balance'];
				$m['item_no']     = $this->input->post('id');
				$m['location_id'] = $this->session->userdata('Proj_Code');
				$this->md_inventory_master->undo($m);				
				$this->save_inventory($arg);

			}else{

				$this->save_inventory($arg);
				$m['receive_qty'] = $arg['received_quantity'];
			 	$m['item_no']     = $arg['item_no'];
			 	$m['location_id'] = $this->session->userdata('Proj_Code');
				$this->md_inventory_master->received($m);

			}*/	
		}
		return true;
		
	}


	public function save_beginning(){
						
			$arg['item_no'] = $this->input->post('item_no');
			$arg['item_description'] = $this->input->post('item_description');
			$arg['item_measurement'] = $this->input->post('item_measurement');
			$arg['received_quantity'] = $this->input->post('quantity');	
			
			if($this->has_dr($arg)){
				$this->save_inventory($arg);
				$data = $this->return_dr($arg);						
				$m['receive_qty'] = $data['balance'];
				$m['item_no']     = $this->input->post('item_no');
				$m['location_id'] = $this->session->userdata('Proj_Code');
				$m['project_id'] = $this->session->userdata('Proj_Main');
				$this->md_inventory_master->undo($m);		
				
			}else{

				$this->save_inventory($arg);
				$m['receive_qty'] = $arg['received_quantity'];
			 	$m['item_no']     = $arg['item_no'];
			 	$m['location_id'] = $this->session->userdata('Proj_Code');
			 	$m['project_id']  = $this->session->userdata('Proj_Main');
			 	
				$this->md_inventory_master->received($m);

			}

			return true;
		


	}

	public function has_dr($arg){

		$sql = "
			select inventory_id from inventory_main
				where item_no = '".$arg['item_no']."'
				and `type`='DR'
				and project_location_id = '".$this->session->userdata('Proj_Code')."'
				and title_id = '".$this->session->userdata('Proj_Main')."'
		";

		$result = $this->db->query($sql);
		if($result->num_rows() > 0)
		{
			return true;
		}else{
			return false;
		}

	}

	public function return_dr($arg){

		/*
		 	$sql = "
			select * from inventory_main
			where item_no = '".$arg['item_no']."'
			and `type`='DR'
			and project_location_id = '".$this->session->userdata('Proj_Code')."'
			and title_id = '".$this->session->userdata('Proj_Main')."'
			ORDER BY inventory_id DESC LIMIT 1			
		";
		*/

		$sql = "
			SELECT 
			SUM(quantity) 'balance'
			FROM 
			(
				SELECT
				SUBSTRING(rm.receipt_no,1,2) 'code',
				rd.item_id 'item_no',
				rd.item_quantity_actual 'quantity'
				FROM receiving_main rm
				INNER JOIN receiving_details rd
				ON (rm.receipt_id = rd.receipt_id)
				WHERE rm.project_id = '".$this->session->userdata('Proj_Code')."' 
				AND rd.item_id = '".$arg['item_no']."'
				UNION ALL
				SELECT 
				 'DR',
				 im.item_no 'item_no',
				 im.received_quantity 'quantity'
				FROM inventory_main im
				WHERE item_no = '".$arg['item_no']."'
				AND TYPE = 'DR'
				AND project_location_id = '".$this->session->userdata('Proj_Code')."'
				AND inventory_id = (SELECT MAX(inventory_id) FROM inventory_main WHERE item_no = '".$arg['item_no']."'
				AND TYPE = 'DR'
				AND project_location_id = '".$this->session->userdata('Proj_Code')."')	
			) a	
		";


		/*$sql = "
			SELECT 
			(CASE 
			    WHEN `code`='ws' THEN @curRow - quantity
			    WHEN `code`='TRB' THEN @curRow - quantity
			    ELSE @curRow := @curRow + quantity
			END) 'balance',
			@curPoint:= @curPoint + 1 'rank'
			FROM 
			(
				SELECT 
				 'DR' AS 'code',
				 im.item_no 'item_no',
				 im.received_quantity 'quantity'
				FROM inventory_main im
				WHERE item_no = '{$arg['item_no']}'
				AND TYPE = 'DR'
				AND project_location_id = '{$this->session->userdata('Proj_Code')}'
				AND inventory_id = (SELECT MAX(inventory_id) FROM inventory_main WHERE item_no = '{$arg['item_no']}'
				AND TYPE = 'DR'
				AND project_location_id = '{$this->session->userdata('Proj_Code')}')
				UNION ALL
				SELECT
				SUBSTRING(rm.receipt_no,1,2) 'code',
				rd.item_id 'item_no',
				rd.item_quantity_actual 'quantity'
				FROM receiving_main rm
				INNER JOIN receiving_details rd
				ON (rm.receipt_id = rd.receipt_id)
				WHERE rm.project_id = '{$this->session->userdata('Proj_Code')}' 
				AND rd.item_id = '{$arg['item_no']}'
				UNION ALL	
				SELECT 
				'WS',
				item_no,
				withdrawn_quantity
				FROM withdraw_main
				INNER JOIN withdraw_details
				WHERE project_location_id = '{$this->session->userdata('Proj_Code')}'
				AND withdraw_main.`status` = 'ACTIVE'
				UNION ALL
				SELECT 
				'TRA',
				item_no, 
				request_qty
				FROM
				item_transfer_main
				INNER JOIN item_transfer_details
				WHERE request_status ='APPROVED'
				AND to_project_id='{$this->session->userdata('Proj_Code')}'
				UNION ALL
				SELECT 
				'TRB',
				item_no, 
				request_qty
				FROM
				item_transfer_main
				INNER JOIN item_transfer_details
				WHERE request_status ='APPROVED'
				AND project_id='{$this->session->userdata('Proj_Code')}'	
			) b,(SELECT @curRow :=0,@curPoint:=0)z 
			ORDER BY rank DESC
			LIMIT 1
		";*/

		$result = $this->db->query($sql);				
		return $result->row_array();		
	}

	public function save_inventory($arg){

		$insert = array(
			'item_no'=>$arg['item_no'],
			'item_description'=>$arg['item_description'],
			'item_measurement'=>$arg['item_measurement'],
			'supplier_id' => '0',
			'received_quantity' =>$arg['received_quantity'],
			'withdrawn_quantity' =>'0',
			'receipt_no' =>'0',
			'withdraw_no'=>'0',
			'registered_no'=>'',
			'account_code'=>'0',
			'project_location_id'=>$this->session->userdata('Proj_Code'),
			'title_id'=>$this->session->userdata('Proj_Main'),
			'TYPE' =>'DR',
			'property_no'=>'',
			'remarks1'=>'',
			'prepared_by'=>$this->session->userdata('emp_id'),
			);
		$this->db->insert('inventory_main',$insert);

	}

	public function update_inventory($arg,$where){

		$insert = array(			
			'item_description'=>$arg['item_description'],
			'item_measurement'=>$arg['item_measurement'],
			'supplier_id' => '0',
			'received_quantity' =>$arg['received_quantity'],
			'withdrawn_quantity' =>'0',
			'receipt_no' =>'0',
			'withdraw_no'=>'0',
			'registered_no'=>'',
			'account_code'=>'0',
			'project_location_id'=>$this->session->userdata('Proj_Code'),
			'title_id'=>$this->session->userdata('Proj_Main'),
			'TYPE' =>'DR',
			'property_no'=>'',
			'remarks1'=>'',
			);

		$this->db->where('item_no',$where['item_no']);
		$this->db->where('type','DR');
		$this->db->where('project_location_id',$this->session->userdata('Proj_Code'));
		$this->db->where('title_id',$this->session->userdata('Proj_Main'));
		$this->db->update('inventory_main',$insert);
		
	}


	public function get_cumulative(){

		$sql = "
			SELECT 
			*,
			(SELECT group_description FROM setup_group WHERE group_id = setup_group_detail.group_id ) 'group_description'
			FROM setup_group_detail 
			WHERE item_status = 'active';
		";
		$result = $this->db->query($sql);
		return $result;

	}

	public function save_item_group($arg){

		$insert = array(
			'group_description'=>$arg['group_name']			
			);		
		$this->db->insert('setup_group',$insert);
		return true;

	}

	public function save_group($arg){

		if(isset($arg['update']))
		{

			$update = array(
				'setup_group_head'=>""
				);
			$this->db->where('setup_group_head',$arg['update']);
			$this->db->update('setup_group',$update);

			foreach($arg['item_list'] as $row)
			{

				$insert = array(
					'setup_group_head'=>$arg['parent_group']
				);
				$this->db->where('group_id',$row['id']);
				$this->db->update('setup_group',$insert);
			}


		}else
		{
			foreach($arg['item_list'] as $row){

				$insert = array(
					'setup_group_head'=>$arg['parent_group']
				);
				$this->db->where('group_id',$row['id']);
				$this->db->update('setup_group',$insert);

			}		
		}
		
		
		
		return true;
		
	}

	public function update_item_group($arg){

		foreach($arg['item_list'] as $row){

			$insert = array(
				'setup_group_head'=>$arg['group_name'],
				''
				);
			$this->db->where('group_id',$arg['group_id']);
			$this->db->update('setup_group',$insert);

		}
	
		return true;

	}

	public function delete($id){
		$sql = "UPDATE setup_group_detail SET `item_status`='CANCELLED' WHERE group_detail_id = '$id'";
		$this->db->query($sql);
		return true;
	}


	public function get_setup_group_head(){

		$sql = "SELECT 
				* 
				FROM setup_group
				WHERE setup_group_head IS NOT NULL AND setup_group_head <>' '
				GROUP BY setup_group_head;
			";
		$result = $this->db->query($sql);
		return $result->result_array();

	}



	public function remove_group($arg){
		$update = array(
			'setup_group_head'=>' ',
			);
		$this->db->where('setup_group_head',$arg['name']);
		$this->db->update('setup_group',$update);
		return true;
	}



	public function get_head($head){

		$sql = "SELECT * FROM setup_group WHERE setup_group_head = '{$head}'";
		$result = $this->db->query($sql);		
		return $result->result_array();

	}


	public function get_account_classification($short_description = ""){


		if(!empty($short_description)){

			$short_description = "'".implode("','",$short_description)."'";

			$where = " AND short_description IN ({$short_description})";
		}

		$sql = "SELECT * FROM classification_setup WHERE STATUS = 'ACTIVE' ".$where;
		$result = $this->db->query($sql);
		
		return $result->result_array();
	}


	public function get_account_setup($class_code){		

		$sql = "
			SELECT
			  account_code,
			  account_description,
			  account_setup.account_id,
			  classification_code
			FROM account_setup
			INNER JOIN setup_title_accounts 
			ON (account_setup.account_id = setup_title_accounts.account_id)
			WHERE account_setup.STATUS = 'ACTIVE'
			     AND setup_title_accounts.status = 'ACTIVE'
			    AND classification_code = '{$class_code}'
			     AND setup_title_accounts.title_id = '{$this->session->userdata('Proj_Code')}'
			ORDER BY account_description
		";
		
		$result = $this->db->query($sql);
		return $result->result_array();
		
	}

	
	

}