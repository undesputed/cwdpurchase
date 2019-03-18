<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Equipment_history extends CI_Controller {

	public function __construct(){

		parent :: __construct();				
		$this->load->model(array('equipment_history/md_equipment_history'));

	}

	public function index(){

		$this->lib_auth->title = "Equipment History";		
		$this->lib_auth->build = "equipment_history/equipment_history";
		
		$result = $this->md_equipment_history->get_inhouse_equipment_2();
		$group = array();
		foreach($result as $row){
			$group[trim($row['description'])][] = $row['equipment_brand'];
		}

		$data['group'] = $group;

		$this->lib_auth->render($data);
		
	}


	public function get_details_breakdown(){


		$arg = $this->input->post();

		$result = $this->md_equipment_history->equipment_breakdown($arg);


		$total    = array();
		$complete = array();
		$ticks    = array();
		$cnt      = 1;

		foreach($result as $row)
		{

			$ticks[$cnt] = $row['month'];			
			$complete[] = array($cnt,$row['complete'],'Complete Jo',$row['month']);
			$total[] = array($cnt,$row['total'],'total Jo',$row['month']);			
			$cnt++;

		}
				
		$a['output'][] = array(
							'label'=>'Complete Jo',
							'data'=>$complete,
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
							'label'=>'Total Jo',
							'data'=>$total,
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
		$a['ticks']   = $ticks;
		$a['label']   = $arg['unit'];
		$data['json'] = json_encode($a);

		$this->load->view('equipment_history/equipment_breakdown',$data);

	}


	public function get_details()
	{
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();

		/*$arg['unit'] = 'ADT-01';
		$arg['from'] = '2014-09-01';
		$arg['to'] = '2014-09-15';*/

		$result = $this->md_equipment_history->equipment_productivity($arg);

		$trips = array();
		$wmt   = array();
		$ticks = array();
		$cnt = 1;
		foreach($result as $row)
		{
			$ticks[$cnt] = date('M d',strtotime($row['trans_date']));
			
			$trips[] = array($cnt,$row['trips'],'trips');
			$wmt[] = array($cnt,$row['wmt'],'wmt');
			
			$cnt++;
		}

			$a['output'][] = array(
					'label'=>'Trips',
					'data'=>$trips,
					'lines'=>array(
						'show'=>'true',
						),
					'points'=>array(
						'show'=>'true',
						),
					'xaxis'=>1,
					'yaxis'=>1,
				);
			
			$a['output'][] = array(	
						'label'=>'Wmt',
						'data'=>$wmt,
						'lines'=>array(
							'show'=>'true',
							),
						'points'=>array(
							'show'=>'true',
							),
						'xaxis'=>1,
						'yaxis'=>2,
					);

			$a['ticks'] = $ticks;
			$a['label'] = $arg['unit'];

			$data['json'] = json_encode($a);			
			$this->load->view('equipment_history/equipment_productivity',$data);

			$this->get_details_breakdown();

			/*
			$ticks = array();
			$cnt = 1;
			foreach($result as $row){

				$ticks[$cnt] = $row['Month'];
				$ma = $row['MA'] * 100;
				$data_content[] = array(
							'label'=>date('F',strtotime($row['Month'])),
							'data'=>array(array($cnt,$ma)),
							'bars'=>array(
								'show'=>'true',
								'align'=>'center',						
								'fill'=>'true',
								'lineWidth'=>'1',							
								'barWidth'=> 0.70,
								),						
							'valueLabels'=>array('show'=>true,'showAsHtml'=>true),
							);
				$cnt++;

			}

			$output = array(
				'data'=>$data_content,
				'ticks'=>$ticks,
				'title'=>$arg['equipment'],
				);
			*/


	}


}