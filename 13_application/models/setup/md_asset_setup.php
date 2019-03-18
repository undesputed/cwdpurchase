<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_asset_setup extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_cumulative($date="",$display_type = "ALL"){
		
		$project = $this->session->userdata('Proj_Main');		
		$sql    = "CALL display_asset_setup('".$project."','".$date."','ALL');";
		$result =  $this->db->query($sql);
		$this->db->close();
		return $result;

	}

	public function get_bank_setup(){

		$sql = "SELECT bank_id,bank_name,bank_address FROM bank_setup;";
		$result = $this->db->query($sql);		
		return $result->result_array();

	}

	public function get_bank_setup2(){

		$sql = "SELECT * FROM bank_setup;";
		$result = $this->db->query($sql);		
		return $result;

	}

	public function save_asset_setup(){

		$this->db->trans_begin();

		$data = array(
			'classification_id' => $this->input->post('classification'),
			'sub_classID'       => $this->input->post('classification_sub'),
			'account_code'      => $this->input->post('account_id'),
			'account_description' => $this->input->post('account_description'),
			'total_amount' => $this->input->post('total'),
			'account_classification' => $this->input->post('account_classification'),
			'category' => $this->input->post('category'),
			'payee_type' =>$this->input->post('payee_type'),
			'proj_main' =>$this->input->post('project'),
			'proj_profit_center' =>$this->input->post('profit_center'),
			'status' =>$this->input->post('status'),
			'journal_id' =>$this->input->post('journal_id'),
		);
		$this->db->insert('setup_asset',$data);

		$max_id = $this->db->insert_id();

		$sql = "DELETE FROM setup_asset_details WHERE asset_id = ?";
		$this->db->query($sql,array($max_id));

		$details = $this->input->post('details');
		
		if(isset($details)){

			foreach($details as $row){

				$insert = array(
							$max_id,
							$row['bank_id'],
							$row['account_name'],
							$row['address'],
							$row['account_number'],
							str_replace(',','', $row['account_balance']),
							$row['date'],
							str_replace(',','', $row['account_balance']),
							str_replace(',','',$row['maintaining_balance']),
							$row['currency'],
							$row['account_type'],
					);				
				$sql = "CALL insert_setup_assetDtl(?,?,?,?,?,?,?,?,?,?,?)";
				$this->db->query($sql,$insert);

			}
		}

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();		    
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}

		
	}


	public function account_description($location,$classification,$sub_classification){

		$sql = "SELECT
			  account_setup.account_id,
			  account_code,
			  account_description
			  FROM account_setup
			  INNER JOIN setup_title_accounts
			    ON (account_setup.account_id = setup_title_accounts.account_id)
		      WHERE setup_title_accounts.title_id = '".$location."' AND account_setup.classification_code = '".$classification."' AND account_setup.sub_class_code ='".$sub_classification."'";

		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function get_details($asset_id){

		$sql    = "CALL display_assetDtl_setup('".$asset_id."')";
		$result = $this->db->query($sql);
		return $result;

	}

	public function get_mainTable($id){
		$sql    = "SELECT * FROM setup_asset WHERE asset_id = '".$id."'";
		$result = $this->db->query($sql);
		return $result->row_array();
	}
	


	public function get_detailTable($id){
		$sql = "SELECT
				setup_asset_details.bank_id,
				bank_setup.bank_name 'bank',
				bank_setup.bank_address 'address',
				account_name,
				account_no 'account_number' ,
				amount 'account_balance',
				balance 'maintaining_balance',
				currency_type 'currency' ,
				account_type ,
				save_date 'date'
				FROM setup_asset_details
				left JOIN bank_setup 
				ON (bank_setup.bank_id = setup_asset_details.bank_id)
				WHERE setup_asset_details.asset_id = '".$id."'";	

		$result = $this->db->query($sql);

		return $result->result_array();

	}
		

	public function update_asset_setup(){

		$this->db->trans_begin();

		$data = array(
			'classification_id' => $this->input->post('classification'),
			'sub_classID'       => $this->input->post('classification_sub'),
			'account_code'      => $this->input->post('account_id'),
			'account_description' => $this->input->post('account_description'),
			'total_amount' => $this->input->post('total'),
			'account_classification' => $this->input->post('account_classification'),
			'category' => $this->input->post('category'),
			'payee_type' =>$this->input->post('payee_type'),
			'proj_main' =>$this->input->post('project'),
			'proj_profit_center' =>$this->input->post('profit_center'),
			'status' =>$this->input->post('status'),
			'journal_id' =>$this->input->post('journal_id'),
		);
		$this->db->where('asset_id',$this->input->post('asset_id'));
		$this->db->update('setup_asset',$data);

		$max_id = $this->input->post('asset_id');

		$sql = "DELETE FROM setup_asset_details WHERE asset_id = ?";
		$this->db->query($sql,array($max_id));

		$details = $this->input->post('details');
		
		if(!empty($details)){

			foreach($details as $row){

				$insert = array(
							$max_id,
							$row['bank_id'],
							$row['account_name'],
							$row['address'],
							$row['account_number'],
							str_replace(',','', $row['account_balance']),
							$row['date'],
							str_replace(',','', $row['account_balance']),
							str_replace(',','',$row['maintaining_balance']),
							$row['currency'],
							$row['account_type'],
					);				
				$sql = "CALL insert_setup_assetDtl(?,?,?,?,?,?,?,?,?,?,?)";
				$this->db->query($sql,$insert);				
			}
		}

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();		    
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}

	}



	public function save_bank(){


		if($this->input->post('id')==""){

			$data = array(
				'bank_name'=>$this->input->post('bank_name'),
				'bank_address'=>$this->input->post('address'),
				'account_no'=>$this->input->post('account_no'),				
				'short_name'=>$this->input->post('short_name'),
			);
			$this->db->insert('bank_setup',$data); 

			return true;
		}else{
			$data = array(
				'bank_name'=>$this->input->post('bank_name'),
				'bank_address'=>$this->input->post('address'),
				'account_no'=>$this->input->post('account_no'),				
				'short_name'=>$this->input->post('short_name'),
			);
			$this->db->where('bank_id',$this->input->post('id'));
			$this->db->update('bank_setup',$data); 
			return true;
		}

	}

	public function save_customer(){

		if($this->input->post('id')==""){

			$data = array(
				'business_name'=>$this->input->post('customer_name'),
				'trade_name'=>$this->input->post('customer_name'),
				'address'=>$this->input->post('address'),
				'tin_number'=>$this->input->post('tin'),
				'type'=>'CUSTOMER'
			);
			$this->db->insert('business_list',$data); 

			return true;
		}else{

			$data = array(
				'business_name'=>$this->input->post('customer_name'),
				'trade_name'=>$this->input->post('customer_name'),
				'address'=>$this->input->post('address'),
				'tin_number'=>$this->input->post('tin'),
			);
			$this->db->where('business_number',$this->input->post('id'));
			$this->db->update('business_list',$data); 
			return true;			
		}
		
	}


	public function get_customer(){

		$sql = "SELECT * FROM business_list where type = 'CUSTOMER' AND status = 'ACTIVE'";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

		



}