<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_billing_statement extends CI_MODEL {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('md_project');
	}


	public function save(){

		$this->db->trans_begin();

		$terms = $this->input->post('cmbterms');

		$insert_invoice = array(
				'invoice_no'=>$this->input->post('txtInvoice'),
				'invoice_date'=>$this->input->post('dtpInvoiceDate'),
				'due_date'=>$this->input->post('dtpDueDate'),
				'customer_id'=>$this->input->post('cbxCustomer'),
				'customer_name'=>$this->input->post('cbxCustomer_name'),
				'customer_type'=>$this->input->post('rbtnBusiness'),
				'address'=>$this->input->post('txtAddress'),
				'remarks'=>$this->input->post('txtRemarks'),
				'type'=>$this->input->post('txtType'),
				'descrition'=>$this->input->post('txtDesc'),
				'amount'=>$this->input->post('txtAmount'),
				'customer_po_no'=>$this->input->post('txtPO'),
				'payment_terms'=>$this->input->post('cmbterms'),
				'days'=>$this->input->post('nupterms'),
				'balance'=>$this->input->post('txtBal'),
				'preparedBy'=>$this->input->post('cmbPreparedBy'),
				'title_id'=>$this->session->userdata('Proj_Main'),
				'location'=>$this->input->post('MAIN_FORM'),
			);
		$this->db->insert('tbl_invoice',$insert_invoice);

		$reference_no = $this->get_journalEntry($this->input->post('MAIN_FORM'),$this->input->post('dtpInvoiceDate'));

		$insert_journal_main = array(
				'reference_no'=>$reference_no,
				'trans_date'=>$this->input->post('dtpInvoiceDate'),
				'type'=>'INVOICE',
				'memo'=>$this->input->post('remarks'),
				'trans_type'=>'ENTER INVOICE',
				'status'=>'ACTIVE',
				'location'=>$this->input->post('MAIN_FORM'),
				'title_id'=>$this->session->userdata('Proj_Main'),
				'userid'=>$this->input->post('preparedBy'),
				'division'=>'0',
				'transaction_type'=>'0',
				'expense_type'=>'0',
				'cost_type'=>'0',
				'fund_location'=>$this->input->post('MAIN_FORM'),
				'po_id'=>'0',
				'account_type'=>'0',
				'pay_center'=>'0',
				'pay_item'=>$this->input->post('project_category'),
				'name_id'=>'0',
				'name_type'=>'BILLING STATEMENT',
				'_Amount'=>$this->input->post('txtAmount'),
				'_bAlance'=>$this->input->post('txtAmount'),
		);

		$this->db->insert('journal_main',$insert_journal_main);


		$sql    = "select journal_id from journal_main where reference_no = '".$reference_no."'";
		$result = $this->db->query($sql);
		$result = $result->row_array();
		$journal_id = $result['journal_id'];


		if($terms == 'COD'){

			$insert_detail = array(
				'journal_id'=>$journal_id,
				'account_id'=>'7',
				'amount'=>$this->input->post('txtAmount'),
				'discount_tax'=>'',
				'dr_cr'=>'DEBIT',
				'supplier_id'=>$this->input->post('cbxCustomer'),
				'posted'=>'FALSE',
				'type'=>$this->input->post('rbtnBusiness'),
				'bank_account_no'=>'',
				'bank_account_name'=>'',
				'cv_no'=>'',
				'check_id'=>'',
				'check_date'=>$this->input->post('dtpInvoiceDate'),
				'balance'=>'0',
				'from_location'=>'0',
				'subsidiary'=>$this->input->post('cbxCustomer_name'),
				'subsidiary_type'=>'BANK',
				'bank_account_id'=>'0',
				'bank'=>'TRUE',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'cv_id'=>'0',
				);
			$this->db->insert('journal_detail',$insert_detail);

			$insert_detail = array(
				'journal_id'=>$journal_id,
				'account_id'=>'178',
				'amount'=>$this->input->post('txtAmount'),
				'discount_tax'=>'',
				'dr_cr'=>'CREDIT',
				'supplier_id'=>$this->input->post('cbxCustomer'),
				'posted'=>'FALSE',
				'type'=>$this->input->post('rbtnBusiness'),
				'bank_account_no'=>'',
				'bank_account_name'=>'',
				'cv_no'=>'',
				'check_id'=>'',
				'check_date'=>$this->input->post('dtpInvoiceDate'),
				'balance'=>'0',
				'from_location'=>'0',
				'subsidiary'=>$this->input->post('cbxCustomer_name'),
				'subsidiary_type'=>'SUPPLIER',
				'bank_account_id'=>'0',
				'bank'=>'FALSE',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'cv_id'=>'0',
				);
			$this->db->insert('journal_detail',$insert_detail);			
		}else{

			$insert_detail = array(
				'journal_id'=>$journal_id,
				'account_id'=>'10',
				'amount'=>$this->input->post('txtAmount'),
				'discount_tax'=>'',
				'dr_cr'=>'DEBIT',
				'supplier_id'=>$this->input->post('cbxCustomer'),
				'posted'=>'FALSE',
				'type'=>$this->input->post('rbtnBusiness'),
				'bank_account_no'=>'',
				'bank_account_name'=>'',
				'cv_no'=>'',
				'check_id'=>'',
				'check_date'=>$this->input->post('dtpInvoiceDate'),
				'balance'=>'0',
				'from_location'=>'0',
				'subsidiary'=>$this->input->post('cbxCustomer_name'),
				'subsidiary_type'=>'SUPPLIER',
				'bank_account_id'=>'0',
				'bank'=>'FALSE',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'cv_id'=>'0',
				);
			$this->db->insert('journal_detail',$insert_detail);

			$insert_detail = array(
				'journal_id'=>$journal_id,
				'account_id'=>'178',
				'amount'=>$this->input->post('txtAmount'),
				'discount_tax'=>'',
				'dr_cr'=>'CREDIT',
				'supplier_id'=>$this->input->post('cbxCustomer'),
				'posted'=>'',
				'type'=>'',
				'bank_account_no'=>'',
				'bank_account_name'=>'',
				'cv_no'=>'',
				'check_id'=>'',
				'check_date'=>'',
				'balance'=>'',
				'from_location'=>'',
				'subsidiary'=>$this->input->post('cbxCustomer_name'),
				'subsidiary_type'=>'SUPPLIER',
				'bank_account_id'=>'0',
				'bank'=>'FALSE',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'cv_id'=>'0',
				);
			$this->db->insert('journal_detail',$insert_detail);
		}


		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();		
		    return true;
		}	

	}


	public function get_journalEntry($project_id = '',$_date = '' ){

			$date        = explode('-',$_date);
			$type_name   = 'ENTER INVOICE';
			$type        = 'EIV';
			$project_id  = $project_id;
			$month       = $date[1];
			$year        = $date[0];
			$data        = $this->md_project->get_journalEntry($month,$year,$type_name,$project_id);			
			if(empty($data[0]['max'])){
				return  $type.'-'.$month."-".$this->str_pad(1)."-".$year;
			}else{
				return $type.'-'.$month."-".$this->str_pad($data[0]['max'])."-".$year;
			}

	}


	public function get_invoice_max_id(){

		$date = (empty($_POST['date']))? date('Y-m-d') : $_POST['date'];
		$date  = explode('-',$date);
		$month = $date[1];
		$year  = $date[0];
		$data  = $this->md_project->get_max_invoice($month,$year);
		$type = "INV";
		if(empty($data[0]['max'])){
			return  $type."-".$month."-".$this->str_pad(1)."-".$year;
		}else{
			return $type."-".$month."-".$this->str_pad($data[0]['max'] + 1)."-".$year;
		}

	}


	private function str_pad($num){
		 return str_pad($num, 3, '0', STR_PAD_LEFT);
	}


	public function get_all_invoice($project_id){
		$sql = "
				SELECT
					iNvoice_id,
					invoice_no 'INVOICE NO.',
					invoice_date 'INVOICE DATE',
					due_date 'DUE DATE',
					customer_name 'CUSTOMER',
					`type` 'TYPE',
					customer_po_no 'P.O NO.',
					payment_terms 'TERMS',
					FORMAT(amount,2) 'AMOUNT',
					FORMAT(balance,2) 'BALANCE'
					
				FROM tbl_invoice
				
				WHERE title_id = {$this->session->userdata('Proj_Main')}
				AND location = {$project_id}
				AND `status` = 'ACTIVE';
		";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}


	public function get_invoice_date($project_id,$from,$to){

		$sql = "
			SELECT
			invoice_id,
			invoice_no 'INVOICE NO.',
			invoice_date 'INVOICE DATE',
			due_date 'DUE DATE',
			customer_name 'CUSTOMER',
			`type` 'TYPE',
			customer_po_no 'P.O NO.',
			payment_terms 'TERMS',
			FORMAT(amount,2) 'AMOUNT',
			FORMAT(balance,2) 'BALANCE'			
		FROM tbl_invoice		
		WHERE invoice_date BETWEEN '{$from}' AND '{$to}'
		AND title_id = {$this->session->userdata('Proj_Main')}
		AND location = {$project_id}
		AND `status` = 'ACTIVE'";

		$result = $this->db->query($sql);		
		$this->db->close();
		return $result->result_array();

	}



}


