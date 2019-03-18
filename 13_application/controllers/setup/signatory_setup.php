<?php defined('BASEPATH') OR exit('No direct script access allowed');

class signatory_setup extends CI_Controller {

	public function __construct(){
		parent::__construct();	
		$this->load->model(array('setup/md_signatory_setup'));
	}

	public function cmbprojectlocation(){
		$hide = array("Form Name",
					"Signatory",
					"Employee Name",
					"Priority No");
		$heading = array();
		echo $this->extra->generate_table(array("result"	=> $this->md_signatory_setup->cmbprojectlocation($this->input->post('data')),
												"hide"		=> $hide,
												"bool"		=> true,
												"heading"	=> $heading)
		);
	}

	public function btnSave(){
		parse_str($this->input->post('data'),$data);
		echo ($this->md_signatory_setup->btnSave($data)) ? 'true' : 'false';
	}

	public function cumulative(){
		$this->build = "";
		$this->title = "";

		$this->render($data);
	}


	public function index(){
		$this->lib_auth->title 		= "Signatory Setup";
		$this->lib_auth->build 		= "setup/signatory_setup/signatory_setup";

		$cmbForm_opt 				= array("data" 	=> 	$this->md_signatory_setup->cmbForm()->result_array(),
											"text"	=>	"type",
											"val"	=>	"id");
		$cmbSignatory_opt 			= array("data" 	=> 	$this->md_signatory_setup->cmbSignatory()->result_array(),
											"text"	=>	"type",
											"val"	=>	"id");
		$cmbEmployeeName_opt 		= array("data" 	=> 	$this->md_signatory_setup->cmbEmployeeName()->result_array(),
											"text"	=>	"Employee Name",
											"val"	=>	"Employee Number");

		$data['cmbForm']			= $this->extra->generate_options($cmbForm_opt);
		$data['cmbSignatory']		= $this->extra->generate_options($cmbSignatory_opt);
		$data['cmbEmployeeName']	= $this->extra->generate_options($cmbEmployeeName_opt);		

		$this->lib_auth->render($data);
	}
	
	
}
?>