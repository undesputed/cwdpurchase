<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Change_in_equity extends CI_Controller {

	public function __construct(){
		parent :: __construct();	
		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_change_in_equity');

	}

	public function index(){

		$this->lib_auth->title = "Change in Equity";
		$this->lib_auth->build = "accounting/change_in_equity/index";
		$this->lib_auth->render();

	}

	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$result = $this->md_change_in_equity->get_equity($arg);
		$data['result'] = $result['data'];
		$data['date']   = $result['date'];
		$this->load->view('accounting/change_in_equity/tbl_changeequity',$data);

	}

	public function _get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);

		$data = array();
					
			$show = array(
						'DESCRIPTION',
						'PREVIOUS',
						'CURRENT',
				 		);
			$this->table->add_row('EQUITY','','');

			$arg  = $this->input->post();
			$data = $this->md_change_in_equity->get_equity($arg);


				$total_debit = 0;
				$total_credit = 0;
				foreach($data as $key => $value){
					$row_content = array();
																	
					if(strtoupper($value['dr_cr'])=="DEBIT"){


					}else{

						$row_content[] = array('data'=>$value['account_description'],'style'=>'padding-left:3em;');						
						$row_content[] = array('data'=>$this->number_format($value['Previous']));
						$row_content[] = array('data'=>$this->number_format($value['Current']));

						$this->table->add_row($row_content);

						$total_debit  = $total_debit + $value['Previous'];
						$total_credit = $total_credit + $value['Current'];

					}   
					
				}

			$this->table->add_row(
				 array('data'=>'TOTAL EQUITY','style'=>'padding-left:1em;','class'=>'sub-total')
				,array('data'=>$this->number_format($total_debit),'class'=>'sub-total')
				,array('data'=>$this->number_format($total_credit),'class'=>'sub-total'));
			$this->table->add_row('','','');

			

		
		$this->table->set_heading($show);		
		echo $this->table->generate();

	}

	private function number_format($value){
		if(is_numeric($value)){
			return number_format($value,2,'.',',');
		}else{
			return '';
		}
	}




}