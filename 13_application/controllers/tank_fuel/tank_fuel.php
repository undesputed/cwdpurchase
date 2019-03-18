<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tank_fuel extends CI_Controller {

	public function __construct(){
		parent :: __construct();		

		$this->load->model('tank_fuel/md_tank_fuel');
	}

	public function index(){

		redirect(base_url().index_page().'/monthly_report','refresh');

		/*$this->lib_auth->title = "Tank & Fuel Monitoring";		
		$this->lib_auth->build = "tank_fuel/fuel_monthly_report";				
		$this->lib_auth->render();*/

	}


	public function tank_fuel_monitoring(){

		$this->lib_auth->title = "Tank & Fuel Monitoring";		
		$this->lib_auth->build = "tank_fuel/tank_fuel_monitoring";				
		$this->lib_auth->render();

	}


	public function get_tank_fuel_total($arg){
				
		$result = $this->md_tank_fuel->get_tank_fuel_total($arg);
		
		$ltrs = array();
		$recieved = array();
		$withdraw = array();
		$begining = array();
		$ticks    = array();
		$cnt = 1;

		foreach($result as $row)
		{	

			$ticks[$cnt] = $row['TANK DESCRIPTION'];				
			$ltrs[]      = array($cnt,str_replace(',','',$row['BALANCE']),'Ending Balance');
			$withdraw[]  = array($cnt,str_replace(',','',$row['MONTH_WITHDRAWN']),'Withdrawn');
			$recieved[]  = array($cnt,str_replace(',','',$row['MONTH_RECEIVED']),'Received');
			$begining[]  = array($cnt,str_replace(',','',$row['BEGINING']),'Beginning');
			$cnt++;

		}


		$a['output'][] = array(
					'label'=>'Withdrawn Fuel',
					'data'=>$withdraw,
					'bars'=>array(
						'order'=>'1',
						'show'=>'true',
						'lines'=>array(
							'show'=>'false'
							),
						'barWidth'=>0.10,
						),
					'xaxis'=>1,
					'stack'=>false,
					'yaxis'=>1,
					'valueLabels'=>array('show'=>true,'showAsHtml'=>true),
		);

		$a['output'][] = array(	
					'label'=>'Received Fuel',
					'data'=>$recieved,
					'bars'=>array(
						'order'=>'2',
						'show'=>'true',
						'lines'=>array(
							'show'=>'false'
							),
						'barWidth'=>0.10,
						),
					'xaxis'=>1,
					'stack'=>false,
					'yaxis'=>1,
					'valueLabels'=>array('show'=>true,'showAsHtml'=>true),
		);

		$a['output'][] = array(	
					'label'=>'Beginning Balance',		
					'data'=>$begining,
					'bars'=>array(
						'order'=>'3',
						'show'=>'true',
						'lines'=>array(
							'show'=>'false'
							),
						'barWidth'=>0.10,
						),
					'xaxis'=>1,
					'stack'=>false,
					'yaxis'=>1,
					'valueLabels'=>array('show'=>true,'showAsHtml'=>true),
		);

		$a['output'][] = array(
					'label'=>'Ending Balance',
					'data'=>$ltrs,
					'bars'=>array(
						'order'=>'4',
						'show'=>'true',
						'lines'=>array(
							'show'=>'false'
							),
						'barWidth'=>0.10,
						),
					'xaxis'=>1,
					'stack'=>false,
					'yaxis'=>1,
					'valueLabels'=>array('show'=>true,'showAsHtml'=>true),
		);

		$a['ticks'] = $ticks;
		return $a;

	}



	public function get_tank_fuel_monitoring(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$result = $this->md_tank_fuel->get_tank_fuel_monitoring($arg);

		$ltrs = array();
		$recieved = array();
		$withdraw = array();
		$begining = array();
		$ticks    = array();
		$cnt = 1;

		foreach($result as $row)
		{	

			$ticks[$cnt] = $row['TANK DESCRIPTION'];				
			$ltrs[]      = array($cnt,str_replace(',','',$row['ENDING_BALANCE']),'Ending Balance');
			$withdraw[]  = array($cnt,str_replace(',','',$row['WITHDRAWN']),'Withdrawn');
			$recieved[]  = array($cnt,str_replace(',','',$row['RECEIVED']),'Received');
			$begining[]  = array($cnt,str_replace(',','',$row['BEGINING']),'Beginning');
			$cnt++;

		}

		

		$a['output'][] = array(
					'label'=>'Withdrawn Fuel',
					'data'=>$withdraw,
					'bars'=>array(
						'order'=>'1',
						'show'=>'true',
						'lines'=>array(
							'show'=>'false'
							),
						'barWidth'=>0.10,
						),
					'xaxis'=>1,
					'stack'=>false,
					'yaxis'=>1,
					'valueLabels'=>array('show'=>true,'showAsHtml'=>true),
		);

		$a['output'][] = array(	
					'label'=>'Received Fuel',
					'data'=>$recieved,
					'bars'=>array(
						'order'=>'2',
						'show'=>'true',
						'lines'=>array(
							'show'=>'false'
							),
						'barWidth'=>0.10,
						),
					'xaxis'=>1,
					'stack'=>false,
					'yaxis'=>1,
					'valueLabels'=>array('show'=>true,'showAsHtml'=>true),
		);

		$a['output'][] = array(	
					'label'=>'Beginning Balance',		
					'data'=>$begining,
					'bars'=>array(
						'order'=>'3',
						'show'=>'true',
						'lines'=>array(
							'show'=>'false'
							),
						'barWidth'=>0.10,
						),
					'xaxis'=>1,
					'stack'=>false,
					'yaxis'=>1,
					'valueLabels'=>array('show'=>true,'showAsHtml'=>true),
		);

		$a['output'][] = array(
					'label'=>'Ending Balance',
					'data'=>$ltrs,
					'bars'=>array(
						'order'=>'4',
						'show'=>'true',
						'lines'=>array(
							'show'=>'false'
							),
						'barWidth'=>0.10,
						),
					'xaxis'=>1,
					'stack'=>false,
					'yaxis'=>1,
					'valueLabels'=>array('show'=>true,'showAsHtml'=>true),
		);



		$a['ticks'] = $ticks;
		
		$output = array(
			'main'=>$a,
			'sidebar'=>$this->get_tank_fuel_total($arg),
			);

		echo json_encode($output);


	}


	public function monthly_report(){

		$this->lib_auth->title = "Tank & Fuel Monthly Report";		
		$this->lib_auth->build = "tank_fuel/fuel_monthly_report";				
		$this->lib_auth->render();

	}

	public function get_fuel_monthly(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();

		$result = $this->md_tank_fuel->fuel_monthly($arg);

		$option = array(
			'result'=>$result,
			'table_class'=>array('table','table-condensed','myTable','table-hover'),
		);

		echo $this->extra->generate_table($option);		

	}


	public function fuel_equipment(){
		
		$this->lib_auth->title = "Fuel Equipment";
		$this->lib_auth->build = "tank_fuel/fuel_equipment";				
		$this->lib_auth->render();

	}

	public function fuel_withdrawal(){

		$this->lib_auth->title = "Fuel Withdrawal";
		$this->lib_auth->build = "tank_fuel/fuel_withdrawal";				
		$this->lib_auth->render();

	}
	
	public function fuel_delivery(){

		$this->lib_auth->title = "Fuel Delivery";
		$this->lib_auth->build = "tank_fuel/fuel_delivery";				
		$this->lib_auth->render();
		
	}
	
	public function get_fuel_withdrawal(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$result = $this->md_tank_fuel->get_fuel_withdrawal($arg);
		$result_total = $this->md_tank_fuel->get_total($arg);
		$data['result'] = $result;

			 $output = array(
			 	'table'=>$this->load->view('tank_fuel/tbl_fuel_withdrawal',$data,true),
			 	'total'=>$result_total,
			 	);
			 
			 echo json_encode($output);

	}

	public function get_fuel_equipment(){

		if(!$this->input->is_ajax_request()){			
			exit(0);
		}

		$arg = $this->input->post();
		$result = $this->md_tank_fuel->get_equipment_fuel($arg);


		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);


		$show = array(
					'UNIT',
					'NO. DAYS',
					'AVE.DAILY NEED',
					'OWNER',
					'TYPE',
					'TOTAL LITERS',
			 		);

			foreach($result as $key => $value){

				$row_content = array();	
				$row_content[] = array('data'=>$value['UNIT']);
				$row_content[] = array('data'=>$value['NO. DAYS']);
				$row_content[] = array('data'=>$value['AVE.DAILY NEED']);
				$row_content[] = array('data'=>$value['OWNER']);
				$row_content[] = array('data'=>$value['TYPE']);
				$row_content[] = array('data'=>$value['TOTAL LITERS']);

				$this->table->add_row($row_content);
			}
	
			$this->table->set_heading($show);

			echo $this->table->generate();


	}

	public function get_fuel_equipment_detail(){

			if(!$this->input->is_ajax_request()){
				exit(0);
			}



			$arg = $this->input->post();
			$result      = $this->md_tank_fuel->get_equipment_fuel_detail($arg);
			$production  = $this->md_tank_fuel->get_production_equipment($arg);

			$year = date('Y',strtotime($arg['date_from']));

			
			$group = array();			
			foreach($result as $row)
			{				
				foreach($production as $key=>$prod_row)
				{	
					
					//echo $prod_row['date'].'-'.$year."<br>";
					$prod_date = date('Y-m-d',strtotime($prod_row['date']));
					$row_date  = date('Y-m-d',strtotime($row['DATE']));

					//echo $prod_date ."<=".$row_date."<br>";
					//echo $prod_date ."=".$prod_row['date']."<br>";
					//echo $row_date ."=".$row['DATE']."<br>";

					if($prod_date <= $row_date)
					{

						$group[$row_date]['fuel'] = $row;
						$group[$row_date]['production'][] = $prod_row;
						unset($production[$key]);

					}					
				}
							
			}
						

			$cnt = 1;
			$ticks = array();
			$line_data = array();
			$bar_data  = array();
			foreach($group as $key=>$row)
			{	
				$ticks[$cnt] = $key;
				$line_data[] = array($cnt,$row['fuel']['LITERS/DAY']);
				foreach($row['production'] as $row1)
				{

					$bar_data[$cnt]['data'][]  = array($cnt,$row1['wmt'],$row1['date'],$row1['production']);
					$bar_data[$cnt]['label'][] = $row1['production'];
					$bar_data[$cnt]['date'][]  = $row1['date'];

				}				
				$cnt++;

			}

			$a = array();			
			$a['output'][] = array(
				'label'=>'Fuel Consumption',
				'data'=>$line_data,
				'lines'=>array(
					'show'=>'true',
					),
				'points'=>array(
					'show'=>'true',
					),
				'xaxis'=>1,
				'yaxis'=>1,
			);
			
			$bar = array();
			
			foreach($bar_data as $row)
			{

				foreach($row['data'] as $key1=>$row2)
				{
					$bar[$key1]['data'][] = $row2;
				}
				$bar[$key1]['label'] = $row['label'];

			}
						
			foreach($bar as $row)
			{

				$a['output'][] = array(					
					'data'=>$row['data'],
					'bars'=>array(
						'order'=>'1',
						'show'=>'true',
						'lines'=>array(
							'show'=>'false'
							),
						'barWidth'=>0.10,
						),
					'xaxis'=>1,
					'stack'=>'stack',
					'yaxis'=>2,
				);

			}

			/*
			echo "<pre>";
			print_r($line_data);
			echo "</pre>";
				
			echo "<pre>";
			print_r($bar_data);
			echo "</pre>";
			*/

			$a['ticks']    = $ticks;
			$data['json']  = json_encode($a);

			$data['title'] = $arg['body_no'];
			$this->load->view('tank_fuel/details_fuel_equipment',$data);

			/*
			$array = array();
			$cnt = 1;
			$ticks = array();
			foreach($result as $row){
				$ticks[$cnt] = $row['DATE'];
				$array[] = array($cnt,$row['LITERS/DAY']);
				$cnt++;

			}

			$a = array();			
			$a['output'] = array(
				'label'=>'Fuel Consumption',
				'data'=>$array,
				'lines'=>array(
					'show'=>'true',
					),
				'points'=>array(
					'show'=>'true',
					),
				'xaxis'=>1,
				'yaxis'=>1,
			);

			$b = array();
			$cnt = 1;
			foreach($production as $row){

				if(isset($ticks[$cnt]) && $ticks[$cnt] == $row['date']){
					$b[] = array($cnt,$row['wmt']);
					$cnt++;
				}

			}

			$a['bargraph'] = array(
				'label'=>'Production',
				'data'=>$b,
				'bars'=>array(
					'show'=>'true',
					'lines'=>array(
						'show'=>'false'
						),
					'barWidth'=>0.10,
					),
				'xaxis'=>1,
				'yaxis'=>2,				
				);

			$a['ticks'] = $ticks;
			$data['json'] = json_encode($a);

			$data['title'] = $arg['body_no'];
			$this->load->view('tank_fuel/details_fuel_equipment',$data);
			*/


		}


		public function get_withdrawal(){
			if(!$this->input->is_ajax_request()){
				exit(0);
			}

			$arg = $this->input->post();
			$data['result'] = $this->md_tank_fuel->fuel_transaction($arg);			
			$this->load->view('tank_fuel/tbl_transaction',$data);

		}

		public function get_received(){
			if(!$this->input->is_ajax_request()){
				exit(0);
			}

			$arg = $this->input->post();
			$data['result'] = $this->md_tank_fuel->get_received_fuel($arg);			
			$this->load->view('tank_fuel/tbl_received',$data);

		}



}
