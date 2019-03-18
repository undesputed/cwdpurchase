<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Manage extends CI_Controller {

	public function __construct(){
		parent :: __construct();		

		$this->load->model(array('manage/md_manage'));		
		if($this->session->userdata('type_user') != 'admin'){
			redirect(base_url().index_page(),'refresh');
		}

	}

	public function index(){


		$this->lib_auth->title = "Manage Users";		
		$this->lib_auth->build = "manage/view";

		$data['table'] = $this->_userList();		
		$this->lib_auth->render($data);
			
	}


	public function create(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$this->load->view("manage/create");		
	}

	public function save(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo $this->md_manage->save();
	}


	private function _userList(){

		$result = $this->md_manage->getUsers();
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-condensed tbl-event table-hover">' );
		$this->table->set_template($tmpl);

			$show = array(
						'Id',
						'Name',
						'UserName',						
						'User Type',
						'Status',
						'Action',
				 		);
				foreach($result->result_array() as $key => $value){
					$row_content = array();

					$row_content[] = array('data'=>$this->checker($value['id']),'class'=>'id');
					$row_content[] = array('data'=>$this->checker($value['firstname']));
					$row_content[] = array('data'=>$this->checker($value['username']));				
					$row_content[] = array('data'=>$this->checker($value['position']));					
					$row_content[] = array('data'=>$this->checker($value['status']));
					$label = ($value['status']=='ACTIVE')? 'Block' : 'Unblock' ;
					$row_content[] = array('data'=>'<span class=" btn-link block">'.$label.'</span> | <span class="btn-link update"> Update</span> ');
					$this->table->add_row($row_content);

				}
		
				$this->table->set_heading($show);
				return  $this->table->generate();

	}

	

	private function checker($value){
		return (!empty($value))? $value : "-";
	}

	public function update(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['update'] = $this->md_manage->getUserId($this->input->post('id'));
		$this->load->view("manage/update",$data);

	}

	public function update_position(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['update'] = $this->md_manage->getUserId($this->input->post('id'));
		$this->load->view("manage/update_position",$data);

	}


	public function run_update(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		$this->md_manage->update($this->input->post('id'));	
	}

		public function run_update_position(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		
		$this->md_manage->update_position($this->input->post('id'));	
	}

	public function block(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$this->md_manage->block($this->input->post('id'));
		return true;
	}

}