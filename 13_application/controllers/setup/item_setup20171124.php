<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Item_setup extends CI_Controller {

	public function __construct(){

		parent :: __construct();		
		$this->load->model('setup/md_item_setup');

	}
	
	public function index(){

		$data['item_group'] = $this->md_item_setup->get_item_group();

		$data['account_classification'] = $this->md_item_setup->get_account_classification(array('EXPENSES','ASSETS'));
				
		$this->lib_auth->title = "Item Setup";		
		$this->lib_auth->build = "setup/item_setup/index";		
		$this->lib_auth->render($data);

	}

	public function get_data(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);	

		$result = $this->md_item_setup->get_cumulative();

		$show = array(
				'Item No',
				'Item Category',
				'Item Description',
				'Unit Measure',				
				array('data'=>'item_quantity','style'=>'display:none'),
				array('data'=>'group_id','style'=>'display:none'),
				'Action',
			 	);
			
			foreach($result->result_array() as $key => $value){
				$row_content   = array();
				$row_content[] = array('data'=>$value['group_detail_id'],'class'=>'id');
				$row_content[] = array('data'=>$value['group_description'],'class'=>'group_description');
				$row_content[] = array('data'=>$value['description'],'class'=>'description');				
				$row_content[] = array('data'=>$value['unit_measure'],'class'=>'unit_measure');	
				$row_content[] = array('data'=>$value['quantity'],'class'=>'quantity','style'=>'display:none');	
				$row_content[] = array('data'=>$value['group_id'],'class'=>'group_id','style'=>'display:none');	
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span> <span class="event">|</span> <span class="btn-link event remove_class">Remove</span>','class'=>'');
				$this->table->add_row($row_content);
				
			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();


	}

	public function save_item(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_item_setup->save_item();

	}


	public function item_group(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		$this->load->view('setup/item_setup/item_group');
	}

	public function edit_group(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data = $this->input->post();
		$this->load->view('setup/item_setup/edit_group',$data);

	}

	public function save_item_group(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo $this->md_item_setup->save_item_group($arg);

	}

	public function update_item_group(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo $this->md_item_setup->update_item_group($arg);

	}

	public function get_group_item(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$group_id = $this->input->post('group_id');
		$item_group = $this->md_item_setup->get_item_group();
		$div = "";
		
		foreach($item_group as $row){
			$selected  = ($row['group_id'] == $group_id)? 'selected' : '' ;
			$div .="<option  ".$selected." value='".$row['group_id']."'>".$row['group_description']."</option>";
		}
		
		echo $div;

	}
	public function delete(){
		if(!$this->input->is_ajax_request()){
					exit(0);
		}

		echo $this->md_item_setup->delete($this->input->post('group_detail_id'));

	}
	

	public function get_account_setup(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$class_code = $this->input->post('class_code');
		$result = $this->md_item_setup->get_account_setup($class_code);
		echo json_encode($result);

	}



}