<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Trial_balance extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_trial_balance');
		$this->load->model('md_project');

	}

	public function index(){

		$this->lib_auth->title = "Trial Balance";
		$this->lib_auth->build = "accounting/trial_balance/index";
		$data['project'] = $this->md_project->get_profit_center();
		
		$this->lib_auth->render($data);
		
	}


	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-condensed">' );
		$this->table->set_template($tmpl);
		
		$arg = $this->input->post();
		$result = $this->md_trial_balance->get_cumulative($arg);		

			$show = array(
						'Account Title',
						'Account Code',
						'Debit',
						'Credit',
				 		);

			$total_debit  = null;
			$total_credit = null;

				foreach($result->result_array() as $key => $value){
					$row_content = array();
					$row_content[] = array('data'=>$value['DESCRIPTION']);													   
					$row_content[] = array('data'=>$value['ACCOUNT CODE']);					
					$row_content[] = array('data'=>$this->number_format($value['DEBIT']));
					$row_content[] = array('data'=>$this->number_format($value['CREDIT']));

					$this->table->add_row($row_content);
					$total_debit  = $total_debit + $value['DEBIT'];
					$total_credit = $total_credit + $value['CREDIT'];
				}
		
		$this->table->set_heading($show);

		$this->table->add_row(array('TOTAL','',$this->total($total_debit),$this->total($total_credit)));

		echo $this->table->generate();

	}


	private function number_format($value){
		if(is_numeric($value)){
			return number_format($value,2,'.',',');
		}else{
			return '';
		}
	}


	private function total($value){
		return "<strong>".$this->number_format($value)."</strong>";
	}

}