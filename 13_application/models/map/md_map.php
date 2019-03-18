<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_map extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function save(){
		$insert = array(
				'site'=>$this->input->post('site'),
				'status'=>$this->input->post('status'),
				'x'=>$this->input->post('x'),
				'y'=>$this->input->post('y'),
			);			
		$this->db->insert('rfid_map_location',$insert);
	}

	public function get_delivery_ticket($arg){

		$filter = array();

		switch ($arg['filter']) {
			case 'all':
					$filter['shift'] = '';
				break;			
			case 'ns':
					$filter['shift'] = " AND shift = 'ns'";
				break;
			case 'ds':
					$filter['shift'] = " AND shift = 'ds'";
				break;
		}

		switch ($arg['haul_owner']) {
			case 'inhouse':				
					$filter['haul_owner'] = " AND haul_owner = '1'";
				break;			
			case 'subcon':
					$filter['haul_owner'] = " AND haul_owner <> '1'";
				break;
			case 'all':
					$filter['haul_owner'] = '';
				break;
		}

		switch($arg['filter_truck']){

			case 'dt':
					$filter['filter_truck'] = " AND equipment_description <> 'ADT'";
				break;
			case 'adt':
					$filter['filter_truck'] = " AND equipment_description = 'ADT'";
				break;

			default:
					$filter['filter_truck'] = "";
			break;	

		}


		$sql = "SELECT 
				equipment_brand,
				production,
				tag_id,
				shift,
				truck_factor,
				COUNT(*) 'trips',
				SUM(truck_factor) 'wmt',
				CONVERT(SUBSTRING(equipment_brand,INSTR(equipment_brand,'-')+1),UNSIGNED INTEGER ) AS num
				FROM 
				view_area_delivery
				WHERE 
				trans_date = '".$arg['date']."'
				".$filter['haul_owner']."
				".$filter['filter_truck']."
				".$filter['shift']."
				AND (production = 'production' OR production = 'direct')
				GROUP BY equipment_brand
				ORDER BY num ASC;";

		$result = $this->db->query($sql);
		$this->db->close();

		return $result->result_array();

	}



	public function get_rfLogs($dateFrom,$dateTo){
		$sql = "SELECT * FROM rfid_logs WHERE DATE(time_in) BETWEEN DATE('".$dateFrom.".') AND DATE('".$dateTo."')";
		$result = $this->db->query($sql);
		return $result->result_array();
	}

	public function get_trucks($dateFrom,$dateTo,$arg=''){
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
		
		/*AND LEFT(body_no,INSTR(body_no,'-')-1) = 'ADT'*/
		$sql = "SELECT tag_id,id,TRIM(device_name)'device_name',time_in,body_no,CONVERT(SUBSTRING(body_no,INSTR(body_no,'-')+1),UNSIGNED INTEGER) AS num FROM view_rfid WHERE DATE(time_in) BETWEEN DATE('".$dateFrom."') AND DATE('".$dateTo."') ".$extend." ".$extend1." GROUP BY tag_id,id ORDER BY num,time_in asc";
		$result = $this->db->query($sql);


		$row_data = array();
		
		foreach($result->result_array() as $row){
			$row_data[$row['body_no']][] = array($row['time_in'],$row['device_name']);
		}

		return $row_data;
	}




	public function get_site($dateFrom,$dateTo){
		$sql    = "SELECT TRIM(device_name)'device_name' FROM rfid_logs WHERE DATE(time_in) BETWEEN DATE('".$dateFrom."') AND DATE('".$dateTo."') GROUP BY device_name";
		$result = $this->db->query($sql);		
		return $result;
	}


	public function get_date($dateFrom,$dateTo){
		$sql    = "SELECT DATE(time_in) 'date' FROM rfid_logs WHERE DATE(time_in) BETWEEN DATE('".$dateFrom."') AND DATE('".$dateTo."') GROUP BY DATE(time_in)";
		$result = $this->db->query($sql);		
		return $result->result_array();
	}



	public function per_dt($dateFrom,$dateTo){

		$sql = "SELECT * FROM view_rfid WHERE DATE(time_in)BETWEEN DATE('".$dateFrom."') AND DATE('".$dateTo."');";
		$result = $this->db->query($sql);	
		return $result->result_array();

	}


	public function get_all_dt(){
		$sql ="SELECT * FROM view_rfdt GROUP BY body_no;";
		$result = $this->db->query($sql);
		return $result;
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


	public function pattern($arg,$patern,$device,$cnt,$pat,$selected){

			$arg['cnt'] = $cnt;
			$arg['patern'] = $pat;
			$counter = false ;
			$count_loop = 1;
			$prev_device = null;
			$default_loop = -1;

			if(isset($arg['skip_loop'])){
				$default_loop = $arg['skip_loop'];
			}			
			if(in_array($device,$patern)){
			
					foreach ($patern as $key => $value){

						if($count_loop > $default_loop){

								if($value == $device){
									
									if($counter == true){
										$arg['dup_device'][] = $key;
									}
																
									if(!empty($arg['cnt_patern'])){
											
										if(count($arg['cnt_patern']) == $key){
																				
											if(!in_array($cnt,$arg['cnt_patern'])){

												if($prev_device != $device){
													$arg['cnt_patern'][$key] = $cnt;
													$arg['skip_loop'] = -1;
												}									

											}else{
												$arg['skip_loop'] = 1;
											}

											$prev_device = $value;
											
										}else if($key == 0 && count($arg['cnt_patern']) == 1){

											$arg['dup_key'] = $cnt;
											$arg['cnt_patern'][$key] = $cnt;

											/*echo $key."[".$cnt."]<br>";*/
											//$arg['cnt_patern'][0] = $cnt;
										}
										
									}else if($key == 0){
										$arg['cnt_patern'][$key] = $cnt;								
									}else{
										$arg['cnt_patern'] = array();
									}

									if($pat == 'pattern0'){
										//echo "[key:".$key."][device: ".$device."] [cnt : ".$cnt."]<br>";
									}
									$counter = true;									
								}
							}

							$count_loop++;

					}

						if($pat == 'pattern0'){
							
							/*  echo "<pre>";
								print_r($arg);
							  echo "</pre>";*/
							  
						}
											
					/*
					echo "<pre>";
					print_r($arg['cnt_patern']);
					echo "</pre>";	
					*/

					if(count($arg['cnt_patern']) == count($patern)){
						
						$bool = false;
							$save_value = '';
							foreach($arg['cnt_patern'] as $key => $row){
								if($save_value == '')
								{
									$save_value = $row;
								}else if($save_value > $row){									
									$bool = true;
								}
							}

						if($bool){
							$arg['cnt_patern'] = array();
						}else{

							if($selected !=''){							
								$arg['prev_cnt'] = $arg['cnt_patern'][$selected];		
							}							
							$arg['first'] = $arg['cnt_patern'][0];	
							/*
							  echo "<pre>";
							  print_r($arg);
							  echo "</pre>";
							*/
						}
											
					}
				
				if($pat == 'pattern12'){
					/*	
						echo "<pre>";
							print_r($arg);
						echo "</pre>";		
					*/			
				}	

			
				return $arg; 
			}else{
				$arg['cnt_patern'] = array();									
				return $arg;
			}
		

	}

	public function get_rfLogs_dt($dt,$date)
	{

		$date2 = date('Y-m-d', strtotime($date .' +1 day'));		
		$sql = "SELECT * FROM view_rfid WHERE body_no = '$dt' AND DATE (time_in) BETWEEN '$date' AND '$date2' ORDER BY time_in";
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function get_dt_2($dt,$date){

		$date2 = date('Y-m-d', strtotime($date .' +1 day'));

		$sql = "SELECT * FROM view_rfid WHERE body_no = '$dt' AND DATE (time_in) BETWEEN '$date' AND '$date2' AND time_in < '".$date2." 07:00:00' AND time_in > '".$date." 07:00:00'  ORDER BY time_in ";

		//$sql = "call _display_rfid_truck_next('".$dt."','2014-05-23')";	
		$result = $this->db->query($sql);		
		
		$data   = $result->result_array();
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
				$arg[$i] = $this->pattern($arg[$i],$patern[$i]['data'],$data_merge[$y][0]['device_name'],$y,'pattern'.$i,$patern[$i]['selected']);	
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

					$return_data[$a_index]['to']             = $data_merge[$counter][0]['device_name'];
					$return_data[$a_index]['end_time']       = $this->format_date($data_merge[$counter][0]['time_in']);
					$return_data[$a_index]['end_time_dup']   = $data_merge[$counter][0]['time_in'];
					$return_data[$a_index]['operation']      = $operation;

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
						$data['DS'][$cnt]['est'] = "<label class='label label-danger'>- ".($row['time'] - $avg['DS']['BARGING'])." Minutes </label>";
					}else{
						$data['DS'][$cnt]['est'] = "<label class='label label-success'>+ ".($avg['DS']['BARGING'] - $row['time'])." Minutes</label>";
					}

				break;

				case"Production":

					if($row['time']> $avg['DS']['PRODUCTION']){
						$data['DS'][$cnt]['est'] = "<label class='label label-danger'> - ".($row['time'] - $avg['DS']['PRODUCTION'])." Minutes</label>";
					}else{
						$data['DS'][$cnt]['est'] = "<label class='label label-success'>+ ".($avg['DS']['PRODUCTION'] - $row['time'])." Minutes</label>";
					}

				break;

				case"Transfer":

					if($row['time']> $avg['DS']['TRANSFER']){
						$data['DS'][$cnt]['est'] = "<label class='label label-danger'> - ".($row['time'] - $avg['DS']['TRANSFER'])." Minutes</label>";
					}else{
						$data['DS'][$cnt]['est'] = " <label class='label label-success'>+ ".($avg['DS']['TRANSFER'] - $row['time'])." Minutes</label>";
					}

				break;

				case "Direct":

					if($row['time']> $avg['DS']['DIRECT']){
						$data['DS'][$cnt]['est'] = "<label class='label label-danger'> - ".($row['time'] - $avg['DS']['DIRECT'])." Minutes</label>";
					}else{
						$data['DS'][$cnt]['est'] = " <label class='label label-success'>+ ".($avg['DS']['DIRECT'] - $row['time'])." Minutes</label>";
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
			);

		return $output;

	}



	public function get_dt($dt,$date){

		$date2 = date('Y-m-d', strtotime($date .' +1 day'));

		//$sql    = "SELECT * FROM view_rfid WHERE body_no = '$dt' AND DATE (time_in) BETWEEN '$date' AND '$date2' ORDER BY time_in ";
		$sql = "SELECT * FROM view_rfid WHERE body_no = '$dt' AND DATE (time_in) BETWEEN '$date' AND '$date2' AND time_in < '".$date2." 07:00:00' ORDER BY time_in ";

		//$sql = "call _display_rfid_truck_next('".$dt."','2014-05-23')";
		$result = $this->db->query($sql);						
		$data   = $result->result_array();
		$original_data = $data;
		$next = 0;
		$counter = count($data);
		$data_merge = array();
		$bool = false;
		$merge = array();

		for ($i=0; $i < $counter; $i++) { 			
				$next = $i + 1; 					
				if(isset($data[$next]['device_name']) && $data[$i]['device_name'] == $data[$next]['device_name']){					
					$merge[] = $data[$i];
					$merge[] = $data[$next];
					$z = $next+1;
					$i = $next;
					for ($z;$z < $counter; $z++){						
						if($data[$next]['device_name'] == $data[$z]['device_name']){																					
							$merge[] = $data[$z];
							$i = $z;
							$bool = true;
						}else{														
							break;
						}
					}
					$data_merge[] = $merge;
					$merge = array();

				}else{
					$merge[] = $data[$i];
					$data_merge[] = $merge;
					$merge = array();
										
				}				
		}
	

		$start = array(			
			'SAMPLING 7'=>array('next'=>'SAMPLING 9'),
			'PY 7'=>array('next'=>'SAMPLING 7'),
			'LOCATION I'=>array('next'=>'-'),
			'SAMPLING 9'=>array('next'=>'SAMPLING 7'),
			);

		$end = array(
			'SAMPLING A'=>array('next'=>'-','operation'=>'Barging','end'=>'true'),
			'SAMPLING 9'=>array('next'=>'SAMPLING 7','operation'=>'Production'),
			);
	

		$cnt = 0;
		$cnt1 = 0;
		$data_keys = array();
		$return_data = array();
		$cnt2 = 0 ;
		foreach($data_merge as $key=>$row)
		{	
			//if($row[0]['device_name']=="SAMPLING A")
			/*echo isset($end[$row[0]['device_name']]);*/
			
			if(isset($end[$row[0]['device_name']]) && $cnt1 > 0)
			{		
				/*if(isset($end[$row[0]['device_name']]['next']))		
				{
					$search_value = $end[$row[0]['device_name']]['next'];
					$y = $cnt;
					while($search_value == $data_merge[$y][0]['device_name']){						

						$y++;

					}


				}*/
				
				
				$return_data[$cnt]['to'] = $row[0]['device_name'];
				$return_data[$cnt]['end_time'] = $this->format_date($row[0]['time_in']);
				$return_data[$cnt]['end_time_dup'] = $row[0]['time_in'];
				$return_data[$cnt]['operation'] = $end[$row[0]['device_name']]['operation'];
				
				for ($i=$cnt1; $i > $cnt2 ; $i--){
					if(isset($start[$data_merge[$i][0]['device_name']]))
					{

						if(isset($data_merge[$i+1][0]['device_name']) && $start[$data_merge[$i][0]['device_name']]['next'] == $data_merge[$i+1][0]['device_name'] )
						{
							$return_data[$cnt]['from'] = $data_merge[$i+1][0]['device_name'];
							$return_data[$cnt]['start_time'] = $this->format_date($data_merge[$i+1][0]['time_in']);
							$return_data[$cnt]['start_time_dup'] = $data_merge[$i+1][0]['time_in'];

						}else{
							$return_data[$cnt]['from'] = $data_merge[$i][0]['device_name'];
							$return_data[$cnt]['start_time'] = $this->format_date($data_merge[$i][0]['time_in']);	
							$return_data[$cnt]['start_time_dup'] = $data_merge[$i][0]['time_in'];
						}
						
					}									
				}

				if(!isset($return_data[$cnt]['from'])){
					unset($return_data[$cnt]);
				}else{
					$data_keys[] = $key;
					$cnt++;		
					$cnt2 = $cnt1;		
				}

			}

			$cnt1++;
		}
		$total_time = array();
		
		for ($i=0; $i < count($data_keys); $i++){ 
			if($i==0)
			{
					$from_time = strtotime($return_data[0]['start_time_dup']);
					$to_time   = strtotime($data_merge[$data_keys[$i]][0]['time_in']);
					$time = round(abs($to_time - $from_time) / 60);
					$total_time[] = $time;
					$return_data[$i]['cycle_time'] =  $time." minutes";
					$return_data[$i]['time'] = $time;
					
			}else
			{
					$from_time = strtotime($data_merge[$data_keys[$i-1]][0]['time_in']);
					$to_time   = strtotime($data_merge[$data_keys[$i]][0]['time_in']);
					$time = round(abs($to_time - $from_time) / 60);
					$total_time[] = $time;
					$return_data[$i]['cycle_time'] = $time." minutes";
					$return_data[$i]['time'] = $time;
					
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

		$truck_factor = 18;

		foreach($return_data as $row)
		{

			$time1 = strtotime($row['start_time']);
			$time2 = strtotime("07:00:00 PM");
						
			if($time1 > $time2){
				$data['NS'][] = $row;

				if($row['operation'] = 'Barging'){

					$cnt_barging['ns'] = $cnt_barging['ns'] + 1;
					$data['ns_total_barging'] = $cnt_barging['ns'];

				}else{

					$cnt_production['ns'] = $cnt_production['ns'] + 1;
					$data['ns_total_production'] = $cnt_production['ns'];

				}

			}else{

				$data['DS'][] = $row;
				if($row['operation'] =='Barging'){

					$cnt_barging['ds'] = $cnt_barging['ds'] + 1;
					$data['ds_total_barging'] = $cnt_barging['ds'];

				}else{

					$cnt_production['ds'] = $cnt_production['ds'] + 1;
					$data['ds_total_production'] = $cnt_production['ds'];

				}

			}
		}


		$data['ds_total_barging_wmt']    = ($data['ds_total_barging'] * $truck_factor);
		$data['ds_total_production_wmt'] = ($data['ds_total_production'] * $truck_factor);

		$data['ns_total_barging_wmt']    = ($data['ns_total_barging'] * $truck_factor);
		$data['ns_total_production_wmt'] = ($data['ns_total_production'] * $truck_factor);

		$total_day   = 0;
		$total_night = 0;

		if(isset($data['DS'])){
			foreach ($data['DS'] as $row){
				$total_day   += $row['time'];
			}
		}

		if(isset($data['NS'])){
			foreach ($data['NS'] as $row){
				$total_night += $row['time'];
			}
		}		

		$row['DS'] = count($data['DS']);
		$avg['DS'] =@ round(($total_day/$row['DS']),2);

		$row['NS'] = count($data['NS']);
		$avg['NS'] =@ round(($total_night/$row['NS']),2);

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
			);

		return $output;
		
	}


	public function format_date($date){
		return date("h:i:s A",strtotime($date));
	}
	

	public function get_dt2($dt,$date){

		$sql    = "SELECT * FROM view_rfid WHERE body_no = '$dt' AND DATE(time_in) = '$date' ORDER BY time_in ";
		$result = $this->db->query($sql);
		$data   = $result->result_array();

		$points = array(
			'startpoint'=>array(
							 'PY 5 and 6'=>'PY 5 and 6',
							 'SITE K'=>'K: MINE PIT',
							 'SITE D'=>'D: STOCKYARD : PY5 | PY6',
							 'SAMPLING STAND-7'=>'SAMPLING STAND 7 ',
							 'PY 7'=>'PY 7',
							 ),
			'endpoint'=>array(
							 'SAMPLING STAND-A'=>array('prev'=>array('PY 7','SAMPLING STAND 7'),'next'=>array('PY 7','SAMPLING STAND-B',''),'title'=>'SAMPLING STAND A - BARGING'),
							 'SAMPLING STAND-B'=>array('prev'=>array('PY 7','SAMPLING STAND 7'),'next'=>array('SAMPLING STAND-A','PY 7'),'title'=>'SAMPLING STAND B - BARGING'),
							 'SITE F'=>array('prev'=>array('SITE H'),'next'=>array('SITE H'),'title'=>'F: PY4'),
							 'PY 6'=>array('prev'=>array('SITE J','SITE I'),'next'=>array('SITE H'),'title'=>'G: PY5 | PY6 | SUB-CON'),							
							 'SITE D'=>array('prev'=>array('SITE H','SITE M'),'next'=>array('SITE L'),'title'=>'D: PY7'),
							 ),
			);
		
		//'SITE H'=>array('prev'=>array('SITE '),'next'=>array(),'title'=>'H: PY5 | PY6 | IN-HOUSE'),

		$return = array();
		$index  = null;
		$site   = array();
		$row_content = array();
		for ($i=0; $i < count($data) ; $i++) 
		{ 
			
			$device_name = trim($data[$i]['device_name']);	
			$time = date('Y-m-d',strtotime($data[$i]['time_in']));			
			if(isset($data[$i+1]['device_name']) && $data[$i]['device_name'] == $data[$i+1]['device_name']){
				$time2 = date('Y-m-d',strtotime($data[$i+1]['time_in']));
				if($time==$time2){					
					
					$site[$device_name][] = $i;					
					$return[$site[$device_name][0]][$device_name][] = $data[$i]['time_in'];

				}
			}else{

				if(isset($data[$i-1]['device_name']) && trim($data[$i]['device_name']) == trim($data[$i-1]['device_name'])){	
					$return[$site[$device_name][0]][$device_name][] = $data[$i]['time_in'];
				}else{
					$return[][$device_name][] = $data[$i]['time_in'];	
				}

			}

			$row_content[$time] = $return;

		}


	/*	
		echo "<pre style='font-size:11px'>";
		print_r($row_content);
		echo "</pre>";
	*/

		echo "<pre>";
		print_r($row_content);
		echo "</pre>";


		$point = array();
		$main_data = array();
		$end_point = array();
		foreach ($row_content as $key => $value){
			echo "foreach-value<br>";

				/*echo "<pre style='font-size:11px'>";
					print_r(array_keys($value));
				echo "</pre>";*/
							
				$keys = array_keys($value);
				
				$start_index = array();
				$current_array =  array();
				foreach (array_keys($keys) as $k)
				{

					echo "<span style='padding-left:1em'></span>foreach-keys<br>";
					
					
					$prev_key    = (isset($keys[$k-1]))? $keys[$k-1] : "-";
					$current_key = $keys[$k];
					$next_key    = (isset($keys[$k+1]))? $keys[$k+1] : "-";
					
					$prev    = (isset($value[$prev_key]))? $value[$prev_key] : '-' ;
					$current = $value[$current_key];
					$next    = (isset($value[$next_key]))? $value[$next_key] : '-' ;

					$prev_value = "";
										
					if(is_array($prev))
					{
						while($element1 = current($prev)){
							$prev_value = key($prev);							
							next($prev);							
						}
					}

					$next_value = "";
					if(is_array($next))
					{
						while($element1 = current($next)){
							$next_value = key($next);							
							next($next);													
						}						
					}
					
					
					while($element = current($current)) {
						echo "<span style='padding-left:3em'></span>while-current<br>";
					    $key_current = trim(key($current));
					   
					    if(isset($points['endpoint'][$key_current]))
					    {	
					    						    	
					    	$bool = array();
					    	
					    	/*echo "prev".$prev_value."<br>";
					    	echo "curr".$key_current."<br>";
					    	echo "next".$next_value."<br>";*/

					    	if(isset($points['endpoint'][$key_current]['prev']))
					    	{					    		
					    		$bool['prev'] = (in_array($prev_value,$points['endpoint'][$key_current]['prev']))? 1 : 0;
					    		$end_key['prev'] = $k;

					    		echo $prev_value;

					    	}

					    	if(isset($points['endpoint'][$key_current]['next']))
					    	{						    					    			
					    		$bool['next'] = (in_array($next_value,$points['endpoint'][$key_current]['next']))? 1 : 0;
					    		$end_key['next'] = $k;

					    		echo $next_value;
					    	}

					    	
					    	
						    	echo "<pre>";
						    	print_r($bool);
						    	echo "</pre>";
						    
					    	
					    	//print_r($points['endpoint'][$key_current]['prev']);
					    	//echo "------------------------------<br>";
					    	//$bool['next']= (isset($points['endpoint'][$key_current]['next'][$next_value]))? 1 : 0;
							
							if($bool['prev'] == 1 && $bool['next'] == 1){

								
							/*	echo "<pre>";
								print_r($end_key);
								echo "</pre>";*/
								//echo " { END :".$points['endpoint'][$key_current]['title']." - ";
								// echo "<pre>";
								// print_r($start_index);
								// echo "</pre>";

								$point['end'] = $points['endpoint'][$key_current]['title'];	
								$point['end_time'] = $current[$key_current][0];
								//echo $point['end'];


								//print_r($prev[$start_index[$i]]);
								//print_r($value[$key]);
								//echo $key;

								echo "<pre>";
									print_r($start_index);
								echo "</pre>";

								sort($start_index);

								echo "<pre>";
									print_r($start_index);
								echo "</pre>";

								// echo "<pre>";
								// print_r($start_index);
								// echo "</pre>";


								for ($i=0; $i <count($start_index); $i++){
									
									$start_next = (isset($start_index[$i+1]))? $start_index[$i+1] : '-';
									
									if(isset($points['startpoint'][$start_next])){
										
									}else{
										//echo "START".$points['startpoint'][$start_index[$i]]." } </br>";
										
										$point['start'] = $points['startpoint'][$start_index[$i]];
										$point['start_time'] = '';
										$start_index = array();
										$main_data[] = $point;
										break;
									}

								}

									//print_r($start_row);
																	
								/*
								echo "<pre>";
									print_r($current);
								echo "</pre>";
								*/

							}
					    	
					    }

					    $start_index[] = $key_current;
					    $current_array[] = $current;
					    next($current);
											
					}
					
				}             
		}

		return $main_data;

	}



	public function report($date,$data){


		foreach($data as $key=>$value){

			$sql = "select id from rfid_reports where date = '$date' and type = '$key'";
			$result = $this->db->query($sql);

			if($result->num_rows() > 0){
				$bool = true;
			}else{
				$bool = false;
			}

			$insert = array(
				'date'=>$date,
				'ds_adt_unit'=>$value['ds_adt_unit'],
				'ds_adt_trip'=>$value['ds_adt_trip'],
				'ds_adt_wmt'=>$value['ds_adt_wmt'],
				'ds_dt_unit'=>$value['ds_dt_unit'],
				'ds_dt_trip'=>$value['ds_dt_trip'],
				'ds_dt_wmt'=>$value['ds_dt_wmt'],
				'ds_total_wmt'=>$value['ds_total_wmt'],
				'ns_adt_unit'=>$value['ns_adt_unit'],
				'ns_adt_trip'=>$value['ns_adt_trip'],
				'ns_adt_wmt'=>$value['ns_adt_wmt'],
				'ns_dt_unit'=>$value['ns_dt_unit'],
				'ns_dt_trip'=>$value['ns_dt_trip'],
				'ns_dt_wmt'=>$value['ns_dt_wmt'],
				'ns_total_wmt'=>$value['ns_total_wmt'],
				'type'=>$key,
			);
			
			if($bool){
				$this->db->where('date',$date);
				$this->db->where('type',$key);
				$this->db->update('rfid_reports',$insert);
				
			}else{
				$this->db->insert('rfid_reports',$insert);	
				
			}

		}
			return true;




	}


	public function get_report($type,$date){

		$sql = "SELECT * FROM rfid_reports WHERE TYPE = '$type' AND MONTH(date) = '".$date['month']."' AND YEAR(date) = '".$date['year']."' ORDER BY date;";
		$result = $this->db->query($sql);

		return $result->result_array();

	}


	public function update_status($arg){

		$status = array('ACTIVE'=>'INACTIVE','INACTIVE'=>'ACTIVE');
		$sql = "select status from rfid_logs where id = '".$arg['id']."'";
		$result = $this->db->query($sql);		
		$row = $result->row_array();		

		$update = array(
		 	'status'=>$status[$row['status']],
			);

		$this->db->where('id',$arg['id']);
		$this->db->update('rfid_logs',$update);

		return $status[$row['status']];
	}


	public function delivery_details($arg){
		switch($arg['shift']){
			case"ds":
				$extend = " AND SHIFT = 'ds'";
			break;

			case"ns":
			$extend = " AND SHIFT = 'ns'";
			break;

			case"all":
			$extend = "";
			break;

		}

		$sql = "SELECT * FROM 
				view_area_delivery
				WHERE equipment_brand = '".$arg['body_no']."'
				AND trans_date = '".$arg['date']."'
				AND (production = 'PRODUCTION' OR production = 'DIRECT')
				".$extend."
				ORDER BY arrival_time ASC;
				";		

		$result = $this->db->query($sql);
		$this->db->close();

		return $result->result_array();
			
	}











}
