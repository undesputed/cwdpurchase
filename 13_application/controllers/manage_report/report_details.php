<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report_details extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('manage_report/md_report_details');		
		
	}


	public function operation($type){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$equipment_type = array(
			'DT'=>'ADT',
			'ADT'=>'DT',
			);

		$x = $this->input->post('x');
		$y = $this->input->post('y');
		$label = $this->input->post('label');
		$date = date('Y-m-d',($x/1000));

		

		$label = explode(' - ',$label);
		$data['date']  = date('F d, Y',($x/1000));
		$data['shift'] = $label;
		
		switch($type)
		{			
			case "A":
				$result = $this->md_report_details->get_area_delivery($date,$label[1],$equipment_type[$label[0]]);
			break;
			case "B":
				$result = $this->md_report_details->get_area_delivery_subcon($date,$label[1],$equipment_type[$label[0]]);
			break;
			case "C":
				$result = $this->md_report_details->get_area_delivery_inhouse($date,$label[1],$equipment_type[$label[0]]);
			break;
			case "D":
				$result = $this->md_report_details->get_area_delivery_shipment($date,$label[1],$equipment_type[$label[0]]);
			break;
			case "E":
				$result = $this->md_report_details->get_area_delivery_shipment_subcon($date,$label[1],$equipment_type[$label[0]]);				
			break;
			case "F":
				$result = $this->md_report_details->get_area_delivery_shipment_inhouse($date,$label[1],$equipment_type[$label[0]]);
			break;


		}

		$this->mine_operation($result,$data);

	}




	public function mine_operation($result,$data){

	
				
		
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);

			$show = array(
						'No.',
						'Body No',
						'Description',
						'Trip Count',						
				 		);
				$cnt = 0;
				$total_trips = 0;
				foreach($result as $key => $value){
					$cnt ++;
					$row_content = array();
					$row_content[] = array('data'=>$cnt);
					$row_content[] = array('data'=>$value['equipment_brand']);
					$row_content[] = array('data'=>$value['equipment_description']);	
					$row_content[] = array('data'=>$value['trips']);
					$total_trips = $total_trips + $value['trips'];

					$this->table->add_row($row_content);			
				}
			
				$this->table->set_heading($show);
		$data['table'] = $this->table->generate();

		$data['total_trips'] = $total_trips;
		$data['total_unit'] = $cnt;		
		$this->load->view('manage_report/report_details',$data);

	}	



	public function daily_production(){
		$x = $this->input->post('x');
		$y = $this->input->post('y');



		//$date = date('Y-m-d',strtotime($x));
		$date = $x;
		$data['date']  = date('F d, Y',strtotime($x));
		$result = $this->md_report_details->get_equipment_monitoring($date);
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);


		$total_wmt = 0;
		$total_trips = 0;
		$show = array(
						'No.',
						'Body No',
						'Trips',
						'Truck Factor',	
						'WMT',
				 		);
				$cnt = 0;
				$total_trips = 0;
				foreach($result as $key => $value){
					$cnt ++;
					$row_content = array();
					$row_content[] = array('data'=>$cnt);
					$row_content[] = array('data'=>$value['UNIT']);
					$row_content[] = array('data'=>$value['TOTAL']);	
					$row_content[] = array('data'=>$value['truck_factor']);
					$row_content[] = array('data'=>$value['TOTAL WMT']);
					$total_wmt += $value['TOTAL WMT']; 
					$total_trips += $value['TOTAL']; 
					$this->table->add_row($row_content);			
				}
			
		$this->table->set_heading($show);
		$data['table'] = $this->table->generate();
		$data['total_wmt'] = $total_wmt;
		$data['total_trips'] = $total_trips;


		$this->load->view('manage_report/daily_production',$data);

	}




}