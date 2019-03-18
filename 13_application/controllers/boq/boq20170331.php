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

	//added skip
	public function report_new(){
		$arg = array(
				'project' => $this->input->post('project'),
				'category' => $this->input->post('category')
			);
		$data['result'] = $this->md_boq->get_boq_new_details($arg);

		$this->load->view('boq/table_report_details_new',$data);
		
	}

	public function boqdetails(){
		$arg = array(
				'boq_id' => $this->input->post('boq_id')
			);

		$data['result'] = $this->md_boq->get_po_details($arg);

		$this->load->view('boq/table_boq_details_new',$data);
	}

	public function run_export(){
		$arg = array(
				'project' => $this->input->get('project'),
				'category' => $this->input->get('category')
			);
		$data['result'] = $this->md_boq->get_boq_new_details($arg);

		$filename = "boq_".date('Y-m-d');

		header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=$filename.xls");

        $this->load->view('boq/table_report_details_new',$data);
	}

	//end added skip

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

	public function projects_new(){
		$data['projects'] = $this->md_boq->get_data('setup_project', array('status' => 'ACTIVE'));
		$data['project_category'] = $this->md_project->get_project_category('where view = "BOQ"');		
		$this->lib_auth->title = "BOQ Projects (New)";
		$this->lib_auth->build = "boq/project_new";
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
	 
	public function upload() { 
		$this->lib_auth->title = "BOQ Projects";
		$this->lib_auth->build = "boq/boq_upload_view";
		
		$data['projects'] = $this->md_boq->get_data('setup_project', array('status' => 'ACTIVE'));
		$data['project_categories'] = $this->md_boq->get_data('project_category', array('view'=>'BOQ'));
		$data['boq_records'] = $this->md_boq->get_boq_records();
		
		$this->lib_auth->render($data);
	}
	
	public function process_upload() { 
		if ( !$this->input->post() ) { 
			redirect(base_url().index_page().'/boq/upload','refresh');
		}
	
		$upload_path = './uploads/boq_files/';
		if ( !is_dir( $upload_path ) ) {
			mkdir($upload_path, 0777, TRUE);
		}
		
		$project_id = $this->input->post('project');
		$project_category_id = $this->input->post('project_type');
		$original_file = $_FILES['boq_upload_file']['name'];
		$upload_filename = str_replace(' ', '_', $original_file);
		
		$errors = array();
		$success_message = '';
		// Validations:
		if ( trim($project_id) == '' ) array_push($errors, 'Please select project.');
		if ( trim($project_category_id) == '' ) array_push($errors, 'Please select project type.');
		if ( trim($original_file) == '' ) array($errors, 'Please select a file to upload.');
		
		if ( file_exists( $upload_path . $upload_filename ) ) { 
			array_push($errors, 'File already exist. Please upload another file or change filename.');
		}
		else { 
		
			if ( !count($errors) ) { 
				
				$config['upload_path'] = $upload_path;
				$config['allowed_types'] = 'xls|xlsx';

				$this->load->library('upload', $config);
				
				if ( ! $this->upload->do_upload('boq_upload_file')) {
					
					$upload_data = $this->upload->data();
					
					$error_str = strip_tags($this->upload->display_errors());
					switch ( $upload_data['file_ext'] ) { 
						case '.xls':
						case '.xlsx':
								// DO NOTHING BECAUSE FILES ARE OKAY.
							break;
						default:
								$error_str .= ' Please upload a ".xls" or ".xlsx" file.';
							break;
					}
					
					array_push($errors, $error_str);
				}
				else {
					$upload_data = $this->upload->data();
					/* print_r($upload_data); */
					
					// Insert data to database.. and rename the file to "id-<filename>";
					$insert_data = array( 
						'project_id' => $project_id, 
						'project_category_id' => $project_category_id, 
						'original_file' => $original_file, 
						'uploaded_file' => $upload_filename, 
						'status' => 'PENDING', 
						'date_created' => date('Y-m-d H:i:s')
					);
					$boq_id = $this->md_boq->insert('boq_main_new', $insert_data);
					if ( $boq_id ) { 
						// Rename the file to "id-<filename>"
						$current_file = $upload_filename;
						$new_file =  $boq_id . '-' . $upload_filename;
						
						if ( rename($upload_path.$current_file, $upload_path.$new_file) ) {
							$update_data = array( 
								'uploaded_file' => $new_file
							);
							$this->md_boq->update2('boq_main_new', $update_data, array('id' => $boq_id));
						}
						
						$success_message .= 'File successfully uploaded.';
					}
				}
				
			}
		}
		
		$response = array( 
			'errors' => $errors, 
			'success_message' => $success_message
		);
		/* print_r($response); */
		
		$this->session->set_flashdata('upload_response', $response);
		redirect(base_url().index_page().'/boq/upload','refresh');
	}
	
	public function ajax_delete_boq() { 
		if ( !$this->input->is_ajax_request() ) { exit('Invalid access!...'); }
		
		$boq_id = $this->input->post('boq_id');
		$boq_main_data = $this->md_boq->get_row('boq_main_new', array('id' => $boq_id));
		
		$ctr = false;
		$msg = '';
		
		$where = array(
			'status' => 'PENDING', // OFC, dont delete completed status. ^_^
			'id' => $boq_id
		);
		
		$update_data = array(
			'status' => 'DELETED', 
			'date_updated' => date('Y-m-d H:i:s')
		);
		if ( $this->md_boq->update2('boq_main_new', $update_data, $where) ) {
			
			// Also delete the file associated with the record to save disk space.
			/* $upload_path = './uploads/boq_files/';
			$filename = $boq_main_data->uploaded_file;
			$file_fullpath = $upload_path.$filename;
			
			unlink($file_fullpath); */
			
			$ctr = true;
			$msg = 'BOQ has been successfully deleted.';
		}
		else { 
			$msg = 'BOQ cannot be deleted.';
		}
		
		$response = array( 
			'ctr' => $ctr,
			'msg' => $msg
		);
		
		echo json_encode($response);
	}
	
	public function boq_details($boq_main_id) { 
		$this->lib_auth->title = "BOQ Project Details";
		$this->lib_auth->build = "boq/boq_details_new_view";
		
		$data['boq_main_new'] = $this->md_boq->get_boq_main_new_data($boq_main_id);
		$this->lib_auth->render($data);
	}
	
	public function ajax_mark_as_completed() { 
		if ( !$this->input->is_ajax_request() ) { exit('Invalid access!...'); }
		
		$boq_main_id = $this->input->post('boq_main_id');
		
		$ctr = false;
		$msg = '';
		
		if ( $this->md_boq->has_novalue_boq_details($boq_main_id) ) { 
			$msg = "Cannot be marked as completed because there are items that has '#VALUE!'\nPlease marked those blank or double check the real value.";
		}
		else {
			$where = array( 
				'id' => $boq_main_id
			);
			$update_data = array( 
				'status' => 'COMPLETED', 
				'date_updated' => date('Y-m-d H:i:s')
			);
			$this->md_boq->update2('boq_main_new', $update_data, $where);
			
			$ctr = true;
			$msg = 'BOQ has been successfully marked as completed.';
		}
		
		$response = array( 
			'ctr' => $ctr, 
			'msg' => $msg
		);
		
		echo json_encode($response);
	}
	
	public function ajax_process_boq_details($boq_main_id) { 
		if ( !$this->input->is_ajax_request() ) { exit('Invalid access!...'); } 
		
		$ctr = false;
		$msg = '';
		
		if ( $this->md_boq->has_existing_boq_details($boq_main_id) ) { 
			$msg = "Can't process BOQ details.\nIt appears that it has been proccessed already.\nPlease check the BOQ Details page.";
		}
		else {
			$boq_main_data = $this->md_boq->get_row('boq_main_new', array('id' => $boq_main_id));
			
			
			
			if ( count($boq_main_data) ) { 
				$upload_path = './uploads/boq_files/';
				$filename = $boq_main_data->uploaded_file;
				$file_fullpath = $upload_path.$filename;
				
				if ( file_exists($file_fullpath) ) { 
					
					include 'Classes/PHPExcel/IOFactory.php';
					
					$inputFileName 	= $file_fullpath; 
					$objPHPExcel 	= PHPExcel_IOFactory::load($inputFileName);
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
					$arrayCount 	= count($allDataInSheet);  // Here get total count of row in that Excel sheet
					
					$boq_details = array();
					$sequence_no = 1;
					for ( $i=12; $i <= $arrayCount; $i++) {
						$col_A = trim($allDataInSheet[$i]['A']);
						$col_B = trim($allDataInSheet[$i]['B']);
						$col_C = trim($allDataInSheet[$i]['C']);
						$col_D = trim($allDataInSheet[$i]['D']);
						$col_E = trim($allDataInSheet[$i]['E']);
						$col_F = trim($allDataInSheet[$i]['F']);
						$col_G = trim($allDataInSheet[$i]['G']);
						$col_H = trim($allDataInSheet[$i]['H']);
						
						
						if ( ($col_A != '') || ($col_B != '') || ($col_C != '') || ($col_D != '') || ($col_E != '') || ($col_F != '') || ($col_G != '') || ($col_H != '') ) { 
							$boq_celldata = array( 
								'boq_main_id' => $boq_main_id, 
								'item_no' => $col_A, 
								'description' => $col_B, 
								'unit' => $col_C, 
								'qty' => $col_D, 
								'material' => $col_E, 
								'labor_and_other_cost' => ( is_numeric($col_F) ) ? number_format($col_F, 2) : $col_F, 
								'total' => ( is_numeric($col_G) ) ? number_format($col_G, 2) : $col_G, 
								'amount' => ( is_numeric($col_H) ) ? number_format($col_H, 2) : $col_H, 
								'sequence_no' => $sequence_no
							);
							array_push($boq_details, $boq_celldata);
							/* echo "$i - $col_A | $col_B | $col_C | $col_D | $col_E | $col_F | $col_G | $col_H";
							echo '<BR>'; */
							
							$sequence_no++;
						}
						
					}
					/* echo '<table border="1" style="border-collapse:collapse;">';
						echo '<tr>';
							echo '<th>Item NO.</th>';
							echo '<th>Description</th>';
							echo '<th>Unit</th>';
							echo '<th>Qty</th>';
							echo '<th>Material</th>';
							echo '<th>Labor and Other Cost</th>';
							echo '<th>Total</th>';
							echo '<th>Amount</th>';
						echo '</tr>';
						foreach ( $boq_details AS $boq_data ) { 
							echo '<tr>';
								echo '<td>'.$boq_data['item_no'].'</td>';
								echo '<td>'.$boq_data['description'].'</td>';
								echo '<td>'.$boq_data['unit'].'</td>';
								echo '<td>'.$boq_data['qty'].'</td>';
								echo '<td>'.$boq_data['material'].'</td>';
								echo '<td>'.$boq_data['labor_and_other_cost'].'</td>';
								echo '<td>'.$boq_data['total'].'</td>';
								echo '<td>'.$boq_data['amount'].'</td>';
							echo '</tr>';
						}
					echo '</table>'; */
					
					$this->md_boq->insert_batch('boq_details_new', $boq_details);
					
					//UPDATE boq_main.status = 'ON PROCESS'
					$where_boq_main = array('id' => $boq_main_id);
					$update_boq_main_data = array( 
						'status' => 'ON PROCESS', 
						'date_updated' => date('Y-m-d H:i:s')
					);
					$this->md_boq->update2('boq_main_new', $update_boq_main_data, $where_boq_main);
					
					$ctr = true;
					$msg = 'BOQ Details successfully processed. You will be redirected to BOQ Details page.';
				}
			}
		}
		
		$response = array( 
			'ctr' => $ctr, 
			'msg' => $msg
		);
		
		echo json_encode($response);
	}
	
	public function download_boq_file($boq_main_id) { 
		$boq_main_data = $this->md_boq->get_row('boq_main_new', array('id' => $boq_main_id));
		
		if ( count($boq_main_data) ) { 
			$upload_path = './uploads/boq_files/';
			$filename = $boq_main_data->uploaded_file;
			$file_fullpath = $upload_path.$filename;
			
			if ( file_exists($file_fullpath) ) { 
				/* $this->load->helper('MY_download_helper');
				force_download($filename, $file_fullpath);
				rcanz_force_download($file_fullpath); */
				/* $this->load->helper('download'); */
				
				/* $data = file_get_contents($file_fullpath); // Read the file's contents
				force_download('test.xlsx', $data); */
				redirect(base_url().'uploads/boq_files/'.$filename, 'refresh');
			}
			else { 
				exit($filename . ' - File does not exist.');
			}
		}
	}
	
	public function ajax_show_boq_table() { 
		if ( !$this->input->is_ajax_request() ) { exit('Invalid access...'); }
		
		$boq_records = $this->md_boq->get_boq_records(); ?>
		
		<table class="table table-boq-report">
			<thead>
				<tr>
					<th>ID</th>
					<th>PROJECT NAME</th>
					<th>PROJECT TYPE</th>
					<th>FILENAME</th>
					<th>STATUS</th>
					<th>DATE UPLOADED</th>
					<th>ACTION</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach ( $boq_records AS $boq ) { ?>
						<tr>
							<td><?php echo $boq->id; ?></td>
							<td><?php echo $boq->project_name; ?></td>
							<td><?php echo $boq->project_category_name; ?></td>
							<td>
								<?php echo $boq->uploaded_file; ?>
								<a class="download-file pointer" title="Download File" data-id="<?php echo $boq->id; ?>" target="_blank" href="index.php/boq/download_boq_file/<?php echo $boq->id; ?>"><i class="glyphicon glyphicon-circle-arrow-down"></i></a>
							</td>
							<td><?php echo $boq->status; ?></td>
							<td><?php echo $boq->date_created; ?></td>
							<td>
								<?php if ( $this->md_boq->has_existing_boq_details($boq->id) ) { ?>
									<a class="view-boq-details pointer" title="View BOQ Details" href="index.php/boq/boq_details/<?php echo $boq->id; ?>"><i class="glyphicon glyphicon-th-list"></i></a>
								<?php } ?>
								<?php if ( !$this->md_boq->has_existing_boq_details($boq->id) ) { ?>
									<button class="btn btn-primary nxt-btn process_boq_details" data-id="<?php echo $boq->id; ?>">Process BOQ Details</button><br/>
								<?php } ?>
									<a class="delete-boq close-link pointer" title="Delete" data-id="<?php echo $boq->id; ?>"><i class="glyphicon glyphicon-remove"></i></a>
							</td>
						</tr>
				<?php 
					} ?>
			</tbody>
		</table>
<?php 
	}
	
	public function ajax_update_boq_detail( $boq_detail_id ) { 
		if ( !$this->input->is_ajax_request() ) { exit('Invalid access!...'); }
		
		$ctr = false;
		$msg = '';
		
		$item_no = trim($this->input->post('item_no'));
		$description = trim($this->input->post('description'));
		$unit = trim($this->input->post('unit'));
		$qty = trim($this->input->post('qty'));
		$material = trim($this->input->post('material'));
		$labor_and_other_cost = trim($this->input->post('labor_and_other_cost'));
		$total = trim($this->input->post('total'));
		$amount = trim($this->input->post('amount'));
		
		$update_data = array( 
			'item_no' => $item_no, 
			'description' => $description, 
			'unit' => $unit, 
			'qty' => $qty, 
			'material' => $material, 
			'labor_and_other_cost' => $labor_and_other_cost, 
			'total' => $total, 
			'amount' => $amount, 
			'date_updated' => date('Y-m-d H:i:s')
		);
		$where = array( 
			'id' => $boq_detail_id
		);
		
		$this->md_boq->update2('boq_details_new', $update_data, $where);
		$ctr = true;
		$msg = 'BOQ detail successfully updated.';
		
		$response = array( 
			'ctr' => $ctr, 
			'msg' => $msg
		);
		
		echo json_encode($response);
	}
	
	public function ajax_popup_edit_boq_detail( $boq_detail_id ) { 
		if ( !$this->input->is_ajax_request() ) { exit('Invalid access!...'); }
		
		$boq_detail_data = $this->md_boq->get_row('boq_details_new', array('id' => $boq_detail_id)); ?>
		
		<div class="form-group" style="width: 500px;">
			<form id="frm-boq-details" action="" method="post">
				<table class="table">
					<tr>
						<td><label class="control-label" for="item_no">Item No.</label></td>
						<td><input type="text" id="item_no" name="item_no" value="<?php echo $boq_detail_data->item_no; ?>" class="form-control" /></td>
					</tr>
					<tr>
						<td><label class="control-label" for="description">Description.</label></td>
						<td>
							<textarea id="description" name="description" class="form-control" style="font-size: 10px; height: 150px;"><?php echo $boq_detail_data->description; ?></textarea>
						</td>
					</tr>
					<tr>
						<td><label class="control-label" for="unit">Unit</label></td>
						<td><input type="text" id="unit" name="unit" value="<?php echo $boq_detail_data->unit; ?>" class="form-control" /></td>
					</tr>
					<tr>
						<td><label class="control-label" for="qty">Qty</label></td>
						<td><input type="text" id="qty" name="qty" value="<?php echo $boq_detail_data->qty; ?>" class="form-control" /></td>
					</tr>
					<tr>
						<td><label class="control-label" for="material">Material</label></td>
						<td><input type="text" id="material" name="material" value="<?php echo $boq_detail_data->material; ?>" class="form-control" /></td>
					</tr>
					<tr>
						<td><label class="control-label" for="labor_and_other_cost">Labor & Other Cost</label></td>
						<td><input type="text" id="labor_and_other_cost" name="labor_and_other_cost" value="<?php echo $boq_detail_data->labor_and_other_cost; ?>" class="form-control" /></td>
					</tr>
					<tr>
						<td><label class="control-label" for="total">Total</label></td>
						<td><input type="text" id="total" name="total" value="<?php echo $boq_detail_data->total; ?>" class="form-control" /></td>
					</tr>
					<tr>
						<td><label class="control-label" for="amount">Amount</label></td>
						<td><input type="text" id="amount" name="amount" value="<?php echo $boq_detail_data->amount; ?>" class="form-control" /></td>
					</tr>
				</table>
			</form>
		</div>
		<div style="text-align: right;">
			<button id="btn-update-boq-detail" class="btn btn-primary nxt-btn" data-id="<?php echo $boq_detail_id; ?>"> UPDATE BOQ DETAIL </button>
			<a onclick="javascript: $.fancybox.close();" style="cursor: pointer; vertical-align: bottom;">Cancel</a>
		</div>
<?php 
	}
	
	public function ajax_show_pr_request_form() { 
		if ( !$this->input->is_ajax_request() ) {exit('Invalid access!...'); } 
		/* $this->session->unset_userdata('session_pr_items'); */
		$session_pr_items = (!is_null($this->session->userdata('session_pr_items')) && is_array($this->session->userdata('session_pr_items'))) ? $this->session->userdata('session_pr_items') : array(); 
		
		$prepared_by = $this->md_boq->get_employee_data($this->session->userdata('emp_id'));
		$requested_by = $this->md_boq->get_pr_websignatory('pr', 'requested_by');
		$checked_by = $this->md_boq->get_pr_websignatory('pr', 'checked_by');
		$approved_by = $this->md_boq->get_pr_websignatory('pr', 'approved_by');
		?>
		
		<div class="content-title">
			<h3>New Purchase Requisition</h3>
		</div>
		<div class="row" id="table-pr-items-container">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="table-responsive">
						<table id="item_add" class="table table-striped">
							<thead>
								<tr>
									<th style="display:none;">Description</th>
									<th>Item</th>
									<th>Item Description</th>
									<th>Brand</th>
									<th>Qty</th>
									<th>Unit Of Measure</th>
									<th>Model No</th>
									<th>Remarks</th>
									<th>For Project</th>
									<th>Charging</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									if ( is_array($session_pr_items) && count($session_pr_items) ) { 
										foreach ( $session_pr_items AS $index => $pr_items ) { ?>
											<tr>
												<td style="display:none;"><?php echo $pr_items['description']; ?></td>
												<td><?php echo $pr_items['item_no']; ?></td>
												<td><?php echo $pr_items['item_description']; ?></td>
												<td><?php echo $pr_items['brand']; ?></td>
												<td><?php echo $pr_items['qty']; ?></td>
												<td><?php echo $pr_items['unit']; ?></td>
												<td><?php echo $pr_items['model_no']; ?></td>
												<td><?php echo $pr_items['remarks']; ?></td>
												<td><?php echo $pr_items['for_usage']; ?></td>
												<td><?php echo $pr_items['charging']; ?></td>
												<td><a class="remove-pr-item close-link pointer" title="Delete" data-id="<?php echo $index; ?>"><i class="glyphicon glyphicon-remove"></i></a></td>
											</tr>
									<?php 
										}
									}
									else { ?>
										<tr>
											<td colspan='11'>No Data</td>				  		
										</tr>
								<?php 
									}?>
							</tbody>
							<tfoot>
								<tr>
									<td><span id='cnt-items'><?php echo count($session_pr_items); ?></span> item(s)</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tfoot>
					  </table>
					</div>
				</div>
			</div>
		</div>

		<div class="row" id="pr-main-info-container">
			<div class="col-md-12">
				<div class="panel panel-default">		
					<div class="panel-body">
						<div class="row">
							<form id="frm-purchase-request" action="" method="post">
								<div class="col-md-4">
									<div class="form-group col-md-6">
										<div class="control-label">PR NO.</div>
										<input name="pr_no" id="pr_no" class="form-control input-sm required" readonly="" type="text">
									</div>
									<div class="form-group col-md-6">
										<div class="control-label">DATE</div>
										<input type="text" id="pr_date" name="pr_date" value="<?php echo date('Y-m-d'); ?>" class="form-control input-sm required" readonly=""/>
									</div>
									<div class="form-group">
										<textarea id="pr_remarks" name="pr_remarks" cols="30" rows="5" class="form-control input-sm" placeholder="Remarks"></textarea>					  			
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<div class="control-label">Prepared by</div>
										<select name="prepared_by" id="prepared_by" class="form-control input-sm">
											<?php 
												foreach ( $prepared_by AS $person ) { ?>
													<option value="<?php echo $person->emp_number; ?>"><?php echo $person->person_name; ?></option>
											<?php 
												} ?>
										</select>
									</div>
									<div class="form-group">
										<div class="control-label">Request by</div>
										<select name="recommended_by" id="recommended_by" class="form-control input-sm">
											<option value=""> - Select Person - </option>
											<?php 
												foreach ( $requested_by AS $person ) { ?>
													<option value="<?php echo $person->emp_number; ?>"><?php echo $person->person_name; ?></option>
											<?php 
												} ?>
										</select>
									</div>
									<div class="form-group">
										<div class="control-label">Checked by</div>
										<select name="checked_by" id="checked_by" class="form-control input-sm">
											<option value=""> - Select Person - </option>
											<?php 
												foreach ( $checked_by AS $person ) { ?>
													<option value="<?php echo $person->emp_number; ?>"><?php echo $person->person_name; ?></option>
											<?php 
												} ?>
										</select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<div class="control-label">Priority</div>
										<div class="radio-inline">
											<input type="radio" id="priority" name="legend" value="1.)Priority / Emergency"><label for="priority">Priority / Emergency</label>
										</div>
										<div class="radio-inline">
											<input type="radio" id="regular" name="legend" value="2.)Regular" checked><label for="regular">Regular </label>
										</div>
										<input type="text" id="priority_date" name="priority_date" value="" />
									</div>
									<div class="form-group">
										<div class="control-label">Approved by</div>
										<select name="approved_by" id="approved_by" class="form-control input-sm">
											<option value=""> - Select Person - </option>
											<?php 
												foreach ( $approved_by AS $person ) { ?>
													<option value="<?php echo $person->emp_number; ?>"><?php echo $person->person_name; ?></option>
											<?php 
												} ?>
										</select>
									</div>
								</div>
							</form>
						</div>			  		
					</div>	 

					<div class="form-footer">			  	
						<div class="row">
							<div class="col-md-8"> </div>
							<div class="col-md-4">
								<input id="btn-save-pr" class="btn btn-success  col-xs-5 pull-right" type="submit" value="Save" />
							</div>
						</div>					
					</div>
				</div>
			</div>
		</div>
<?php 
	}
	
	public function ajax_process_save_new_pr($project_id) { 
		if ( !$this->input->is_ajax_request() ) { exit('Invalid access...'); }
		
		$ctr = false;
		$msg = '';
		
		$project_details = $this->md_boq->get_row('setup_project', array('project_id' => $project_id));
		
		/* print_r($this->input->post()); */
		$pr_no = trim($this->input->post('pr_no'));
		$pr_date = trim($this->input->post('pr_date'));
		$pr_remarks = trim($this->input->post('pr_remarks'));
		$prepared_by = trim($this->input->post('prepared_by'));
		$recommended_by = trim($this->input->post('recommended_by'));
		$checked_by = trim($this->input->post('checked_by'));
		$legend = trim($this->input->post('legend'));
		$priority_date = trim($this->input->post('priority_date'));
		$approved_by = trim($this->input->post('approved_by'));
		
		$pr_main_data = array( 
			'purchaseNo' => $pr_no, 
			'purchaseDate' => $pr_date, 
			'department' => '0', 
			'title_id' => '1',
			'project_id' => $project_id,
			'location' => '('. $project_details->project .') - ' . $project_details->project_location,
			'to_' => $project_details->project_location,
			'preparedBy' => $prepared_by, 
			'recommendedBy' => $recommended_by, 
			'checked_by' => $checked_by, 
			'approvedBy' => $approved_by, 
			'legend_' => $legend, 
			'requiredDate' => $priority_date, 
			'date_saved' => date('Y-m-d H:i:s'), 
			'pr_remarks' => $pr_remarks, 
			'is_boq_new' => '1'
		);
		
		$pr_id = $this->md_boq->insert('purchaserequest_main', $pr_main_data);
		
		//Transaction history:
		$history_data['from_projectCode'] = $project_id;
		$history_data['from_projectMain'] = '1';
		$history_data['to_projectCode'] = $project_id;
		$history_data['to_projectMain'] = '1';
		$history_data['type'] = 'Purchase Request';
		$history_data['status'] = 'PENDING';
		$history_data['reference_id'] = $pr_id;
		$this->md_boq->insert('transaction_history', $history_data);
		
		if ( (int)$pr_id ) { 
			$session_pr_items = (!is_null($this->session->userdata('session_pr_items')) && is_array($this->session->userdata('session_pr_items'))) ? $this->session->userdata('session_pr_items') : array();
			
			$pr_details_data = array();
			foreach ( $session_pr_items AS $pr_items ) { 
				$ctr_data = array( 
					'pr_id' => $pr_id, 
					'itemNo' => $pr_items['item_no'], 
					'itemDesc' => $pr_items['item_description'], 
					'qty' => $pr_items['qty'], 
					'req_qty' => $pr_items['qty'], 
					'unitmeasure' => $pr_items['unit'], 
					'modelNo' => $pr_items['model_no'], 
					'remarkz' => $pr_items['remarks'], 
					'for_usage' => $pr_items['for_usage'], 
					'is_boq_new' => '1', 
					'boq_id' => $pr_items['boq_id'], 
					'charging' => $pr_items['charging']
				);
				array_push($pr_details_data, $ctr_data);
			}
			$this->md_boq->insert_batch('purchaserequest_details', $pr_details_data);
			$this->session->unset_userdata('session_pr_items');
			
			$ctr = true;
			$msg = 'Purchase Request has been successfully created.';
		}
		
		$response = array( 
			'ctr' => $ctr, 
			'msg' => $msg
		);
		
		echo json_encode($response);
	}
	
	public function ajax_remove_pr_item() { 
		if ( !$this->input->is_ajax_request() ) {exit('Invalid access!...'); } 
		
		$index = $this->input->post('index');
		
		$session_pr_items = (!is_null($this->session->userdata('session_pr_items')) && is_array($this->session->userdata('session_pr_items'))) ? $this->session->userdata('session_pr_items') : array();
		
		$new_pr_items = array();
		foreach ( $session_pr_items AS $i => $pr_item ) { 
			if ( $index != $i ) { 
				array_push($new_pr_items, $pr_item);
			}
		}
		$this->session->set_userdata('session_pr_items', $new_pr_items);
	}
	
	public function ajax_add_pr_item( $boq_detail_id ) { 
		if ( !$this->input->is_ajax_request() ) {exit('Invalid access!...'); } 
		
		$boq_detail_data = $this->md_boq->get_row('boq_details_new', array('id' => $boq_detail_id));
		$boq_main_new_data = $this->md_boq->get_boq_main_new_data($boq_detail_data->boq_main_id);
		
		$boq_id			= $boq_detail_data->id;
		$description 	= $boq_detail_data->description;
		$item_no 		= trim($this->input->post('item'));
		$item_description = trim($this->input->post('item_description'));
		$unit 			= trim($this->input->post('unit'));
		$qty 			= trim($this->input->post('qty'));
		$brand 			= trim($this->input->post('brand'));
		$model_no 		= trim($this->input->post('model_no'));
		$remarks 		= trim($this->input->post('remarks'));
		$for_usage 		= $boq_main_new_data['project_category_name'];
		$charging 		= $boq_detail_data->item_no;
		
		$item_data = array( 
			'item_no' => $item_no,  
			'description' => $description, 
			'item_description' => $item_description, 
			'unit' => $unit, 
			'qty' => $qty, 
			'brand' => $brand, 
			'model_no' => $model_no, 
			'remarks' => $remarks, 
			'for_usage' => $for_usage, 
			'boq_id' => $boq_id, 
			'charging' => $charging 
		);
		
		$session_pr_items = (!is_null($this->session->userdata('session_pr_items')) && is_array($this->session->userdata('session_pr_items'))) ? $this->session->userdata('session_pr_items') : array();
		
		array_push($session_pr_items, $item_data);
		$this->session->set_userdata('session_pr_items', $session_pr_items);
	}
	
	public function ajax_popup_pr_add_item( $boq_detail_id ) { 
		if ( !$this->input->is_ajax_request() ) {exit('Invalid access!...'); } 
		
		$boq_detail_data = $this->md_boq->get_row('boq_details_new', array('id' => $boq_detail_id)); ?>
		
		<div class="form-group" style="width: 500px;">
			<form id="frm-boq-details" action="" method="post">
				<table class="table">
					<tr>
						<td><label class="control-label" for="charging">Charging</label></td>
						<td><?php echo $boq_detail_data->item_no; ?></td>
					</tr>
					<tr>
						<td><label class="control-label" for="item_no">BOQ ID</label></td>
						<td><?php echo $boq_detail_data->id; ?></td>
					</tr>
					<tr>
						<td><label class="control-label" for="description">Description</label></td>
						<td>
							<?php echo $boq_detail_data->description; ?>
						</td>
					</tr>
					<tr>
						<td><label class="control-label" for="item">Item</label></td>
						<td>
							<input type="text" name="item" id="item" style="width:100%" placeholder="Search item Description" />
							<input type="hidden" name="item_description" id="item_description" />
						</td>
					</tr>
					<tr>
						<td>Unit</td>
						<td><input type="text" name="unit" id="unit" value="" readonly="readonly" style="border: 1px solid #ccc; padding: 2px; background: #ccc;" /></td>
					</tr>
					<tr>
						<td><label class="control-label" for="qty">Qty</label></td>
						<td><input type="text" id="qty" name="qty" value="" class="form-control" /></td>
					</tr>
					<tr>
						<td><label class="control-label" for="brand">Brand</label></td>
						<td><input type="text" id="brand" name="brand" value="" class="form-control" /></td>
					</tr>
					<tr>
						<td><label class="control-label" for="model_no">Model No</label></td>
						<td><input type="text" id="model_no" name="model_no" value="" class="form-control" /></td>
					</tr>
					<tr>
						<td><label class="control-label" for="remarks">Remarks</label></td>
						<td><input type="text" id="remarks" name="remarks" value="" class="form-control" /></td>
					</tr>
				</table>
			</form>
		</div>
		<div style="text-align: right;">
			<button id="btn-pr-add-item" class="btn btn-primary nxt-btn" data-id="<?php echo $boq_detail_id; ?>"> ADD ITEM </button>
			<a onclick="javascript: $.fancybox.close();" style="cursor: pointer; vertical-align: bottom;">Cancel</a>
		</div>
		<script type="text/javascript">
			$('#item').select2({
				placeholder: "Search Items Here",
				allowClear: true,
				ajax: {
					url: '<?php echo site_url("procurement/purchase_request/get_items"); ?>',
					dataType: 'json',
					type: "GET",
					quietMillis: 50,
					data: function (term) {
						return {
							q: term
						};
					},				     
					results: function (data){
						return {
							results: $.map(data, function (item) {					                              
								return {
									text: item.description,
									id  : item.group_detail_id,
									group_id : item.group_id,
									account_id : item.account_id,
									unit_cost : item.unit_cost,
									unit      : item.unit_measure,
								}
							})
						};
					}
				},initSelection: function (element, callback){
						var id = $(element).val();
				}
			});
		</script>
<?php 
	}
	
	public function ajax_show_boq_details( $boq_main_id ) { 
		if ( !$this->input->is_ajax_request() ) { exit('Invalid access!...'); }
		
		$boq_main_new = $this->md_boq->get_row('boq_main_new', array('id' => $boq_main_id));
		$boq_details_new = $this->md_boq->get_data('boq_details_new', array('boq_main_id' => $boq_main_id));
		
		if ( $this->md_boq->has_novalue_boq_details($boq_main_id) || ( $boq_main_new->status != 'COMPLETED' ) ) { ?>
			<div class="row">
				<div class="col-md-3" style="margin-bottom: 10px;">
					<button id="btn-mark-completed" class="btn btn-primary nxt-btn" data-id="<?php echo $boq_main_id; ?>">Mark As Completed</button>
				</div>
			</div>
	<?php 
		} ?>
		<div class="row">
			<div class="col-md-12" id="tbl-boq-details">
				<table class="table table-boq-details">
					<thead>
						<tr>
							<td class="thead" rowspan="2">&nbsp;</td>
							<td class="thead" rowspan="2">&nbsp;</td>
							<td class="thead" rowspan="2">&nbsp;</td>
							<td class="thead" colspan="5">UNIT COST</td>
							<td class="thead" colspan="3">ACTUAL PO</td>
							<td class="thead">REM / DISCREPANCY</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="col-md-1 thead">ITEM NO.</td>
							<td class="col-md-5 thead">DESCRIPTION</td>
							<td class="col-md-1 thead">UNIT</td>
							
							<td class="col-md-1 thead">QTY</td>
							<td class="col-md-1 thead">MATERIAL</td>
							<td class="col-md-1 thead">LABOR & OTHER COST</td>
							<td class="col-md-1 thead">TOTAL</td>
							<td class="col-md-1 thead">AMOUNT</td>
							
							<td class="col-md-1 thead">QTY</td>
							<td class="col-md-1 thead">UNIT</td>
							<td class="col-md-1 thead">AMOUNT</td>
							
							<td class="col-md-1 thead">+/-</td>
							
							<td class="col-md-1 thead">ACTION</td>
						</tr>
						<?php 
							foreach ( $boq_details_new AS $boq_detail ) { ?>
								<tr>
									<td><?php echo $boq_detail->item_no; ?></td>
									<td class="text-left"><?php echo $boq_detail->description; ?></td>
									<td><?php echo $boq_detail->unit; ?></td>
									<td><?php echo $boq_detail->qty; ?></td>
									<td class="text-right"><?php echo $boq_detail->material; ?></td>
									<td class="text-right" <?php echo ( $boq_detail->labor_and_other_cost == '#VALUE!' ) ? 'style="background: #ff0000;"' : ''; ?>><?php echo $boq_detail->labor_and_other_cost; ?></td>
									<td class="text-right" <?php echo ( $boq_detail->total == '#VALUE!' ) ? 'style="background: #ff0000;"' : ''; ?>><?php echo $boq_detail->total; ?></td>
									<td class="text-right" <?php echo ( $boq_detail->amount == '#VALUE!' ) ? 'style="background: #ff0000;"' : ''; ?>><?php echo $boq_detail->amount; ?></td>
									
									<td class="text-right">&nbsp;</td>
									<td class="text-right">&nbsp;</td>
									<td class="text-right">&nbsp;</td>
									<td class="text-right">&nbsp;</td>
									
									<td>
										<a class="edit-boq-detail close-link pointer" title="Edit" data-id="<?php echo $boq_detail->id; ?>"><i class="glyphicon glyphicon-pencil"></i></a>
									<?php if ( floatval($boq_detail->amount) ) { ?>
										<button class="btn btn-primary nxt-btn btn-purchase-request" data-id="<?php echo $boq_detail->id; ?>">Purchase Request</button>
									<?php } ?>
									</td>
								</tr>
						<?php 
							} ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php 
	}
	
}
