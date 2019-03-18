<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Journal_report extends CI_Controller {

	public function __construct(){
		parent :: __construct();	

		$this->load->model('accounting/md_journalReport');	
		$this->lib_auth->default = "default-accounting";

	}

	public function index(){

		$this->lib_auth->title = "Journal Report";
		$this->lib_auth->build = "accounting/journal_report/index";
			
		$this->lib_auth->render();

	}


	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-hover">' );
		$this->table->set_template($tmpl);

		$heading = array(
				array('data'=>'journal_id','style'=>'display:none'),
				array('data'=>'<input type="checkbox" class="chk-header">'),
				array('data'=>'Status'),
				array('data'=>'Reference No.'),
				array('data'=>'Transaction'),
				array('data'=>'Type'),
				array('data'=>'Memo'),
				array('data'=>'Amount'),
				array('data'=>'Location'),
			);
		$this->table->set_heading($heading);

		$result = $this->md_journalReport->get_journal();

		foreach($result as $row){

				$row_content = array(
					array('data'=>$row['journal_id'],'style'=>'display:none'),
					array('data'=>$this->checkbox($row['Post?'],$row['journal_id'])),
					array('data'=>$this->extra->label($row['Status'])),
					array('data'=>$row['Reference No.']),
					array('data'=>$row['Transaction Date']),
					array('data'=>$row['Type']),
					array('data'=>(empty($row['Memo']))? '-' : $row['Memo'] ),
					array('data'=>$this->extra->number_format($row['amount'])),
					array('data'=>$row['Location']),
					);
				$this->table->add_row($row_content);
				
		}
		echo $this->table->generate();
	}

	private function checkbox($type,$journal){
		$check = (strtoupper($type)=="TRUE")? 'checked="checked"' : '';
		return "<input type='checkbox' class='chk-post' value='' ".$check." data-journal='$journal'>";
	}

	private function number_format($value){
		if(is_numeric($value)){
			return number_format($value,2,'.',',');
		}else{
			return '';
		}
	}

	public function save_journal(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_journalReport->save_journal();

	}



}