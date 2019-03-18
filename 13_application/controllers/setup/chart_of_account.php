<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Chart_of_account extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->lib_auth->default = "default-accounting";
		
		$this->load->model(array('setup/md_chart_of_account'));

	}

	public function index(){

		$this->lib_auth->title = "Chart of Account";		
		$this->lib_auth->build = "setup/chart_of_account/index";		
		$this->lib_auth->render();
		
	}


	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-hover">' );
		$this->table->set_template($tmpl);

		$result = $this->md_chart_of_account->get_cumulative();		

		$show = array(
					array('data'=>'account_id','style'=>'display:none'),
					array('data'=>'status','style'=>'display:none'),
					'<input type="checkbox" class="chk-main">',
					'ACCOUNT',
					'CLASSIFICATION',
					'SUBCLASS',
					'ACCOUNT TITLE',
					'Code',
					'Action'
			 	);
			foreach($result->result_array() as $key => $value){

				$row_content = array();
				$row_content[] = array('data'=>$value['account_id'],'class'=>'account_id','style'=>'display:none');
				$row_content[] = array('data'=>$value['status'],'class'=>'status','style'=>'display:none');
				$row_content[] = array('data'=>$this->checkbox($value['INCLUDE?']),'class'=>'include','style'=>'');
				$row_content[] = array('data'=>$value['ACCOUNT'],'class'=>'account','style'=>'');
				$row_content[] = array('data'=>$value['CLASSIFICATION'],'class'=>'classification');
				$row_content[] = array('data'=>$value['SUBCLASS'],'class'=>'subclass');
				$row_content[] = array('data'=>$value['ACCOUNT TITLE'],'class'=>'account_title');
				$row_content[] = array('data'=>$value['sub_classification_code'],'class'=>'sub_classification_code');
				$row_content[] = array('data'=>'<a href="javascript:void(0)" class="delete">Delete</a>','class'=>'');

				
				$this->table->add_row($row_content);
				
			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();
			
	}
	private function checkbox($type){
		$checkbox = (strtoupper($type)=='TRUE')? "checked='checked'": "" ;
		return "<input type='checkbox' class='chk-list' $checkbox  value='".$type."'>";		
	}



	public function save_changes(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_chart_of_account->save_changes();	

	}

	public function delete(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_chart_of_account->delete($this->input->post('id'));
	}

}
