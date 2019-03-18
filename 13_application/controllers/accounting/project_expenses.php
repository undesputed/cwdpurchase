<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Project_expenses extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_project_expense');
		$this->load->model('md_project');

	}

	public function index(){

		$this->lib_auth->title = "Project Expenses";
		$this->lib_auth->build = "accounting/project_expenses/project_expenses";
		
		$data['project'] = $this->md_project->get_profit_center();
		$this->lib_auth->render($data);
		
	}


	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$result = $this->md_project_expense->get_expense2($arg);
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-condensed">' );
		$this->table->set_template($tmpl);

			$show = array(
					array('data'=>'Type','style'=>'width:60px'),
					'Project',
					'Others',
					'PAYROLL',
					'Total',
			 		);
				$total_debit  = 0;
				$total_credit = 0;

				$po_list = array();
				$total_cost = 0;

				$grand_total['others'] = 0;
				$grand_total['boq'] = 0;
				$grand_total['payroll'] = 0;
				$grand_total['total'] = 0;

				foreach($result as $key => $value){			
					$total = 0;
					$row_content = array();
					
					$row_content[] = array('data'=>(isset($value['project_type'])? $this->extra->project_type_label2($value['project_type']) : '' ));
					$row_content[] = array('data'=>(isset($value['project_name'])? $value['project_name'] : '' ));
					$row_content[] = array('data'=>(isset($value['OTHERS'])? $this->extra->number_format($value['OTHERS']) : ''));					
					/*$row_content[] = array('data'=>(isset($value['BOQ'])? $this->extra->number_format($value['BOQ']) : ''));*/
					$row_content[] = array('data'=>(isset($value['PAYROLL'])? $this->extra->number_format($value['PAYROLL']) : ''));
					/*$total = $value['OTHERS'] +  $value['BOQ'] + $value['PAYROLL'];*/
					$total = $value['OTHERS'] + $value['PAYROLL'];

					$grand_total['others'] = $grand_total['others'] + $value['OTHERS'];
					/*$grand_total['boq'] = $grand_total['boq'] + $value['BOQ'];*/
					$grand_total['payroll'] = $grand_total['payroll'] + $value['PAYROLL'];
					$grand_total['total'] = $grand_total['total'] + $total;

					$row_content[] = array('data'=>(isset($total)? $this->extra->number_format($total) : ''));					
					$this->table->add_row($row_content);					
				}

					$row_content = array();				

				$row_content[] = array('data'=>'','style'=>'font-weight:bold');
				$row_content[] = array('data'=>'Total','style'=>'font-weight:bold');
				$row_content[] = array('data'=>$this->extra->number_format($grand_total['others']),'style'=>'font-weight:bold;');
				/*$row_content[] = array('data'=>$this->extra->number_format($grand_total['boq']),'style'=>'font-weight:bold;');*/
				$row_content[] = array('data'=>$this->extra->number_format($grand_total['payroll']),'style'=>'font-weight:bold;');
				$row_content[] = array('data'=>$this->extra->number_format($grand_total['total']),'style'=>'font-weight:bold;');				

				$this->table->add_row($row_content);

		$this->table->set_heading($show);		
		echo $this->table->generate();



	}


	public function _get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();

		$result = $this->md_project_expense->get_expense($arg);	
			
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-condensed">' );
		$this->table->set_template($tmpl);

			$show = array(
					'Project',
					'Others',
					'BOQ',
					'PAYROLL',
					'Total',
			 		);

				$total_debit  = 0;
				$total_credit = 0;

				$po_list = array();
				$total_cost = 0;

				$grand_total['others'] = 0;
				$grand_total['boq'] = 0;
				$grand_total['payroll'] = 0;
				$grand_total['total'] = 0;

				foreach($result as $key => $value){			
					$total = 0;
					$row_content = array();
					
					$row_content[] = array('data'=>(isset($value['project_name'])? $value['project_name'] : '' ));
					$row_content[] = array('data'=>(isset($value['OTHERS'])? $this->extra->number_format($value['OTHERS']) : ''));					
					$row_content[] = array('data'=>(isset($value['BOQ'])? $this->extra->number_format($value['BOQ']) : ''));
					$row_content[] = array('data'=>(isset($value['PAYROLL'])? $this->extra->number_format($value['PAYROLL']) : ''));
					$total = $value['OTHERS'] +  $value['BOQ'] + $value['PAYROLL'];

					$grand_total['others'] = $grand_total['others'] + $value['OTHERS'];
					$grand_total['boq'] = $grand_total['boq'] + $value['BOQ'];
					$grand_total['payroll'] = $grand_total['payroll'] + $value['PAYROLL'];
					$grand_total['total'] = $grand_total['total'] + $total;

					$row_content[] = array('data'=>(isset($total)? $this->extra->number_format($total) : ''));					
					$this->table->add_row($row_content);					
				}

					$row_content = array();				
				$row_content[] = array('data'=>'Total','style'=>'font-weight:bold');
				$row_content[] = array('data'=>$this->extra->number_format($grand_total['others']),'style'=>'font-weight:bold;');
				$row_content[] = array('data'=>$this->extra->number_format($grand_total['boq']),'style'=>'font-weight:bold;');
				$row_content[] = array('data'=>$this->extra->number_format($grand_total['payroll']),'style'=>'font-weight:bold;');
				$row_content[] = array('data'=>$this->extra->number_format($grand_total['total']),'style'=>'font-weight:bold;');				

				$this->table->add_row($row_content);

		$this->table->set_heading($show);		
		echo $this->table->generate();


	}
}
