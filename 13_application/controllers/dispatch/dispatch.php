<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dispatch extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('dispatch/md_dispatch');
	}

	public function index(){

		$this->lib_auth->title = "Dispatching";		
		$this->lib_auth->build = "dispatch/index";
		
		$this->lib_auth->render();		
	}


	public function project_resource_monitoring_report(){
		
		$this->lib_auth->title = "Dispatching";
		$this->lib_auth->build = "dispatch/index";
		$this->lib_auth->render();
		
	}


	public function drivers(){

		$this->lib_auth->title = "Dispatch & Drivers";		
		$this->lib_auth->build = "dispatch/drivers";		
		$this->lib_auth->render();

	}

	public function get_drivers_details(){

		$arg['department'] = $this->input->get('department');
		/*$arg['equipment'] = $this->input->get('equipment_type');*/
		$arg['equipment'] = 'all';
		$arg['date'] = $this->input->get('date');
		$arg['shift'] = $this->input->get('shift');

		$equipment_group = array();		



		$get_person = $this->md_dispatch->get_person($arg);

		$dispatch_person = $this->md_dispatch->get_dispatch_person($arg);



		$count_group = array();
			
		for ($i=0; $i < count($get_person); $i++){

			foreach($dispatch_person as $row2){
				if($get_person[$i]['id'] == $row2['employee_id'])
				{
					$get_person[$i]['equipment_name'] = "<span class='label label-success'>".$row2['equipment_name']."</span>";
					$get_person[$i]['remarks'] = $row2['remarks'];
					$get_person[$i]['assigned'] = true;
					
					break;
				}
			}

			if(!isset($get_person[$i]['equipment_name']))
			{
				$get_person[$i]['equipment_name'] = "<span class='label label-warning'>To be Assign</span>";
				$get_person[$i]['remarks'] = '';
			}
			$count_group[trim($get_person[$i]['equipment'])][] = $get_person[$i];

		}

		$sidebar_group = array();
		foreach($count_group as $key=>$row){
			$cnt1 = 0;
			foreach($row as $data)
			{
				if(isset($data['assigned'])){
					$cnt1++;
				}
			}
					
			$sidebar_group[] = array(
				'equipment'=>($key=='HOWO DT')? 'DUMP TRUCK' : $key,
				'nominator'=>$cnt1,
				'denominator'=>count($row),
			);

		}

		switch($this->input->get('dispatch_type')){
			case "all":

				$equipment_group = $count_group;

			break;

			case"not_dispatch":
				
					for ($i=0; $i < count($get_person); $i++){

						foreach($dispatch_person as $row2){

							if($get_person[$i]['id'] == $row2['employee_id'])
							{
								unset($get_person[$i]);
								break;
							}
										
						}

						if(isset($get_person[$i]))
						{
							$get_person[$i]['equipment_name'] = "<span class='label label-warning'>To be Assign</span>";
							  $get_person[$i]['remarks'] = '';
							$equipment_group[trim($get_person[$i]['equipment'])][] = $get_person[$i];
						}
						
					}
			break;

			case"dispatch":

				foreach ($dispatch_person as $key => $value) 
				{
					$equipment_group[trim($value['equipment_category'])][] = array(
							'name'=>$value['employee_name'],
							'position'=>'',
							'equipment_name'=>"<span class='label label-success'>".$value['equipment_name']."</span>",
							'remarks'=>$value['remarks'],
						);				
				}	

			break;
		}

		

		
		$data['person'] = $get_person;
		$data['group']  = $equipment_group;
		$data['sidebar'] = $sidebar_group;
		$output = array(
			'content'=>$this->load->view('dispatch/driver_details',$data,true),
			'sidebar'=>$this->load->view('dispatch/driver-sidebar',$data,true),
			);		
		echo json_encode($output);
		
	}


	public function get_date(){		
		echo date('F d, Y',strtotime($this->input->post('date')));
	}

	public function get_data()
	{		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$result = $this->md_dispatch->get_data($arg);
		$data['result'] = $result;

		$department = $this->session->userdata('department');

		if($department == 'admin')
		{
			$this->load->view('dispatch/tbl_data_admin',$data);			
		}else
		{
			$this->load->view('dispatch/tbl_data',$data);
		}

	}


	public function get_data_ns(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$result = $this->md_dispatch->get_data($arg);
		$data['result'] = $result;
		$department = $this->session->userdata('department');

		if($department == 'admin')
		{
			$this->load->view('dispatch/tbl_data_admin_ns',$data);			
		}else
		{
			$this->load->view('dispatch/tbl_data_ns',$data);
		}

		
	}


	public function get_online(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$result = $this->md_dispatch->get_online($arg);		
		echo json_encode($result);

	}


	public function get_driver(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$result = $this->md_dispatch->get_person($arg);	
		$dispatch_person = $this->md_dispatch->get_dispatch_person($arg);
		$available_equipment = $this->md_dispatch->get_available_equipment($arg);		
		$data['available_equipment'] = $available_equipment;
		
		$data['drivers'] = $result;
		$data['dispatch_person'] = $dispatch_person;
		$this->load->view('dispatch/tbl_assigning',$data);		
	}

	public function get_equipment(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();		
		$result = $this->md_dispatch->get_available_equipment($arg);
		$div = "";		
		foreach($result as $row){
			$div .="<option value=".$row['equipment_id'].">".$row['equipment_brand']."</option>";			
		}		
		$output = array(
			'category'=>$arg['equipment'],
			'div'=>$div,
			);
		echo json_encode($output);

	}


	public function get_standby_unit(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();

		$data['delay_list'] = $this->md_dispatch->get_cause_delay();
		$data['available_equipment'] = $this->md_dispatch->get_available_equipment($arg);
		$this->load->view('dispatch/tbl_standby_unit',$data);

	}
	
	public function set_driver(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		switch($this->input->post('action')){
			case "Release":
				$return = $this->md_dispatch->release_dispatch();
			break;

			case "Confirm":
				$return = $this->md_dispatch->save_dispatch();
			break;

		}		
		echo json_encode($return);

	}


	public function get_assigned(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$result = $this->md_dispatch->get_assigned($arg);

		$data['ds'] = array();
		$data['ns'] = array();
		$content_depart = array();

		foreach($result as $row){
			if(isset($row['department']))
			{
				$data['department'][$row['department']][$row['shift']][] = $row;
			}else
			{
				if($row['shift'] == 'ds'){
					$data['ds'][] = $row;
				}else{
					$data['ns'][] = $row;
				}
			}

		}
		
		echo json_encode($data);

	}


	public function set_delay(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$this->md_dispatch->set_delay();

	}



	public function download(){


		$arg['date'] = '2014-08-16';
		$arg['shift'] = 'ds';

		$data['result'] = $this->md_dispatch->get_data($arg);
		$data['get_online'] = $this->md_dispatch->get_online($arg);
		$data['assigned'] = $this->md_dispatch->get_assigned($arg);
		
		$this->load->view('dispatch/print_info',$data);

		$this->load->library('dompdf_gen');
		//$html = $this->output->get_output();

				
		/*
		$this->dompdf->load_html($html);
		$this->dompdf->render();
		$this->dompdf->stream("test".$this->recepient."_".date('y-m-d').".pdf");
		*/


	}



}



