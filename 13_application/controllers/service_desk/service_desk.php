<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Service_desk extends CI_Controller {

	public function __construct(){
		parent :: __construct();	
		$this->load->model('service_desk/md_service_desk');	
	}

	public function index(){

		$this->lib_auth->title = "Service Desk";		
		$this->lib_auth->build = "service_desk/main/index";			
		$this->lib_auth->render();

	}

	public function create(){
		
		$this->lib_auth->title = "Service Desk";		
		$this->lib_auth->build = "service_desk/main/index";			
		$this->lib_auth->render();
		
	}
	
	public function in_house(){
		
		$this->lib_auth->title = "Service Desk";		
		$this->lib_auth->build = "service_desk/request_list/list";
		$this->lib_auth->render();
				
	}

	public function job_out(){
		
		$this->lib_auth->title = "Service Desk";		
		$this->lib_auth->build = "service_desk/main/index";
		$this->lib_auth->render();
		
	}


	public function get_ref_no(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo $this->md_service_desk->get_ref_no();
	}

	public function get_list(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$table  ="<table class='table'>";
		$table .="<thead>";
		$table .="<tr><th width='20px'>Status</th><th>Subject</th><th>Body No</th><th>Mechanic</th><th>Date</th></tr>";
		$table .="</thead>";

		$result = $this->md_service_desk->get_service();		
		$table .="<tbody>";

		$output = $this->group_by_date($result);
		
		foreach($output as $key => $value){			
			$table .='<tr>
				<td class="service-day" colspan="5"><a href=".'.$key.'" data-toggle="collapse" class="collapsed"><span class="toggle-icon"></span> <span class="day">'.$this->get_date_name($key).'</span></a></td>
				</tr>';
				foreach($value as $key1 => $value1){
						$table.='<tr class="'.$key.' out collapse">
									<td>'.$this->status_type($value1['service_status']).'</td>
									<td><a href="javascript:void(0)" class="details">'.$value1['bd_location'].'</a></td>
									<td>BH0s</td>
									<td>Juan</td>
									<td>02/16/2014</td>
					    </tr>';
				}		
		}
		
		$table .="</tbody>";
		$table .= "</table>";


		echo $table;





	}

	function group_by_date($array){
		$date_group = array();
		foreach($array as $key => $value){
			if(array_key_exists($value['bd_date'],$date_group)){
				$date_group[$value['bd_date']][] = $value;
			}else{
				$date_group[$value['bd_date']][] = $value;
			}
		}
		return $date_group;
	}


	private function get_date_name($date){
		if($this->input->get('date')==$date){
			return "Today - ".date('F d, Y',strtotime($date));
		}else{
			return date('l, F d, Y',strtotime($date));
		}
		
	}
	
	private function status_type($status){
		$status = strtoupper($status);
		switch($status){
			case"PENDING":
				 return '<span class="label label-warning">P</span>';
			break;
		}
	}



}