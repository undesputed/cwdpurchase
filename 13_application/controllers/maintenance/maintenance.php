<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('maintenance/md_maintenance');		
	}

	public function index(){

		$this->lib_auth->title = "Maintenance";		
		$this->lib_auth->build = "maintenance/maintenance/index_2";

		$data['equipment_list'] = $this->md_maintenance->get_equipment();
		$data['total_pending'] = $this->md_maintenance->count_jo_pending();

		$arg['date'] = date('Y-m-d');
		$data['maintenance'] = $this->md_maintenance->get_maintenance($arg);

		$pending_jo_monthly = array();
		$result = $this->md_maintenance->get_pending_jo_monthly();	

		foreach($result as $row)
		{
				$pending_jo_monthly[] = array(
				'value'=>$row['cnt'],
				'label'=>$row['date'],
				);
		}
				
		$data['pending_monthly'] = json_encode($pending_jo_monthly);		
		$this->lib_auth->render($data);
		
	}

	public function old(){

		$this->lib_auth->title = "Maintenance";		
		$this->lib_auth->build = "maintenance/maintenance/index";

		$data['equipment_list'] = $this->md_maintenance->get_equipment();
		$data['total_pending'] = $this->md_maintenance->count_jo_pending();

		$this->lib_auth->render($data);
		
	}




	public function get_complete_jo(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$arg = $this->input->get();

		$data['all']   = $this->md_maintenance->get_totalcomplete_jo();
		$data['today'] = $this->md_maintenance->get_today_complete($arg);		
		echo json_encode($data);
	}


	public function get_body_no(){		

		$result = $this->md_maintenance->get_body_no($this->input->post('id'));
		$div = "";
		$div .="<option value=''>ALL</option>";
		foreach($result as $row){
			$div .="<option value='".$row['equipment_id'].">".$row['equipment_brand']."</option>";
		}	

		echo  $div;
	}


	public function get_jo(){

		$result = $this->md_maintenance->get_jo();
		$option = array(
			'result'=>$result
			);
		echo $this->extra->generate_table($option);

	}

	public function get_jo_summary_v2(){

		$arg = $this->input->get();
		$date_from = date('F d',strtotime($arg['from']));
		$date_to = date('F d',strtotime($arg['to']));
		$data_content = array();

		$arg['from'] = '2014-08-01';
		$arg['to'] = '2014-08-31';
		$arg['equipment'] = 'HOWO DT';

		$result = $this->md_maintenance->get_jo_summary($arg);

		$data = array();
		foreach($result as $row)
		{
			$data[] = array('x'=>$row['NAME'],'y'=>$row['COUNT(`INHOUSE NUMBER`)']);
		}

		$output = array(
			'data'=>$data,
			'ykeys'=>array('y'),
			'labels'=>array('Total'),
			);

		echo json_encode($output);


	}

	public function get_jo_summary(){

		$arg = $this->input->post();
		$date_from = date('F d',strtotime($arg['from']));
		$date_to = date('F d',strtotime($arg['to']));
		$data_content = array();
		$result = $this->md_maintenance->get_jo_summary($arg);			
		$ticks = array();
		$cnt = 1;
		foreach($result as $row){
			$ticks[$cnt] = $row['NAME'];

			$data_content[] = array(
						'label'=>$row['NAME'],
						'data'=>array(array($cnt,$row['COUNT(`INHOUSE NUMBER`)'])),
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
			'title'=>'COMPONENT - '.$arg['equipment'].'('.$date_from.' - '.$date_to.')',
			);
		echo json_encode($output);

	}

	public function get_breakdown_history(){		

		if(!$this->input->is_ajax_request()){
			exit(0);
		}		

		$arg = $this->input->post();
		$result = $this->md_maintenance->get_breakdown_history($arg);

			$ticks = array();
			$cnt = 1;
			foreach($result as $row){

				$ticks[$cnt] = date('F',strtotime($row['transaction_date']));

				$data_content[] = array(
							'label'=>date('F',strtotime($row['transaction_date'])),
							'data'=>array(array($cnt,$row['cnt'])),
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

			echo json_encode($output);


	}





	public function get_jo_breakdown(){		

		$arg = $this->input->post();

		$result = $this->md_maintenance->get_jo_breakdown($arg);
		$data_content = array();
		$cnt = 1;
		$ticks = array();
		foreach($result as $row){			
			$date = date('F',strtotime($row['TRANS DATE']));
			$ticks[$cnt] = $date;
			$data_content[] = array(
						'label'=>$date,
						'data'=>array(array($cnt,$row['COUNT(`JO NUMBER`)'])),
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

		if($arg['body_no']!=''){
			$title = $arg['equipment_name']." ( ".$arg['body_no']." )";
		}else{
			$title = $arg['equipment_name'];
		}

		$output = array(
			'data'=>$data_content,
			'ticks'=>$ticks,
			'title'=>$title,
			);
		echo json_encode($output);

	}


	public function view_pending(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$result = $this->md_maintenance->get_jo_pending();

		$option = array(
			'result'=> $result,
			'hide'=>array(
				'BD START DATE',
				'BD START TIME',
				'UNIT NUMBER',
				'REPAIR DESCRIPTION',
				'PARTS STATUS',
				),
			'bool'=>true

			);
		$data['table'] = $this->extra->generate_table($option);	
		$data['sidebar'] = $this->md_maintenance->get_pending_jo_monthly();		
		$this->load->view('maintenance/maintenance/view_pending',$data);		
	}

	public function get_mech_availability(){

		$from = $this->input->post('from');
		$to = $this->input->post('to');


		
		/*$result = $this->md_maintenance->get_mech_availability($this->input->post('to'));


		$temp  = array();
		$total = array();
		$cnt   = 0;

		$total['NUMBER OF UNIT'] = 0;
		$total['UTILIZED UNITS'] = 0;
		$total['NUMBER OF UNIT'] = 0;
		$total['UTILIZED UNITS'] = 0;
		$total['STANDBY OPERATIONAL'] = 0;
		$total['WITH JO'] = 0;
		$total['MA'] = 0;
		$total['PA'] = 0;		
		$total['EQUIPMENT'] = 'TOTAL';

		foreach($result as $row){
			
			//DT(x)("PA") = Format((DT(x)("STANDBY OPERATIONAL") + DT(x)("UTILIZED UNITS")) / DT(x)("NUMBER OF UNIT"), "0.00")						
			$temp[] = $row;			
						
			$total['NUMBER OF UNIT'] += $row['NUMBER OF UNIT'];
			$total['UTILIZED UNITS'] += $row['UTILIZED UNITS'];
			$total['STANDBY OPERATIONAL'] += $row['STANDBY OPERATIONAL'];
			$total['WITH JO'] += $row['WITH JO'];
			$total['MA'] += $row['MA'];
			$total['PA'] += $row['PA'];
			$cnt ++;
			
		}*/

		$dates = $this->extra->createDateRangeArray($from,$to);

		foreach($dates as $row){
			$query[$row] = $this->md_maintenance->get_mech_availability($row);
		}
		$data = array();
		$cnt = 1;
		$dates = array();
		$ticks = array();
		foreach($query as $key=>$row){		
			$date = date('F d',strtotime($key));	
			$ticks[$cnt] = $date;
			
			foreach($row as $r){
				$percentage = ($r['MA'] * 100);
				$data[trim($r['EQUIPMENT'] .'- MA' )][] = array($cnt,$percentage);	
			}
			
			//$data['adt_ma'][] = array($key,$row[0]['MA']);
			$cnt++;
		}

		$a = array();
		foreach($data as $key=>$row){
			$a['output'][] = array(
				'label'=>$key,
				'data'=>$row,
				);
		}

		
		$a['ticks'] = $ticks;
	
		/*
		foreach($result as $row){
			$data_obj['total_wmt'][] = array(,$row['TO-DATE WMT']);
		}

		$total_wmt[] = array(
					'label'=>'Total WMT',
					'data'=>$data_obj['total_wmt'],
		);
		*/
		
		echo json_encode($a);
		
	}


	public function _get_mech_availability(){
				
		$result = $this->md_maintenance->get_mech_availability($this->input->post('to'));

		$temp  = array();
		$total = array();
		$cnt   = 0;

		$total['NUMBER OF UNIT'] = 0;
		$total['UTILIZED UNITS'] = 0;
		$total['NUMBER OF UNIT'] = 0;
		$total['UTILIZED UNITS'] = 0;
		$total['STANDBY OPERATIONAL'] = 0;
		$total['WITH JO'] = 0;
		$total['MA'] = 0;
		$total['PA'] = 0;		
		$total['EQUIPMENT'] = 'TOTAL';

		foreach($result as $row){
			
			//DT(x)("PA") = Format((DT(x)("STANDBY OPERATIONAL") + DT(x)("UTILIZED UNITS")) / DT(x)("NUMBER OF UNIT"), "0.00")						
			$temp[] = $row;			
						
			$total['NUMBER OF UNIT'] += $row['NUMBER OF UNIT'];
			$total['UTILIZED UNITS'] += $row['UTILIZED UNITS'];
			$total['STANDBY OPERATIONAL'] += $row['STANDBY OPERATIONAL'];
			$total['WITH JO'] += $row['WITH JO'];
			$total['MA'] += $row['MA'];
			$total['PA'] += $row['PA'];
			$cnt ++;
			
		}

		$temp[] = $total;

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);

		$show = array(
					'EQUIPMENT',
					'NUMBER OF UNIT',
					'UTILIZED UNITS',
					'NUMBER OF UNIT',
					'UTILIZED UNITS',
					'STANDBY OPERATIONAL',
					'WITH JO',
					'MA',
					'PA',
			 		);

			foreach($temp as $key => $value){
				
				$row_content = array();

				$row_content[] = array('data'=>$value['EQUIPMENT']);
				$row_content[] = array('data'=>$value['NUMBER OF UNIT']);
				$row_content[] = array('data'=>$value['UTILIZED UNITS']);
				$row_content[] = array('data'=>$value['NUMBER OF UNIT']);
				$row_content[] = array('data'=>$value['UTILIZED UNITS']);
				$row_content[] = array('data'=>$value['STANDBY OPERATIONAL']);
				$row_content[] = array('data'=>$value['WITH JO']);
				$row_content[] = array('data'=>$value['MA']);
				$row_content[] = array('data'=>$value['PA']);

				$this->table->add_row($row_content);

			}
			
			$this->table->set_heading($show);
			echo $this->table->generate();

	}


	public function get_ma(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$arg = $this->input->post();
		$result = $this->md_maintenance->get_ma_ben($arg);
		
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

			echo json_encode($output);





	}





}