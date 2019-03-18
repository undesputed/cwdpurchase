<?php defined('BASEPATH') OR exit('No direct script access allowed');

class inventory_entry extends CI_Controller {

	public function __construct(){
		parent::__construct();	
		$this->load->model(array('setup/md_inventory_entry'));
	}

	public function btnSave_Click(){
		parse_str($this->input->post('form'),$form);
		$result = $this->md_inventory_entry->btnSave_Click($form,get_object_vars(json_decode($this->input->post('obj'))));
		echo ($result) ? "true" : "false";
	}

	public function c1display(){
		$table=array("result"=>$this->md_inventory_entry->c1display(),"hide"=>array("Item No."));
		$response=array('table'=>$this->extra->generate_table($table));
		echo json_encode($response);
		return true;
	}

	public function txtitemcode(){
		echo $this->md_inventory_entry->txtitemcode($this->input->post('data'));
	}

	public function cmbModel(){
		$select_options=array('data'=>$this->md_inventory_entry->cmbModel($this->input->post('Item_No'))->result_array());
		echo $this->extra->generate_options($select_options);
	}

	public function dtpdateofpurchase(){
		$date = $this->input->post('dtpdateofpurchase');
		$date=explode("-", $date);
		$year = (strlen($date[0]) >2 ) ? substr($date[0], strlen($date[0]) - 2, strlen($date[0])) :$date[0];
		$month = $date[1];
		$result=$this->md_inventory_entry->dtpdateofpurchase($this->session->userdata('Proj_Main'),$month,$year);

		$midnum=0;
		if($result['prNoStr']==""){
			$txtpropertyno = $date[1]."-"."001"."-".$date[0];
		}else{
			$midnum = +substr($result['prNoStr'],3,3) + 1 ;
			$txtpropertyno = $date[1]."-".str_pad($midnum,3,"0",STR_PAD_LEFT)."-".$date[0];
		}

		$midnum=0;
		if($result['prNoStr1']==""){
			$txtreceipt = "RR-".$date[1]."-"."001"."-".$date[0];
		}else{
			$midnum = +substr($result['prNoStr1'],6,3) + 1 ;
			$txtreceipt = "RR-".$date[1]."-".str_pad($midnum,3,"0",STR_PAD_LEFT)."-".$date[0];
		}

		echo json_encode(array("txtreceipt"=>$txtreceipt,"txtpropertyno"=>$txtpropertyno));
	}






	public function index(){
		$this->lib_auth->title 		= "Inventory Entry";
		$this->lib_auth->build 		= "setup/inventory_entry/inventory_entry";
		$data="";

		$cmbItemType_options=array("data"=>$this->md_inventory_entry->cmbItemType()->result_array());
		$data['cmbItemType']=$this->extra->generate_options($cmbItemType_options);

		$cmbSupplier_options=array("data"=>$this->md_inventory_entry->cmbSupplier()->result_array());
		$data['cmbSupplier']=$this->extra->generate_options($cmbSupplier_options);
		
		$cmbaccount_options=array("data"=>$this->md_inventory_entry->cmbaccount()->result_array());
		$data['cmbaccount']=$this->extra->generate_options($cmbaccount_options);
		
		$cmbdriver_options=array("data"=>$this->md_inventory_entry->cmbdriver()->result_array(),"text"=>"Full Name","val"=>"Person Code");
		$data['cmbdriver']=$this->extra->generate_options($cmbdriver_options);

		$cmbequipmenttype_options=array("data"=>$this->md_inventory_entry->cmbequipmenttype()->result_array());
		$data['cmbequipmenttype']=$this->extra->generate_options($cmbequipmenttype_options);

		$cmbfueltype_options=array("data"=>$this->md_inventory_entry->cmbfueltype()->result_array(),"text"=>"description","val"=>"group_detail_id");
		$data['cmbfueltype']=$this->extra->generate_options($cmbfueltype_options);

		// $data['cmbfueltype']=json_encode($this->md_inventory_entry->cmbfueltype()->result_array());
		$data['cmbdivision']=json_encode($this->md_inventory_entry->cmbdivision()->result_array());

		$DOMS=$this->createDOMS();
		$data['DOMS']=json_encode($DOMS);



		$this->lib_auth->render($data);
	}
	
	public function createDOMS($DOMS=array()){

		array_push($DOMS,array("dtpdateofpurchase"=> '<div class="form-group">
								<span class="form-label">Date of Purchase:</span>
									<input type="text" name="dtpdateofpurchase" class="form-control">
							</div>'));


		array_push($DOMS,array("txtreferenceno"=>'<div class="form-group">
							<span class="form-label">Reference(PO#):</span>
								<input type="text" name="txtreferenceno" class="form-control">
						</div>'));

		array_push($DOMS,array("txtqty"=>'<div class="form-group">
							<span class="form-label">Item Qty:</span>
								<input type="text" name="txtqty" value="1" class="numbers_only form-control">
						</div>'));

		array_push($DOMS,array("cmbSupplier"=>'<div class="form-group">
								<span class="form-label">Supplier Name:</span>
									<select name="cmbSupplier" class="form-control"></select>
							</div>'));

		array_push($DOMS,array("txtequipmentname"=>'<div class="form-group">
								<span class="form-label">Item Name:</span>
									<input type="text" name="txtequipmentname" class="form-control">
							</div>'));

		array_push($DOMS,array("txtpropertyno"=>'<div class="form-group">
								<span class="form-label">Tag/Plate No:</span>
									<input type="text" name="txtpropertyno" class="form-control">
							</div>'));

		array_push($DOMS,array("txtunitcost"=>'<div class="form-group">
								<span class="form-label">Unit Cost:</span>
									<input type="text" name="txtunitcost" class="numbers_only form-control">
							</div>'));

		array_push($DOMS,array("txtremarks"=>'<div class="form-group">
								<span class="form-label">Remarks:</span>
									<input type="text" name="txtremarks" class="form-control">
							</div>'));

		array_push($DOMS,array("cmbdriver"=>'<div class="form-group">
								<span class="form-label">Operator Name:</span>
									<select name="cmbdriver" class="form-control"></select>
							</div>'));

		array_push($DOMS,array("cmbModel"=>'<div class="form-group">
								<span class="form-label">Model:</span>
									<select name="cmbModel" class="form-control"></select>
							</div>'));

		array_push($DOMS,array("cmbequipmenttype"=>'<div class="form-group">
								<span class="form-label">Type:</span>
									<select name="cmbequipmenttype" class="form-control"></select>
							</div>'));

		array_push($DOMS,array("txtbrand"=>'<div class="form-group">
								<span class="form-label">Body No:</span>
									<input type="text" name="txtbrand" class="form-control">
							</div>'));

		array_push($DOMS,array("cmbfueltype"=>'<div class="form-group">
								<span class="form-label">Fuel:</span>
									<select name="cmbfueltype" class="form-control"></select>
							</div>'));

		array_push($DOMS,array("txtmadein"=>'<div class="form-group">
								<span class="form-label">Made:</span>
									<input type="text" name="txtmadein" class="form-control">
							</div>'));

		array_push($DOMS,array("txtengineno"=>'<div class="form-group">
								<span class="form-label">Engine No:</span>
									<input type="text" name="txtengineno" class="form-control">
							</div>'));

		array_push($DOMS,array("txtchassisno"=>'<div class="form-group">
								<span class="form-label">Chasis No:</span>
									<input type="text" name="txtchassisno" class="form-control">
							</div>'));

		array_push($DOMS,array("txtlife"=>'<div class="form-group">
								<span class="form-label">Estimated Life:</span>
									<input type="text" name="txtlife" class="numbers_only form-control" placeholder="0"> 
							</div>'));

		array_push($DOMS,array("txtproghrs"=>'<div class="form-group">
								<span class="form-label">Program Hrs:</span>
									<input type="text" name="txtproghrs" class="numbers_only form-control" placeholder="0">
							</div>'));

		array_push($DOMS,array("txttruckfactor"=>'<div class="form-group">
								<span class="form-label">Factor Depth:</span>
									<input type="text" name="txttruckfactor" class="numbers_only form-control" placeholder="0">
							</div>'));

		array_push($DOMS,array("txtyear"=>'<div class="form-group">
								<span class="form-label">Year:</span>
									<input type="text" name="txtyear" class="form-control">
							</div>'));

		array_push($DOMS,array("txtfulltank"=>'<div class="form-group">
								<span class="form-label">Capacity:</span>
									<input type="text" name="txtfulltank" class="numbers_only form-control" placeholder="0">
							</div>'));

		array_push($DOMS,array("txtSMR"=>'<div class="form-group">
								<span class="form-label">SMR:</span>
									<input type="text" name="txtSMR" class="numbers_only form-control" placeholder="0">
							</div>'));

		return $DOMS;

	}


	public function render($arg = null){
		if(!$this->my_auth->logged_in()){
			redirect('auth/login','refresh');
		}
		
		$this->template->set($arg);
		$this->template->title($this->title,'Frasec Inventory')
			->set_layout('default')
			->build($this->build);		
	}
	
	
}
?>