<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mis_report extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model(array('procurement/md_material_requisition'));

	}

	public function index(){

		$this->lib_auth->title = "MIS Report";		
		$this->lib_auth->build = "procurement/mis_report/index";

		$this->lib_auth->render();
		
	}

	public function get_mis_report(){

		$arg    = $this->input->post();
		$result = $this->md_material_requisition->get_mis_report($arg);

		$tmpl = array ( 'table_open'  => '<table class="table table-condensed table-hover myTable table-striped">' );
		$this->table->set_template($tmpl);

		$show = array(
					'ITEM NO.',
					'ITEM DESCRIPTION',
					'UoM',
					'WITHDRAWN QTY',
					'ACCOUNT TITLE',
					'UNIT',
			 		);

			foreach($result as $key => $value){
				$row_content = array();

				$row_content[] = array('data'=>$this->extra->label($value['ITEM NO.']));
				$row_content[] = array('data'=>$value['ITEM DESCRIPTION']);
				$row_content[] = array('data'=>$value['UoM']);
				$row_content[] = array('data'=>(isset($value['WITHDRAWN QTY'])? $value['WITHDRAWN QTY'] :'-'));			
				$row_content[] = array('data'=>$value['ACCOUNT TITLE']);
				$row_content[] = array('data'=>$value['UNIT']);
				

				$this->table->add_row($row_content);			
			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();



	}






	public function mis_report(){


		
	}







}