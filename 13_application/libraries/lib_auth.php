<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lib_auth{

	var $default = "default-2";
	var $title;
	var $build;
	var $redirect = true;

	function __construct(){		
		$this->load->model('md_auth');		
		$this->load->helper('cookie');
	}
	
	public function __get($var)
	{
		return get_instance()->$var;
	}
	
	public function logged_in(){
		return (bool) $this->session->userdata('user');
	}
	
	public function themeplate(){
		$user_type = $this->session->userdata('type_user');
		switch($user_type){
			case "admin":
				redirect(base_url(),'refresh');
			break;
			case"dispatcher":
				redirect(base_url().index_page()."/dispatch",'refresh');
			break;
		}
	}

	public function is_admin(){
		$user  = $this->md_auth->user()->row();
		if($user->position=='1'){
			return true;
		}
			return false;
	}
		
	public function logout(){
		$this->session->unset_userdata('user');
		$this->session->sess_destroy();
		if (get_cookie('identity'))
		{
			delete_cookie('identity');
		}
		return true;
	}


	public function restriction($type = ''){
		$type_user = $this->session->userdata('type_user');
		if($type_user == 'ADMIN'){
			return true;
		}else{
			$bool = false;

			if(is_array($type)){
				if(in_array($type_user,$type)){
					$bool = true;	
				}
			}else{
				if($type_user == $type)
				{
					$bool = true;
				}
			}

			
			return $bool;
		}
	}

	
	public function get_user_id(){
		$user_id = $this->session->userdata('user');	
		if (!empty($user_id))
		{
			return $user_id;
		}
		return null;
	}
	
	public function render($arg = null){

		/*$this->output->enable_profiler(TRUE);*/
		
		if(!$this->logged_in() && $this->redirect === true){
		 		redirect('auth/login','refresh');
		}
		
		$this->template->set($arg);		
		$this->template->title($this->title,$this->config->item('site_title'))
		->set_layout($this->default)
		->build($this->build);
				
	}

	public function navbar(){	
			$menu = array();

			/***
			$menu['reports']  = array('title'=>'Reports','icon'=>'fa-tasks','sub_menu'=>
					array(
						array(
						'title'=>'View Reports',
						'url'=>'reports/'
						),
						array(
						'title'=>'Create Reports',
						'url'=>'reports/create'
						)
					)
				);				
			***/

			$user_type = $this->session->userdata('type_user');			
			switch(strtolower($user_type)){				
				case "admin":

					/*
					$menu['onepager']  = array('title'=>'Daily Project Report','icon'=>'fa-tasks','url'=>'daily-project-report');
					$menu['mine_ops']  = array('title'=>'Production Report','icon'=>'fa-bar-chart-o','url'=>'production_report');
					$menu['equipment_history']  = array('title'=>'Equipment History','icon'=>'fa-rotate-left','url'=>'equipment_history');
					$menu['maintenance']  = array('title'=>'Maintenance','icon'=>'fa-wrench','url'=>'maintenance');
					$menu['hr']  = array('title'=>'Human Resource','icon'=>'fa-users','sub_menu'=>
						array(
							array(
								'title'=>'DTR',
								'url'=>'hr/dtr'
								),
							array(
								'title'=>'Payroll',
								'url'=>'hr/payroll'
								),
							array(
								'title'=>'Driver Monitoring',
								'url'=>'hr/driver_monitoring'
								)
							)
						);

					$menu['jo']    = array('title'=>'Equipment Monitoring','icon'=>'fa-truck','url'=>'equipment_utilization');
					$menu['fuel']  = array('title'=>'Tank & Fuel','icon'=>'fa-tint','sub_menu'=>
						array(
							array(
								'title'=>'Tank Fuel Monitoring',
								'url'=>'tank-fuel-monitoring/tank_fuel_monitoring'
								),
							array(
								'title'=>'Fuel Equipment',
								'url'=>'tank-fuel-monitoring/fuel_equipment'
								),
							array(
								'title'=>'Fuel Withdrawal',
								'url'=>'tank-fuel-monitoring/fuel_withdrawal'
								),
							array(
								'title'=>'Monthly Report',
								'url'=>'tank-fuel-monitoring/monthly_report'
								),
							
							/*	
								array(
								'title'=>'Fuel Delivery',
								'url'=>'tank-fuel-monitoring/fuel_delivery'
								)								
							)
						);
				

					$menu['rf']  = array('title'=>'RF','icon'=>'fa-rss','sub_menu'=>
						array(
							array(
								'title'=>'Equipment Reading',
								'url'=>'rf/equipment_reading'
								),
							array(
								'title'=>'Reading Board',
								'url'=>'rf/reading_board'
								),
							array(
								'title'=>'Delivery Ticket - Rf Readings',
								'url'=>'rf/dt_rf'
								),
							)
						);

					$menu['procurement'] = array('title'=>'Warehouse','icon'=>'fa-truck','sub_menu'=>
						array(
							array(
								'title'=>'Requisition List',
								'url'=>'procurement/material_requisition'
								),
							array(
								 'divider'=>'true'
								),
							array(
								'title'=>'MIS Report',
								'url'=>'procurement/mis_report'
								),
							array(
								'title'=>'Ending Inventory',
								'url'=>'procurement/material_inventory'
								),
							array(
								'title'=>'Fixed Asset Availability',
								'url'=>'procurement/fixed_asset_availability'
								)
							)
						);
					
					$menu['dispatch'] = array('title'=>'Dispatching','icon'=>'fa-bullhorn','sub_menu'=>
						array(
							array(
								'title'=>'Project Resource Monitoring Report',
								'url'=>'dispatch/project_resource_monitoring_report'
								),
							array(
								'title'=>'Drivers Dispatch List',
								'url'=>'dispatch/drivers',
								),								
							)
						);
					
				$menu['equipment_history']  = array('title'=>'Equipment History','icon'=>'fa-wrench','url'=>'equipment_history');
				*/	

					$menu['Setup'] = array('title'=>'Manage Setup','icon'=>'fa-wrench','sub_menu'=>
						array(
							array(
								'title'=>'Company Setup',
								'url'=>'setup/company_setup',
								),
							array(
								'title'=>'Project Setup',
								'url'=>'setup/profit_center',
								),
							array(
								'title'=>'Supplier Setup',
								'url'=>'setup/supplier_setup',
								),	
							array(
								 'divider'=>'true'
								),
							array(
								'title'=>'Person Setup',
								'url'=>'setup/person_setup',
								),
							array(
								'title'=>'Employee Setup',
								'url'=>'setup/employee_setup',
								),
							array(
								'title'=>'User Setup',
								'url'=>'setup/user_setup',
								),
							array(
								 'divider'=>'true'
								),
							array(
								'title'=>'Item Setup',
								'url'=>'setup/item_setup',
								),
							array(
								'title'=>'Beginning Inventory',
								'url'=>'setup/beginning',
								),
												
							)
					);

					/*$menu['procurement2'] = array('title'=>'Warehouse Module','icon'=>'fa-truck','sub_menu'=>
							array(
								array(
								'title'=>'Stock Availability',
								'url'=>'procurement/stock_availability'
								),
								array(
								'title'=>'Equipment Availability',
								'url'=>'procurement/equipment_availability'
								),
								array(
								'title'=>'Purchase History',
								'url'=>'procurement/purchase_history'
								),
								array(
								'title'=>'Purchase Delivery',
								'url'=>'procurement/purchase_delivery'
								),
								array(
								 'divider'=>'true'
								),
								array(
								'title'=>'Purchase Request [PR]',
								'url'=>'procurement/purchase_request'
								),
								array(
								'title'=>'Canvass Sheet [CS]',
								'url'=>'procurement/canvass_sheet'
								),
								array(
								'title'=>'Purchase Order [PO]',
								'url'=>'procurement/purchase_order'
								),
								array(
								'title'=>'Receiving Report [RR]',
								'url'=>'procurement/received_purchase',
								),							
								
								array(
								'title'=>'Stock Receiving',
								'url'=>'procurement/stock_receiving',
								),
								array(
								 'divider'=>'true'
								),
								array(
								'title'=>'Received Transfer [RT]',
								'url'=>'procurement/received_transfer'
								),
						
							)
						);*/


					
					
						
					/*
					
					$menu['monitoring'] = array('title'=>'Monitoring','icon'=>'fa-tint','sub_menu'=>
						array(
							array(
								'title'=>'Tire Monitoring',
								'url'=>'monitoring/tire',
								),
							array(
								'title'=>'Maintenance Monitoring',
								'url'=>'monitoring/maintenance',
								),
							array(
								'title'=>'Operation Monitoring',
								'url'=>'monitoring/operation',
								),
							array(
								'title'=>'Productivity',
								'url'=>'monitoring/productivity',
								),
							)
					);
					*/
				case "canvass user":
				case "pr user":
				case "po user":
				case "head":
				case "user" :
					$menu['procurement3'] = array('title'=>'Transaction List','icon'=>'fa-tags','url'=>'transaction_list/purchase_request/incoming');
					/*$menu['boq'] = array('title'=>'Bill of Quantities','icon'=>'fa-gears','url'=>'boq');*/

				break;

				case "dispatch":
				break;

				

			}
			


			/*
				$menu['mine_operation']  = array('title'=>'Mine Operation Reports','icon'=>'fa-tasks','url'=>'mine_operation');
				$menu['shipment_operation']  = array('title'=>'Shipment Operation Reports','icon'=>'fa-tasks','url'=>'shipment_operation');
			*/

			if($this->extra->is_admin()){
				//$menu['reports']  = array('title'=>'Reports ','icon'=>'fa-tasks','url'=>'reports');		
				//$menu['manage']  = array('title'=>'Manage Report ','icon'=>'fa-flash','url'=>'manage_report');				
			}
				
		$dom = "";
		$active = "";

		if(!isset($menu) && count($menu)<=0)
		{

			return false;
		}

		foreach($menu as $key => $value)
		{
						
			if(isset($value['sub_menu']) && count($value['sub_menu'])> 0)
			{

				$parent_active = $this->get_parent_active($value['sub_menu']);
				$dom .='<li class="dropdown '.$parent_active.'"><a href="#" class="dropdown-toggle menu" data-toggle="dropdown"><span class="i-size"><i class="fa '.$value['icon'].'"></i></span>'.$value['title'].'<b class="caret"></b></a>';

				$dom .='<ul class="dropdown-menu">';
				foreach ($value['sub_menu'] as $key1 => $value1) 				
				{
					if(isset($value1['divider']))
					{
						$dom.='<li class="divider"></li>';
					}else
					{
						$active = $this->get_active_pages($value1['url']);
						$dom.='<li class="'.$active.'"><a href="'.base_url().index_page()."/".$value1['url'].'">'.$value1['title'].'</a></li>';
					}					
				}
				$dom .='</ul>';
			}else{

				$active = $this->get_active_page($value['url']);
				$dom.='<li class="'.$active.'"><a class="menu" href="'.base_url().index_page()."/".$value['url'].'"><span class="i-size"><i class="fa '.$value['icon'].' "></i></span>'.$value['title'].'</a>';
			}
			$dom .='</li>';
		}

		echo $dom;

	}


	private function get_active_page( $url ){

		$url = explode('/',$url);
		if($url[0] == $this->uri->segment(1)){
			return "active";
		}else{
			return "";
		}

	}

	private function get_active_pages( $url ){

		$url = explode('/',$url);
		if($url[1] == $this->uri->segment(2) && $url[0] == $this->uri->segment(1)){
			return "active";
		}else{
			return "";
		}

	}

	private function get_parent_active( $url ){

		foreach($url as $key => $value){

			if(isset($value['url']) && $this->get_active_pages($value['url'])=="active"){
				return "active";
				break;
			}
		}
	}


}