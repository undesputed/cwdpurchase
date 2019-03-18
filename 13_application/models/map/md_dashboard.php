<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_dashboard extends CI_Model {

	public function __construct(){
		parent :: __construct();	
		$this->load->model('map/md_map');
	}



	public function get_dt_all($date){

		$date2 = date('Y-m-d', strtotime($date .' +1 day'));
		$sql = "SELECT * FROM view_rfid WHERE DATE (time_in) BETWEEN '$date' AND '$date2' AND time_in < '".$date2." 07:00:00' AND time_in > '".$date." 07:00:00'  ORDER BY time_in";

		$result = $this->db->query($sql);		
		return $result->result_array();

	}


	public function get_dt_type($date,$type){
		$date2 = date('Y-m-d', strtotime($date .' +1 day'));
		$sql = "SELECT * FROM view_rfid WHERE DATE (time_in) BETWEEN '$date' AND '$date2' AND time_in < '".$date2." 07:00:00' AND time_in > '".$date." 07:00:00' AND TYPE = '".$type."'   ORDER BY time_in";
		$result = $this->db->query($sql);

		return $result->result_array();
	}

	public function get_dt_type2($date,$arg){

		$extend = '';
		$extend1 = '';

		if(is_array($arg))
		{	
			if(!empty($arg['filter_truck'])){
				switch($arg['filter_truck'])
				{
					case"adt":
						$extend = "AND LEFT(body_no,INSTR(body_no,'-')-1) = 'ADT'";
					break;

					case"dt":
						$extend = "AND LEFT(body_no,INSTR(body_no,'-')-1) = 'DT'";
					break;

					case "all":
						$extend = '';
					break;
				}
			}


			switch($arg['haul_owner'])
			{
				case "inhouse":
					$extend1 = "AND SUBSTRING(body_no,INSTR(body_no,'-')+1) < 300";
				break;

				case "subcon":
					$extend1 = "AND SUBSTRING(body_no,INSTR(body_no,'-')+1) > 300";
				break;

				case "all":
					$extend1 = "";
				break;

			}

		}

		
		$date2 = date('Y-m-d', strtotime($date .' +1 day'));
		$sql = "SELECT *,CONVERT(SUBSTRING(body_no,INSTR(body_no,'-')+1),UNSIGNED INTEGER) AS num FROM view_rfid WHERE DATE (time_in) BETWEEN '$date' AND '$date2' AND time_in < '".$date2." 07:00:00' AND time_in > '".$date." 07:00:00' ".$extend." ".$extend1."  ORDER BY num,time_in asc";
		$result = $this->db->query($sql);

		return $result->result_array();
	}





	public function get_dt_interval($date1,$date2){

		$sql = "SELECT *
				FROM view_rfid
				WHERE time_in BETWEEN '".$date1."'
				    AND '".$date2."'
				ORDER BY time_in";


		$result = $this->db->query($sql);		
		return $result->result_array();
	}


	
	public function diftime($a ,$b , $time = 900){

		$to_next = strtotime($a);
		$to_z = strtotime($b);
	
		$diff_time = ($to_z - $to_next);	
	
		if($diff_time > $time){
			return false;
		}else{
			return true;
		}
	}


	public function format_date($date){
		return date("h:i:s A",strtotime($date));
	}
	

	public function get_dt_solo($data,$date){

		$dt = $data[0]['body_no'];
		$haul_type = $data[0]['type'];
		$haul_info = $data[0]['type_desc'];
		$original_data = $data;
		$next = 0;
		$counter = count($data);
		$data_merge = array();
		$bool = false;
		$merge = array();

			for ($i=0; $i < $counter; $i++) {

				if($data[$i]['status']=='ACTIVE')
				{
					$next = $i + 1; 					
					if(isset($data[$next]['device_name']) && $data[$i]['device_name'] == $data[$next]['device_name'] && $this->diftime($data[$i]['time_in'], $data[$next]['time_in'])){					
						$merge[] = $data[$i];
						$merge[] = $data[$next];
						$z = $next+1;
						$i = $next;
						for ($z;$z < $counter; $z++){						
							if($data[$next]['device_name'] == $data[$z]['device_name'] && $this->diftime($data[$next]['time_in'], $data[$z]['time_in']) ){								
														
							/*	echo "[next: ".$data[$next]['time_in']."]";
								echo "[z: ".$data[$z]['time_in']."]"; */

								$merge[] = $data[$z];
								$i = $z;
								$bool = true;
							}else{														
								break;
							}
						}
						$data_merge[] = $merge;
						$merge = array();

					}else if(isset($data[$next]['time_in']) && $this->diftime($data[$i]['time_in'],$data[$next]['time_in'],10)){
						$merge[] = $data[$i];
						//$merge[] = $data[$next];
						$data_merge[] = $merge;
					}else{
						$merge[] = $data[$i];											
						$data_merge[] = $merge;
						$merge = array();
											
					}	
				}			
		}
				
		$patern = $this->config->item('pattern');


		$cnt = 0;
		$check = 0;
		$check1 = 0;
		$check_array = array();
		$index = 0;

		for ($i=0; $i <count($patern); $i++){ 
			$arg[$i] = array(
				'check'=>0,
				'cnt'=>0,				
				'index'=>0,
				'cnt_patern'=>array(),
				'first'=>0,
			);
		}

		$bool = false;
		$a_index = 0;
		$a_index_skip = false;
		$data_keys = array();

		$return_data = array();
		
				for ($y=0; $y < count($data_merge); $y++){ 
			
		
		/*foreach($data_merge as $key=>$row){*/
						
			//$check,$cnt,$patern1,$data_merge;
			//echo "@".$data_merge[$cnt][0]['device_name']."<br>";
			for ($i=0; $i <count($arg) ; $i++) { 
				$arg[$i] = $this->md_map->pattern($arg[$i],$patern[$i]['data'],$data_merge[$y][0]['device_name'],$y,'pattern'.$i,$patern[$i]['selected']);	
			}
			
			for ($i=0; $i <count($arg); $i++) { 

				if($i == 14){
					
					/*	echo "<pre>";
						print_r($arg[$i]);
						echo "</pre>";
					*/
				}
				
				if(count($arg[$i]['cnt_patern']) == count($patern[$i]['data'])){

					if($arg[$i]['patern']=='pattern0'){
						
					/*	echo "<pre>";
						print_r($arg[$i]);
						echo "</pre>";*/
						
					}

					$bool = true;

					/*
					  echo "<pre>";
						print_r($arg[$i]);
					  echo "</pre>";*/
						/*if($i==7){
						}
					*/

					if($arg[$i]['patern']=='pattern0' || $arg[$i]['patern'] == 'pattern1'){					
						$y--;
					}
					
						$first = $arg[$i]['first'];
						$index = $arg[$i]['index'];						
					
						$counter = (empty($arg[$i]['prev_cnt']))? $arg[$i]['cnt'] : $arg[$i]['prev_cnt'];				
						$operation = $patern[$i]['operation'];
						$arg[$i]['prev_cnt'] = null;						
				}
									
				if($bool){

					/*echo "[BOOOL : ".$counter."]<br>";*/

					$return_data[$a_index]['from']           = $data_merge[$first][0]['device_name'];
					$return_data[$a_index]['start_time']     = $this->format_date($data_merge[$first][0]['time_in']);
					$return_data[$a_index]['start_time_dup'] = $data_merge[$first][0]['time_in'];

					$return_data[$a_index]['to']           = $data_merge[$counter][0]['device_name'];
					$return_data[$a_index]['end_time']     = $this->format_date($data_merge[$counter][0]['time_in']);
					$return_data[$a_index]['end_time_dup'] = $data_merge[$counter][0]['time_in'];
					$return_data[$a_index]['operation']    = $operation;

					for ($i=0; $i < count($arg);$i++){ 
						$arg[$i]['cnt_patern'] = array();
						$arg[$i]['skip_loop']  = -1;
					}
					
					$a_index++;
					$data_keys[] = $first;

				}
			}

			$bool = false;
			/*$cnt++;*/
		}


		$total_time = array();
		$time_night = false;
		for ($i=0; $i < count($data_keys); $i++){ 


				if($i==0)
				{								
						$from_time = strtotime($return_data[0]['end_time_dup']);
						$to_time   = strtotime($data_merge[$data_keys[$i]][0]['time_in']);
						
						$time = round(abs($from_time - $to_time) / 60);
						$total_time[] = $time;
						$return_data[$i]['cycle_time'] =  $time." minutes";
						$return_data[$i]['time'] = $time;
						
				}else
				{	
						
						$time1 = strtotime($return_data[$i]['start_time_dup']);						
						$time2 = strtotime($date." 19:00:00");						

						if($time1 > $time2 ){
															
								if($time_night){
									
									$from_time = strtotime($return_data[$i - 1]['start_time_dup']);
									$to_time   = strtotime($return_data[$i]['start_time_dup']);
									
									$time = round(abs($from_time - $to_time) / 60);
									$total_time[] = $time;
									$return_data[$i]['cycle_time'] =  $time." minutes";
									$return_data[$i]['time'] = $time;
								}else{
									$from_time = strtotime($return_data[$i]['start_time_dup']);
									$to_time   = strtotime($return_data[$i]['end_time_dup']);
									
									$time = round(abs($from_time - $to_time) / 60);
									$total_time[] = $time;
									$return_data[$i]['cycle_time'] =  $time." minutes";
									$return_data[$i]['time'] = $time;
								}
								
								$time_night = true;
						}else{
								
								$from_time = strtotime($data_merge[$data_keys[$i-1]][0]['time_in']);
								$to_time   = strtotime($data_merge[$data_keys[$i]][0]['time_in']);
								$time = round(abs($to_time - $from_time) / 60);
								$total_time[] = $time;
								$return_data[$i]['cycle_time'] = $time." minutes";
								$return_data[$i]['time'] = $time;
						}
								
				}

		}


		$data = array();
		
		$data['NS'] = array();		
		$data['DS'] = array();

		$data['ds_total_barging']     = 0;
		$data['ds_total_production']  = 0;
		$data['ds_total_barging_wmt'] = 0;
		$data['ds_total_production_wmt'] = 0; 

		$data['ns_total_barging']     = 0;
		$data['ns_total_production']  = 0;
		$data['ns_total_barging_wmt'] = 0;
		$data['ns_total_production_wmt'] = 0; 

		$cnt_barging['ds']    = 0;		
		$cnt_production['ds'] = 0;
		$cnt_barging['ns']    = 0;
		$cnt_production['ns'] = 0;
				
		foreach($return_data as $row)
		{
					
			$time1 = strtotime($row['start_time_dup']);
			$time2 = strtotime($date." 19:00:00 ");
						
			if($time1 > $time2){
				$data['NS'][] = $row;


				switch($row['operation']){
					case "Barging":
							$cnt_barging['ns'] = $cnt_barging['ns'] + 1;
							$data['ns_total_barging'] = $cnt_barging['ns'];
					break;

					case "Production":
							$cnt_production['ns'] = $cnt_production['ns'] + 1;
							$data['ns_total_production'] = $cnt_production['ns'];
					break;

					case "Transfer":

					break;

					case "Direct":
							$cnt_barging['ns'] = $cnt_barging['ns'] + 1;
							$data['ns_total_barging'] = $cnt_barging['ns'];

							$cnt_production['ns'] = $cnt_production['ns'] + 1;
							$data['ns_total_production'] = $cnt_production['ns'];
					break;
				}
								

			}else{
			


				$data['DS'][] = $row;


				switch($row['operation']){
					case "Barging":
							$cnt_barging['ds'] = $cnt_barging['ds'] + 1;
							$data['ds_total_barging'] = $cnt_barging['ds'];

					break;

					case "Production":
							$cnt_production['ds'] = $cnt_production['ds'] + 1;
							$data['ds_total_production'] = $cnt_production['ds'];

					break;

					case "Transfer":

					break;

					case "Direct":

						$cnt_barging['ds'] = $cnt_barging['ds'] + 1;
						$data['ds_total_barging'] = $cnt_barging['ds'];

						$cnt_production['ds'] = $cnt_production['ds'] + 1;
						$data['ds_total_production'] = $cnt_production['ds'];	

					break;
				}
				

			}
		}

		$truck_factor = $this->config->item('truck_factor');

		$data['ds_total_barging_wmt']    = ($data['ds_total_barging'] * $truck_factor['BARGING']['DT']);
		$data['ds_total_production_wmt'] = ($data['ds_total_production'] * $truck_factor['PRODUCTION']['DT']);

		$data['ns_total_barging_wmt']    = ($data['ns_total_barging'] * $truck_factor['BARGING']['DT']);
		$data['ns_total_production_wmt'] = ($data['ns_total_production'] * $truck_factor['PRODUCTION']['DT']);


		$total_day   = 0;
		$total_night = 0;

		$barging_cycle_ds    = array();
		$production_cycle_ds = array();
		$transfer_cycle_ds   = array();
		$direct_cycle_ds     = array();

		$barging_cycle_ns    = array();
		$production_cycle_ns = array();
		$transfer_cycle_ns   = array();
		$direct_cycle_ns     = array();

		$avg['DS']['BARGING'] = 0;
		$avg['DS']['PRODUCTION'] = 0;

		if(isset($data['DS'])){
			foreach ($data['DS'] as $row){
				$total_day   += $row['time'];
			
				switch($row['operation'])
				{

					case"Barging":
						$barging_cycle_ds[] =  $row['time'];						
					break;

					case"Production":
						$production_cycle_ds[] = $row['time'];
					break;

					case"Transfer":
						$transfer_cycle_ds[] = $row['time'];
					break;

					case "Direct":
						$direct_cycle_ds[] = $row['time'];
					break;

				}
			}
		}

	

		if(isset($data['NS'])){
			foreach ($data['NS'] as $row){
				$total_night += $row['time'];
				switch($row['operation'])
				{

					case"Barging":
						$barging_cycle_ns[] =  $row['time'];						
					break;

					case"Production":
						$production_cycle_ns[] = $row['time'];
					break;

					case"Transfer":
						$transfer_cycle_ns[] = $row['time'];
					break;

					case"Direct":
						$direct_cycle_ns[] = $row['time'];
					break;

				}
			}
		}


		$row['DS'] = count($data['DS']);
		$avg['DS']['ALL'] =@ round(($total_day/$row['DS']),2);
		$avg['DS']['BARGING'] =@ round((array_sum($barging_cycle_ds)/count($barging_cycle_ds)),2);
		$avg['DS']['PRODUCTION'] =@ round((array_sum($production_cycle_ds)/count($production_cycle_ds)),2);
		$avg['DS']['TRANSFER'] =@ round((array_sum($transfer_cycle_ds)/count($transfer_cycle_ds)),2);
		$avg['DS']['DIRECT'] =@ round((array_sum($direct_cycle_ds)/count($direct_cycle_ds)),2);


		$row['NS'] = count($data['NS']);
		$avg['NS']['ALL'] =@ round(($total_night/$row['NS']),2);
		$avg['NS']['BARGING'] =@ round((array_sum($barging_cycle_ns)/count($barging_cycle_ns)),2);
		$avg['NS']['PRODUCTION'] =@ round((array_sum($production_cycle_ns)/count($production_cycle_ns)),2);
		$avg['NS']['TRANSFER'] =@ round((array_sum($transfer_cycle_ns)/count($transfer_cycle_ns)),2);
		$avg['NS']['DIRECT'] =@ round((array_sum($direct_cycle_ns)/count($direct_cycle_ns)),2);

		$cnt = 0;
				
		foreach($data['DS'] as $row){

			switch($row['operation'])
			{
				case"Barging":

					if($row['time']> $avg['DS']['BARGING']){
						$data['DS'][$cnt]['est'] = "<label class='label label-danger'>- ".$avg['DS']['BARGING']." Minutes </label>";
					}else{
						$data['DS'][$cnt]['est'] = "<label class='label label-success'>+ ".$avg['DS']['BARGING']." Minutes</label>";
					}

				break;

				case"Production":

					if($row['time']> $avg['DS']['PRODUCTION']){
						$data['DS'][$cnt]['est'] = "<label class='label label-danger'> - ".$avg['DS']['PRODUCTION']." Minutes</label>";
					}else{
						$data['DS'][$cnt]['est'] = "<label class='label label-success'>+ ".$avg['DS']['PRODUCTION']." Minutes</label>";
					}

				break;

				case"Transfer":

					if($row['time']> $avg['DS']['TRANSFER']){
						$data['DS'][$cnt]['est'] = "<label class='label label-danger'> - ".$avg['DS']['TRANSFER']." Minutes</label>";
					}else{
						$data['DS'][$cnt]['est'] = " <label class='label label-success'>+ ".$avg['DS']['TRANSFER']." Minutes</label>";
					}

				break;

				case "Direct":

					if($row['time']> $avg['DS']['DIRECT']){
						$data['DS'][$cnt]['est'] = "<label class='label label-danger'> - ".$avg['DS']['DIRECT']." Minutes</label>";
					}else{
						$data['DS'][$cnt]['est'] = " <label class='label label-success'>+ ".$avg['DS']['DIRECT']." Minutes</label>";
					}

				break;

			}
			
			$cnt++;

		}

		$cnt = 0;
		foreach($data['NS'] as $row){

			switch($row['operation']){

				case "Barging":

					if($row['time']> $avg['NS']['BARGING']){
						$data['NS'][$cnt]['est'] = "<label class='label label-danger'> - ".$avg['NS']['BARGING']." Minutes </label>";
					}else{
						$data['NS'][$cnt]['est'] = "<label class='label label-success'>+ ".$avg['NS']['BARGING']." Minutes</label>";
					}


				break;

				case "Production":
					if($row['time']> $avg['NS']['PRODUCTION']){
						$data['NS'][$cnt]['est'] = "<label class='label label-danger'>- ".$avg['NS']['PRODUCTION']." Minutes</label>";
					}else{
						$data['NS'][$cnt]['est'] = "<label class='label label-success'>+ ".$avg['NS']['PRODUCTION']." Minutes</label>";
					}

				break;

				case "Transfer":
					if($row['time']> $avg['NS']['TRANSFER']){
						$data['NS'][$cnt]['est'] = "<label class='label label-danger'>- ".$avg['NS']['TRANSFER']." Minutes </label>";
					}else{
						$data['NS'][$cnt]['est'] = "<label class='label label-success'>+ ".$avg['NS']['TRANSFER']." Minutes</label>";
					}

				break;


				case"Direct":
					if($row['time']> $avg['NS']['DIRECT']){
						$data['NS'][$cnt]['est'] = "<label class='label label-danger'>- ".$avg['NS']['DIRECT']." Minutes </label>";
					}else{
						$data['NS'][$cnt]['est'] = "<label class='label label-success'>+ ".$avg['NS']['DIRECT']." Minutes</label>";
					}

				break;


			}

			$cnt++;
		}
	


		if($total_day > 60){
			$total_day = round(($total_day/60),2)." Hours";
		}else{
			$total_day = $total_day ." Minutes";
		}

		if($total_night > 60){
			$total_night = round(($total_night/60),2)." Hours";
		}else{
			$total_night = $total_night ." Minutes";
		}
		
		$output = array(			
			'data'=>$return_data,
			'avg'=>$avg,
			'shift'=>$data,
			'total_day_time'=>$total_day,
			'total_night_time'=>$total_night,
			'original_data'=>$original_data,
			'dt'=>$dt,
			'haul_type'=>$haul_type,
			'haul_desc'=>$haul_info,
			);		
		return $output;


	}


}