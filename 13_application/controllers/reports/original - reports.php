<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('reports/md_reports');
	}


	public function index(){

		$dates = date('F-Y',time());
		
		redirect("reports/dates/$dates");

		$this->lib_auth->title = "Reports";		
		$this->lib_auth->build = "reports/reports/view";	

		$data['sidebar'] = $this->generate_date();
		$this->lib_auth->render($data);

		/*		
		if($this->form_validation->run()==TRUE){
		
				if(!$this->upload->do_upload('file')){
										
					$message['content'] = $this->upload->display_errors();
					$message['type'] = "alert-danger";
										
				}else{
					
					$data = array('upload_data' => $this->upload->data());
					$this->md_reports->create($data['upload_data']['full_path']);
					$message['content'] = "Successfully Save";
					$message['type'] = "alert-success";
					
				}
				
				$this->session->set_flashdata(array('message'=>$message['content'],'type'=>$message['type']));
				redirect(current_url());
		}		
		*/
	}


	public function dates($dates){

		if(empty($dates)){
			redirect('reports');
		}
				
		$this->lib_auth->title = "Reports";		
		$this->lib_auth->build = "reports/reports/view";	

		$data['sidebar'] = $this->generate_date($dates);
		$data['table']   = $this->get_data($this->format_date_reverse($dates));
		$this->lib_auth->render($data);

	}




	public function execute_create(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		print_r($this->input->post());

	}


	public function create(){

		if(!$this->extra->is_admin()){
			redirect('reports');				
		}

		//$this->load->view('reports/reports/create');
			
		$this->lib_auth->title = "Create - Reports";
		$this->lib_auth->build = "reports/reports/create";	
		$data['sidebar'] = $this->generate_date();		
		
		$this->form_validation->set_rules("subject","Subject","required");		

		if($this->form_validation->run()==TRUE){
				
				$this->lib_upload->makeDir();
				$config['upload_path'] = $this->lib_upload->upload_path;

				$config['allowed_types'] = 'pdf';
				$config['max_size'] = '10000';
				$this->upload->initialize($config);

				if(!$this->upload->do_upload('file')){

					$message['content'] = $this->upload->display_errors();
					$message['type'] = "alert-danger";
					
				}else{

					$data = array('upload_data' => $this->upload->data());					
					$this->md_reports->create($this->lib_upload->url_path.'/'.$data['upload_data']['file_name']);
					$message['content'] = "Successfully Save";
					$message['type'] = "alert-success";

				}

				$this->session->set_flashdata(array('message'=>$message['content'],'type'=>$message['type']));
				redirect(current_url());
		}

		$this->lib_auth->render($data);	

	}


	public function update($id){

		if(empty($id) || !is_numeric($id)){

			redirect('reports');

		}

		$row = $this->md_reports->get_row($id);
		//$this->load->view('reports/reports/create');
				
		$this->lib_auth->title = "Update - Reports";
		$this->lib_auth->build = "reports/reports/update";	
		$data['sidebar'] = $this->generate_date();
		$data['row'] = $row;

		$this->form_validation->set_rules("subject","Subject","required");		
		if($this->form_validation->run()==TRUE){

				if(!$this->upload->do_upload('file')){

					$message['content'] = $this->upload->display_errors();
					$message['type'] = "alert-danger";
					
				}else{

					$data = array('upload_data' => $this->upload->data());
					$this->md_reports->update($id,$data['upload_data']['full_path']);
					$message['content'] = "Successfully Save";
					$message['type'] = "alert-success";
				}

				$this->session->set_flashdata(array('message'=>$message['content'],'type'=>$message['type']));
				redirect(current_url());
		}		
		$this->lib_auth->render($data);
	
	}


	public function get_data($get_date){

		/*if(!$this->input->is_ajax_request()){
			exit(0);
		}*/

		$result = $this->md_reports->view($get_date);

		$tmpl = array ( 'table_open'  => '<table class="table myTable tbl-event table-striped">' );
		$this->table->set_template($tmpl);
		
		if($this->extra->is_admin()){
			$show = array(
					array('data'=>'id','style'=>'display:none'),
					array('data'=>'Date','style'=>'width:100px;'),
					array('data'=>'Subject','style'=>'width:800px;'),				
					array('data'=>'Action','style'=>'width:100px;'),
			);

		}else{
			$show = array(
					array('data'=>'id','style'=>'display:none'),
					array('data'=>'Date','style'=>'width:100px;'),
					array('data'=>'Subject','style'=>'width:800px;'),									
			);
		}
			
				$dates = array();
				foreach($result->result_array() as $key => $value){
					$row_content = array();

					$sub_date = $this->format_date($value['submission_date']);

					if(in_array($sub_date,$dates)){							
						$submission_date = "";
					}else{
						$dates[] = $sub_date;
						$submission_date = $sub_date;
					}

					$row_content[] = array('data'=>$value['id'],'style'=>'display:none','class'=>'id');
					$row_content[] = array('data'=>$submission_date);
					$row_content[] = array('data'=>'<a href="javascript:void(0)">'.$value['subject'].'</a>','class'=>'viewpdf');		
					if($this->extra->is_admin()){	
						$row_content[] = array('data'=>'<a href="'.base_url().index_page().'/reports/update/'.$value['id'].'" class="event update">Update</a>');
					}

					$this->table->add_row($row_content);
					
				}
				
		$this->table->set_heading($show);
		return  $this->table->generate();		

	}

	private function format_date($date){
		return date('F - d',strtotime($date));
	}
	private function format_date_reverse($date){
		return date('Y-m-d',strtotime($date));	
	}

	public function view_pdf(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['row'] = $this->md_reports->get_row($this->input->post('id'));
		$this->load->view('reports/reports/fancy',$data);

	}

	public function generate_date($dates = ""){
		
		$result = $this->md_reports->generate_date();		
		$output = "";
		foreach($result as $row){

			$row_date = date('F Y',strtotime($row['submission_date']));
			$testdate = str_replace(" ", "-", $row_date);
			$selected = ($testdate == $dates )? "selected-date": "";
			$output .="<li class='".$selected."'><a href='".base_url().index_page()."/reports/dates/".$testdate."'>".$row_date."</a></li>";

		}		
		return $output;

	}




}