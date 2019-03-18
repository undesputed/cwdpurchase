<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Journal_entry_cumulative extends CI_Controller {

	public function __construct(){
		parent :: __construct();	

		$this->load->model('accounting/md_journalEntry');	
	}


	public function index(){

		$this->lib_auth->title = "Journal Entry Cumulative";
		$this->lib_auth->build = "accounting/journal_entry_cumulative/index";
				
		$this->lib_auth->render();

	}	


	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-hover tbl-event">' );

		$this->table->set_template($tmpl);

		$result = $this->md_journalEntry->get_cumulative();


			$show = array(
					    array('data'=>'journal_id','style'=>'display:none'),
						'Reference No',
						'Trans Date',
						'Type',
						'Memo',
						'Status',
						array('data'=>'Action','style'=>'width:170px'),						
				 		);

				foreach($result->result_array() as $key => $value){
					$row_content = array();
					$row_content[] = array('data'=>$value['journal_id'],'class'=>'journal_id','style'=>'display:none;');
					$row_content[] = array('data'=>$value['Reference No'],'class'=>'reference_no','');
					$row_content[] = array('data'=>$value['Trans Date'],'class'=>'trans_date','');
					$row_content[] = array('data'=>$value['Type'],'class'=>'type','');
					$row_content[] = array('data'=>$value['Memo'],'class'=>'memo','');
					$row_content[] = array('data'=>$this->extra->label($value['Status']),'class'=>'status','');
					$row_content[] = array('data'=>'<span class="btn-link event cancel">Cancel</span> <span class="event">|</span> <span class="btn-link event modify">Modify</span> <span class="event">|</span> <span class="btn-link event details">Details</span>','');

					$this->table->add_row($row_content);

				}
							
				$this->table->set_heading($show);
				echo $this->table->generate();

	}


	public function change_status(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_journalEntry->update_status($this->input->post('journal_id'));

	}

	public function get_details(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table  table-striped table-hover">' );
		$this->table->set_template($tmpl);

		$result = $this->md_journalEntry->get_journal_detail($this->input->post('journal_id'));

		$show = array(
					'Account Code',
					'Account',					
					'Discount/Tax',
					'Debit',
					'Credit',
			 		);
			$total_debit = 0;
			$total_credit = 0;
			foreach($result->result_array() as $key => $value){
				$row_content = array();
				$row_content[] = array('data'=>$value['Account Code']);
				$row_content[] = array('data'=>$value['Account']);
				$row_content[] = array('data'=>$value['Discount/Tax']);
				if($value['CR/DR']=="DEBIT"){
					$row_content[] = array('data'=>$this->extra->number_format($value['Amount']));
					$row_content[] = array('data'=>'');
					$total_debit += $value['Amount'];
				}else{
					$row_content[] = array('data'=>'');
					$row_content[] = array('data'=>$this->extra->number_format($value['Amount']));
					$total_credit += $value['Amount'];
				}
				

				$this->table->add_row($row_content);			
			}

			$footer = array(
				array('data'=>'TOTAL','colspan'=>'3','class'=>'sub-total'),
				array('data'=>$this->extra->number_format($total_debit),'class'=>'sub-total'),
				array('data'=>$this->extra->number_format($total_credit),'class'=>'sub-total'),
				);
			$this->table->add_row($footer);			
	
			$this->table->set_heading($show);
			echo $this->table->generate();



	}

	public function edit(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['main']   = $this->md_journalEntry->get_journalMain($this->input->post('journal_id'));
		$data['detail'] = $this->md_journalEntry->get_journalDetail($this->input->post('journal_id'));

		$data['payCenter'] = $this->md_journalEntry->get_payCenter();
		$data['payItem']   = $this->md_journalEntry->get_payItem();
		$data['accountDescription'] = $this->md_journalEntry->get_accountDescription();

		$this->load->view('accounting/journal_entry_cumulative/edit',$data);
		
	}



}

