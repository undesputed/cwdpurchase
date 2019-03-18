<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Manage_report extends CI_Controller {

	var $backdate = 2;

	public function __construct(){
		parent :: __construct();

		$this->load->model(array('manage_report/md_manage_report','daily_production_report/md_daily_production_report'));

		$this->load->library('lib_manage_report');

	}


	public function index(){

		$this->lib_auth->title = "Manage Report";		
		$this->lib_auth->build = "manage_report/view";		
		$this->lib_auth->render();
		
	}

	public function get_stockpile(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$from = date('Y-m-01');
		//$to   = date('Y-m-t');
		$to    = date('Y-m-d');
		$prev_month = date("Y-m-1", strtotime("first day of previous month") );

		$result = $this->md_manage_report->get_stockpile($prev_month,$from,$to);		
		$data_content = array();
		$ykeys = array();


		$color = array('LG HF'=>'#f8e72a','LG MF'=>'#a2ee3f','MG HF'=>'#eea229','MG MF'=>'#1380dd','MG LF'=>'#ee3229','HG'=>'#CCC');

		$date = "";
		$total = 0;

		foreach($result as $row)
		{
			$cnt = 0;
			foreach($row as $keys=>$row1)
			{
				
				switch($keys){
					case"DATE" :
						$date = date('F d,Y',strtotime($row1));
					break;
					default:
					$cnt++;
					$total = $total + $row1;
					$data_content[] = array(
						'label'=>$keys,
						'data'=>array(array($cnt,$row1)),
						'bars'=>array(
							'show'=>'true',
							'align'=>'center',						
							'fill'=>'true',
							'lineWidth'=>'1',
							'fillColor'=>$color[$keys],
							'barWidth'=> 0.90,
							),
						'color'=>$color[$keys],
						'valueLabels'=>array('show'=>true,'showAsHtml'=>true),
						);

					break;
				}
			}
			//$row['DATE'] = date('F d, Y',strtotime($row['DATE']));
			
		}

		/*$data_content[] = array(
						'label'=>'LG HF',
						'data'=>array(array($keys,$row1)),
						'bars'=>array(
							'show'=>'true',
							'align'=>'center',						
							'fill'=>'true',
							'lineWidth'=>'1',
							),
						'color'=>$color[$keys],

			);*/

		$data = array(
			'data'=>$data_content,	
			'date'=>$date,
			'total'=>$total,
			);
		
		echo json_encode($data);
		
	}


	public function get_production(){

		$content = array();
		$header  = array();
		$data    = array();
		$avg     = array();


		$_from = strtotime($this->input->get('from'));
		$_to = strtotime($this->input->get('to'));


		$from = date('Y-m-01',$_from);
		$to   = date('Y-m-t',$_to);		
		$month = date('F',$_from);

		$result = $this->md_manage_report->get_production($from,$to);
		
		$cnt = 0;
		$data_obj = array();
		$row_ = array();


		$data_obj['unit_adt_day']= array();
		$data_obj['unit_adt_night']= array();
		$data_obj['unit_dt_day']= array();
		$data_obj['unit_dt_night']= array();
		$data_obj['trip_adt_day']= array();
		$data_obj['trip_adt_night']= array();
		$data_obj['trip_dt_day']= array();
		$data_obj['trip_dt_night']= array();


		foreach($result as $row){

				$data_obj['unit_adt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS ADT UNIT']);
				$data_obj['unit_adt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS ADT UNIT']);
				$data_obj['unit_dt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS DT UNIT']);
				$data_obj['unit_dt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS DT UNIT']);

				$data_obj['trip_adt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS ADT TRIP']);
				$data_obj['trip_adt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS ADT TRIP']);
				$data_obj['trip_dt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS DT TRIP']);
				$data_obj['trip_dt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS DT TRIP']);

				$row_[] = $row;

		}



			$cnt = count($data_obj['unit_dt_day']);

			$backdate =  ($cnt!=0)? $cnt-$this->backdate : 0;
			if($cnt == 0){
				$day_date = $from;

			}else{
				$day_date   = date('Y-m-d',$this->backtotime($data_obj['unit_dt_day'][$backdate][0]));
				$night_date = date('Y-m-d',$this->backtotime($data_obj['unit_dt_day'][$backdate][0]));	
				$avg = $row_[$backdate];
			}
			

			$date = date('F d',strtotime($day_date));			
			
			$date1 = date('Y-m-d',strtotime($day_date));

			$operation = "PRODUCTION";
			$type = "ADT";
			$owner = "";

			$result = $this->md_manage_report->avg_trips($date1,$operation,$type,$owner);			
			$average['mine']['adt']['all'] =@ round(($result[0]['total_trips'] / $result[0]['no_trucks']));

			$type = "DT";
			$result = $this->md_manage_report->avg_trips($date1,$operation,$type,$owner);
			$average['mine']['dt']['all'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));


			$data = array();
			$data[] = array(
					'label'=>'ADT - DS',
					'data'=>$data_obj['unit_adt_day'],
				);
			$data[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['unit_dt_day'],
				);

			$data[] = array(
					'label'=>'ADT - NS',
					'data'=>$data_obj['unit_adt_night'],
				);
		

			$data[] = array(
					'label'=>'DT - NS',
					'data'=>$data_obj['unit_dt_night'],
				);

		


		

			$trip = array();			
			$trip[] = array(
					'label'=>'ADT - DS',
					'data'=>$data_obj['trip_adt_day'],
				);

			$trip[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['trip_dt_day'],
				);	

			$trip[] = array(
					'label'=>'ADT - NS',
					'data'=>$data_obj['trip_adt_night'],
				);
		
			$trip[] = array(
					'label'=>'DT - NS',
					'data'=>$data_obj['trip_dt_night'],
				);

			$result = array(				
				'unit'=>$data,
				'trip'=>$trip,
				'avg'=>$avg,
				'date'=>$date,
				'month'=>$month,
				'average'=>$average,
			);
						
			echo json_encode($result);

			//$this->output->enable_profiler(TRUE);			
	}

	public function get_subcon(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$content = array();
		$header  = array();
		$data    = array();
		$avg     = array();

		$_from = strtotime($this->input->get('from'));
		$_to = strtotime($this->input->get('to'));


		$from = date('Y-m-01',$_from);
		$to   = date('Y-m-t',$_to);

		$month = date('F',$_from);

		/*$result_dates = $this->md_manage_report->get_production_dates($from,$to);
		$result = array();
		foreach ($result_dates as $key => $value) {
			$result[]  = $this->md_manage_report->get_subcon_inhouse($value['trans_date'],'subcon');	
		}*/
			

		$result  = $this->md_manage_report->get_subcon_inhouse($from,$to,'SUBCON');	

		$cnt = 0;		
		$data_obj = array();
		$row_ = array();


		$data_obj['unit_dt_day'] = array();
		$data_obj['unit_dt_night'] = array();
		$data_obj['trip_dt_day'] = array();
		$data_obj['trip_dt_night'] = array();


		foreach($result as $row){


				//$data_obj['unit_adt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS ADT UNIT']);
				//$data_obj['unit_adt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS ADT UNIT']);
				$data_obj['unit_dt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS DT UNIT']);
				$data_obj['unit_dt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS DT UNIT']);

				//$data_obj['trip_adt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS ADT TRIP']);
				//$data_obj['trip_adt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS ADT TRIP']);
				$data_obj['trip_dt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS DT TRIP']);
				$data_obj['trip_dt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS DT TRIP']);

				$row_[] = $row;

			}

					
			$cnt = count($data_obj['unit_dt_day']);			

			if($cnt == 0){

				$day_date = $from;
			}else{
				$avg = $row_[$cnt-$this->backdate];
				$day_date   = date('Y-m-d',$this->backtotime($data_obj['unit_dt_day'][$cnt-$this->backdate][0]));
				$night_date = date('Y-m-d',$this->backtotime($data_obj['unit_dt_day'][$cnt-$this->backdate][0]));
			}

		

			$date = date('F d',strtotime($day_date));
			$date1 = date('Y-m-d',strtotime($day_date));


			$operation = "PRODUCTION";		
			$owner = "AND haul_owner <> '1'";
			$type  = "DT";
			$result = $this->md_manage_report->avg_trips($date1,$operation,$type,$owner);			
			$average['mine']['dt']['subcon'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));

			$data = array();

			/*$data[] = array(
					'label'=>'ADT - DS',
					'data'=>$data_obj['unit_adt_day'],
				);*/
			$data[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['unit_dt_day'],
				);

			/*$data[] = array(
					'label'=>'ADT - NS',
					'data'=>$data_obj['unit_adt_night'],
				);*/
		

			$data[] = array(
					'label'=>'DT - NS',
					'data'=>$data_obj['unit_dt_night'],
				);

			/*
				$data[] = array(
					'label'=>'AVG',
					'data'=>array(array('1399420800000','40'),array('1399507200000','30')),
					'yaxis'=>2,
				);*/
			



			$trip = array();			
			/*$trip[] = array(
					'label'=>'ADT - DS',
					'data'=>$data_obj['trip_adt_day'],
				);
*/
			$trip[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['trip_dt_day'],
				);	

			/*$trip[] = array(
					'label'=>'ADT - NS',
					'data'=>$data_obj['trip_adt_night'],
				);*/
		
			$trip[] = array(
					'label'=>'DT - NS',
					'data'=>$data_obj['trip_dt_night'],
				);

			$result = array(				
				'unit'=>$data,
				'trip'=>$trip,
				'avg'=>$avg,
				'date'=>$date,
				'month'=>$month,
				'average'=>$average
				);

			echo json_encode($result);
			

	}


	public function get_inhouse(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$content = array();
		$header  = array();
		$data    = array();
		$avg     = array();

		$_from = strtotime($this->input->get('from'));
		$_to = strtotime($this->input->get('to'));

		$from = date('Y-m-01',$_from);
		$to   = date('Y-m-t',$_to);

		$month = date('F',$_from);
		

		$result  = $this->md_manage_report->get_subcon_inhouse($from,$to,'INHOUSE');	
			
		$cnt = 0;		
		$data_obj = array();
		$row_ = array();


		$data_obj['unit_adt_day']   = array();
		$data_obj['unit_adt_night'] = array();
		$data_obj['unit_dt_day']    = array();
		$data_obj['unit_dt_night']  = array();
		$data_obj['trip_adt_day']   = array();
		$data_obj['trip_adt_night'] = array();
		$data_obj['trip_dt_day']    = array();
		$data_obj['trip_dt_night']  = array();



		foreach($result as $row){

				$data_obj['unit_adt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS ADT UNIT']);
				$data_obj['unit_adt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS ADT UNIT']);
				$data_obj['unit_dt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS DT UNIT']);
				$data_obj['unit_dt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS DT UNIT']);

				$data_obj['trip_adt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS ADT TRIP']);
				$data_obj['trip_adt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS ADT TRIP']);
				$data_obj['trip_dt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS DT TRIP']);
				$data_obj['trip_dt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS DT TRIP']);

				$row_[] = $row;

		}

							
			$cnt = count($data_obj['unit_dt_day']);		

			if($cnt == 0 ){
				$day_date = $from;
			}else{

				$avg = $row_[$cnt-$this->backdate];
				$day_date   = date('Y-m-d',$this->backtotime($data_obj['unit_dt_day'][$cnt-$this->backdate][0]));
				$night_date = date('Y-m-d',$this->backtotime($data_obj['unit_dt_day'][$cnt-$this->backdate][0]));

			}
			
						
			
			$date = date('F d',strtotime($day_date));
			$date1 = date('Y-m-d',strtotime($day_date));
			
			$operation = "PRODUCTION";		

			$owner = "AND haul_owner = '1'";
			$type = "ADT";
			$result = $this->md_manage_report->avg_trips($date1,$operation,$type,$owner);				
			$average['mine']['adt']['inhouse'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));


			$owner = "AND haul_owner = '1'";
			$type = "DT";
			$result = $this->md_manage_report->avg_trips($date1,$operation,$type,$owner);		
			$average['mine']['dt']['inhouse'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));
			

			$data = array();
			$data[] = array(
					'label'=>'ADT - DS',
					'data'=>$data_obj['unit_adt_day'],
				);
			$data[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['unit_dt_day'],
				);

			$data[] = array(
					'label'=>'ADT - NS',
					'data'=>$data_obj['unit_adt_night'],
				);
			
			$data[] = array(
					'label'=>'DT - NS',
					'data'=>$data_obj['unit_dt_night'],
				);
			
			$trip = array();			
			$trip[] = array(
					'label'=>'ADT - DS',
					'data'=>$data_obj['trip_adt_day'],
				);

			$trip[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['trip_dt_day'],
				);	

			$trip[] = array(
					'label'=>'ADT - NS',
					'data'=>$data_obj['trip_adt_night'],
				);
		
			$trip[] = array(
					'label'=>'DT - NS',
					'data'=>$data_obj['trip_dt_night'],
				);

			$result = array(				
				'unit'=>$data,
				'trip'=>$trip,
				'avg'=>$avg,
				'date'=>$date,
				'month'=>$month,
				'average'=>$average
				);

			echo json_encode($result);

	}
	
	public function get_shipment_subcon(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$content = array();
		$header  = array();
		$data    = array();
		$avg     = array();

		$_from = strtotime($this->input->get('from'));
		$_to = strtotime($this->input->get('to'));

		$from = date('Y-m-01',$_from);
		$to   = date('Y-m-t',$_to);

		$month = date('F',$_from);

	
			
		$result = $this->md_manage_report->shipment_subcon_inhouse($from,$to,'SUBCON');	

		$cnt = 0;
		$data_obj = array();
		$row_ = array();


		$data_obj['unit_dt_day']   = array();
		$data_obj['unit_dt_night'] = array();
		$data_obj['trip_dt_day']   = array();
		$data_obj['trip_dt_night'] = array();

		foreach($result as $row){
								
				$data_obj['unit_dt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS DT UNIT']);
				$data_obj['unit_dt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS DT UNIT']);
				
				$data_obj['trip_dt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS DT TRIP']);
				$data_obj['trip_dt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS DT TRIP']);

				$row_[] = $row;

		}

					
			$cnt = count($data_obj['unit_dt_day']);

			if($cnt == 0){

				$day_date = $from;

			}else{
				$avg = $row_[$cnt-$this->backdate];
				$day_date   = date('Y-m-d',$this->backtotime($data_obj['unit_dt_day'][$cnt-$this->backdate][0]));
				$night_date = date('Y-m-d',$this->backtotime($data_obj['unit_dt_day'][$cnt-$this->backdate][0]));
			}

			
						
			
			$date = date('F d',strtotime($day_date));
			$date1 = date('Y-m-d',strtotime($day_date));


			$type = "DT";
			$operation = "BARGING";
			$owner = "AND haul_owner <> '1'";
			$result = $this->md_manage_report->avg_trips($date1,$operation,$type,$owner);				
			$average['shipment']['dt']['subcon'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));



			$data = array();

			/*
			$data[] = array(
					'label'=>'ADT - DS',
					'data'=>$data_obj['unit_adt_day'],
			);
			*/

			$data[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['unit_dt_day'],
				);

			/*$data[] = array(
					'label'=>'ADT - NS',
					'data'=>$data_obj['unit_adt_night'],
				);*/
			
			$data[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['unit_dt_night'],
				);
						

			$trip = array();

			/*$trip[] = array(
					'label'=>'ADT - DS',
					'data'=>$data_obj['trip_adt_day'],
				);*/

			$trip[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['trip_dt_day'],
				);	

			/*$trip[] = array(
					'label'=>'ADT - NS',
					'data'=>$data_obj['trip_adt_night'],
				);*/
		
			$trip[] = array(
					'label'=>'DT - NS',
					'data'=>$data_obj['trip_dt_night'],
				);

			$result = array(				
				'unit'=>$data,
				'trip'=>$trip,
				'avg'=>$avg,
				'date'=>$date,
				'month'=>$month,
				'average'=>$average,
				);

			echo json_encode($result);

	}

	public function get_shipment_inhouse(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$content = array();
		$header  = array();
		$data    = array();
		$avg     = array();

		$_from = strtotime($this->input->get('from'));
		$_to = strtotime($this->input->get('to'));

		$from = date('Y-m-01',$_from);
		$to   = date('Y-m-t',$_to);

		$month = date('F',$_from);

		$result = $this->md_manage_report->shipment_subcon_inhouse($from,$to,'INHOUSE');	
			
		$cnt = 0;
		$data_obj = array();
		$row_ = array();


		$data_obj['unit_dt_day'] = array();
		$data_obj['unit_dt_night'] = array();
		$data_obj['trip_dt_day'] = array();
		$data_obj['trip_dt_night'] = array();


		foreach($result as $row){

				//$data_obj['unit_adt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS ADT UNIT']);
				//$data_obj['unit_adt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS ADT UNIT']);
				$data_obj['unit_dt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS DT UNIT']);
				$data_obj['unit_dt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS DT UNIT']);

				//	$data_obj['trip_adt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS ADT TRIP']);
				//	$data_obj['trip_adt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS ADT TRIP']);
				$data_obj['trip_dt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS DT TRIP']);
				$data_obj['trip_dt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS DT TRIP']);

				$row_[] = $row;

		}
			

			$cnt = count($data_obj['unit_dt_day']);


			if($cnt == 0){

				$day_date = $from;

			}else{

			   $avg = $row_[$cnt-$this->backdate];
			   $day_date   = date('Y-m-d',$this->backtotime($data_obj['unit_dt_day'][$cnt-$this->backdate][0]));
			   $night_date = date('Y-m-d',$this->backtotime($data_obj['unit_dt_day'][$cnt-$this->backdate][0]));

			}
					
			$date = date('F d',strtotime($day_date));
			$date1 = date('Y-m-d',strtotime($day_date));

			$type = "DT";
			$operation = "BARGING";
			$owner = "AND haul_owner = '1'";
			$result = $this->md_manage_report->avg_trips($date1,$operation,$type,$owner);
					
			$average['shipment']['dt']['inhouse'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));


			$data = array();
					
			$data[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['unit_dt_day'],
				);

			/*
				$data[] = array(
					'label'=>'ADT - NS',
					'data'=>$data_obj['unit_adt_night'],
				);
			*/
			
			$data[] = array(
					'label'=>'DT - NS',
					'data'=>$data_obj['unit_dt_night'],
				);
						

			$trip = array();

			/*$trip[] = array(
					'label'=>'ADT - DS',
					'data'=>$data_obj['trip_adt_day'],
				);*/

			$trip[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['trip_dt_day'],
				);	

			/*$trip[] = array(
					'label'=>'ADT - NS',
					'data'=>$data_obj['trip_adt_night'],
				);*/
		
			$trip[] = array(
					'label'=>'DT - NS',
					'data'=>$data_obj['trip_dt_night'],
				);

			$result = array(				
				'unit'=>$data,
				'trip'=>$trip,
				'avg'=>$avg,
				'date'=>$date,
				'month'=>$month,
				'average'=>$average,
				);

			echo json_encode($result);

	}




	public function get_shipment(){

		$content = array();
		$header  = array();
		$data    = array();

		$avg['day_unit'] = array('date'=>'-','total'=>'-');
		$avg['night_unit'] = array('date'=>'-','total'=>'-');
		$avg['total_unit'] = array();
		$avg['day_trips'] = array('date'=>'-','total'=>'-','wmt'=>'-');
		$avg['night_trips'] = array('date'=>'-','total'=>'-','wmt'=>'-');
		$avg['total_trips'] = array();
		$avg['total_wmt'] = array();



		$_from = strtotime($this->input->get('from'));
		$_to = strtotime($this->input->get('to'));

		$from  = date('Y-m-01',$_from);
		$to    = date('Y-m-t',$_to);
		$month = date('F',$_from);


		/*
		$result_dates = $this->md_manage_report->get_production_dates($from,$to);
		$result = array();
		foreach ($result_dates as $key => $value) {
			$result[]  = $this->md_manage_report->get_barging($value['trans_date']);	
		}
		*/

		$result  = $this->md_manage_report->get_barging($from,$to);	
		
		$cnt = 0;		
		$data_obj = array();


		$data_obj['unit_dt_day'] = array();
		$data_obj['unit_dt_night'] = array();
		$data_obj['trip_dt_day'] = array();
		$data_obj['trip_dt_night'] = array();
		$data_obj['day_wmt'] = array();
		$data_obj['night_wmt'] = array();
		$data_obj['total_wmt'] = array();

		foreach($result as $row){

				$data_obj['unit_dt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS DT UNIT']);
				$data_obj['unit_dt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS DT UNIT']);

				$data_obj['trip_dt_day'][] = array($this->milisecond($row['Transaction Date']),$row['DS DT TRIP']);
				$data_obj['trip_dt_night'][] = array($this->milisecond($row['Transaction Date']),$row['NS DT TRIP']);
								
				$data_obj['day_wmt'][]   = $row['DS DT WMT'];
				$data_obj['night_wmt'][] = $row['NS DT WMT'];
				$data_obj['total_wmt'][] = $row['TO-DATE WMT'];

		}


			$cnt = count($data_obj['unit_dt_day']);	

			$backdate = ($cnt!=0)? $cnt-$this->backdate : 0 ;

			if($cnt==0){
				$day_date   = $from;

			}else{
				$day_date   = date('Y-m-d',$this->backtotime($data_obj['unit_dt_day'][$cnt-$this->backdate][0]));
				$night_date = date('Y-m-d',$this->backtotime($data_obj['unit_dt_day'][$cnt-$this->backdate][0]));

				$avg['day_unit']        = array('date'=>$day_date,'total'=>$data_obj['unit_dt_day'][$backdate][1]);
				$avg['night_unit']      = array('date'=>$night_date,'total'=>$data_obj['unit_dt_night'][$backdate][1]);
				$avg['total_unit']      = $avg['day_unit']['total'] + $avg['night_unit']['total'];

				$avg['day_trips']        = array('date'=>$day_date,'total'=>$data_obj['trip_dt_day'][$backdate][1],'wmt'=>number_format($data_obj['day_wmt'][$backdate]),2);
				$avg['night_trips']      = array('date'=>$night_date,'total'=>$data_obj['trip_dt_night'][$backdate][1],'wmt'=>number_format($data_obj['night_wmt'][$backdate],2));
				$avg['total_trips']      = $avg['day_trips']['total'] + $avg['night_trips']['total'];
				$avg['total_wmt']        = number_format($data_obj['total_wmt'][$backdate],2);

			}
			
	
			
			$date = date('F d',strtotime($day_date));
			$date1 = date('Y-m-d',strtotime($day_date));

			$data = array();
			
			$data[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['unit_dt_day'],
				);
		

			$data[] = array(
					'label'=>'DT - NS',
					'data'=>$data_obj['unit_dt_night'],
				);




			$owner = "";
			$type = "DT";
			$operation = "BARGING";

			$result = $this->md_manage_report->avg_trips($date1,$operation,$type,$owner);			
			$average['shipment']['dt']['all'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));



			$trip = array();			
			
			$trip[] = array(
					'label'=>'DT - DS',
					'data'=>$data_obj['trip_dt_day'],
				);	
			
			$trip[] = array(
					'label'=>'DT - NS',
					'data'=>$data_obj['trip_dt_night'],
				);
			

			

			$result = array(				
				'unit'=>$data,
				'trip'=>$trip,
				'avg_unit'=>$avg,
				'date'=>$date,
				'month'=>$month,
				'average'=>$average,
				);

			echo json_encode($result);

	}

	public function get_draft_survey(){


		$result = $this->md_manage_report->get_draft_survey();

		$data = array();
		$draft = array();
		foreach ($result as $key => $value) {

			//$data[$value['vessel_name']]['truck_load'][] = array($this->milisecond($value['draft_date']),$value['truck_load']);
			$data[$value['vessel_name']]['truck_load']['label']  = 'Truck Load';
			$data[$value['vessel_name']]['truck_load']['yaxis']  = 2;			
			$data[$value['vessel_name']]['truck_load']['data'][] = array($this->milisecond($value['draft_date']),$value['truck_load']);

			$data[$value['vessel_name']]['truck_factor']['label']  = 'Truck Factor';
			$data[$value['vessel_name']]['truck_factor']['data'][] = array($this->milisecond($value['draft_date']),$value['truck_factor']);

		}

		echo json_encode($data);

	}




	public function get_shipment_trips(){
		
		$result = $this->md_manage_report->get_shipment();
		$cnt = 0;

		$data_obj = array();
		foreach($result as $row){


			$data_obj['day'][] = array($this->milisecond($row['date']),$row['dt_trip_day']);
			$data_obj['night'][] = array($this->milisecond($row['date']),$row['dt_trip_night']);

		}

		$data = array();		
		$data[] = array(
				'label'=>'DS',
				'data'=>$data_obj['day'],
			);

		$data[] = array(
				'label'=>' NS',
				'data'=>$data_obj['night'],
			);

		echo json_encode($data);

	}

	public function get_mining_plot(){
		$content = array();
		$header  = array();
		$data    = array();
		$result = $this->md_manage_report->get_plot();
		$cnt = 0;

		foreach($result as $row){
			$date = explode('-',$row['date']);
			$cnt++;
			if(in_array($row['type'],$header)){				
				$data[$row['type']][] = array($this->milisecond($row['date']),$row['amount']);
			}else{
				$header[]  = $row['type'];
				$data[$row['type']][] = array($this->milisecond($row['date']),$row['amount']);
				/*$content[] = array('label'=>$row['type'],
				'data'=>array($row['date'],$row['amount']),*/				
			}
		}
		
		$content1 = array();
		foreach($header as $row){
			$content1 = array(
					'label'=>$row,
					'data' =>$data[$row],
				);
			$content[] = $content1;
		}		
		echo json_encode($content);

	}


	private function milisecond($date){
		return (strtotime($date)*1000);
	}

	private function backtotime($date){
		return ($date/1000);
	}

	public function get_donut(){

		$drivers_result = $this->md_manage_report->get_type('driver');
		$truck_result   = $this->md_manage_report->get_type('trucks');
		$drop_result    = $this->md_manage_report->get_type('drop');
		$drivers  = array();
		$trucks   = array();
		$drop     = array();

		foreach($drivers_result as $row){
			$drivers[] = array('label'=>$row['description'],'value'=>$row['value']);
		}

		foreach($truck_result as $row){
			$trucks[] = array('label'=>$row['description'],'value'=>$row['value']);
		}

		foreach($drop_result as $row){
			$drop[] = array('label'=>$row['description'],'value'=>$row['value']);
		}
		
		$output = array(
			'drivers'=>$drivers,
			'trucks'=>$trucks,
			'drop'=>$drop,
			);
		echo json_encode($output);
		
	}

	public function get_total(){
		$result = $this->md_manage_report->get_total();		
		echo json_encode($result);
	}

	public function page(){
		switch($this->input->post('page')){
			case "mining":
				$data['mining'] = $this->md_manage_report->get_all();
				$this->load->view('manage_report/mining',$data);				
			break;
			case"etc":				
				$this->load->view('manage_report/etc');
			break;
			case "total":
				$this->load->view('manage_report/total');
			break;
		}

	}

	public function mining($type){
		switch($type){

			case "save":
				$this->md_manage_report->save();
			break;			

		}

	}


	public function etc($type){
		switch($type){

			case "save":

				$this->md_manage_report->delete_etc($this->input->post('label'));
				foreach($this->input->post('data') as $row){					
					$this->md_manage_report->save_etc($this->input->post('label'),$row['description'],$row['value']);
				}
				
			break;			

		}

	}

	public function total($type){
		switch($type){
			case "save":

				$this->md_manage_report->save_total();

			break;

		}

	}

	public function upload($type){

		switch($type){
			case "production":
				$this->lib_auth->title = "Manage Report";		
				$this->lib_auth->build = "manage_report/upload/production";

				$this->form_validation->set_rules('chker','POST','required');
				if($this->form_validation->run()==TRUE){
					
					$this->lib_upload->makeDir();
					$config['upload_path'] = $this->lib_upload->upload_path;

					$config['allowed_types'] = 'xls';
					$config['max_size'] = '10000';
					$this->upload->initialize($config);

					if(!$this->upload->do_upload('file')){

						$message['content'] = $this->upload->display_errors();
						$message['type'] = "alert-danger";
						$this->session->set_flashdata(array('message'=>$message['content'],'type'=>$message['type']));
						redirect(current_url());
						
					}else{
												
						$data = array('upload_data' => $this->upload->data());

						switch($this->input->post('type')){
							case "mine":

								$result = $this->lib_excel->production($data['upload_data']['full_path']);
								$this->md_manage_report->insert_production($result);

								$message['content'] = "Successfully Import Mine Production Data";
								$message['type'] = "alert-success";

							break;

							case "shipment":

								$result = $this->lib_excel->production($data['upload_data']['full_path']);
								$this->md_manage_report->insert_shipment($result);								
								$message['content'] = "Successfully Import Shipment Operations Data";													
								$message['type'] = "alert-success";

							break;
						}

						

						/*
						$this->md_reports->create($this->lib_upload->url_path.'/'.$data['upload_data']['file_name']);
						$message['content'] = "Successfully Save";
						$message['type'] = "alert-success";
						*/

						$this->session->set_flashdata(array('message'=>$message['content'],'type'=>$message['type']));
						redirect(current_url());

					}
				}else{
					echo validation_errors();
				}


				$this->lib_auth->render();
				
			break;
		}

	}

	public function avg_trip(){
			
		$from = strtotime($this->input->post('from'));
		$to = strtotime($this->input->post('to'));

		$from = date('Y-m-01',$from);
		$to   = date('Y-m-t',$to);
		$month = date('F',strtotime($this->input->post('from')));

		$result_dates = $this->md_manage_report->get_production_dates($from,$to);

		$index = (count($result_dates)-$this->backdate);
		
		$date = $result_dates[$index]['trans_date'];



		$operation = "PRODUCTION";
		$type = "ADT";
		$owner = "";

		$result = $this->md_manage_report->avg_trips($date,$operation,$type,$owner);			
		$avg['mine']['adt']['all'] =@ round(($result[0]['total_trips'] / $result[0]['no_trucks']));


		$type = "DT";
		$result = $this->md_manage_report->avg_trips($date,$operation,$type,$owner);				
		$avg['mine']['dt']['all'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));


		$owner = "AND haul_owner <> '1'";
		$type = "DT";
		$result = $this->md_manage_report->avg_trips($date,$operation,$type,$owner);		
		
		$avg['mine']['dt']['subcon'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));


		$owner = "AND haul_owner = '1'";
		$type = "ADT";
		$result = $this->md_manage_report->avg_trips($date,$operation,$type,$owner);				
		$avg['mine']['adt']['inhouse'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));


		$owner = "AND haul_owner = '1'";
		$type = "DT";
		$result = $this->md_manage_report->avg_trips($date,$operation,$type,$owner);		
		$avg['mine']['dt']['inhouse'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));

		$owner = "";
		$type = "DT";
		$operation = "BARGING";

		$result = $this->md_manage_report->avg_trips($date,$operation,$type,$owner);
				
		$avg['shipment']['dt']['all'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));

		$type = "DT";
		$operation = "BARGING";
		$owner = "AND haul_owner <> '1'";
		$result = $this->md_manage_report->avg_trips($date,$operation,$type,$owner);				
		$avg['shipment']['dt']['subcon'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));

		$type = "DT";
		$operation = "BARGING";
		$owner = "AND haul_owner = '1'";
		$result = $this->md_manage_report->avg_trips($date,$operation,$type,$owner);
				
		$avg['shipment']['dt']['inhouse'] =@  round(($result[0]['total_trips'] / $result[0]['no_trucks']));

		
		echo json_encode($avg);

	}

	public function get_target(){

		$_from = strtotime($this->input->post('from'));
		$_to = strtotime($this->input->post('to'));


		$from  = date('Y-m-01',$_from);
		$to    = date('Y-m-t',$_to);

		/*
		$from = $this->input->post('from');
		$to = $this->input->post('to');
		*/

		$month = date('F',strtotime($from));
		$data_obj['total_wmt'] = array();
		$result = $this->md_manage_report->get_production($from,$to);
		$ticks = array();
		$cnt = 0;
		$transaction_date = array();
		foreach($result as $row){

			//$data_obj['total_wmt'][] = array($this->milisecond($row['Transaction Date']),$row['TO-DATE WMT']);
			$date = date('d',strtotime($row['Transaction Date']));
			$transaction_date[$cnt] = $row['Transaction Date'];
			$ticks[$cnt] = $date;
			$data_obj['total_wmt'][] = array($cnt,$row['TO-DATE WMT']);
			$cnt++;			
		}

		$total_wmt[] = array(
					'label'=>'Total WMT',
					'data'=>$data_obj['total_wmt'],
		);
		
		$output = array(
			'total_wmt'=>$total_wmt,
			'month'=>$month,
			'ticks'=>$ticks,
			'dates'=>$transaction_date
			);
		echo json_encode($output);
	}


	public function get_utilization(){

		$arg = $this->input->post();
		$data['result'] = $this->md_manage_report->get_utilization($arg);
		
		$arg['shift']   = 'ds';
		$arg['date']    = '2014-07-29';
		//$driver_result = $this->md_dispatch->get_online($arg);
		//$this->load->model('dispatch/md_dispatch');
		/*
			foreach($data['result'] as $row){
				foreach($driver_result as $row_a){
					if(str_replace('_',' ',$row_a['equipment']) == $row['EQUIPMENT']){
						echo $row['EQUIPMENT'];
					}
				}
			}
		*/			
		$this->load->view('manage_report/utilization',$data);		
	}


	public function get_vessel(){

		$arg    = $this->input->post();
		$result = $this->md_daily_production_report->display_shipment($arg['date']);

		
		$barge_out = $this->md_manage_report->get_barge_out();

		$div = "";
		$data = array();
		$moris = array();
		$cnt = 1;

		foreach($result as $row){
			$data[] = $row[0];
			
			$div .='<div class="vessel-content" style="display:block">';
	  		$div .='<div class="v-left" style="float:left">';
	  		$div .='<div id="vessel-'.$cnt.'" style="height:120px;width:120px"></div>';
	  		$div .='</div>';
	  		$div .='<div class="v-right" >';
	  		$div .='<h5>'.$row[0]['VESSEL NAME'].'</h5>';
	  		$div .='<h2>'.$this->extra->comma($row[0]['LOAD TODATE']).'</h2>';
	  		$div .='<small>today : '.$this->extra->comma($row[0]['LOAD TODAY']).'</small>';
	  		$div .='</div>';
			$div .='</div>';
			$div .='<div class="clearfix"></div>';

			$remaining = $row[0]['TARGET TONNAGE'] - $row[0]['LOAD TODATE'];

			$moris[] = array(
						array('value'=>$row[0]['LOAD TODATE'],'label'=>'Load'),
						array('value'=>$remaining,'label'=>'Remaining'),
					   );
			$cnt++;

		};



		$output = array(
			'table'=>$div,
			'moris'=>$moris,
			'barge_out'=>$barge_out,
			);

		echo json_encode($output);

	}


	public function progress_target(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo json_encode($this->md_manage_report->progress_target());

	}




}