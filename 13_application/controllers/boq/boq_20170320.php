<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Boq extends CI_Controller {

	public function __construct(){
		parent :: __construct();		


		$this->load->model('boq/md_boq');
		$this->load->model('md_project');

	}

	public function index(){		
		redirect(base_url().index_page().'/boq/transaction','refresh');		
	}

	public function transaction(){

		$this->lib_auth->title = "Bill of Quantities";	



		$data['project_site'] = $this->md_project->get_profit_center();

		$result = $this->md_boq->get_main_category();		
		$result_sub = $this->md_boq->get_sub_category();
		$result_items =  $this->md_boq->get_items();
		

		$data['project_category'] = $this->md_project->get_project_category('where view = "BOQ"');
		$data['floor'] = $this->md_boq->get_floor();
		

		$main_category = array();
		foreach($result as $row){
			$main_category[$row['paycenter']] = $row['paycenter'];
			$main_category_data[$row['paycenter']] = $row;
		}

		$sub_category = array();
		foreach($result_sub as $row){
			$sub_category[$row['itemdescription']] = $row['itemdescription'];
			$sub_category_data[$row['itemdescription']] = $row;
		}

		$item_list = array();
		foreach($result_items as $row){
			$item_list[$row['description']]      = $row['description'];
			$item_list_data[$row['description']] = $row;
		}

		
		$data['main_category']      = json_encode($main_category);
		$data['main_category_data'] = json_encode($main_category_data);
		$data['sub_category']       = json_encode($sub_category);
		$data['sub_category_data']  = json_encode($sub_category_data);
		$data['item_list']          = json_encode($item_list);
		$data['item_list_data']     = json_encode($item_list_data);


		$data['view'] = $this->load->view('boq/transaction',$data,true);

		$this->lib_auth->build = "boq/index";
		$this->lib_auth->render($data);

	}

	private function boq_get_data($ref_id,$floor = ''){
				
		$data['result_boq'] = $this->md_boq->get_boq($ref_id,$floor);

		$main     = array();
		$main_cnt = array();
		$sub_cnt  = array();
		$item_cnt = array();

		$cnt = 0;
		foreach($data['result_boq'] as $row){
			$num_level = explode('.', $row['no']);
			switch($row['type']){
				case "main":

					$main[$cnt] = array(
						'sub'=>array(),
						'main_id'=>$row['type_id'],
						'main_title'=>$row['description'],
						'no'=>$row['no'],
						'qty'    => $row['qty'],
						'unit'   => $row['unit'],
						'material' => $row['material'],
						'labor'  => $row['labor'],
						'others' => $row['others'],
						'total'  => $row['total'],
						'amount' => $row['amount'],
						);
					$main_cnt[$num_level[0]] = $cnt;

				break;
				case "sub":

					$sub = array(
						'id' => '',
						'items' => array(),
						'main_id' => $row['type_id'],
						'main_title' => $row['description'],
						'no' => $row['no'],
						'qty'    => $row['qty'],
						'unit'   => $row['unit'],
						'material'=> $row['material'],
						'labor'  => $row['labor'],
						'others' => $row['others'],
						'total'  => $row['total'],
						'amount' => $row['amount'],
						);

					$sub_cnt[$num_level[1]] = $cnt;
					$main[$main_cnt[$num_level[0]]]['sub'][$cnt] = $sub;
									
				break;

				case "items":

					$items = array(
						'id' => '',
						'main_id' => $row['type_id'],
						'main_title' => $row['description'],
						'no'     => $row['no'],
						'qty'    => $row['qty'],
						'unit'   => $row['unit'],
						'material'=> $row['material'],
						'labor'  => $row['labor'],
						'others' => $row['others'],
						'total'  => $row['total'],
						'amount' => $row['amount'],
						);

					$main[$main_cnt[$num_level[0]]]['sub'][$sub_cnt[$num_level[1]]]['items'][] = $items;

				break;


			}

			$cnt++;
		}

		$m = array();
		
		

		foreach($main as $key=>$row)
		{			
			$s = array();
			foreach($row['sub'] as $row1)
			{
				$s[] = $row1;
			}
			$m[$key] = $row;
			$m[$key]['sub'] = $s;			
		}

		$dup = array();		
		/* $data['result_boq'] = json_encode(array_values($main)); */

		return array_values($m);

		/*echo json_encode(array_values($m));*/

	}



	public function boq_data(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$ref_id = $this->input->post('ref_id');
		$floor_id = $this->input->post('floor_id');

		$result = $this->boq_get_data($ref_id,$floor_id);

		$contract_amt = $this->md_boq->get_contract_amt($ref_id);

		$output = array(
				'result'=>$result,
				'contract_amt'=>(isset($contract_amt['contract_amt']))? $contract_amt['contract_amt'] : 0,
			);
						
		echo json_encode($output);
	}

	public function save_contract(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg['project_category_id'] = $this->input->post('project_category');
		$arg['contract_amt'] = $this->input->post('value');
		$this->md_boq->save_contract($arg);

		echo  $this->input->post('value');

	}


	public function save(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$post = $this->input->post();

		$data = json_decode($this->input->post('data'),true);
			

		$params['description']         = '';
		$params['project_category_id'] = $this->input->post('project_type');
		$params['project_category']    = $this->input->post('project_type_name');
		$params['floor_id']            = $this->input->post('floor_id');
		$params['contract_amt']     = $this->input->post('contract_amt');
		$params['project_code'] = $this->input->post('project_site');
		if(empty($params['project_code'] )){
			$params['project_code'] = $this->session->userdata('Proj_Code');
		}

		

		if($params['project_code'] == 0){
			exit(1);
		}
				
		$ref_id = $this->md_boq->save_main($params);

		if(empty($data)){
			echo 1;
			exit(0);
		}

	
		$cnt = 0;
		foreach($data as $key=>$row1){
			$arg = array();

			$arg[$cnt]['ref_id'] = $ref_id;
			$arg[$cnt]['type'] = 'main';
			$arg[$cnt]['type_id'] = $row1['main_id'];
			$arg[$cnt]['description'] = $row1['main_title'];
			$arg[$cnt]['qty']  = $row1['qty'];
			$arg[$cnt]['unit'] = $row1['unit'];
			$arg[$cnt]['material'] = $row1['material'];
			$arg[$cnt]['labor']    = $row1['labor'];
			$arg[$cnt]['others']   = $row1['others'];
			$arg[$cnt]['no']       = $row1['no'];
			$arg[$cnt]['total']    = $row1['total'];
			$arg[$cnt]['amount']   = $row1['amount'];
			$arg[$cnt]['flr_id']   = $params['floor_id'];

			/*$this->md_boq->save_details($arg);*/
			$cnt++;
			if(isset($row1['sub']) && is_array($row1['sub']) && count($row1['sub']) > 0){
				
				foreach($row1['sub'] as $row2){

					$arg[$cnt]['ref_id'] = $ref_id;
					$arg[$cnt]['type']    = 'sub';
					$arg[$cnt]['type_id'] = $row2['main_id'];
					$arg[$cnt]['description'] = $row2['main_title'];
					$arg[$cnt]['qty']      = $row2['qty'];
					$arg[$cnt]['unit']     = $row2['unit'];
					$arg[$cnt]['material'] = $row2['material'];
					$arg[$cnt]['labor']    = $row2['labor'];
					$arg[$cnt]['others']   = $row2['others'];
					$arg[$cnt]['no']       = $row2['no'];
					$arg[$cnt]['total']    = $row2['total'];
					$arg[$cnt]['amount']   = $row2['amount'];
					$arg[$cnt]['flr_id']   = $params['floor_id'];

					/*$this->md_boq->save_details($arg);*/

					$cnt++;
					if(!empty($row2['items']) && count($row2['items']) > 0){
						
						foreach($row2['items'] as $row3){
							
							$arg[$cnt]['ref_id']      = $ref_id;
							$arg[$cnt]['type']        = 'items';
							$arg[$cnt]['type_id']     = $row3['main_id'];
							$arg[$cnt]['description'] = $row3['main_title'];
							$arg[$cnt]['qty']         = $row3['qty'];
							$arg[$cnt]['unit']        = $row3['unit'];
							$arg[$cnt]['material']    = $row3['material'];
							$arg[$cnt]['labor']       = $row3['labor'];
							$arg[$cnt]['others']      = $row3['others'];
							$arg[$cnt]['no']          = $row3['no'];
							$arg[$cnt]['total']       = $row3['total'];
							$arg[$cnt]['amount']      = $row3['amount'];
							$arg[$cnt]['flr_id']      = $params['floor_id'];

							/*$this->md_boq->save_details($arg);*/
							$cnt++;
						}

					}								
				}
			}
		
			$this->md_boq->save_batch($arg);			
		}
		echo 1;
	}


	public function report($arg = ''){
		$data['project_id'] = $this->session->userdata('Proj_Code');

		if($arg!=""){
			$data['project_id'] = $arg;
		}
		
		$data['project_category'] = $this->md_project->get_project_category('where view = "BOQ"');
		$this->lib_auth->title = "BOQ Monitoring";
		$this->lib_auth->build = "boq/report_main";
		$this->lib_auth->render($data);
		
	}

	public function report2($arg = ''){

		$data['project_id'] = $this->session->userdata('Proj_Code');
		if($arg!=""){
			$data['project_id'] = $arg;
		}

		$data['project_site'] = $this->md_project->get_setup_project($data['project_id']);
		$data['project_category'] = $this->md_project->get_project_category('where view = "BOQ"');
		$this->load->view('boq/fancy_report_main',$data);
		
	}

	public function get_report_details(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$project_id  = $this->input->post('project_id');
		$type_id     = $this->input->post('type');
		$type_name   = $this->input->post('type_name');
		$type_name   = explode(' ',$type_name);

		$data['boq'] = $this->get_data_report($type_id,$project_id,$type_name[0]);			
		$data['item_no_boq'] = $this->md_boq->get_po_no_boq($type_id,$project_id,$type_name[0]);

		$tenant_list = array();
		$tenant      = $this->md_boq->tenant($project_id);
		foreach($tenant as $row){
			$tenant_list[$row['name']]['amount']   = $row['amount'];
			$tenant_list[$row['name']]['items'][]  = $row;
		}

		$data['tenant'] = $tenant_list;
		$cost_result    = $this->md_boq->save_cost($project_id,$type_id);
		$data['cost_result'] = $cost_result->result_array();
		$this->load->view('boq/report',$data);
		
	}

	private function get_data_report($type_id,$project_id,$type_name){
		
		$data['result_boq'] = $this->md_boq->get_report($type_id,$project_id,$type_name);

		$main     = array();
		$main_cnt = array();
		$sub_cnt  = array();
		$item_cnt = array();

		$cnt = 0;
		foreach($data['result_boq'] as $row){
			$num_level = explode('.', $row['no_type']);

		
			switch($row['type']){
				case "main":

					$main[$cnt] = array(
						'sub'=>array(),
						'main_id'=>$row['type_id'],
						'main_title'=>$row['description'],
						'no'=>$row['no'],
						'qty'    => $row['qty'],
						'unit'   => $row['unit'],
						'material' => $row['material'],
						'labor'  => $row['labor'],
						'others' => $row['others'],
						'total'  => $row['total'],
						'amount' => $row['amount'],
						'po_qty' => $row['total_quantity'],
						'po_unit'=> $row['unit_msr'],
						'po_amount' => $row['total_unitcost'],
						'unit_cost' => $row['unit_cost'],
						);
					$main_cnt[$num_level[0]] = $cnt;

				break;
				case "sub":

					$sub = array(
						'id' => '',
						'items' => array(),
						'main_id' => $row['type_id'],
						'main_title' => $row['description'],
						'no' => $row['no'],
						'qty'    => $row['qty'],
						'unit'   => $row['unit'],
						'material'=> $row['material'],
						'labor'  => $row['labor'],
						'others' => $row['others'],
						'total'  => $row['total'],
						'amount' => $row['amount'],
						'po_qty' => $row['total_quantity'],
						'po_unit'=> $row['unit_msr'],
						'po_amount' => $row['total_unitcost'],
						'unit_cost' => $row['unit_cost'],
						);

					$sub_cnt[$num_level[1]] = $cnt;
					$main[$main_cnt[$num_level[0]]]['sub'][$cnt] = $sub;

				break;

				case "items":

					$items = array(
						'id' => '',
						'main_id' => $row['type_id'],
						'main_title' => $row['description'],
						'no'     => $row['no'],
						'qty'    => $row['qty'],
						'unit'   => $row['unit'],
						'material'=> $row['material'],
						'labor'  => $row['labor'],
						'others' => $row['others'],
						'total'  => $row['total'],
						'amount' => $row['amount'],
						'po_qty' => $row['total_quantity'],
						'po_unit'=> $row['unit_msr'],
						'po_amount' => $row['total_unitcost'],
						'unit_cost' => $row['unit_cost'],
						);

					$main[$main_cnt[$num_level[0]]]['sub'][$sub_cnt[$num_level[1]]]['items'][] = $items;
										
				break;

			}

			$cnt++;
		}

		$m = array();
		
	
		

		foreach($main as $key=>$row)
		{			
			$s = array();
			foreach($row['sub'] as $row1)
			{
				$s[] = $row1;
			}
			$m[$key] = $row;
			$m[$key]['sub'] = $s;			
		}

		$dup = array();		
		/* $data['result_boq'] = json_encode(array_values($main)); */
		return array_values($m);
		/*echo json_encode(array_values($m));*/
	}



	public function projects(){
					
		$data['project_category'] = $this->md_project->get_project_category('where view = "BOQ"');		
		$this->lib_auth->title = "BOQ Projects";
		$this->lib_auth->build = "boq/projects";
		$this->lib_auth->render($data);

	}

	public function get_project(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$type_id   = $this->input->post('type_id');
		$type_name = $this->input->post('type_name');
		$type_name = explode(' ',$type_name);		
		$data['get_projects'] = $this->md_boq->get_project_boq($type_id,$type_name[0]);
		$this->load->view('boq/projects_details',$data);

	}

	public function cost_entry(){

		$data['project_category'] = $this->md_project->get_project_category('where view = "BOQ"');

	 	$this->lib_auth->title = "Cost Entry";
		$this->lib_auth->build = "boq/cost_entry";
		$this->lib_auth->render($data);		
	}

	 function get_cost(){
	 
	 	if(!$this->input->is_ajax_request()){
	 		exit(0);
	 	}

	 	$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
	 	$this->table->set_template($tmpl);

	 	$project_id = $this->input->post('project_id');
	 	$result = $this->md_boq->save_cost($project_id);

	 	$show = array(
	 		'Project',
	 		'Description',
	 		'Cost',
	 		'Action',
	 		);
	 	foreach($result->result_array() as $key => $value) {
	 		$row_content = array();
	 		$row_content[] = array('data'=>$value['id'],'class'=>'id','style'=>'display:none' );
	 		$row_content[] = array('data'=>$value['project_category_id'],'class'=>'project_category_id','style'=>'display:none' );
	 		$row_content[] = array('data'=>$value['project_category'],'class'=>'project_category' );
	 		$row_content[] = array('data'=>$value['description'],'class'=>'description' );
	 		$row_content[] = array('data'=>$this->extra->number_format($value['cost']),'class'=>'cost' );
	 		$row_content[] = array('data'=>'<span class="btn-link event update">Update</span><span class="event"> | </span><span class="btn-link event remove">Remove</span>','class'=>'');
			$this->table->add_row($row_content);
	 	}

			$this->table->set_heading($show);
			echo $this->table->generate();

	 }

	 public function save_labor_cost(){

	   if(!$this->input->is_ajax_request()){
			exit(0);
	   }
	   echo $this->md_boq->get_labor_cost();

	 }

	 public function update(){	 	
	 	if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$arg = $this->input->post();
		echo $this->md_boq->update();
	 }

	 public function remove_cost(){
	 	if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$id = $this->input->post('id');
		echo $this->md_boq->remove_cost($id);
	 	
	 }
	 

	 public function item_history(){
	 	if(!$this->input->is_ajax_request()){
	 		exit(0);
	 	}

	 	$item_no = $this->input->post('item_no');
	 	$project_id = $this->input->post('project_id');
	 	$data['pr_items'] = $this->md_boq->get_po_items($item_no,$project_id);	 	


	 	$table = $this->load->view('boq/item_history',$data,true);

	 	$output = array(
	 		'table' => $table,
	 		'title'=> $data['pr_items'][0]['item_name'],
	 		);
	 	echo json_encode($output);

	 }



}
