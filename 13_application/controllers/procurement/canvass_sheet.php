<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Canvass_sheet extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('procurement/md_canvass_sheet');		
		$this->load->model('procurement/md_purchase_request');
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);	
	}

	public function index(){

		redirect('procurement/canvass_sheet/cumulative','refresh');
		
	}


	public function create_form(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['approved_pr'] =  $this->md_canvass_sheet->get_approved_pr();
		$this->load->view('procurement/canvass_sheet/create',$data);
	}



	public function cumulative(){
		$this->lib_auth->title = "Canvass Sheet";		
		$this->lib_auth->build = "procurement/canvass_sheet/cumulative";
		
		$this->lib_auth->render();
	}

	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_canvass_sheet->get_cumulative();

		$show = array(
					'CANVASS #',
					'DATE',
					'PR NO',
					'PR DATE',
					'APPROVED BY',
					'PREPARED BY',
					'STATUS',
			 		);
		
		$header = $show;
			foreach($result->result_array() as $key => $value){
				$row_content = array();
				$header = array();
				foreach($value as $key1 => $value1){
						$value1 = (isset($value1))? $value1 : '';
						if(in_array($key1,$show)){
							switch($key1){
								case "APPROVED":
								case "STATUS":
									$row_content[] = array('data'=>$this->extra->label($value1));
								break;
								case "PR DATE":
								case "DATE":
									/*$row_content[] = array('data'=>$value1);*/
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
	}

	public function supplier(){
		if(isset($_POST)){
			switch($this->input->post('type')){
				case"PERSON":
						echo json_encode($this->md_canvass_sheet->supplier());
					break;
				case"BUSINESS":
						echo json_encode($this->md_canvass_sheet->business());
					break;
			}
		}
	}

	public function get_pr_details(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$result = $this->md_canvass_sheet->get_pr_details($this->input->post('pr_id'));
		echo json_encode($result);
				
	}


	public function save_canvass(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo  $this->md_canvass_sheet->save_canvass();			
	}

	public function save_canvass2(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$post = $this->input->post();		
		$prepared_by = $post['preparedBy'];
		$prepared_by = (!empty($post['preparedBy']) || $post['preparedBy']=="")? $this->session->userdata('emp_id') : $post['preparedBy'];
		$arg['c_number']   = $post['c_number'];
		$arg['c_date']     = $post['c_date'];
		$arg['pr_id']      = $post['pr_id'];
		$arg['is_boq_new'] = $post['is_boq_new'];
		$arg['approvedBy'] = $post['approvedBy'];
		$arg['preparedBy'] = $prepared_by;
		$arg['title_id']   = $this->session->userdata('Proj_Main');
		$arg['project_id'] = $this->session->userdata('Proj_Code');
			
		if($this->md_canvass_sheet->saveCanvass($arg)){
			$this->session->set_flashdata(array('message'=>'Successfully Save Canvass ! ','type'=>'alert-success'));
			echo 1;	
		}else{
			echo 0;
		}

	}	

	public function get_cumulative_detail(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_canvass_sheet->cumulative_details();
		$main   = $this->md_canvass_sheet->canvass_main();
			
		$option = array(
			'result' => $result,					
			'table_class'=>array('table','table-striped')
			);
		$data['main']     = $main;
		$data['table']    = $this->extra->generate_table($option);
		// $data['approved'] = ($main['approvedPR']=='True')? true : false;
		$data['type']     = 'CS';
		$data['id']       = $this->input->post('canvass_id');
		$data['status']   = $main['status'];
		$data['edit_url'] = 'procurement/canvass_sheet/edit_form';
		$this->load->view('template/cumulative_2',$data);

	}


	public function get_supplier(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}	

		$tmpl = array ( 'table_open'  => '<table class="table tbl-supplier table-striped">' );
		$this->table->set_template($tmpl);

		$result = $this->md_canvass_sheet->get_supplier($this->input->post('can_id'),$this->input->post('item_no'));
		$this->table->set_heading(array(
			'Approve',
			'Supplier',
			'Unit',
			'Stock',
			'Unit Price',
			'QTY',
			'REM',
			'Total',
			));
		foreach($result as $key => $value){
			$row = array();
			$row[]= array('data'=>$this->checkbox($value));
			$row[]= array('data'=>$value['supplier_id'],'style'=>'display:none');
			$row[]= array('data'=>$value['SUPPLIER']);
			$row[]= array('data'=>$value['UNIT']);
			$row[]= array('data'=>$value['STOCKS']);
			$row[]= array('data'=>$value['UNIT PRICE']);
			$row[]= array('data'=>$value['QTY']);
			$row[]= array('data'=>$value['REM']);
			$row[]= array('data'=>$value['TOTAL']);
			$row[]= array('data'=>$value['stocks_sup'],'style'=>'display:none');

			$this->table->add_row($row);

		}
		echo $this->table->generate();
		
	}

	private function checkbox( $type ){
		$approved = strtoupper($type['APPROVE?']);
		$checked = ($approved=='TRUE')? 'checked' : '';
		return "<input type='checkbox' value='' class='approved-chk' ".$checked." data-can-id='".$this->input->post('can_id')."' data-item-no='".$this->input->post('item_no')."' data-supplier-id='".$type['supplier_id']."' data-qty='".$type['QTY']."' data-sup-qty='".$type['stocks_sup']."' data-stocks='".$type['STOCKS']."'>";
	}


	public function change_status_supplier(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
	
		if(count($this->input->post('data')) > 0){			
				$this->md_canvass_sheet->update_approvedBy($this->input->post('data'));
				foreach($this->input->post('data') as $key => $value){
					$this->md_canvass_sheet->update_canvass_details($value);
				}		

		}
		echo "1";

	}


	public function canvass_detail_status(){

		if(!$this->input->is_ajax_request())
		{
			exit(0);
		}		
		$data = $this->input->post();
		$output = $this->md_canvass_sheet->update_canvass_details_status($data);

		echo json_encode($output);
	}

	
	public function edit_form(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$data['approved_pr'] =  $this->md_canvass_sheet->get_approved_pr();

		$data['main']     = $this->md_canvass_sheet->canvass_main_id();
		$data['supplier'] = json_encode($this->md_canvass_sheet->get_canvassDetails());
		$data['details']  = json_encode($this->md_canvass_sheet->canvass_display_item());
		
		$this->load->view('procurement/canvass_sheet/edit',$data);
	}


	public function update_canvass(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_canvass_sheet->update_canvass();

	}


	public function add_supplier(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$arg = $this->input->post();

		$data['supplier'] = '';
		if($arg['type'] == 'affiliate'){
			$data['supplier'] = $this->md_project->get_supplier_affiliate($arg['supp_id']);	
		}else{
			$data['supplier'] = $this->md_project->get_supplier_business($arg['supp_id']);	
		}

		$data['pr_details'] = $this->md_purchase_request->get_pr_details($arg['pr_id']);
		$this->load->view('procurement/canvass_sheet/add_supplier',$data);
	}





}