<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_production_report extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('daily_production_report/md_daily_production_report');
	}

	public function index(){

		$this->lib_auth->title = "Daily Production Report";
		$this->lib_auth->build = "daily_production_report/index";		
		$this->lib_auth->render();

	}

	
	public function get_production(){
		//$date = $this->input->post('date');
		$date = '2014-06-20';
		$data['data'] = $this->md_daily_production_report->display_mine_operation($date);


		$this->load->view('daily_production_report/tblproduction',$data);
		
		/*$this->output->enable_profiler();*/
	}


	public function get_transffering(){
		$date = $this->input->post('date');
		$data['data'] = $this->md_daily_production_report->display_transferring($date);
		$this->load->view('daily_production_report/tbltransferring',$data);
	}

	public function get_barging_operation(){
		$date = $this->input->post('date');
		$data['data'] = $this->md_daily_production_report->display_barging_operation($date);
		$this->load->view('daily_production_report/tblbarging',$data);
	}

	public function get_shipment(){
		$date = $this->input->post('date');
		$data['data'] = $this->md_daily_production_report->display_shipment($date);
		$this->load->view('daily_production_report/tblshipment',$data);	
	}

	public function get_equipment_inventory(){
		$date = $this->input->post('date');
		$data['data'] = $this->md_daily_production_report->display_equipment_inventory($date);		
		$this->load->view('daily_production_report/tblequipment_inventory',$data);
	}

	public function get_weather(){

		$arg = $this->input->post();
		
		$result = $this->md_daily_production_report->hourly_weather($arg);
		$data = array();
		
		foreach($result as $row)
		{

			switch(date('h A',strtotime($row['weather_time']))){
				case "01 PM":
					$data[1] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "02 PM":
					$data[2] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "03 PM":
					$data[3] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "04 PM":
					$data[4] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "05 PM":
					$data[5] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "06 PM":
					$data[6] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "07 PM":
					$data[7] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "08 PM":
					$data[8] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;	
				case "09 PM":
					$data[9] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "10 PM":
					$data[10] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "11 PM":
					$data[11] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "12 PM":
					$data[12] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "01 AM":
					$data[13] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "02 AM":
					$data[14] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "03 AM":
					$data[15] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "04 AM":
					$data[16] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;			
				case "05 AM":
					$data[17] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;			
				case "06 AM":
					$data[18] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;			
				case "07 AM":
					$data[19] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "08 AM":
					$data[20] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;
				case "09 AM":
					$data[21] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;			
				case "10 AM":
					$data[22] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;			
				case "11 AM":
					$data[23] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;			
				case "12 AM":
					$data[24] = array('weather'=>$row['weather'],'operation'=>$row['operation']);
				break;												

			}			
		} /**end loop**/


		for ($i=1; $i <=24 ; $i++){
			if(isset($data[$i])){
				$data[$i]['weather'] = str_replace(' ','_',$data[$i]['weather']);
			}else{
				$data[$i]['weather'] = '';
				$data[$i]['operation'] = '';
			}
			
		}


		
		
		echo json_encode($data);
		
	}


	

}