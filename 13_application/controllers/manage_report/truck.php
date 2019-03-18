<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Truck extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('manage_report/md_report_details');		
	}

	public function index($dt){

		if(!isset($dt)){
			redirect(base_url());
		}

		//$result = $this->md_report_details->exist_truck($truck);
		echo $dt;
		/*$this->lib_auth->title = "test";		
		$this->lib_auth->build = "manage_report/truck";		
		$this->lib_auth->render();	*/	

	}
	

	public function details($dt){

		if(!isset($dt)){
			redirect(base_url());
		}

		//$result = $this->md_report_details->exist_truck($truck);
		
		$this->lib_auth->title = $dt;		
		$this->lib_auth->build = "manage_report/truck";

		$data['details'] = $this->md_report_details->max_min_mod($dt);
		$data['truck']   = $dt;
		$data['drivers'] = $this->drivers($dt);

		$this->lib_auth->render($data);

	}

	public function drivers($dt){		
		$result = $this->md_report_details->get_driver_perDT($dt);
		$tmpl = array ( 'table_open'  => '<table class="table tbl-driver table-hover table-striped">' );
		$this->table->set_template($tmpl);

		$show = array(	
					'shift',						
					'Total Trips',	
					'Name',											
			 		);

			foreach($result as $key => $value){
				$row_content = array();		
				$row_content[] = array('data'=>$value['shift']);
				$row_content[] = array('data'=>$value['trip']);		
				$row_content[] = array('data'=>$value['pp_fullname']);				
				
				$this->table->add_row($row_content);			
			}			
			$this->table->set_heading($show);

		return  $this->table->generate();

	}


	public function truck_history(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_report_details->truck_history($this->input->post('truck'));
		$data_cont = array();

		foreach($result as $row)
		{
			$data_cont[$row['shift']][] = array($this->extra->milisecond($row['trans_date']),$row['trips']);
		}

			/*$data_content[] = array(
						'label'=>'DS',
						'data'=>$data_cont['DS'],
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
						);*/

			$data_content[] = array(
						'label'=>'DS',
						'data'=>$data_cont['DS'],												
						);

			$data_content[] = array(
						'label'=>'NS',
						'data'=>$data_cont['NS'],												
						);

			$data = array(
				'data'=>$data_content
				);

		echo json_encode($data);
		

	}


}