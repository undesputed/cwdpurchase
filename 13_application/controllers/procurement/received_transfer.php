<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Received_transfer extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('procurement/md_received_transfer');

	}


	public function index(){

		$this->lib_auth->title = "Received Transfer";		
		$this->lib_auth->build = "procurement/received_transfer/index";			
		$this->lib_auth->render();
		
	}

	public function get_receivedTransfer(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table po_table table-striped">' );
		$this->table->set_template($tmpl);

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
				
		$result = $this->md_received_transfer->get_received_transfer($this->input->post('location'));

		$show = array(
					 array('data'=>'dispatch_id','style'=>'display:none'),
					'Ref.no',
					'Ref.date',
					'Status',
					'Location from',
			 		);
		
		foreach($result->result_array() as $key => $value){
			$row_content = array();
			$row_content[] = array('data'=>$value['dispatch_id'],'style'=>'display:none','class'=>'id');
			$row_content[] = array('data'=>$value['REF. NO.'],'style'=>'','class'=>'ref_no');
			$row_content[] = array('data'=>$value['REF. DATE'],'style'=>'','class'=>'ref_date');
			$row_content[] = array('data'=>$value['STATUS'],'style'=>'','class'=>'stats');
			$row_content[] = array('data'=>$value['LOCATION FROM'],'style'=>'','class'=>'location');			
			$this->table->add_row($row_content);			
		}

		$this->table->set_heading($show);
		echo $this->table->generate();


	}

	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table table-cumulative table-striped">' );
		$this->table->set_template($tmpl);

		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		$result = $this->md_received_transfer->get_cumulative();

		$show = array(
					'Receipt Number',
					'Received Date',
					'Location From',
					'Received By',
					'Checked By',
					'Delivered By',
			 		);
		
		foreach($result->result_array() as $key => $value){
			
			$row_content = array();
			$row_content[] = array('data'=>$value['Receipt Number'],'style'=>'','class'=>'');
			$row_content[] = array('data'=>$value['Received Date'],'style'=>'','class'=>'');
			$row_content[] = array('data'=>$value['Location From'],'style'=>'','class'=>'');
			$row_content[] = array('data'=>$value['Received By'],'style'=>'','class'=>'');			
			$row_content[] = array('data'=>$value['Checked By'],'style'=>'','class'=>'');	
			$row_content[] = array('data'=>$value['Delivered By'],'style'=>'','class'=>'');	
			$this->table->add_row($row_content);			
		}

		$this->table->set_heading($show);
		echo $this->table->generate();


	}

	public function received(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['location_info'] = array(
			'location' =>$this->input->post('location'),
			'project'  =>$this->input->post('project'),
			);
		$data['main'] = $this->md_received_transfer->get_singleMain($this->input->post('id'),$this->input->post('location'));		
		$data['item'] = $this->md_received_transfer->get_details($this->input->post('id'));
		$data['names'] = $this->md_received_transfer->get_names();
				
		$this->load->view('procurement/received_transfer/received',$data);		
	}

	public function save(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_received_transfer->save();
				
	}


}