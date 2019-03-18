<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Material_setup extends CI_Controller {

	public function __construct(){
		
		parent::__construct();	
		$this->load->model(array('setup/md_material_setup'));
		$this->build = '';
		$this->title = '';
		
	}

	public function index(){
		$this->lib_auth->title 		= "Material Setup";		
		$this->lib_auth->build 		= "setup/material_item/material_setup";
		$option=array("data"		=> $this->md_material_setup->cmbGroupName()->result_array());
		$data['cmbGroupName']		= $this->extra->generate_options($option);
		$data['cmbClassification']	= json_encode($this->md_material_setup->cmbClassification()->result_array());
		$data['cmbIncomeAccountDescription']=json_encode($this->md_material_setup->cmbIncomeAccountDescription()->result_array());

		$this->lib_auth->render($data);
	}

	public function insert_group_detail_setup(){
		parse_str($this->input->post('form'),$data);
		echo $this->md_material_setup->insert_group_detail_setup($data);
	}

	public function display_group_detail_setup(){


		$tmpl = array ( 'table_open'  => '<table class="table myTable tbl-event table-striped">' );
		$this->table->set_template($tmpl);

		$result  =  $this->md_material_setup->display_group_detail_setup($this->input->post('txtSItemDescr'));

			$show = array(
					  array('data'=>'group_id1','style'=>'display:none'),
				 	  array('data'=>'group_id','style'=>'display:none'),
					  array('data'=>'base1','style'=>'display:none'),
					  array('data'=>'base2','style'=>'display:none'),
					  array('data'=>'base3','style'=>'display:none'),
					  array('data'=>'base4','style'=>'display:none'),
					  "Item No.",					  
					  "Item Description",
					  "Unit Cost",
					  "Unit Measure",
					  "Action",
					  );

				foreach($result->result_array() as $key => $value){

					$row_content = array();
					
					$row_content[] = array('data'=>$value['group_id1'],'style'=>'display:none','class'=>'group_id1');
					$row_content[] = array('data'=>$value['group_id'],'style'=>'display:none','class'=>'group_id');
					$row_content[] = array('data'=>$value['base1'],'style'=>'display:none','class'=>'base1');
					$row_content[] = array('data'=>$value['base2'],'style'=>'display:none','class'=>'base2');
					$row_content[] = array('data'=>$value['base3'],'style'=>'display:none','class'=>'base3');
					$row_content[] = array('data'=>$value['base4'],'style'=>'display:none','class'=>'base4');
					$row_content[] = array('data'=>$value['Item No.'],'style'=>'','class'=>'item_no');
					$row_content[] = array('data'=>$value['Item Description'],'style'=>'','class'=>'item_description');
					$row_content[] = array('data'=>$value['Unit Cost'],'style'=>'','class'=>'unit_cost');
					$row_content[] = array('data'=>$value['Unit Measure'],'style'=>'','class'=>'unit_measure');
					$row_content[] = array('data'=>'<span class="event btn-link update">Update</span>','style'=>'','class'=>'');
					$this->table->add_row($row_content);

				}
		
				$this->table->set_heading($show);
				echo $this->table->generate();

	}


}
?>