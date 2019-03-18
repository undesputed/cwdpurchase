<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Truck_monitoring extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('manage_report/md_report_details');

	}

	public function index(){

		$this->lib_auth->title = "Truck Monitoring";		
		$this->lib_auth->build = "truck_monitoring/index";


		//$this->md_report_details

		$this->lib_auth->render();

	}










}