<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_request extends CI_Controller {

	public function __construct(){
		parent :: __construct();	
		$this->load->model('procurement/md_purchase_request');
		$this->load->model('procurement/md_event_logs');
		$tbl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tbl);
	}

	public function index(){

		// $this->lib_auth->title = "Purchase_request";		
		// $this->lib_auth->build = "procurement/purchase_request/index";		
		// $this->lib_auth->render();
		redirect('procurement/purchase_request/cumulative','refresh');
	}
		
	public function get_items(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		/*
		$result = $this->md_purchase_request->item_name();
		$group = array();
		foreach($result as $row){
			$group[$row['group_description']][] = $row;
		}
		$div = "";
		foreach($group as $key=>$row){
			  $div .='<optgroup label="'.$key.'">';
			  foreach($row as $data)
			  {
			  	$div .='<option value="audi">'.$data['description'].'</option>';
			  }
			  $div .='</optgroup>';
		}
		echo $div;
		echo json_encode($result);	
		*/	
				
		$q = $this->input->get('q');		
		$query_strings = preg_split('/\s+/', $q);
		$where = array();		
		foreach($query_strings as $row){
			 $where[] = "description LIKE '%{$this->db->escape_str($row)}%'";
		}		
		$where = implode(' AND ', $where);
		$result = $this->md_purchase_request->select2($where);
		echo json_encode($result);

	}

	public function get_category(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo json_encode($this->md_purchase_request->item_category($_POST['group_id']));
	}

	public function save_purchaseRequest(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$output = "";

		switch($this->md_purchase_request->save_purchaseRequest()){
			case true:
				$output = "success";
				$this->session->set_flashdata(array('message'=>'Successfully Create New Requestition','type'=>'alert-success'));
			break;
			case "0":
				$output = "P.O Number Already Exist! ";
				
			break;
			case "1":
				$output = "Session timeout, Please refresh the page..";
			break;
		}			
		echo json_encode($output);
	}


	public function update_purchaseRequest(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_purchase_request->update_purchaseRequest();
	}


	/**CUMULATIVE**/


	public function cumulative(){

		$this->lib_auth->title = "Purchase_request";		
		$this->lib_auth->build = "procurement/purchase_request/cumulative";
		
		$this->lib_auth->render();
		
	}

	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_purchase_request->cumulative();
		
		$show = array(
				'APPROVED',
				'PR NO',
				'PR DATE',
				'Project',
				'RECOMMENDED BY',
				'PR STATUS',
		 		);
		foreach($result->result_array() as $key => $value){
			$row_content = array();

			$row_content[] = array('data'=>$value['pr_id'],'class'=>'pr_id','style'=>'display:none');
			$row_content[] = array('data'=>$this->extra->label($value['APPROVED']));
			$row_content[] = array('data'=>$value['PR NO']);
			$row_content[] = array('data'=>date('F d, Y',strtotime($value['PR DATE'])));
			$row_content[] = array('data'=>'<div>'.$value['PROJECT'].'</div><small>'.$value['LOCATION'].'</small>');
			$row_content[] = array('data'=>$value['RECOMMENDED BY']);
			$row_content[] = array('data'=>$this->extra->label($value['PR STATUS']));

			$this->table->add_row($row_content);			
		}

		$this->table->set_heading($show);
		echo $this->table->generate();

		/*
			 $show = array(
						'APPROVED',
						'PR NO',
						'PR DATE',
						'PROJECT',
						'DEPARTMENT',
						'PR STATUS',
				 		);
				
				foreach($result->result_array() as $key => $value){
					$row_content = array();
					$header = array();
					foreach($value as $key1 => $value1){
							$value1 = (isset($value1))? $value1 : '';
							if(in_array($key1,$show)){
								switch($key1){
									case "APPROVED":													
									case "PR STATUS":
									$row_content[] = array('data'=>$this->extra->label($value1));
									break;
									case "PR DATE":
									$row_content[] = array('data'=>date('F d, Y',strtotime($value1)));
									break;
									default:
									$row_content[] = array('data'=>$value1,'class'=>$key1);
									break;
								}						
								$header[] = array('data'=>$key1);
							}else{
								$row_content[] = array('data'=>$value1,'style'=>'display:none','class'=>$key1);
								$header[] = array('data'=>$key1,'style'=>'display:none');
							}
					}		
					$this->table->add_row($row_content);			
				}

				$this->table->set_heading($header);
				echo $this->table->generate();
		*/
		
	}


	public function get_cumulative_detail(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_purchase_request->cumulative_details();
		$main   = $this->md_purchase_request->pr_main();

		$option = array(
			'result' => $result,
			'hide'   => array(
				'itemNo',
				'itemDesc',
				'qty',
				),
			'bool'=>true,
			'table_class'=>array('table','table-striped')
			);

		$data['table']    = $this->extra->generate_table($option);
		$data['approved'] = ($main['approvedPR']=='True')? true : false;
		$data['type']     = 'PR';
		$data['id']       = $this->input->post('pr_id');
		$data['status']   = $main['pr_status'];
		$data['edit_url'] = 'procurement/purchase_request/edit_form';
		$this->load->view('template/cumulative',$data);


	}

	function create_form(){

		$data['account_setup'] = $this->md_purchase_request->account_setup()->result();
		$data['pay_center'] = $this->md_purchase_request->pay_center();
		$data['division']   = $this->md_purchase_request->division();
		$this->load->view('procurement/purchase_request/create',$data);	
		
	}



	/********
	EDIT FORM
	*******/

	function edit_form(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_purchase_request->cumulative_details();
		$main   = $this->md_purchase_request->pr_main();


		$data['main'] = $main;
		$DATA = array();
		foreach($result->result_array() as $key => $value){

				$details = array();
				$details['item_no']  = $value['itemNo'];
				$details['item_description'] = $value['itemDesc']; 
				$details['qty'] = $value['qty'];              
				$details['model_no']  = $value['modelNo'];         
				$details['serial_no'] = $value['serialNo'];        
				$details['remarks']   = $value['remarkz'];          
				$details['groupID']   = $value['groupID'];          
				$details['unit_cost'] = $value['unit_cost'];
				$details['charging'] = $value['charging'];
				$DATA[] = $details; 

		}
		
		$data['DATA'] = json_encode($DATA);

		$this->load->view('procurement/purchase_request/edit',$data);
		
	}

	public function get_incomeAcct(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$account_id = $this->input->post('account_id');
		$result = $this->md_purchase_request->get_incomeAcct($account_id);
		foreach($result as $row){
			echo trim($row);
		}		

	}


	public function closing(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

	}

	public function update(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg['pr_id']      = $this->input->post('pr_id');
		$arg['pr_remarks'] = $this->input->post('pr_remarks');
		$details           = $this->input->post('details');
		$this->md_purchase_request->update_pr($arg,$details);	

		$event['type']    = 'Purchase Request';
		$event['transaction_no'] = $this->input->post('transaction_no');
		$event['transaction_id'] = $arg['pr_id'];
		$event['remarks'] = '';
		$event['action']  = 'EDIT';

		echo $this->md_event_logs->create($event);
				

	}

	public function save_joborder(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$output = "";

		switch($this->md_purchase_request->save_joborder()){
			case true:
				$output = "success";
				$this->session->set_flashdata(array('message'=>'Successfully Create New Job Order','type'=>'alert-success'));
			break;
			case "0":
				$output = "J.O Number Already Exist! ";
				
			break;
			case "1":
				$output = "Session timeout, Please refresh the page..";
			break;
		}			
		echo json_encode($output);
	}

	public function save_receiving(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$output = "";

		switch($this->md_purchase_request->save_receiving()){
			case true:
				$output = "success";
				$this->session->set_flashdata(array('message'=>'Successfully Create New Direct Receiving','type'=>'alert-success'));
			break;
			case "0":
				$output = "Direct Receiving Number Already Exist! ";
				
			break;
			case "1":
				$output = "Session timeout, Please refresh the page..";
			break;
		}			
		echo json_encode($output);
	}

	public function save_transfer(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$output = "";

		switch($this->md_purchase_request->save_transfer()){
			case true:
				$output = "success";
				$this->session->set_flashdata(array('message'=>'Successfully Create New Direct Transfer','type'=>'alert-success'));
			break;
			case "0":
				$output = "Direct Transfer Number Already Exist! ";
				
			break;
			case "1":
				$output = "Session timeout, Please refresh the page..";
			break;
		}			
		echo json_encode($output);
	}

	public function save_return(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$output = "";

		switch($this->md_purchase_request->save_return()){
			case true:
				$output = "success";
				$this->session->set_flashdata(array('message'=>'Successfully Create New Return','type'=>'alert-success'));
			break;
			case "0":
				$output = "Return Number Already Exist! ";
				
			break;
			case "1":
				$output = "Session timeout, Please refresh the page..";
			break;
		}			
		echo json_encode($output);
	}
}