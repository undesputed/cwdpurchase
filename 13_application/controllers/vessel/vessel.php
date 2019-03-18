<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Vessel extends CI_Controller {

	public function __construct(){
		parent :: __construct();		

		$this->load->model(array('vessel/md_vessel'));
	}

	public function index(){

		$this->lib_auth->title = "Vessel History";		
		$this->lib_auth->build = "vessel/index";
		
		$data['result']  = $this->md_vessel->get_area_delivery();
		$data['area_group'] = $this->md_vessel->get_area_delivery_group(); 

		$month = array();
		foreach($data['area_group'] as $row)
		{	
			$m = date('F',strtotime($row['end_date']));
			$month[$m][] = $row;
		}

		$data['sidebar'] = $month;
		$this->lib_auth->render($data);
		
	}




}