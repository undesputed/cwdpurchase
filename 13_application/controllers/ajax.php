<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Ajax extends CI_controller{


	function __construct(){
		parent::__construct();
		$this->load->model(array(		
		'md_auth',
		'md_project',
		'procurement/md_purchase_request',		
		'procurement/md_canvass_sheet',
		'procurement/md_purchase_order',
		'fixed_asset/md_equipment_transfer',
		'fixed_asset/md_obligation_request',
		'procurement/md_stock_availability',
		'inventory/md_stock_withdrawal',
		'procurement/md_item_request'
		));
	}


	private function is_ajax(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
	}
	
	public function get_projects(){
		$this->is_ajax();
		$result = $this->md_project->get_projects();
		echo json_encode(array("projects"=>$result,'title_id'=>$this->session->userdata('Proj_Main'),'proj'=>$this->session->userdata('Proj_Code')));
	}
	
	public function get_profit_center(){
		$this->is_ajax();
		$result = $this->md_project->get_profit_center();
		$main_office = '';
		foreach($result as $row){
			if($row['project'] == 'MAIN OFFICE'){
				$main_office = $row['project_id'];
				break;
			}
		}

		echo json_encode(array('profit_center'=>$result,'main_office'=>$main_office));

	}

	


	public function signatory3(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg['form'] = $this->input->post('type');

		if(isset($_POST['prepared_by'])){				
			$data['prepared_by']  = $this->md_project->signatory1($_POST['prepared_by']);	
		}

		if(!empty($_POST['recommended_by'])){
			if(isset($_POST['recommended_by'])){
				$arg['signatory'] = 'recommended_by';
				$data['recommended_by'] = $this->md_project->get_websignatory($arg);
			}
		}

		if(!empty($_POST['approved_by'])){
			if(isset($_POST['approved_by'])){
				$arg['signatory'] = 'approved_by';
				$data['approved_by'] = $this->md_project->get_websignatory($arg);
			}
		}

		if(!empty($_POST['requested_by'])){
			$arg['signatory'] = 'requested_by';
			$data['requested_by'] =  $this->md_project->get_websignatory($arg);
		}

		if(!empty($_POST['issued_by'])){
			$arg['signatory'] = 'issued_by';
			$data['issued_by'] = $this->md_project->get_websignatory($arg);
		}

		if(!empty($_POST['noted_by'])){
			$arg['signatory'] = 'noted_by';
			$data['noted_by'] = $this->md_project->get_websignatory($arg);
		}

		if(!empty($_POST['received_by'])){
			$arg['signatory'] = 'received_by';
			$data['received_by'] = $this->md_project->get_websignatory($arg);
		}

		if(!empty($_POST['checked_by'])){
			$arg['signatory'] = 'checked_by';
			$data['checked_by'] = $this->md_project->get_websignatory($arg);
		}
				
					
		echo json_encode($data);


	}


	


	public function signatory2(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		

		$data = array();

		if(isset($_POST['prepared_by'])){				
			$data['prepared_by']  = $this->md_project->signatory1($_POST['prepared_by']);	
		}

		if(!empty($_POST['recommended_by'])){
			if(isset($_POST['recommended_by'][3])){
				$data['recommended_by'] = $this->md_project->all_employee2();
			}
		}

		if(!empty($_POST['approved_by'])){
			if(isset($_POST['approved_by'])){
				$data['approved_by'] = $this->md_project->all_employee2();		
			}
		}

		if(!empty($_POST['requested_by'])){
			$data['requested_by'] =  $this->md_project->all_employee2();
		}

		if(!empty($_POST['issued_by'])){
			$data['issued_by'] = $this->md_project->all_employee2();
		}

		if(!empty($_POST['noted_by'])){
			$data['noted_by'] = $this->md_project->all_employee2();
		}

		if(!empty($_POST['received_by'])){
			$data['received_by'] = $this->md_project->all_employee2();
		}

		if(!empty($_POST['checked_by'])){
			$data['checked_by'] = $this->md_project->all_employee2();
		}
					
		echo json_encode($data);

	}

	public function signatory(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
	
			$data = array();

			if(isset($_POST['prepared_by'])){				
				$data['prepared_by']  = $this->md_project->signatory1($_POST['prepared_by']);	
			}

			if(!empty($_POST['recommended_by'])){
				if(isset($_POST['recommended_by'][3])){
					$data['recommended_by'] = $this->md_project->signatory($_POST['recommended_by'][0],$_POST['recommended_by'][1],$_POST['recommended_by'][2],$_POST['recommended_by'][3]);
				}				
			}

			if(!empty($_POST['approved_by'])){
				if(isset($_POST['approved_by'])){
					$data['approved_by'] = $this->md_project->signatory($_POST['approved_by'][0],$_POST['approved_by'][1],$_POST['approved_by'][2],$_POST['approved_by'][3]);		
				}				
			}

			if(!empty($_POST['requested_by'])){
				$data['requested_by'] =  $this->md_project->signatory1($this->session->userdata('lblpersoncode'));
			}

			if(!empty($_POST['issued_by'])){
				$data['issued_by'] = $this->md_project->signatory($_POST['issued_by'][0],$_POST['issued_by'][1],$_POST['issued_by'][2],$_POST['issued_by'][3]);
			}

			if(!empty($_POST['noted_by'])){
				$data['noted_by'] = $this->md_project->signatory($_POST['noted_by'][0],$_POST['noted_by'][1],$_POST['noted_by'][2],$_POST['noted_by'][3]);
			}

			if(!empty($_POST['received_by'])){
				$data['received_by'] = $this->md_project->signatory($_POST['received_by'][0],$_POST['received_by'][1],$_POST['received_by'][2],$_POST['received_by'][3]);
			}

			if(!empty($_POST['checked_by'])){
				$data['checked_by'] = $this->md_project->signatory($_POST['checked_by'][0],$_POST['checked_by'][1],$_POST['checked_by'][2],$_POST['checked_by'][3]);
			}
						
			echo json_encode($data);
	}


	public function get_pr_code(){
			
			if(!isset($_POST['date'])){
				return false;
			}
			
			$date = explode('-',$_POST['date']);
			$month = $date[1];
			$year = $date[0];			
			$data = $this->md_project->get_max_pr($month,$year);
			if(empty($data[0]['max'])){
				echo "PR-".$year."-".$month.'-'.$this->str_pad('1').'';
			}else{
				$date = explode('-',$data[0]['max']);				
				echo "PR-".$year."-".$month.'-'.$this->str_pad(($date[3]+1)).'';
			}
			
	}

	
	public function get_ccv_code(){		

			$date = explode('-',$_POST['date']);
			$month = $date[1];
			$year = $date[0];			
			$data = $this->md_project->get_max_canvass($month,$year);					
			if(empty($data[0]['max'])){
				echo "CV-".$year."-".$month.'-'.$this->str_pad('1').'';
			}else{
				$date = explode('-',$data[0]['max']);
				echo "CV-".$year."-".$month.'-'.$this->str_pad(($date[3]+1)).'';
			}
			
	}

		
	public function get_po_code(){
			$date = explode('-',$_POST['date']);
			$month = $date[1];
			$year = $date[0];			
			$data = $this->md_project->get_max_po($month,$year);
			
			if(empty($data[0]['max'])){
				echo "PO-".$year."-".$month.'-'.$this->str_pad('1');
			}else{
				$date = explode('-',$data[0]['max']);
				echo "PO-".$year."-".$month.'-'.$this->str_pad(($date[3]+1));
			}
	}
	

	public function get_rr_code(){
			$date = explode('-',$_POST['date']);
			$month = $date[1];
			$year = $date[0];			
			$data = $this->md_project->get_max_rr($month,$year);		
			if(empty($data[0]['max'])){
				echo "RR-".$year."-".$month.'-'.$this->str_pad('1');
			}else{
				$date = explode('-',$data[0]['max']);				
				echo "RR-".$year."-".$month.'-'.$this->str_pad(($date[3]+1));
			}
	}


	public function get_max_itemCode(){
			$date = explode('-',$_POST['date']);
			$month = $date[1];
			$year = $date[0];			
			$data = $this->md_project->get_max_itemCode($month,$year);		
			if(empty($data[0]['max'])){
				 echo $month."-".$this->str_pad((1)).'-'.$year.'';				
			}else{				
				 $date = explode('-',$data[0]['max']);		
				 echo $month."-".$this->str_pad(($date[1]+1)).'-'.$year.'';
			}
	}

	public function get_maxMIS(){
			$date = explode('-',$_POST['date']);
			$month = $date[1];
			$year = $date[0];			
			$data = $this->md_project->get_maxMIS($month,$year);		
			if(empty($data[0]['max'])){
				 echo "MIS-".$this->str_pad((1)).'-'.$month."-".$year."";				
			}else{
				 echo "MIS-".$this->str_pad(($data[0]['max']+1)).'-'.$month."-".$year.'';
			}
	}

	public function get_max_equipmentRequest(){
			$date = explode('-',$_POST['date']);
			$month = $date[1];
			$year = $date[0];			
			$data = $this->md_project->get_max_equipmentRequest($month,$year);		
			if(empty($data[0]['max'])){
				 echo "MQ-".$month."-".$this->str_pad((1))."-".$year."";				
			}else{
				 $date = explode('-',$data[0]['max']);
				 echo "MQ-".$month."-".$this->str_pad(($date[2]+1))."-".$year."";
			}
	}

	public function get_max_equipmentTransfer(){
			$date = explode('-',$_POST['date']);
			$month = $date[1];
			$year = $date[0];			
			$data = $this->md_project->get_max_equipmentTransfer($month,$year);		
			if(empty($data[0]['max'])){
				 echo "EQ-".$month."-".$this->str_pad((1))."-".$year."";				
			}else{
				 $date = explode('-',$data[0]['max']);
				 echo "EQ-".$month."-".$this->str_pad(($date[3]+1))."-".$year."";
			}
	}


	public function get_max_dispatchMain(){
			$date = explode('-',$_POST['date']);
			$month = $date[1];
			$year = $date[0];			
			$data = $this->md_project->get_max_dispatchMain($month,$year);		
			if(empty($data[0]['max'])){
				 echo "GPS-".$month."-".$this->str_pad((1))."-".$year."";				
			}else{
				 $date = explode('-',$data[0]['max']);
				 echo "GPS-".$month."-".$this->str_pad(($date[2]+1))."-".$year."";
			}

	}

	public function get_max_equipmentlist(){
			$date = explode('-',$_POST['date']);
			$month = $date[1];
			$year = $date[0];			
			$data = $this->md_project->get_max_equipmentlist($month,$year);		
			if(empty($data[0]['max'])){
				 echo $month."-".$this->str_pad((1))."-".$year."";				
			}else{
				 $date = explode('-',$data[0]['max']);
				 echo $month."-".$this->str_pad(($date[1]+1))."-".$year."";
			}

	}

	public function get_mr_code2(){

			$date  = explode('-',$_POST['date']);
			$month = $date[1];
			$year  = $date[0];			
			$data  = $this->md_project->get_max_mr2($month,$year);
			
			if(empty($data[0]['max'])){
				 echo "MR-".$month."-".$this->str_pad(1)."-".$year;
			}else{
				 $date = explode('-',$data[0]['max']);
				 echo  preg_replace('/\s+/', ' ',$date[0]."-".$date[1]."-".$this->str_pad($date[2]+1)."-".$date[3]);				 
			}

	}

	public function get_journalEntry(){

			$date  = explode('-',$_POST['date']);
			$type_name = $this->input->post('transaction_type');
			$project_id  = $this->input->post('project_id');
			$month = $date[1];
			$year  = $date[0];
			$data  = $this->md_project->get_journalEntry($month,$year,$type_name,$project_id);
			$type  = $this->input->post('type');

			if(empty($data[0]['max'])){
				echo $type.'-'.$year.'-'.$month."-".$this->str_pad(1);
			}else{
				echo $type.'-'.$year.'-'.$month."-".$this->str_pad($data[0]['max']);

				/*
				$date = explode('-',$data[0]['max']);
				echo  preg_replace('/\s+/', ' ',$date[0]."-".$date[1]."-".$this->str_pad($date[2]+1)."-".$date[3]);
				*/
			}
	}

	public function get_receivedTransfer(){

			$date  = explode('-',$_POST['date']);
			$month = $date[1];
			$year  = $date[0];
			$data  = $this->md_project->get_receivedTransfer($month,$year);
			$type = "TR";
			if(empty($data[0]['max'])){
				echo $type."-".$year."-".$month."-".$this->str_pad(1);
			}else{
				echo $type."-".$year."-".$month."-".$this->str_pad($data[0]['max']);
			}

	}

	public function get_max_withdrawal(){

			$date  = explode('-',$_POST['date']);
			$month = $date[1];
			$year  = $date[0];
			$data  = $this->md_project->get_max_withdrawal($month,$year);
			$type = "WS";
			if(empty($data[0]['max'])){
				echo $type."-".$year."-".$month."-".$this->str_pad(1);
			}else{
				echo $type."-".$year."-".$month."-".$this->str_pad($data[0]['max'] + 1);
			}
			
	}


	public function get_max_issuance(){

			$date  = explode('-',$_POST['date']);
			$month = $date[1];
			$year  = $date[0];
			$data  = $this->md_project->get_max_issuance($month,$year);
			$type = "IS";
			if(empty($data[0]['max'])){
				echo $type."-".$year."-".$month."-".$this->str_pad(1);
			}else{
				echo $type."-".$year."-".$month."-".$this->str_pad($data[0]['max'] + 1);
			}
			
	}


	public function get_max_transfer(){

			$date  = explode('-',$_POST['date']);
			$month = $date[1];
			$year  = $date[0];
			$data  = $this->md_project->get_max_transfer($month,$year);
			$type = "TR";
			if(empty($data[0]['max'])){
				echo $type."-".$year."-".$month."-".$this->str_pad(1);
			}else{
				echo $type."-".$year."-".$month."-".$this->str_pad($data[0]['max'] + 1);
			}
			
	}
	public function get_max_request(){

			$date  = explode('-',$_POST['date']);
			$month = $date[1];
			$year  = $date[0];
			$data  = $this->md_project->get_max_request($month,$year);
			$type = "IR";
			if(empty($data[0]['max'])){
				echo $type."-".$year."-".$month."-".$this->str_pad(1);
			}else{
				echo $type."-".$year."-".$month."-".$this->str_pad($data[0]['max'] + 1);
			}
			
	}


	public function get_journal_max_id(){

			$date = (empty($_POST['date']))? date('Y-m-d') : $_POST['date'];
			$date  = explode('-',$date);
			$month = $date[1];
			$year  = $date[0];
			$data  = $this->md_project->get_journal_max_id($month,$year);
			$type = "EIV";
			if(empty($data[0]['max'])){
				echo $type."-".$month."-".$this->str_pad(1)."-".$year;
			}else{
				echo $type."-".$month."-".$this->str_pad($data[0]['max'] + 1)."-".$year;
			}
			
	}


	public function get_invoice_max_id(){

		$date = (empty($_POST['date']))? date('Y-m-d') : $_POST['date'];
		$date  = explode('-',$date);
		$month = $date[1];
		$year  = $date[0];
		$data  = $this->md_project->get_max_invoice($month,$year);
		$type = "INV";
		if(empty($data[0]['max'])){
			echo   $type."-".$month."-".$this->str_pad(1)."-".$year;
		}else{
			echo  $type."-".$month."-".$this->str_pad($data[0]['max'] + 1)."-".$year;
		}
		
	}
		

	private function str_pad($num){
		 return str_pad($num, 3, '0', STR_PAD_LEFT);
	}


	public function approved(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		switch($this->input->post('type')){
			case"PR":
				$this->md_purchase_request->approvePR();
			break;
			case "CS":
				$data['items'] = $this->md_canvass_sheet->canvass_display_item();
				$data['main']  = $this->md_canvass_sheet->canvass_main_id();
				$this->load->view('procurement/canvass_sheet/approval',$data);				
			break;
			
		}

	}

	public function changeStatus(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		switch($this->input->post('type')){
			case"PR":
				echo  $this->md_purchase_request->changePrStatus();
			break;

			case"CS":
				echo $this->md_canvass_sheet->changeCsStatus();
			break;

			case "PO":
				echo $this->md_purchase_order->changePoStatus();
			break;

			case "EQ":
				echo $this->md_equipment_transfer->changeEqStatus();
			break;

			case "MR":
				echo $this->md_obligation_request->changeMRStatus();		
			break;

			
		}	
	}


	public function get_division(){
		$result = $this->md_project->division($this->input->post('project'));
		echo json_encode($result);
	}


	public function display(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo $this->input->post('value');
		
	}

	public function item_select(){
		
		$result = $this->md_stock_withdrawal->get_item_withdrawal();
		$data = array();
		foreach($result as $row){
			$data[$row['ITEM DESCRIPTION']] = $row['ITEM DESCRIPTION'];
		}
				
		echo json_encode($data);
		
	}


	public function item_request_get_item(){
		
		$arg = $this->input->post();
		$item_list = $this->md_item_request->get_request_list_item($arg['location']);

		$item_content = array();
		$item_value   = array();
		$cnt = 0;
		
		foreach($item_list as $row){
			$item_value[$row['item_no']]   = $row['item_description'];			
			$item_content[$row['item_no']] = array(
						'item_name'=>$row['item_description'],
						'unit_measure'=>$row['item_measurement'],
						'stocks'=>$row['Quantity at Hand'],
						'item_cost'=>$row['item_cost'],
						'description'=>$row['description']
						);
		}

		$data['item_value']   = $item_value;
		$data['item_content'] = $item_content;

		echo json_encode($data);

	}

	public function item_withdraw_get_item(){
		
		$item_list = $this->md_item_request->get_withdraw_list_item();

		$item_content = array();
		$item_value   = array();
		$cnt = 0;
		
		foreach($item_list as $row){
			$item_value[$row['item_no']]   = $row['item_description'];			
			$item_content[$row['item_no']] = array(
						'item_name'=>$row['item_description'],
						'unit_measure'=>$row['item_measurement'],
						'stocks'=>$row['Quantity at Hand'],
						'item_cost'=>$row['item_cost'],
						'description'=>$row['description']
						);
		}

		$data['item_value']   = $item_value;
		$data['item_content'] = $item_content;

		echo json_encode($data);

	}


}
	

