<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Map extends CI_Controller {

	public $rf_result;

	public function __construct(){
		parent :: __construct();
		$this->load->model('map/md_map');
		$this->load->model('map/md_dashboard');
	}


	public function equipment_reading(){

		$this->lib_auth->title = "Equipment Reading";		
		$this->lib_auth->build = "map/rfid";	

		$date = date('Y-m-d',strtotime('2014-05-24'));
		//$date = date('Y-m-d');
		$data['tags'] = array_keys($this->md_map->get_trucks($date,$date));	
		$data['date'] = date('F d, Y');	
		$this->lib_auth->render($data);

	}

	public function reading_board(){

		$this->lib_auth->title = "Reading Board";		
		$this->lib_auth->build = "map/reading_board";
		
		$this->lib_auth->render();

	}

	public function dt_rf(){

		$this->lib_auth->title = "Delivery ticket & Rf";		
		$this->lib_auth->build = "dt_rf/index";

		$this->lib_auth->render();

	}


	public function get_delivery_tickets(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();		
		$result = $this->md_map->get_delivery_ticket($arg);
		$data['data'] = $result;
		$this->load->view('dt_rf/tbL_delivery_ticket',$data);
		
	}

	public function delivery_details(){


		$arg = $this->input->post();
		$result = $this->md_map->delivery_details($arg);
		
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);		

		$show = array(
					'Departure Time',
					'Delivery Time',
					'Source',
					'Destination',
					'shift',
					'Operation',
			 		);
			foreach($result as $key => $value){

				$row_content = array();
				$row_content[] = array('data'=>$value['departure_time']);
				$row_content[] = array('data'=>$value['delivery_time']);
				$row_content[] = array('data'=>$value['source_status']);
				$row_content[] = array('data'=>$value['dump_status']);
				$row_content[] = array('data'=>$value['shift']);
				$row_content[] = array('data'=>$value['production']);

				$this->table->add_row($row_content);

			}
	
			$this->table->set_heading($show);
		echo "<div class='container'>";
		echo "<h3>".$this->input->post('body_no')."</h3>";
		echo $this->table->generate();
		echo "</div>";
				
	}

	public function index(){
		redirect(base_url(),'refresh');
	}

	public function addpoint(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$this->load->view('map/addPoint');
	}


	public function getpoint(){

		$result = $this->md_map->get_location();
		$data = array();
		foreach($result as $row){
			$site = explode('_',$row['site']);
			$data[] = array('site'=>strtoupper($site[1]),'x'=>$row['x'],'y'=>$row['y']);
		}
		echo json_encode($data);
	}


	public function action($type){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		switch($type){
			case"save":
				$this->md_map->save();
			break;

		}
	}




	public function calculate_trips(){
		
		$this->rf_result = $this->md_map->get_rfLogs($this->input->post('from'),$this->input->post('to'));
		$result_trucks   = $this->md_map->get_trucks($this->input->post('from'),$this->input->post('to'),'');
		$result_site     = $this->md_map->get_site($this->input->post('from'),$this->input->post('to'));
		$result_date     = $this->md_map->get_date($this->input->post('from'),$this->input->post('to'));
			
		$tmpl = array ( 'table_open'  => '<table class="dt-table table myTable table-striped table-hover">' );
		$this->table->set_template($tmpl);

			/*
			echo "<pre>";
			print_r($result_trucks);
			echo "</pre>";
			*/

			$header = array(
						array('data'=>'Trucks','style'=>'width:100px'),						
			);
			foreach($result_date as $row)
			{					
				$header[] = $row['date'];
			}
			
			$this->table->set_heading($header);

			/*foreach($result_site->result_array() as $row){
				$header[] = $row['device_name'];
			}
			$col = count($header);*/			
			$row_content = array();
			$cnt = 1;
			foreach($result_trucks as $key=>$row){
								
				$time = array();
				$data_row = array();
				$data_row[0] = $key;
				$site_row = array();
				$bool = false;

				$date = array();


				//$time[] = '<div class="progress">';
				for ($i=0; $i < count($row) ; $i++) 
				{

					if(!$bool){
						$time[] = "<div class='time-block ".$row[$i][1]."'><span class='site'>".$row[$i][1]."</span>";					
					}
					
					$time[] = "<span class='time'>[".date('H:i:s A',strtotime($row[$i][0]))."]</span>";
					if(isset($row[$i+1][1]) && $row[$i+1][1] == $row[$i][1])
					{
						$bool = true;
					}else
					{
						$time[] = "</div>";
						$bool = false;
					}				

					$date[date('Y-m-d',strtotime($row[$i][0]))] = $time;												
				}

				//$time[] = '</div>';
				
				foreach($result_date as $row)
				{					

					$data_row[$cnt] = implode('',$date[$row['date']]);					

				}


/*				echo "<pre>";
					print_r($data_row);
				echo "</pre>";*/

				/*for ($z=0; $z <=count($result_date) ; $z++) { 
					//$result_date[$z]['date']
					//echo "<pre>";
					//print_r($result_date);
					//print_r($time);
					//print_r($date);
					//echo "</pre>";
					echo "<pre>";
					print_r($result_date[$z]);
					echo "</pre>";
					//$data_row[$z+1] = implode('', $date[$result_date[$z]['date']]);	
				}	*/


				/*
				<div class="progress">						
					<div class="progress-bar progress-bar-info" style="width: 49%">
					    <span class="mine-progress-day-unit"></span>
					</div>
					  <div class="progress-bar progress-bar-danger" style="width: 51%">
					    <span class="mine-progress-night-unit"></span>
					</div>
				</div>
				*/

				$this->table->add_row($data_row);
				$cnt++;
			}				
				echo $this->table->generate();
	}




	public function per_dt(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result        = $this->md_map->per_dt($this->input->post('from'),$this->input->post('to'));
		$result_all_dt = $this->md_map->get_all_dt();

		$tmpl = array ( 'table_open'  => '<table class="table myTable tbl-event table-striped table-hover">' );
		$this->table->set_template($tmpl);


			$header = array(
						'Body No',
						'Total Trips',
						'Action',
				 		);
				foreach($result_all_dt->result_array() as $key => $value){
					$row_content = array();
					$row_content[] = array('data'=>$value['body_no'],'class'=>'body_no');
					$row_content[] = '-';
					$row_content[] = '<span class="event btn-link view">View Details</span>';
					$this->table->add_row($row_content);			
				}
		
				$this->table->set_heading($header);
		echo $this->table->generate();
		
	}

	public function dt_details(){

		/*
			if(!$this->input->is_ajax_request()){
			exit(0);
		}*/

		//$data['dt'] = $this->md_map->get_dt($this->input->get('body_no'),$this->input->get('date'));


		$data['dt'] = $this->md_map->get_dt_2($this->input->get('body_no'),$this->input->get('date'));	
		$data['logs'] = $this->md_map->get_rfLogs_dt($this->input->get('body_no'),$this->input->get('date'));	

		$data['dt_name'] = $this->input->get('body_no');
		$this->load->view('map/dt_details',$data);

	}


	public function save_reports(){

		//$result = $this->md_map->get_dt_2('DT-35','2014-05-25');
		//$result = $this->md_map->get_dt_2('DT-532','2014-05-23');
		/*$date = '2014-06-02';*/
		$date = $this->input->post('date');
		$result =  array_keys($this->md_map->get_trucks($date,$date,''));
		
		$dayshift = array();
		$nightshift = array();
		$total_dayshift = 0;

		$ds_adt_unit_barging = 0;
		$ds_adt_unit_production = 0;
		$ds_adt_trip_barging = 0;
		$ds_adt_trip_production = 0;
		$ds_adt_wmt_barging = 0;
		$ds_adt_wmt_production = 0;

		$ds_total_dt_unit_barging    = 0;
		$ds_total_dt_unit_production = 0;
		$ds_total_dt_trip_barging    = 0;
		$ds_total_dt_trip_production = 0;
		$ds_total_dt_wmt_barging     = 0;
		$ds_total_dt_wmt_production  = 0;

		$ns_adt_unit_barging = 0;
		$ns_adt_unit_production = 0;
		$ns_adt_trip_barging = 0;
		$ns_adt_trip_production = 0;
		$ns_adt_wmt_barging = 0;
		$ns_adt_wmt_production = 0;

		$ns_total_dt_unit_barging = 0;
		$ns_total_dt_unit_production = 0;
		$ns_total_dt_trip_barging = 0;
		$ns_total_dt_trip_production = 0;
		$ns_total_dt_wmt_barging = 0;
		$ns_total_dt_wmt_production = 0;


		$inhouse['ds_adt_unit_barging'] = 0;
		$inhouse['ds_adt_unit_production'] = 0;
		$inhouse['ds_adt_trip_barging'] = 0;
		$inhouse['ds_adt_trip_production'] = 0;
		$inhouse['ds_adt_wmt_barging'] = 0;
		$inhouse['ds_adt_wmt_production'] = 0;
		$inhouse['ds_total_dt_unit_barging'] = 0;
		$inhouse['ds_total_dt_unit_production'] = 0;
		$inhouse['ds_total_dt_trip_barging'] = 0;
		$inhouse['ds_total_dt_trip_production'] = 0;
		$inhouse['ds_total_dt_wmt_barging'] = 0;
		$inhouse['ds_total_dt_wmt_production'] = 0;
		$inhouse['ns_adt_unit_barging'] = 0;
		$inhouse['ns_adt_unit_production'] = 0;
		$inhouse['ns_adt_trip_barging'] = 0;
		$inhouse['ns_adt_trip_production'] = 0;
		$inhouse['ns_adt_wmt_barging'] = 0;
		$inhouse['ns_adt_wmt_production'] = 0;
		$inhouse['ns_total_dt_unit_barging'] = 0;
		$inhouse['ns_total_dt_unit_production'] = 0;
		$inhouse['ns_total_dt_trip_barging'] = 0;
		$inhouse['ns_total_dt_trip_production'] = 0;
		$inhouse['ns_total_dt_wmt_barging'] = 0;
		$inhouse['ns_total_dt_wmt_production'] = 0;

		$subcon['ds_adt_unit_barging'] = 0;
		$subcon['ds_adt_unit_production'] = 0;
		$subcon['ds_adt_trip_barging'] = 0;
		$subcon['ds_adt_trip_production'] = 0;
		$subcon['ds_adt_wmt_barging'] = 0;
		$subcon['ds_adt_wmt_production'] = 0;
		$subcon['ds_total_dt_unit_barging'] = 0;
		$subcon['ds_total_dt_unit_production'] = 0;
		$subcon['ds_total_dt_trip_barging'] = 0;
		$subcon['ds_total_dt_trip_production'] = 0;
		$subcon['ds_total_dt_wmt_barging'] = 0;
		$subcon['ds_total_dt_wmt_production'] = 0;
		$subcon['ns_adt_unit_barging'] = 0;
		$subcon['ns_adt_unit_production'] = 0;
		$subcon['ns_adt_trip_barging'] = 0;
		$subcon['ns_adt_trip_production'] = 0;
		$subcon['ns_adt_wmt_barging'] = 0;
		$subcon['ns_adt_wmt_production'] = 0;
		$subcon['ns_total_dt_unit_barging'] = 0;
		$subcon['ns_total_dt_unit_production'] = 0;
		$subcon['ns_total_dt_trip_barging'] = 0;
		$subcon['ns_total_dt_trip_production'] = 0;
		$subcon['ns_total_dt_wmt_barging'] = 0;
		$subcon['ns_total_dt_wmt_production'] = 0;



		foreach($result as $row){

			$dayshift = $this->lib_dt->no_trips($row,$date,'ds');
			
			switch($dayshift['type']){

				case "ADT":
						if($dayshift['trip_barging'] > 0){
							$ds_adt_unit_barging++;

							if($dayshift['haul_owner']=='inhouse'){
								$inhouse['ds_adt_unit_barging']++;	
							}else{
								$subcon['ds_adt_unit_barging']++;
							}
							

						}
						if($dayshift['trip_production'] > 0){
							$ds_adt_unit_production++;
							if($dayshift['haul_owner']=='inhouse'){
								$inhouse['ds_adt_unit_production']++;	
							}else{
								$subcon['ds_adt_unit_production']++;
							}
						}

						if($dayshift['haul_owner']=='inhouse'){
							$inhouse['ds_adt_trip_barging'] += $dayshift['trip_barging'];
							$inhouse['ds_adt_trip_production'] += $dayshift['trip_production'];
							$inhouse['ds_adt_wmt_barging'] += $dayshift['total_barging_wmt'];
							$inhouse['ds_adt_wmt_production'] += $dayshift['total_production_wmt'];

						}else{
							$subcon['ds_adt_trip_barging'] += $dayshift['trip_barging'];
							$subcon['ds_adt_trip_production'] += $dayshift['trip_production'];
							$subcon['ds_adt_wmt_barging'] += $dayshift['total_barging_wmt'];
							$subcon['ds_adt_wmt_production'] += $dayshift['total_production_wmt'];
						}

						$ds_adt_trip_barging += $dayshift['trip_barging'];
						$ds_adt_trip_production += $dayshift['trip_production'];
						$ds_adt_wmt_barging += $dayshift['total_barging_wmt'];
						$ds_adt_wmt_production += $dayshift['total_production_wmt'];
												
				break;


				case"DT":
						if($dayshift['trip_barging'] > 0){
							$ds_total_dt_unit_barging++;

							if($dayshift['haul_owner']=='inhouse'){
								$inhouse['ds_total_dt_unit_barging']++;	
							}else{
								$subcon['ds_total_dt_unit_barging']++;
							}

						}
						if($dayshift['trip_production'] > 0) {
							$ds_total_dt_unit_production++;

							if($dayshift['haul_owner']=='inhouse'){
								$inhouse['ds_total_dt_unit_production']++;	
							}else{
								$subcon['ds_total_dt_unit_production']++;
							}
							
						}

						if($dayshift['haul_owner']=='inhouse'){

							$inhouse['ds_total_dt_trip_barging']    += $dayshift['trip_barging'];
							$inhouse['ds_total_dt_trip_production'] += $dayshift['trip_production'];
							$inhouse['ds_total_dt_wmt_barging']     += $dayshift['total_barging_wmt'];
							$inhouse['ds_total_dt_wmt_production']  += $dayshift['total_production_wmt'];

						}else{

							$subcon['ds_total_dt_trip_barging']    += $dayshift['trip_barging'];
							$subcon['ds_total_dt_trip_production'] += $dayshift['trip_production'];
							$subcon['ds_total_dt_wmt_barging']     += $dayshift['total_barging_wmt'];
							$subcon['ds_total_dt_wmt_production']  += $dayshift['total_production_wmt'];

						}			

						$ds_total_dt_trip_barging += $dayshift['trip_barging'];
						$ds_total_dt_trip_production += $dayshift['trip_production'];
						$ds_total_dt_wmt_barging += $dayshift['total_barging_wmt'];
						$ds_total_dt_wmt_production += $dayshift['total_production_wmt'];

				break;

			}

		

			$nightshift = $this->lib_dt->no_trips($row,$date,'ns');

			switch($nightshift['type']){

				case"ADT":

					if($nightshift['trip_barging'] > 0){
						$ns_adt_unit_barging++;

						if($nightshift['haul_owner']=='inhouse'){
							$inhouse['ns_adt_unit_barging']++;
						}else{
							$subcon['ns_adt_unit_barging']++;
						}

					}

					if($nightshift['trip_production'] > 0){
						$ns_adt_unit_production++;
						if($nightshift['haul_owner']=='inhouse'){
							$inhouse['ns_adt_unit_production']++;
						}else{
							$subcon['ns_adt_unit_production']++;
						}

							
					}

					if($nightshift['haul_owner']=='inhouse'){

						$inhouse['ns_adt_trip_barging'] += $nightshift['trip_barging'];
						$inhouse['ns_adt_trip_production'] += $nightshift['trip_production'];
						$inhouse['ns_adt_wmt_barging'] += $nightshift['total_barging_wmt'];
						$inhouse['ns_adt_wmt_production'] += $nightshift['total_production_wmt'];

					}else{

						$subcon['ns_adt_trip_barging']+= $nightshift['trip_barging'];
						$subcon['ns_adt_trip_production']+= $nightshift['trip_production'];
						$subcon['ns_adt_wmt_barging']+= $nightshift['total_barging_wmt'];
						$subcon['ns_adt_wmt_production']+= $nightshift['total_production_wmt'];

					}

					$ns_adt_trip_barging += $nightshift['trip_barging'];
					$ns_adt_trip_production += $nightshift['trip_production'];
					$ns_adt_wmt_barging += $nightshift['total_barging_wmt'];
					$ns_adt_wmt_production += $nightshift['total_production_wmt'];


				break;


				case"DT":

					if($nightshift['trip_barging'] > 0){
						$ns_total_dt_unit_barging++;
						if($nightshift['haul_owner']=='inhouse'){
							$inhouse['ns_total_dt_unit_barging']++;
						}else{
							$subcon['ns_total_dt_unit_barging']++;
						}

					}

					if($nightshift['trip_production'] > 0){
						$ns_total_dt_unit_production++;
						if($nightshift['haul_owner']=='inhouse'){
							$inhouse['ns_total_dt_unit_production']++;
						}else{
							$subcon['ns_total_dt_unit_production']++;
						}
					}

					if($nightshift['haul_owner']=='inhouse'){
						$inhouse['ns_total_dt_trip_barging'] += $nightshift['trip_barging'];
						$inhouse['ns_total_dt_trip_production'] += $nightshift['trip_production'];
						$inhouse['ns_total_dt_wmt_barging'] += $nightshift['total_barging_wmt'];
						$inhouse['ns_total_dt_wmt_production'] += $nightshift['total_production_wmt'];
					}else{
						$subcon['ns_total_dt_trip_barging'] += $nightshift['trip_barging'];
						$subcon['ns_total_dt_trip_production'] += $nightshift['trip_production'];
						$subcon['ns_total_dt_wmt_barging'] += $nightshift['total_barging_wmt'];
						$subcon['ns_total_dt_wmt_production'] += $nightshift['total_production_wmt'];
					}

					$ns_total_dt_trip_barging += $nightshift['trip_barging'];
					$ns_total_dt_trip_production += $nightshift['trip_production'];
					$ns_total_dt_wmt_barging += $nightshift['total_barging_wmt'];
					$ns_total_dt_wmt_production += $nightshift['total_production_wmt'];

				break;

			}			
	
		}

				
		$production = array(
			'ds_adt_unit'=>$ds_adt_unit_production,
			'ds_adt_trip'=>$ds_adt_trip_production,
			'ds_adt_wmt'=>$ds_adt_wmt_production,
			'ds_dt_unit'=>$ds_total_dt_unit_production,
			'ds_dt_trip'=>$ds_total_dt_trip_production,
			'ds_dt_wmt'=>$ds_total_dt_wmt_production,
			'ds_total_wmt'=>($ds_adt_wmt_production + $ds_total_dt_wmt_production),
			'ns_adt_unit'=>$ns_adt_unit_production,
			'ns_adt_trip'=>$ns_adt_trip_production,
			'ns_adt_wmt'=>$ns_adt_wmt_production,
			'ns_dt_unit'=>$ns_total_dt_unit_production,
			'ns_dt_trip'=>$ns_total_dt_trip_production,
			'ns_dt_wmt'=>$ns_total_dt_wmt_production,
			'ns_total_wmt'=>($ns_adt_wmt_production + $ns_total_dt_wmt_production),
			);

		$barging = array(
			'ds_adt_unit'=>$ds_adt_unit_barging,
			'ds_adt_trip'=>$ds_adt_trip_barging,
			'ds_adt_wmt'=>$ds_adt_wmt_barging,
			'ds_dt_unit'=>$ds_total_dt_unit_barging,
			'ds_dt_trip'=>$ds_total_dt_trip_barging,
			'ds_dt_wmt'=>$ds_total_dt_wmt_barging,
			'ds_total_wmt'=>($ds_adt_wmt_barging + $ds_total_dt_wmt_barging),
			'ns_adt_unit'=>$ns_adt_unit_barging,
			'ns_adt_trip'=>$ns_adt_trip_barging,
			'ns_adt_wmt'=>$ns_adt_wmt_barging,
			'ns_dt_unit'=>$ns_total_dt_unit_barging,
			'ns_dt_trip'=>$ns_total_dt_trip_barging,
			'ns_dt_wmt'=>($ns_adt_wmt_barging + $ns_total_dt_wmt_barging ),	
			'ns_total_wmt'=>($ns_adt_wmt_barging + $ns_total_dt_wmt_barging),		
			);


		$inhouse_production = array(
			'ds_adt_unit'=>$inhouse['ds_adt_unit_production'],
			'ds_adt_trip'=>$inhouse['ds_adt_trip_production'],
			'ds_adt_wmt'=>$inhouse['ds_adt_wmt_production'],
			'ds_dt_unit'=>$inhouse['ds_total_dt_unit_production'],
			'ds_dt_trip'=>$inhouse['ds_total_dt_trip_production'],
			'ds_dt_wmt'=>$inhouse['ds_total_dt_wmt_production'],
			'ds_total_wmt'=>($inhouse['ds_adt_wmt_production'] + $inhouse['ds_total_dt_wmt_production']),
			'ns_adt_unit'=>$inhouse['ns_adt_unit_production'],
			'ns_adt_trip'=>$inhouse['ns_adt_trip_production'],
			'ns_adt_wmt'=>$inhouse['ns_adt_wmt_production'],
			'ns_dt_unit'=>$inhouse['ns_total_dt_unit_production'],
			'ns_dt_trip'=>$inhouse['ns_total_dt_trip_production'],
			'ns_dt_wmt'=>($inhouse['ns_adt_wmt_production'] + $inhouse['ns_total_dt_wmt_production']),	
			'ns_total_wmt'=>($inhouse['ns_adt_wmt_production'] + $inhouse['ns_total_dt_wmt_production']),
			);

		$inhouse_barging = array(
			'ds_adt_unit'=>$inhouse['ds_adt_unit_barging'],
			'ds_adt_trip'=>$inhouse['ds_adt_trip_barging'],
			'ds_adt_wmt'=>$inhouse['ds_adt_wmt_barging'],
			'ds_dt_unit'=>$inhouse['ds_total_dt_unit_barging'],
			'ds_dt_trip'=>$inhouse['ds_total_dt_trip_barging'],
			'ds_dt_wmt'=>$inhouse['ds_total_dt_wmt_barging'],
			'ds_total_wmt'=>($inhouse['ds_adt_wmt_barging'] + $inhouse['ds_total_dt_wmt_barging']),
			'ns_adt_unit'=>$inhouse['ns_adt_unit_barging'],
			'ns_adt_trip'=>$inhouse['ns_adt_trip_barging'],
			'ns_adt_wmt'=>$inhouse['ns_adt_wmt_barging'],
			'ns_dt_unit'=>$inhouse['ns_total_dt_unit_barging'],
			'ns_dt_trip'=>$inhouse['ns_total_dt_trip_barging'],
			'ns_dt_wmt'=>($inhouse['ns_adt_wmt_barging'] + $inhouse['ns_total_dt_wmt_barging']),	
			'ns_total_wmt'=>($inhouse['ns_adt_wmt_barging'] + $inhouse['ns_total_dt_wmt_barging']),
			);

		$subcon_barging = array(
			'ds_adt_unit'=>$subcon['ds_adt_unit_barging'],
			'ds_adt_trip'=>$subcon['ds_adt_trip_barging'],
			'ds_adt_wmt'=>$subcon['ds_adt_wmt_barging'],
			'ds_dt_unit'=>$subcon['ds_total_dt_unit_barging'],
			'ds_dt_trip'=>$subcon['ds_total_dt_trip_barging'],
			'ds_dt_wmt'=>$subcon['ds_total_dt_wmt_barging'],
			'ds_total_wmt'=>($subcon['ds_adt_wmt_barging'] + $subcon['ds_total_dt_wmt_barging']),
			'ns_adt_unit'=>$subcon['ns_adt_unit_barging'],
			'ns_adt_trip'=>$subcon['ns_adt_trip_barging'],
			'ns_adt_wmt'=>$subcon['ns_adt_wmt_barging'],
			'ns_dt_unit'=>$subcon['ns_total_dt_unit_barging'],
			'ns_dt_trip'=>$subcon['ns_total_dt_trip_barging'],
			'ns_dt_wmt'=>($subcon['ns_adt_wmt_barging'] + $subcon['ns_total_dt_wmt_barging']),	
			'ns_total_wmt'=>($subcon['ns_adt_wmt_barging'] + $subcon['ns_total_dt_wmt_barging']),
			);

		$subcon_production = array(
			'ds_adt_unit'=>$subcon['ds_adt_unit_production'],
			'ds_adt_trip'=>$subcon['ds_adt_trip_production'],
			'ds_adt_wmt'=>$subcon['ds_adt_wmt_production'],
			'ds_dt_unit'=>$subcon['ds_total_dt_unit_production'],
			'ds_dt_trip'=>$subcon['ds_total_dt_trip_production'],
			'ds_dt_wmt'=>$subcon['ds_total_dt_wmt_production'],
			'ds_total_wmt'=>($subcon['ds_adt_wmt_production'] + $subcon['ds_total_dt_wmt_production']),
			'ns_adt_unit'=>$subcon['ns_adt_unit_production'],
			'ns_adt_trip'=>$subcon['ns_adt_trip_production'],
			'ns_adt_wmt'=>$subcon['ns_adt_wmt_production'],
			'ns_dt_unit'=>$subcon['ns_total_dt_unit_production'],
			'ns_dt_trip'=>$subcon['ns_total_dt_trip_production'],
			'ns_dt_wmt'=>($subcon['ns_adt_wmt_production'] + $subcon['ns_total_dt_wmt_production']),	
			'ns_total_wmt'=>($subcon['ns_adt_wmt_production'] + $subcon['ns_total_dt_wmt_production']),
			);

	

		$output = array(
			'production'=>$production,
			'barging'=>$barging,
			'inhouse_barging'=>$inhouse_barging,
			'inhouse_production'=>$inhouse_production,
			'subcon_barging'=>$subcon_barging,
			'subcon_production'=>$subcon_production,
			);

		echo $this->md_map->report($date,$output);

	}


	public function get_scan(){

		//$result_trucks = $this->md_map->get_rfLogs('2014-05-14','2014-05-14');
		//$date = date('Y-m-d');
		//$date = date('Y-m-d',strtotime('2014-05-24'));
		$arg = '';
		$date = $this->input->post('date');
		$result_trucks   = $this->md_map->get_trucks($date,$date,$arg);
		$result_date     = $this->md_map->get_date($date,$date);

			
		$data_row = array();

		$rename = array(
			'SAMPLING 9'=>'sampling_9',            
			'SAMPLING 6'=>'sampling_6',
			'SAMPLING 5'=>'sampling_5',
			'SAMPLING 7'=>'sampling_7',
			'LOCATION I'=>'location_i',           
			'SAMPLING B'=>'sampling_b',			
			'MINEYARD 2'=>'mineyard2',
			'MINE BASE'=>'mine_base',
			'SAMPLING A'=>'sampling_a',
			'LOCATION J'=>'location_j',
			'MINE YARD'=>'mineyard',
			'PY 7'=>'py7',
			);

		foreach($result_trucks as $key=>$row){

				$cnt = 0;					
				$time = array();
								
				$site_row = array();
				$bool = false;

				$date = array();
				
				for ($i=0; $i < count($row) ; $i++) 
				{

					if(!$bool){
						$cnt++;
						//$time[]['title']= $row[$i][1];

						//$time[] = "<div class='time-block ".$row[$i][1]."'><span class='site'>".$row[$i][1]."</span>";					
					}
					
					//echo $row[$i][1]."</br>";
					$time[$cnt][$rename[strtoupper($row[$i][1])]][] = date('h:i:s A',strtotime($row[$i][0]));
				
					if(isset($row[$i+1][1]) && $row[$i+1][1] == $row[$i][1])
					{
						$bool = true;
					}else
					{					
						$bool = false;
					}				

					$date[date('Y-m-d',strtotime($row[$i][0]))] = $time;

				}
				
				
				foreach($result_date as $row)
				{
					//$data_row[] = implode('',$date[$row['date']]);
					$data_row[$key] = $date;
				}
								
		}

			/******/
			/*echo "<pre>";
			print_r($data_row['DT-35']['2014-05-14']);		
			echo "</pre>";*/
	/*		$cnt = 1;
			$row_data = array();
			$row_test = array();
			$new = $data_row['DT-35']['2014-05-14'];
			for ($i=1; $i < count($new); $i++) { 


				if($cnt%2 == 0){

					foreach($new[$i-1] as $key=>$row)
					{							
						$row_data['from'] = $key;
						$row_data['from_time'] = $row[0];
					}

					foreach($new[$i] as $key=>$row)
					{
						$counter = count($row);
						$row_data['to'] = $key;
						$row_data['to_time'] = $row[$counter-1];
					}					
					$row_test[] = $row_data;
				}
				$cnt++;
			}
*/			
			//echo json_encode($row_test);

			echo json_encode($data_row);			
	}

	public function time($time){
		return date('H:i:s A',strtotime($time));
	}



	public function get_trip(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$this->md_map->get_trip();

	}


	public function gen_table(){


		$arg['filter_truck'] = $this->input->post('filter_truck');
		$arg['haul_owner']   = $this->input->post('haul_owner');
		$data['tags'] = array_keys($this->md_map->get_trucks($this->input->post('date'),$this->input->post('date'),$arg));
		$data['date'] = date('F d, Y',strtotime($this->input->post('date')));

		//asort($data['tags']);
		/*
		echo "<pre>";
		print_r($data['tags']);
		echo "</pre>";*/
		/*uasort($data['tags'], function ($a, $b) {
		    if ($a['tags']==$b['tags']) return 0;
		    return $a['tags']>$b['tags'] ? 1 : -1;
		});
		*/
		
		$output = $this->load->view('map/generate_table',$data,true);		
		$result = array(
			'output'=>$output,
			'date'=>$data['date'],
			);
		echo json_encode($result);

	}

	public function _gen_report_production(){

		$arg = $this->input->post();


		$data['tags'] = array_keys($this->md_map->get_trucks($this->input->post('date'),$this->input->post('date'),$arg));
				
		$data['date']   = $this->input->post('date');
		$data['filter'] = $this->input->post('filter');

		$output = $this->load->view('map/generate_report_production',$data);

	}


	public function gen_report_production(){

		$arg = $this->input->post();
		$date = $arg['date'];
		$result = $this->md_dashboard->get_dt_type2($date,$arg);

	

		$data = array();
		$dash_result = array();

		foreach($result as $row)
		{
			$data[$row['body_no']][] = $row;
		}

		$dt = array();
		$cnt = 0;
		$dt = array();
		foreach($data as $key=>$row)
		{	

			$result =  $this->md_dashboard->get_dt_solo($row,$date);			
			$trip_result = $this->lib_dt->no_trips_data($result,$date,'all');
		
			if($trip_result['trip_production'] > 0 || $trip_result['trip_barging'] > 0 ){
										
					$dt[$cnt]['dt'] = $key;
					switch($arg['filter']){
						case "ds":
							$dt[$cnt]['trips'] = $trip_result['ds_production']['trips'];
							$dt[$cnt]['factor'] = $trip_result['ds_production']['factor'];
							$dt[$cnt]['wmt'] = $trip_result['ds_production']['wmt'];
						break;

						case "ns":
							$dt[$cnt]['trips'] = $trip_result['ns_production']['trips'];
							$dt[$cnt]['factor'] = $trip_result['ns_production']['factor'];
							$dt[$cnt]['wmt'] = $trip_result['ns_production']['wmt'];
						break;

						case "all":
							$dt[$cnt]['trips'] = $trip_result['ds_production']['trips'] + $trip_result['ns_production']['trips'] ;
							$dt[$cnt]['factor'] = $trip_result['ds_production']['factor'];
							$dt[$cnt]['wmt'] = $trip_result['ds_production']['wmt'] + $trip_result['ns_production']['wmt'];
						break;				

					}

			}

		$cnt++;			
		}
		$data['dt'] = $dt;
		echo $this->load->view('map/generate_report_production2',$data);

		
	}

	public function gen_report_barging(){

		$arg = $this->input->post();
		$data['tags'] = array_keys($this->md_map->get_trucks($this->input->post('date'),$this->input->post('date'),$arg));
		$data['date'] = $this->input->post('date');
		$data['filter'] = $this->input->post('filter');
		$output = $this->load->view('map/generate_report_barging',$data);

	}

	public function update_status(){
		$arg['id'] = $this->input->post('id');
		echo $this->md_map->update_status($arg);
	}

	public function query($date = ''){

		$date = (empty($date))? $this->input->post('date') : $date ;
		$result = $this->md_dashboard->get_dt_type($date,'FVC');
		return $result;

	}


	public function get_total(){

		//$date = $this->input->post('date');
		//$date = '2014-06-05';
		$date = $this->input->post('date');

		$full_date = date('F d, Y',strtotime($date));

		$arg = null;
		$trip_result = array();

		$output['production_trip'] = 0;
		$output['production_wmt']= 0;
		$output['barging_trip']= 0;
		$output['barging_wmt']= 0;

		$output['inhouse']['production']['adt']['ds']['unit']  = 0; 
		$output['inhouse']['production']['adt']['ds']['trips'] = 0;
		$output['inhouse']['production']['adt']['ds']['wmt']   = 0;

		$output['inhouse']['production']['adt']['ns']['unit']  = 0; 
		$output['inhouse']['production']['adt']['ns']['trips'] = 0;
		$output['inhouse']['production']['adt']['ns']['wmt']   = 0;	

		$output['inhouse']['production']['dt']['ds']['unit']  = 0; 
		$output['inhouse']['production']['dt']['ds']['trips'] = 0;
		$output['inhouse']['production']['dt']['ds']['wmt']   = 0;

		$output['inhouse']['production']['dt']['ns']['unit']  = 0; 
		$output['inhouse']['production']['dt']['ns']['trips'] = 0;
		$output['inhouse']['production']['dt']['ns']['wmt']   = 0;

		$output['inhouse']['barging']['adt']['ds']['unit']  = 0; 
		$output['inhouse']['barging']['adt']['ds']['trips'] = 0;
		$output['inhouse']['barging']['adt']['ds']['wmt']   = 0;

		$output['inhouse']['barging']['adt']['ns']['unit']  = 0; 
		$output['inhouse']['barging']['adt']['ns']['trips'] = 0;
		$output['inhouse']['barging']['adt']['ns']['wmt']   = 0;	

		$output['inhouse']['barging']['dt']['ds']['unit']  = 0; 
		$output['inhouse']['barging']['dt']['ds']['trips'] = 0;
		$output['inhouse']['barging']['dt']['ds']['wmt']   = 0;

		$output['inhouse']['barging']['dt']['ns']['unit']  = 0; 
		$output['inhouse']['barging']['dt']['ns']['trips'] = 0;
		$output['inhouse']['barging']['dt']['ns']['wmt']   = 0;


		$output['subcon']['production']['adt']['ds']['unit']  = 0; 
		$output['subcon']['production']['adt']['ds']['trips'] = 0;
		$output['subcon']['production']['adt']['ds']['wmt']   = 0;

		$output['subcon']['production']['adt']['ns']['unit']  = 0; 
		$output['subcon']['production']['adt']['ns']['trips'] = 0;
		$output['subcon']['production']['adt']['ns']['wmt']   = 0;	

		$output['subcon']['production']['dt']['ds']['unit']  = 0; 
		$output['subcon']['production']['dt']['ds']['trips'] = 0;
		$output['subcon']['production']['dt']['ds']['wmt']   = 0;

		$output['subcon']['production']['dt']['ns']['unit']  = 0; 
		$output['subcon']['production']['dt']['ns']['trips'] = 0;
		$output['subcon']['production']['dt']['ns']['wmt']   = 0;

		$output['subcon']['barging']['adt']['ds']['unit']  = 0; 
		$output['subcon']['barging']['adt']['ds']['trips'] = 0;
		$output['subcon']['barging']['adt']['ds']['wmt']   = 0;

		$output['subcon']['barging']['adt']['ns']['unit']  = 0; 
		$output['subcon']['barging']['adt']['ns']['trips'] = 0;
		$output['subcon']['barging']['adt']['ns']['wmt']   = 0;	

		$output['subcon']['barging']['dt']['ds']['unit']  = 0; 
		$output['subcon']['barging']['dt']['ds']['trips'] = 0;
		$output['subcon']['barging']['dt']['ds']['wmt']   = 0;

		$output['subcon']['barging']['dt']['ns']['unit']  = 0; 
		$output['subcon']['barging']['dt']['ns']['trips'] = 0;
		$output['subcon']['barging']['dt']['ns']['wmt']   = 0;


		//$result = $this->md_dashboard->get_dt_all($date);

		$result = $this->query();

		$data = array();
		$dash_result = array();
		foreach($result as $row)
		{
			$data[$row['body_no']][] = $row;
		}

		foreach($data as $row)
		{	

			$result =  $this->md_dashboard->get_dt_solo($row,$date);			
			$trip_result = $this->lib_dt->no_trips_data($result,$date,'all');

			$haul_owner = null;
			if($trip_result['haul_owner']=='inhouse'){
				$haul_owner="inhouse";
			}else{
				$haul_owner="subcon";
			}

			switch($trip_result['type']){

				case"ADT":

						$output[$haul_owner]['production']['adt']['ds']['unit']  += $trip_result['ds_production']['unit'];
						$output[$haul_owner]['production']['adt']['ds']['trips'] += $trip_result['ds_production']['trips'];
						$output[$haul_owner]['production']['adt']['ds']['wmt']   += $trip_result['ds_production']['wmt'];
						$output[$haul_owner]['production']['adt']['ns']['unit']  += $trip_result['ns_production']['unit'];
						$output[$haul_owner]['production']['adt']['ns']['trips'] += $trip_result['ns_production']['trips'];
						$output[$haul_owner]['production']['adt']['ns']['wmt']   += $trip_result['ns_production']['wmt'];
						
						$output[$haul_owner]['barging']['adt']['ds']['unit']  += $trip_result['ds_barging']['unit'];
						$output[$haul_owner]['barging']['adt']['ds']['trips'] += $trip_result['ds_barging']['trips'];
						$output[$haul_owner]['barging']['adt']['ds']['wmt']   += $trip_result['ds_barging']['wmt'];
						$output[$haul_owner]['barging']['adt']['ns']['unit']  += $trip_result['ns_barging']['unit'];
						$output[$haul_owner]['barging']['adt']['ns']['trips'] += $trip_result['ns_barging']['trips'];
						$output[$haul_owner]['barging']['adt']['ns']['wmt']   += $trip_result['ns_barging']['wmt'];

				break;

				case"DT":

						$output[$haul_owner]['production']['dt']['ds']['unit']  += $trip_result['ds_production']['unit'];
						$output[$haul_owner]['production']['dt']['ds']['trips'] += $trip_result['ds_production']['trips'];
						$output[$haul_owner]['production']['dt']['ds']['wmt']   += $trip_result['ds_production']['wmt'];
						$output[$haul_owner]['production']['dt']['ns']['unit']  += $trip_result['ns_production']['unit'];
						$output[$haul_owner]['production']['dt']['ns']['trips'] += $trip_result['ns_production']['trips'];
						$output[$haul_owner]['production']['dt']['ns']['wmt']   += $trip_result['ns_production']['wmt'];

						$output[$haul_owner]['barging']['dt']['ds']['unit']  += $trip_result['ds_barging']['unit'];
						$output[$haul_owner]['barging']['dt']['ds']['trips'] += $trip_result['ds_barging']['trips'];
						$output[$haul_owner]['barging']['dt']['ds']['wmt']   += $trip_result['ds_barging']['wmt'];
						$output[$haul_owner]['barging']['dt']['ns']['unit']  += $trip_result['ns_barging']['unit'];
						$output[$haul_owner]['barging']['dt']['ns']['trips'] += $trip_result['ns_barging']['trips'];
						$output[$haul_owner]['barging']['dt']['ns']['wmt']   += $trip_result['ns_barging']['wmt'];
						
				break;

			}	
					
			$output['production_trip'] += $trip_result['trip_production'];
			$output['production_wmt']  += $trip_result['total_production_wmt'];

			$output['barging_trip'] += $trip_result['trip_barging'];
			$output['barging_wmt'] += $trip_result['total_barging_wmt'];

			$result = null;
			$trip_result = null;
			
		}

		$output['full_date'] = $full_date;
					
		echo json_encode($output);

		//$result = $this->md_dashboard->get_dt_solo2();

		/*$this->output->enable_profiler(TRUE);*/
		/*$trucks = array_keys($this->md_map->get_trucks($date,$date,$arg));
		$output['production_trip'] = 0;
		$output['production_wmt'] = 0;
		$output['barging_trip'] = 0;
		$output['barging_wmt'] = 0;
		foreach($trucks as $row){
			$result = $this->lib_dt->no_trips($row,$date,'all');
			$output['production_trip'] += $result['trip_production'];
			$output['production_wmt']  += $result['total_production_wmt'];

			$output['barging_trip'] += $result['trip_barging'];
			$output['barging_wmt'] += $result['total_barging_wmt'];
		}	
		
		echo "<pre>";
		print_r($output);
		echo "</pre>";
		$this->output->enable_profiler(TRUE);
		*/

	}


	public function get_max(){

		//$date = $this->input->post('date');		
		//$date = $this->input->post('date');

		$date = '2014-06-05';

		$full_date = date('F d, Y',strtotime($date));

		$arg = null;
		$trip_result = array();

		$output['production_trip']= 0;
		$output['production_wmt']= 0;
		$output['barging_trip']= 0;
		$output['barging_wmt']= 0;

		$result = $this->md_dashboard->get_dt_all($date);

		$data = array();
		$dash_result = array();

		foreach($result as $row)
		{
			$data[$row['body_no']][] = $row;
		}

		$tops = array();

		foreach($data as $row)
		{	

			$result =  $this->md_dashboard->get_dt_solo($row,$date);			
			
			//$top_five[] = $result['data'][0];
			//$trip_result = $this->lib_dt->no_trips_data($result,$date,'all');


			if(isset($result['data'][0]))
			{
				$tops[$result['dt']] = $result['data'][0];
				$tops[$result['dt']]['dt'] = $result['dt'];
			}
			
			$result      = null;
			$trip_result = null;

		}

		usort($tops, function($a,$b){
			$a = strtotime($a['start_time_dup']);
			$b = strtotime($b['start_time_dup']);
			 return ($a > $b)? -1 : 1;
		});

		echo "<pre>";
		print_r($tops);
		echo "</pre>";
		
		$this->output->enable_profiler(TRUE);
		echo json_encode($output);

	}


	public function get_trips(){

		//$date = '2014-06-27';
		
		$date = $this->input->post('date');

		$full_date = date('F d, Y',strtotime($date));

		$arg = null;
		$trip_result = array();

		$output['production_trip']= 0;
		$output['production_wmt']= 0;
		$output['barging_trip']= 0;
		$output['barging_wmt']= 0;

		//$result = $this->md_dashboard->get_dt_type($date,'FVC');
		$result = $this->query();

		//$result = $this->md_dashboard->get_dt_all($date);

		$data = array();
		$dash_result = array();

		foreach($result as $row)
		{
			$data[$row['body_no']][] = $row;
		}

		$tops = array();
		$cnt = 0;
		foreach($data as $row)
		{	


			$result =  $this->md_dashboard->get_dt_solo($row,$date);
						
		
			
			//$top_five[] = $result['data'][0];
			//$trip_result = $this->lib_dt->no_trips_data($result,$date,'all');
		
			if(isset($result['data'][0]))
			{

				foreach($result['data'] as $result_row){

					if($result_row['operation']!='Barging'){

						$tops[$cnt]       = $result_row;
						$tops[$cnt]['dt'] = $result['dt'];
						$cnt++;		
					}

				}

			}
			
			$result      = null;
			$trip_result = null;
				
		}

		
	
		usort($tops, function($a,$b){
			$a = strtotime($a['start_time_dup']);
			$b = strtotime($b['start_time_dup']);
			 return ($a < $b)? -1 : 1;
		});

		/*
		echo "<pre>";
		print_r($tops);
		echo "</pre>";
		*/

		/*
			$this->output->enable_profiler(TRUE);
		*/

		echo json_encode($tops);

	}

	public function get_interval(){

		$date = '2014-06-10';
		//$date = $this->input->post('date');
		$full_date = date('F d, Y',strtotime($date));

		$arg = null;
		$trip_result = array();
		
		//
		//$result = $this->md_dashboard->get_dt_all($date);
		$time1 = "2014-06-10 08:47:19";
		$time2 = "2014-06-10 09:20:49";
		$result = $this->md_dashboard->get_dt_interval($time1,$time2);
		//


		$data = array();
		$dash_result = array();

		foreach($result as $row)
		{
			$data[$row['body_no']][] = $row;
		}


		$tops = array();
		$cnt = 0;
		foreach($data as $row)
		{	

			$result =  $this->md_dashboard->get_dt_solo($row,$date);
			
			//$top_five[] = $result['data'][0];
			//$trip_result = $this->lib_dt->no_trips_data($result,$date,'all');
			
			if(isset($result['data'][0]))
			{
				foreach($result['data'] as $result_row){

					$tops[$cnt]       = $result_row;
					$tops[$cnt]['dt'] = $result['dt'];
					$tops[$cnt]['haul_type'] = $result['haul_type'];
					$tops[$cnt]['haul_desc'] = $result['haul_desc'];
					$cnt++;
					
				}				
			}
			
			$result      = null;
			$trip_result = null;
			
		}

		usort($tops, function($a,$b){
			$a = strtotime($a['start_time_dup']);
			$b = strtotime($b['start_time_dup']);
			 return ($a < $b)? -1 : 1;
		});

		echo "<pre>";
			print_r($tops);
		echo "</pre>";

		$this->output->enable_profiler(TRUE);

		/*		
		echo "<pre>";
		print_r($tops);
		echo "</pre>";		
		*/
		/*
			$this->output->enable_profiler(TRUE);
		*/
		//echo json_encode($tops);

	}















}