<?php 

class lib_dt{
		
	private $data;

	public function __construct(){
		$this->load->model('map/md_map');
	}

	public function __get($var)
	{
		return get_instance()->$var;
	}
	
	public function no_trips($dt,$date,$filter){

		$result =  $this->md_map->get_dt_2($dt,$date);
		$hauling_owner = '';

		if (strpos($dt,'ADT-') !== false){
			$type = "ADT";
		}else{
			$type = "DT";
		}

		$dt = explode('-',$dt);

		if($dt[1] < 300){
			$hauling_owner = 'inhouse';
		}else{
			$hauling_owner = 'subcon';
		}

		switch($filter){

			case "ds":
			$data = $result['shift']['DS'];
			break;

			case"ns":
			$data = $result['shift']['NS'];
			break;

			case"all":
			$data = $result['data'];
			break;

		}
	
		$cnt_barging = 0;
		$cnt_production = 0;
		$truck_factor = $this->config->item('truck_factor');

		foreach ($data as $key => $value)
		{

			switch($value['operation']){
				
				case "Direct":
					$cnt_barging++;
					$cnt_production++;
				break;
				case "Barging":
					$cnt_barging++;
				break;
				case "Production":
					$cnt_production++;
				break;
				

			}
		}
		
		$count =  count($data);
			
		if($count == 0)
		{
			$count = '-';
		}

		$output = array(
			'trips'=>$count,
			'factor_production'=>$truck_factor['PRODUCTION'][$type],
			'factor_barging'=>$truck_factor['BARGING'][$type],
			'trip_barging'=>$cnt_barging,
			'trip_production'=>$cnt_production,
			'total_barging_wmt'=>($cnt_barging * $truck_factor['BARGING'][$type]),
			'total_production_wmt'=>($cnt_production * $truck_factor['PRODUCTION'][$type]),
			'type'=>$type,
			'haul_owner'=>$hauling_owner,
			'dt'=>$dt,
			);

		return $output;

	}


	public function no_trips_data($result,$date,$filter){
				
		$hauling_owner = '';
		$dt = $result['dt'];

		if (strpos($dt,'ADT-') !== false){
			$type = "ADT";
		}else{
			$type = "DT";
		}

		$dt = explode('-',$dt);

		if($dt[1] < 300){
			$hauling_owner = 'inhouse';
		}else{
			$hauling_owner = 'subcon';
		}

		$count_shift = array();

		switch($filter){

			case "ds":
			$data = $result['shift']['DS'];
			break;

			case"ns":
			$data = $result['shift']['NS'];
			break;

			case"all":
			$data = $result['data'];
			break;

		}
	
		$cnt_barging = 0;
		$cnt_production = 0;
		$truck_factor = $this->config->item('truck_factor');
		
		$production['wmt']   = 0;
		$production['trips'] = 0;
		$production['type']  = '';
		$production['shift'] = '';
		$production['unit']  = '';
		$production['factor'] = 0;

		$barging['wmt']   = 0;
		$barging['trips'] = 0;
		$barging['type']  = '';
		$barging['shift'] = '';
		$barging['unit']  = '';
		$barging['factor'] = 0;
				
		$shift = array();


		foreach($result['shift']['DS'] as $key=>$value){
			switch($value['operation']){
				case "Direct":
					$barging['trips']++;
					$barging['type'] = $type;
					$barging['wmt'] += $truck_factor['BARGING'][$type];
					$barging['shift'] = 'ds';
					$barging['unit'] = 1;
					$barging['factor'] = $truck_factor['BARGING'][$type];
					$production['trips']++;
					$production['type'] = $type;
					$production['wmt'] += $truck_factor['PRODUCTION'][$type];
					$production['factor'] = $truck_factor['PRODUCTION'][$type];
					$production['shift'] = 'ds';
					$production['unit'] = 1;

					$cnt_barging++;
					$cnt_production++;
				break;
				case "Barging":
					$barging['trips']++;
					$barging['type'] = $type;
					$barging['wmt'] += $truck_factor['BARGING'][$type];
					$barging['factor'] = $truck_factor['BARGING'][$type];
					$barging['shift'] = 'ds';
					$barging['unit'] = 1;
					$cnt_barging++;
					
				break;
				case "Production":
					$production['trips']++;
					$production['type'] = $type;
					$production['wmt'] += $truck_factor['PRODUCTION'][$type];
					$production['factor'] = $truck_factor['PRODUCTION'][$type];
					$production['shift'] = 'ds';
					$production['unit'] = 1;

					$cnt_production++;
				break;
			}
		}

		$shift['ds']['production'] = $production;
		$shift['ds']['barging']    = $barging;

		$production['wmt']   = 0;
		$production['trips'] = 0;
		$production['type']  = '';
		$production['shift'] = '';
		$production['unit']  = '';
		$production['factor'] = 0;

		$barging['wmt']   = 0;
		$barging['trips'] = 0;
		$barging['type']  = '';
		$barging['shift'] = '';
		$barging['unit']  = '';
		$barging['factor'] = 0;

		foreach($result['shift']['NS'] as $key=>$value){
			switch($value['operation']){
				case "Direct":
					$barging['trips']++;
					$barging['type'] = $type;
					$barging['wmt'] += $truck_factor['BARGING'][$type];
					$barging['factor'] = $truck_factor['BARGING'][$type];
					$barging['shift'] = 'ns';
					$barging['unit'] = 1;
					$production['trips']++;
					$production['type'] = $type;
					$production['wmt'] += $truck_factor['PRODUCTION'][$type];
					$production['factor'] = $truck_factor['PRODUCTION'][$type];
					$production['shift'] = 'ns';
					$production['unit'] = 1;
					$cnt_barging++;
					$cnt_production++;
				break;
				case "Barging":
					$barging['trips']++;
					$barging['type'] = $type;
					$barging['wmt'] += $truck_factor['BARGING'][$type];
					$barging['factor'] = $truck_factor['BARGING'][$type];
					$barging['shift'] = 'ns';
					$barging['unit'] = 1;
					$cnt_barging++;					
				break;
				case "Production":
					$production['trips']++;
					$production['type'] = $type;
					$production['wmt'] += $truck_factor['PRODUCTION'][$type];
					$production['factor'] = $truck_factor['PRODUCTION'][$type];
					$production['shift'] = 'ns';
					$production['unit'] = 1;				
					$cnt_production++;
				break;
			}
		}

		$shift['ns']['production'] = $production;
		$shift['ns']['barging']    = $barging;

		$unit['ds'] = 0;
		$unit['ns'] = 0;
		$count_shift['ns'] = count($result['shift']['NS']);
		$count_shift['ds'] = count($result['shift']['DS']);

		if($count_shift['ns'] !=0){
			$unit['ns'] = 1;
		}
		if($count_shift['ds'] !=0){
			$unit['ds'] = 1;
		}

		$count =  count($data);
				
		if($count == 0)
		{
			$count = '-';			
		}else{
			
		}

		$output = array(
			'type'=>$type,
			'ds_production'=>$shift['ds']['production'],
			'ns_production'=>$shift['ns']['production'],
			'ds_barging'=>$shift['ds']['barging'],
			'ns_barging'=>$shift['ns']['barging'],
			'trip_barging'=>$cnt_barging,
			'trip_production'=>$cnt_production,
			'total_barging_wmt'=>($cnt_barging * $truck_factor['BARGING'][$type]),
			'total_production_wmt'=>($cnt_production * $truck_factor['PRODUCTION'][$type]),
			'haul_owner'=>$hauling_owner,
			'dt'=>$dt,
			);
						
		/*$output = array(
			'trips'=>$count,
			'factor_production'=>$truck_factor['PRODUCTION'][$type],
			'factor_barging'=>$truck_factor['BARGING'][$type],
			'trip_barging'=>$cnt_barging,
			'trip_production'=>$cnt_production,
			'total_barging_wmt'=>($cnt_barging * $truck_factor['BARGING'][$type]),
			'total_production_wmt'=>($cnt_production * $truck_factor['PRODUCTION'][$type]),
			'type'=>$type,
			'haul_owner'=>$hauling_owner,
			'dt'=>$dt,
			'ds'=>$count_shift['ds'],
			'ns'=>$count_shift['ns'],
			'unit_ds'=>$unit['ds'],
			'unit_ns'=>$unit['ns'],
			);
		*/

		return $output;

	}







	public function total_production($date){
		$result = $this->md_map->get_trucks($date,$date);

		echo "<pre>";
		print_r($result);
		echo "</pre>";

	}







	
}



 ?>