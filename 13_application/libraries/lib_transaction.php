<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class lib_transaction{

	function __construct()
	{
		
		$this->load->model('procurement/md_transaction');
		$this->load->model('procurement/md_transaction_history');
		$this->load->model('procurement/md_project');

		$this->load->model('procurement/md_canvass_sheet');
		$this->load->model('procurement/md_purchase_order');
		$this->load->model('procurement/md_stock_availability');

		$this->load->model('inventory/md_stock_withdrawal');
		$this->load->model('inventory/md_stock_inventory');
		$this->load->model('procurement/md_received_purchase');

		$this->load->model('procurement/md_item_transfer');
		$this->load->model('procurement/md_event_logs');
		
		$this->load->model('accounting/md_voucher');
		$this->load->model('procurement/md_item_request');
		
	}

	public function __get($var)
	{
		return get_instance()->$var;
	}

	public function project_site_list(){
		$result = $this->md_project->get_project_site();	
		$div = "";
		$id = $this->uri->segment(3);

		$div.='<li class="sub-menu-sidebar-item inventory-select">';
		$div.='<select class="form-control" id="sidebar-inventory-location">';
		foreach($result as $row){

			$home = ($row['project_id'] == $this->session->userdata('Proj_Code') || $row['project_id'] == $id)? 'selected="selected"' : '';
			$active = ($row['project_id'] == $id)? 'active' : '' ;
					/*class="'.$active.'"*/
					/*href="'.base_url().index_page().'/transaction_list/inventory/'.$row['project_id'].'"*/
					$div.='<option '.$home.' value="'.$row['project_id'].'"> ('.$row['project_name'].') '.$row['project_location'].' </option>';
					
		}
		$div.='</select>';
		$div.='<button id="sidebar-btnview" class="btn btn-sm btn-primary btn-block" style="margin-top:5px">View</button>';
		$div.='</li>';	
		return $div;

	}

	public function project_site_list2(){
		$result = $this->md_project->get_project_site();	
		$div = "";
		$id = $this->uri->segment(3);

		$div.='<li class="sub-menu-sidebar-item inventory-select">';
		$div.='<select class="form-control" id="sidebar-inventory-location">';
		foreach($result as $row){

			$home = ($row['project_id'] == '25' || $row['project_id'] == $id)? 'selected="selected"' : '';
			$active = ($row['project_id'] == $id)? 'active' : '' ;
					/*class="'.$active.'"*/
					/*href="'.base_url().index_page().'/transaction_list/inventory/'.$row['project_id'].'"*/
					$div.='<option '.$home.' value="'.$row['project_id'].'"> ('.$row['project_name'].') '.$row['project_location'].' </option>';
					
		}
		$div.='</select>';
		$div.='<button id="sidebar-btnview" class="btn btn-sm btn-primary btn-block" style="margin-top:5px">View</button>';
		$div.='</li>';	
		return $div;

	}


	public function if_has_canvass($pr_id){
		if($this->md_transaction->has_canvass_pr($pr_id)){
			return true;
		}else{
			return false;
		}
	}

	public function hasCanvass_pr($pr_id){
		if($this->md_transaction->has_canvass_pr($pr_id)){
			echo "<span class='label label-warning tool_tip' data-toggle='tooltip' data-placement='top' title='Already Canvass'>CV</span>";
		}
	}

	public function for_canvass_status($pr_id){
		if($this->md_transaction->has_canvass_pr($pr_id)){
			echo "<span class='label label-success'>Already Canvass</span>";
		}else{
			echo "<span class='label label-warning'>Pending</span>";
		}
	}

	public function for_canvass_status_redirect($pr_id){
		$result = $this->md_transaction->has_canvass_pr_2($pr_id);
	
		if(!empty($result)){
			echo "
			<span class='control-item-group'>
				<a href='".base_url().index_page()."/transaction_list/canvass_sheet/for_approval/".$result['c_number']."' class='action-status'>View CV</a>
			</span>
			";
		}

	}


	public function hasCanvass_po($pr_id){
		if($this->md_transaction->has_po_pr($pr_id)){
			echo "<span class='label label-danger tool_tip' data-toggle='tooltip' data-placement='top' title='Already PO'>PO</span>";
		}
	}

	public function hasCanvass_rr($pr_id){
		$result = $this->md_transaction->has_rr($pr_id);
		if(is_array($result)){
			echo "<span class='label ".$result['status']." tool_tip' data-toggle='tooltip' data-placement='top' title='RR - ".$result['title']."'>RR : ".$result['title']."</span>";
		}
	}


	public function priority($legend){		
		$data = explode('.',$legend);
		if($data[0] == 1){
			echo "<span class='label label-danger tool_tip' style='height:50px' data-toggle='tooltip' data-placement='top' title='Prioritize'><span class='fa fa-exclamation-circle' style='font-'></span></span>";
		}		
	}


	public function supplier($id,$type){
		$data = "";
		if($type == 'affiliate'){
			$data =$this->md_project->get_supplier_affiliate($id);
		}else{
			$data = $this->md_project->get_supplier_business($id);
		}
		return $data;
		
	}

	public function notification($type){

		switch($type){
			case "incoming":
				return $this->md_transaction->incoming();
			break;
			
			case "outgoing":				
				return $this->md_transaction->outgoing();
			break;
		}

	}


	public function for_canvass_notification(){
		$div = "";
		$result = $this->md_canvass_sheet->for_canvass_notification();	

		if(count($result) > 0){
			$div = "<span class='badge badge-pos'>".count($result)."</span>";
		}

		return $div;

	}

	public function canvass_notification(){

		$div = "";
		$result = $this->md_canvass_sheet->get_canvassMain_notification();	

		if($result > 0){
			$div = "<span class='badge badge-pos '>".$result."</span>";
		}		
		return $div;

	}


	public function for_po_notification(){
		$div = "";
		$result = $this->md_purchase_order->for_po_notification();
		if(count($result) > 0){
			$div = "<span class='badge badge-pos'>".count($result)."</span>";
		}		
		return $div;		
	}


	public function po_notification(){
		$div = "";
		$result = $this->md_purchase_order->get_all_pending_po();	

		if($result > 0){
			$div = "<span class='badge badge-pos'>".$result."</span>";
		}		
		return $div;

	}

	public function po_woprint_notification(){
		$div = "";
		$result = $this->md_purchase_order->get_all_woprint_po();	

		if($result > 0){
			$div = "<span class='badge badge-pos'>".$result."</span>";
		}		
		return $div;

	}

	public function rr_notification(){	
		$div = "";
		$result = $this->md_purchase_order->get_all_approved_po_notification();	

		if(count($result) > 0){
			$div = "<span class='badge badge-pos'>".count($result)."</span>";
		}		
		return $div;

	}

	public function undelivered_notification(){
		$div = "";
		$result = $this->md_purchase_order->undelivered_notification();		
		if(count($result) > 0){
			$div = "<span class='badge badge-pos'>".count($result)."</span>";
		}		
		return $div;		
	}


	public function transaction_info(){		
		$control = $this->uri->segment(2);

		switch(strtolower($control)){
/*			case "purchase_request":
					$type_id = $this->uri->segment(4);
					$action = $this->uri->segment(5);
					switch($action){
						case "":
							if(!empty($type_id)){
									$type = explode('-',$type_id);
									switch(strtoupper($type[0])){
										case"PR":
											$this->pr_info($type_id);
										break;									
									}
							}
						break;						
						case "edit":
							if(!empty($type_id)){
									$type = explode('-',$type_id);
									switch(strtoupper($type[0])){
										case"PR":
											$this->pr_info_edit($type_id);
										break;									
									}
							}
						break;
						default:
							if(!empty($type_id)){
									$type = explode('-',$type_id);
									switch(strtoupper($type[0])){
										case"PR":
											$this->pr_info($type_id);
										break;									
									}
							}
						break;
					}
					
			break;

			case "canvass_sheet":

				$type = $this->uri->segment(3);
				switch($type){
					case "for_canvass":
						$type_id = $this->uri->segment(4);
						if(!empty($type_id))
						{
							$this->pr_info_for_canvass($type_id);	
						}
						
					break;

					case "for_approval":
						$type_id = $this->uri->segment(4);
						if(!empty($type_id))
						{
							$this->canvass_info($type_id);
						}
					break;
				}			
			break;

			case "purchase_order":
				$type = $this->uri->segment(3);
				switch($type){
					case "for_po":
						$type_id = $this->uri->segment(4);
						if(!empty($type_id))
						{
							$this->canvass_info_po($type_id);
						}
					break;
					case "for_approval":
						$type_id = $this->uri->segment(4);
						$action = $this->uri->segment(5);

						switch($action){
							case "edit":
								$this->po_info_edit($type_id);
							break;
							default:
								if(!empty($type_id)){
									$this->po_info($type_id);
								}
							break;
						}

						

					break;
				}				
			break;

			case "receiving_report":
				$type_id = $this->uri->segment(3);	
				if(!empty($type_id)){
					$this->rr_info($type_id);
				}
			break;*/

			case "inventory":
				$type_id = $this->uri->segment(4);	
				if(!empty($type_id)){
					$this->inventory($type_id);					
				}
			break;
			case "withdrawal":
				$type_id = $this->uri->segment(4);	
				if(!empty($type_id)){
					$this->withdrawal($type_id);					
				}
			break;

			case "issuance":
				$type_id = $this->uri->segment(4);	
				if(!empty($type_id)){
					$this->issuance($type_id);					
				}
			break;

			case "item_withdrawal":				
				$type_id = $this->uri->segment(3);
				if(!empty($type_id)){
					$type_data = explode('-',$type_id);
					echo $type_id;
					if($type_id =='create')
					{
						if(!$this->lib_auth->restriction('USER'))
						{
						?>
						<script>
							alert('You are not allowed to withdraw');
							window.location = "<?php echo base_url().index_page();?>/transaction_list/item_withdrawal";
						</script>
						<?php
						}else{
							$this->item_withdrawal($type_id);	
						}
												
					}else if($type_data[0]=='WS')
					{
						$this->item_withdrawal_no($type_id);
					}
					
				}

			break;

			case "item_issuance":				
				$type_id = $this->uri->segment(3);

				if(!empty($type_id)){
					$type_data = explode('-',$type_id);
					if($type_id =='create')
					{
						if(!$this->lib_auth->restriction('USER'))
						{
						?>
						<script>
							alert('You are not allowed to Issue Items');
							window.location = "<?php echo base_url().index_page();?>/transaction_list/item_issuance";
						</script>
						<?php
						}else{
							$this->item_issuance_create($type_id);	
						}

						
					}else if($type_data[0]=='IS')
					{
						$this->item_issuance_no($type_id);
					}
					
				}

			break;

			case "item_transfer":			
				$type_id = $this->uri->segment(3);
				if(!empty($type_id)){
					if($type_id =='item_request')
					{
						$action = $this->uri->segment(4);
						$type_data = explode('-',$action);
						
						switch($action){

							case "create":
								$this->item_request_create();							
							break;

						}

						if($type_data[0] == 'IR'){	
							
							$this->item_request($action);							
						}

					}	
					if($type_id =='request')
					{
						$action = $this->uri->segment(4);
						$type_data = explode('-',$action);

						switch($action){

							case "create":
								$this->item_transfer_create();							
							break;

						}

						if($type_data[0] == 'TR'){	
							
							$this->item_transfer_no($action);							
						}

					}

					if($type_id == 'for_receiving')
					{
	
						$action = $this->uri->segment(4);	
						$type_data = explode('-', $action);

						if($type_data[0] == 'TR'){

							$this->for_receiving($action);
						}

					}
		     	}

			break;

		/*	case "voucher":
				$type_id = $this->uri->segment(3);
				if($type_id =='create'){
					$this->create_voucher();
				}

				if($type_id =='info'){
					$po_id = $this->uri->segment(4);
					if(is_numeric($po_id)){
						$this->voucher_info($po_id);	
					}
					

				}

			break;*/
			/*case "item_receiving":
				$type_id = $this->uri->segment(4);	
				echo "test";
				if(!empty($type_id)){
					$this->for_receiving($action);
				}
	
				$type_id = $this->uri->segment(3);	
				$action = $this->uri->segment(4);
				$type_data = explode('-', $action)
				if(!empty($type_id)){
					if($type_data[0] == 'TR'){
						$this->for_receiving($action);
					}
					// $this->for_receiving($type_id);					
				}
			break;*/
		}
	}


	public function pr_info_for_canvass($type_id){

		$data['main_data'] = $this->md_purchase_request->get_purchaseNo($type_id);		
		$data['details_data'] = $this->md_purchase_request->get_pr_details($data['main_data']['pr_id']);	
		$this->load->view('procurement/transaction_list/pr_info_for_canvass',$data);
	}


	public function pr_info($type_id,$data = ''){

		$data['main_data'] = $this->md_purchase_request->get_purchaseNo($type_id);		
		$data['details_data'] = $this->md_purchase_request->get_pr_details($data['main_data']['pr_id']);


		$this->load->view('procurement/transaction_list/pr_info',$data);

	}

	public function pr_info_edit($type_id){	

		$data['main_data'] = $this->md_purchase_request->get_purchaseNo($type_id);
		
		$data['details_data'] = $this->md_purchase_request->get_pr_details($data['main_data']['pr_id']);	
		$this->load->view('procurement/transaction_list/pr_info_edit',$data);		

	}

	public function jo_info($type_id,$data = ''){

		$data['main_data'] = $this->md_purchase_request->get_jo_main($type_id);		
		$data['details_data'] = $this->md_purchase_request->get_jo_details($data['main_data']['id']);


		$this->load->view('procurement/transaction_list/jo_info',$data);

	}


	public function status($type,$pr_id=""){

		$status = array(
				  array('status'=>'Pending','label'=>'label-warning','event'=>'pending-event'),
				  array('status'=>'Approved','label'=>'label-success','event'=>'approved-event'),
				  array('status'=>'Rejected','label'=>'label-danger','event'=>'reject-event'),
			);
		$div ="";

		$div ='<div class="control-group">';
			foreach($status as $row){
				if(strtoupper($row['status']) == strtoupper($type))
				{
					$div .='<span class="control-item-group">';
						$div .='<span class="label '.$row['label'].'">'.$row['status'].'</span>';
					$div .='</span>';
				}else{
					if($pr_id !="")
					{
						if(!$this->if_has_canvass($pr_id)){
							$div .='<span class="control-item-group">';
								$div.='<a href="javascript:void(0)" class="'.$row['event'].' action-status">'.$row['status'].'</a>';
							$div .='</span>';
						}

					}else{
						$div .='<span class="control-item-group">';
							$div.='<a href="javascript:void(0)" class="'.$row['event'].' action-status">'.$row['status'].'</a>';
						$div .='</span>';
					}
					

					
				}
			}
		$div .='</div>';		
		return $div;		
	}


	public function po_status($type,$po_no){

		$status = array(				
				  array('status'=>'Approved','slug'=>'APPROVED','label'=>'label-success','event'=>'approved-event'),				  
			);
		$div ="";

		$div ='<div class="control-group">';
			foreach($status as $row){
				if(strtoupper($row['slug']) == strtoupper($type) || $type == 'PARTIAL' || $type == 'COMPLETE')
				{	

					$div .='<span class="control-item-group">';
						$div .='<span class="label '.$row['label'].'">'.$row['status'].'</span>';
					$div .='</span>';

					$div .='<span class="control-item-group">';
						$div .='<a href="'.base_url().index_page().'/print/po/'.$po_no.'" target="_blank" class="action-status"><i class="fa fa-print"></i> Print</a>';
					$div .='</span>';

				}else{
					
					if($this->lib_auth->restriction('PO USER')){
						$div .='<span class="control-item-group">';
							$div.='<a href="javascript:void(0)" class="'.$row['event'].' action-status">'.$row['status'].'</a>';
						$div .='</span>';	
					}					
				}				
			}
		$div .='</div>';		
		return $div;		
	}


	public function status2($type,$pr_no = ""){
		if($pr_no == "")
		{
			$pr_no  =  $this->uri->segment(4);	
		}else{
			$pr_no  =  $pr_no;
		}
		
		$status = array(
				  array('status'=>'Cancel','label'=>'label-danger','event'=>'cancel-event'),
				  array('status'=>'Edit','label'=>'label-success','event'=>'edit-event'),				  
			);
		$div ="";
		$div ='<div class="control-group">';
				foreach($status as $row){
					if(strtoupper($row['status']) == strtoupper($type))
					{
						$div .='<span class="control-item-group">';
							$div .='<span class="label '.$row['label'].'">'.$row['status'].'</span>';
						$div .='</span>';						
						$div .='<span class="control-item-group">';
							$div.='<a href="javascript:void(0)" class="pending-event action-status">Activate</a>';
						$div .='</span>';

					}else{

						if($row['status'] == 'Cancel'){
							$div .='<span class="control-item-group">';
								$div.='<a href="javascript:void(0)" class="'.$row['event'].' action-status">'.$row['status'].'</a>';
							$div .='</span>';
						}else{
							$div .='<span class="control-item-group">';
								$div.='<a data-method="purchase_request" data-type="edit" data-value="'.$pr_no.'" href="'.base_url().index_page().'/transaction_list/purchase_request/outgoing/'.$pr_no.'/edit" class="'.$row['event'].' action-status history-link">'.$row['status'].'</a>';
							$div .='</span>';
						}
						


					}
				}
		$div .='</div>';
		return $div;

	}

	public function canvass_info_po($type_id){

		$data['main_data'] = $this->md_canvass_sheet->get_canvassMain_no_po($type_id);

		if(empty($data['main_data'])){
			$this->load->view('procurement/transaction_list/empty_info');
		}else{
		$data['pr_main']   = $this->md_purchase_request->get_purchaseNo($data['main_data']['purchaseNo']);
		$data['details_data'] = $this->md_purchase_request->get_pr_details($data['main_data']['pr_id']);
		$data['canvass_details'] = $this->md_canvass_sheet->get_canvassDetails_2_approved($data['main_data']['can_id']);

		$data['all_employee'] = $this->md_project->all_employee();
		$this->load->view('procurement/transaction_list/canvass_info_po',$data);
		}
		
	}

	public function canvass_info($type_id){

		$data['main_data'] = $this->md_canvass_sheet->get_canvassMain_no($type_id);

		if(empty($data['main_data'])){
			$this->load->view('procurement/transaction_list/empty_info');
		}else
		{
			$data['pr_main']   = $this->md_purchase_request->get_purchaseNo($data['main_data']['purchaseNo']);
			$data['details_data']    = $this->md_purchase_request->get_pr_details($data['main_data']['pr_id']);
			$data['canvass_details'] = $this->md_canvass_sheet->get_canvassDetails_2($data['main_data']['can_id']);
			
			/*$data['all_employee'] = $this->md_project->all_employee();*/
			$arg['form'] = 'cv';
			$arg['signatory'] = 'finalapproved_by';
			$data['all_employee'] = $this->md_project->get_websignatory($arg);

			$this->load->view('procurement/transaction_list/canvass_info',$data);
		}
		
		
		
	}


	public function status3($type){

		$status = array(
				  array('status'=>'Pending','label'=>'label-warning','event'=>'pending-event'),
				  array('status'=>'Approved','label'=>'label-success','event'=>'approved-event'),
				  array('status'=>'Rejected','label'=>'label-danger','event'=>'reject-event'),
			);
		$div ="";

		$div ='<div class="control-group">';
			foreach($status as $row){
				if(strtoupper($row['status']) == strtoupper($type))
				{
					$div .='<span class="control-item-group">';
						$div .='<span class="label '.$row['label'].'">'.$row['status'].'</span>';
					$div .='</span>';
				}else{
					$div .='<span class="control-item-group">';
						$div.='<a href="javascript:void(0)" class="'.$row['event'].' action-status">'.$row['status'].'</a>';
					$div .='</span>';
				}
			}
		$div .='</div>';
		return $div;
	}

	public function po_info($type){

		$data['main_data']    = $this->md_purchase_order->get_po_main($type);

		if(empty($data['main_data'])){
			$this->load->view('procurement/transaction_list/empty_info');
		}else
		{	
			
			$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
			$data['details_data'] = $this->md_purchase_order->get_po_details($data['main_data']['po_id']);
			$data['pr_main']      = $this->md_purchase_request->get_pr_main($data['main_data']['pr_id']);
			$data['po_status']    = $this->md_purchase_order->get_po_status($data['main_data']['po_id']);
			$this->load->view('procurement/transaction_list/po_info',$data);

		}
	}

	public function po_woprint_info($type){

		$data['main_data']    = $this->md_purchase_order->get_po_main($type);

		if(empty($data['main_data'])){
			$this->load->view('procurement/transaction_list/empty_info');
		}else
		{	
			
			$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
			$data['details_data'] = $this->md_purchase_order->get_po_details($data['main_data']['po_id']);
			$data['pr_main']      = $this->md_purchase_request->get_pr_main($data['main_data']['pr_id']);
			$data['po_status']    = $this->md_purchase_order->get_po_status($data['main_data']['po_id']);
			$this->load->view('procurement/transaction_list/po_wo_info',$data);

		}
	}

	public function po_info_edit($type){

		$data['supplier_list']['Affiliate'] = $this->md_project->get_supplier_affiliate();
		$data['supplier_list']['Business']  = $this->md_project->get_supplier_business();
		
		$data['main_data']    = $this->md_purchase_order->get_po_main($type);
		$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
		$data['details_data'] = $this->md_purchase_order->get_po_details($data['main_data']['po_id']);
		$data['pr_main']      = $this->md_purchase_request->get_pr_main($data['main_data']['pr_id']);
		$this->load->view('procurement/transaction_list/po_info_edit',$data);

	}


	public function rr_info($type,$segment = ""){

		$segment4 = ($segment !="")? $segment : $this->uri->segment(4);
		if($segment4 =='additional')
		{

			if(!$this->lib_auth->restriction('USER')){
				?>
					<script>
					alert('You are not allowed to Received Items');
					window.location = "<?php echo base_url().index_page() ?>/transaction_list/receiving_report/<?php echo $type; ?>";
					</script>
				<?php
			}

			$data['main_data']    = $this->md_purchase_order->get_po_main($type);
			$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
			$data['details_data'] = $this->md_purchase_order->get_po_details($data['main_data']['po_id']);
			$data['signatory']    = $this->md_project->all_employee();
			$data['mode'] = '';

			$data['rr_main'] = $this->md_received_purchase->get_po_received($type);
			$data['rr_details'] = $this->md_received_purchase->get_total_delivered($data['main_data']['po_id']);
			$this->load->view('procurement/transaction_list/rr_info_additional',$data);
			
			
		}else{

			$data['main_data']    = $this->md_purchase_order->get_po_main($type);
			$data['mode'] = '';
			$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
			$data['details_data'] = $this->md_purchase_order->get_po_details($data['main_data']['po_id']);
			$data['signatory']    = $this->md_project->all_employee();

			if($data['main_data']['status']=='APPROVED')
			{
				if(!$this->lib_auth->restriction('USER')){
				?>
					<script>
					alert('You are not allowed to Received Items');
					window.location = "<?php echo base_url().index_page() ?>/transaction_list/receiving_report/";
					</script>
				<?php
				}
				$this->load->view('procurement/transaction_list/rr_info',$data);
			}else
			{

				$rr_main = $this->md_received_purchase->get_po_received($type);
				$data['mode'] = '';
				foreach($rr_main as $row){
					$data['rr_main'][]    = $row;

					$data['rr_details'][] = $this->md_received_purchase->get_rr_details($row['receipt_id']);					
				}
				$this->load->view('procurement/transaction_list/fancy_rr_info_view',$data);

				/*
				$this->load->view('procurement/transaction_list/rr_info_header',$data);
				if($data['main_data']['status'] == "CLOSED" && $data['main_data']['cancel_remarks'] !=""){					
					$cnt_rr = (count($rr_main) - 1);
					$data['rr_details'] = $this->md_received_purchase->get_rr_details($rr_main[$cnt_rr]['receipt_id']);
					$this->load->view('procurement/transaction_list/rr_info_rem',$data);					
				}

				foreach($rr_main as $row){					
					$data['rr_main']    = $row;
					$data['rr_details'] = $this->md_received_purchase->get_rr_details($row['receipt_id']);
					$this->load->view('procurement/transaction_list/rr_info_view',$data);					
				}

				*/

			}
		}


	}

	public function drr_info($type,$segment = ""){
		$data['main_data'] = $data['rr_main']  = $this->md_purchase_request->get_receiving_main($type);
		$data['mode'] = 'DIRECT RECEIVING';
		$data['details_data'] = $data['rr_details'] = $this->md_purchase_request->get_rr_details($data['main_data']['receipt_id']);


		$this->load->view('procurement/transaction_list/fancy_rr_info_view',$data);
	}

	public function rx_info($type){
		$data['main_data'] = $this->md_purchase_request->get_return_main($type);
		$data['details_data'] = $this->md_purchase_request->get_return_details($data['main_data']['receipt_id']);

		
		$this->load->view('procurement/transaction_list/return_info',$data);
	}


	public function _rr_info($type,$segment = ""){

		$segment4 = ($segment !="")? $segment : $this->uri->segment(4);
		if($segment4 =='additional')
		{

			if(!$this->lib_auth->restriction('USER')){
				?>
					<script>
					alert('You are not allowed to Received Items');
					window.location = "<?php echo base_url().index_page() ?>/transaction_list/receiving_report/<?php echo $type; ?>";
					</script>
				<?php
			}

			$data['main_data']    = $this->md_purchase_order->get_po_main($type);
			$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
			$data['details_data'] = $this->md_purchase_order->get_po_details($data['main_data']['po_id']);
			$data['signatory']    = $this->md_project->all_employee();

			$data['rr_main'] = $this->md_received_purchase->get_po_received($type);
			$data['rr_details'] = $this->md_received_purchase->get_total_delivered($data['main_data']['po_id']);
			$this->load->view('procurement/transaction_list/rr_info_additional',$data);
			
			
		}else{

			$data['main_data']    = $this->md_purchase_order->get_po_main($type);


			$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
			$data['details_data'] = $this->md_purchase_order->get_po_details($data['main_data']['po_id']);
			$data['signatory']    = $this->md_project->all_employee();

			if($data['main_data']['status']=='APPROVED')
			{

				if(!$this->lib_auth->restriction('USER')){
				?>
					<script>
					alert('You are not allowed to Received Items');
					window.location = "<?php echo base_url().index_page() ?>/transaction_list/receiving_report/";
					</script>
				<?php
				}

				$this->load->view('procurement/transaction_list/rr_info',$data);
				
			}else
			{

				$rr_main = $this->md_received_purchase->get_po_received($type);					
				$this->load->view('procurement/transaction_list/rr_info_header',$data);	

				if($data['main_data']['status'] == "CLOSED" && $data['main_data']['cancel_remarks'] !=""){

					$cnt_rr = (count($rr_main) - 1);
					$data['rr_details'] = $this->md_received_purchase->get_rr_details($rr_main[$cnt_rr]['receipt_id']);
					$this->load->view('procurement/transaction_list/rr_info_rem',$data);

				}				
				foreach($rr_main as $row){

					$data['rr_main']    = $row;
					$data['rr_details'] = $this->md_received_purchase->get_rr_details($row['receipt_id']);
					$this->load->view('procurement/transaction_list/rr_info_view',$data);					
				}
			}
		}		
	}

	public function rr_edit($rr_id){

		$data['rr_main']    = $this->md_received_purchase->get_rr_received($rr_id);		
		$data['rr_details'] = $this->md_received_purchase->get_rr_details($rr_id);
		$data['main_data']    = $this->md_purchase_order->get_po_main($data['rr_main']['po_number']);
		$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
		$this->load->view('procurement/transaction_list/rr_edit',$data);

	}



	public function inventory($item_no){		
		$result = $this->md_stock_inventory->get_details($item_no,$this->session->userdata('Proj_Code'),$this->session->userdata('Proj_Main'));		
		$data['main_data'] = $result->result_array();
		if(empty($data['main_data'])){
			$this->load->view('procurement/transaction_list/empty_info');
		}else
		{
		$this->load->view('procurement/transaction_list/inventory_info',$data);
		}
	}

	public function withdrawal($item_no){		
		$result = $this->md_stock_inventory->get_withdrawal_history($item_no,$this->session->userdata('Proj_Code'),$this->session->userdata('Proj_Main'));		

		$data['main_data'] = $result->result_array();
		if(empty($data['main_data'])){
			$this->load->view('procurement/transaction_list/empty_info');
		}else
		{
			$this->load->view('procurement/transaction_list/inventory_info',$data);
		}
	}

	public function issuance($item_no){		
		$result = $this->md_stock_inventory->get_issuance_history($item_no,$this->session->userdata('Proj_Code'),$this->session->userdata('Proj_Main'));		
		$data['main_data'] = $result->result_array();
		if(empty($data['main_data'])){
			$this->load->view('procurement/transaction_list/empty_info');
		}else
		{
		$this->load->view('procurement/transaction_list/inventory_info',$data);
		}
	}

	
	public function item_withdrawal(){
		$arg['form'] = 'iw';
		$arg['signatory'] = 'recommended_by';
		$data['requested_by'] = $this->md_project->get_websignatory($arg);
		$arg1['form'] = 'iw';
		$arg1['signatory'] = 'approved_by';
		$data['approved_by']  = $this->md_project->get_websignatory($arg1);

		$data['tenants']    =  $this->md_project->get_tenant();

		$item_list = $this->md_stock_withdrawal->get_item_withdrawal();

		$item_content = array();
		$item_value   = array();
		$cnt = 0;
		
		foreach($item_list as $row){
			$item_value[$row['item_no']]   = $row['item_description'];			
			$item_content[$row['item_no']] = array(
						'item_name'=>$row['item_description'],
						'unit_measure'=>$row['item_measurement'],
						'stocks'=>$row['Quantity at Hand'],
						'item_cost'=>$row['item_cost'],
						'inventory_id'=>$row['inventory_id'],
						);
		}

		$data['item_value']   = json_encode($item_value);
		$data['item_content'] = json_encode($item_content);
		
		$this->load->view('procurement/transaction_list/item_withdrawal_create',$data);
		
	}

	public function item_withdrawal_no($type_id){

		$data['main_data'] = $this->md_stock_withdrawal->get_withdraw_main_no($type_id);
		if(empty($data['main_data'])){
			$this->load->view('procurement/transaction_list/empty_info');
		}else
		{
		$data['details'] = $this->md_stock_withdrawal->get_withdraw_details($data['main_data']['withdraw_id']);
		$this->load->view('procurement/transaction_list/item_withdrawal_info',$data);
		}

	}

	public function item_issuance_create(){
		$item_list = $this->md_stock_withdrawal->get_item_withdrawal();

		$item_content = array();
		$item_value   = array();
		$cnt = 0;
		
		foreach($item_list as $row){
			$item_value[$row['item_no']]   = $row['item_description'];			
			$item_content[$row['item_no']] = array(
						'item_name'=>$row['item_description'],
						'unit_measure'=>$row['item_measurement'],
						'stocks'=>$row['Quantity at Hand'],
						'item_cost'=>$row['item_cost'],
						'inventory_id'=>$row['inventory_id'],
						);
		}
		
		$data['item_value']   = json_encode($item_value);
		$data['item_content'] = json_encode($item_content);
		
		$this->load->view('procurement/transaction_list/item_issuance_create',$data);		
	}

	public function item_issuance_no($type_id){

		$data['main_data'] = $this->md_item_issuance->get_issuance_main($type_id);
		if(empty($data['main_data'])){
			$this->load->view('procurement/transaction_list/empty_info');
		}else
		{
			$data['details'] = $this->md_item_issuance->get_issuance_details($data['main_data']['issuance_id']);
			$this->load->view('procurement/transaction_list/item_issuance_info',$data);
		}

	}

	public function item_request_create(){

		$data['profit_center'] = $this->md_project->get_project_site_not_me();
		$this->load->view('procurement/transaction_list/item_request_create',$data);

	}

	public function item_request($type_id){
		$data['main_data'] = $this->md_item_request->get_main($type_id);
		$data['details'] = $this->md_item_request->get_details($data['main_data']['id']);		
		$this->load->view('procurement/transaction_list/item_request_info',$data);
	}

	
	public function item_transfer_create(){

		$item_list = $this->md_stock_withdrawal->get_item_withdrawal();

		$item_content = array();
		$item_value   = array();
		$cnt = 0;
		
		foreach($item_list as $row){
			$item_value[$row['item_no']]   = $row['item_description'];			
			$item_content[$row['item_no']] = array(
						'item_name'=>$row['item_description'],
						'unit_measure'=>$row['item_measurement'],
						'stocks'=>$row['Quantity at Hand'],
						'item_cost'=>$row['item_cost'],
						'inventory_id'=>$row['inventory_id'],
						);
		}
		
		$data['item_value']   = json_encode($item_value);
		$data['item_content'] = json_encode($item_content);

		$data['profit_center'] = $this->md_project->get_project_site_not_me();
		$this->load->view('procurement/transaction_list/item_transfer_create',$data);		
	}

	public function item_transfer_no($type_id){

		$data['main_data'] = $this->md_item_transfer->get_main($type_id);
		if(empty($data['main_data'])){
			$this->load->view('procurement/transaction_list/empty_info');
		}else
		{
			$data['details'] = $this->md_item_transfer->get_details($data['main_data']['id']);		
			$this->load->view('procurement/transaction_list/item_transfer_info',$data);
		}
	}

	public function for_receiving($type_id){
		$data['main_data'] = $this->md_item_transfer->get_main($type_id);
		if(empty($data['main_data'])){
			$this->load->view('procurement/transaction_list/empty_info');
		}else
		{
		$data['details'] = $this->md_item_transfer->get_details($data['main_data']['id']);		
		$this->load->view('procurement/transaction_list/item_receive_info', $data);
		}
	}

	
	/****************/

	public function comments($a){		
		$data['result'] = $this->md_event_logs->get($a);
		$data['type']  = $a['type'];
		$this->load->view('procurement/transaction_list/comments',$data);
	}




	public function create_voucher(){
		
		$data['result'] = $this->md_voucher->get_po_list();
		$this->load->view('accounting/disbursement_voucher/create',$data);

	}


	public function voucher_info($journal_id){

		$data['journal_id'] = $journal_id;

		$po = $this->md_voucher->get_po_journal_main($journal_id);

		$data['subsidiary'] = $this->md_voucher->get_subsidiary($journal_id);
		$data['po_main']    = $this->md_purchase_order->getBypo_id($po['po_id']);
		
		$data['rr_main']    = $this->md_received_purchase->get_po_received($data['po_main']['po_number']);
		$data['item_list']  = $this->md_voucher->get_voucher_item($po['po_id'],$journal_id);
		$data['voucher_info'] = $this->md_voucher->get_voucher_cumulative_po_id($po['po_id']);
				
		if($data['rr_main'][0]['paymentTerm'] == 'COD'){
			$data['type'] = 'ENTER PAYMENT';
		}else{
			$data['type'] = 'ENTER PAYABLE';
		}

		$cash_or_check = $this->md_voucher->cash_or_check($data['rr_main'][0]['receipt_id']);
		
		if($cash_or_check['check_no']==''){
			
			$data['payment_type'] = "CASH";
		}else{			
			$data['payment_type'] = "CHECK";
		}
				
		$this->load->view('accounting/disbursement_voucher/voucher_info',$data);	
	}



	public function cash_voucher($type,$segment = ""){

		$segment4 = ($segment !="")? $segment : $this->uri->segment(4);
		if($segment4 =='additional')
		{
			
			if(!$this->lib_auth->restriction('USER')){
				?>
					<script>
					alert('You are not allowed to Received Items');
					window.location = "<?php echo base_url().index_page() ?>/transaction_list/receiving_report/<?php echo $type; ?>";
					</script>
				<?php
			}

			$data['main_data']    = $this->md_purchase_order->get_po_main($type);		
			$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
			$data['details_data'] = $this->md_purchase_order->get_po_details($data['main_data']['po_id']);
			$data['signatory']    = $this->md_project->all_employee();

			$data['rr_main'] = $this->md_received_purchase->get_po_received($type);
			$data['rr_details'] = $this->md_received_purchase->get_total_delivered($data['main_data']['po_id']);
			$this->load->view('procurement/transaction_list/rr_info_additional',$data);
			
			
		}else{

			$data['main_data']    = $this->md_purchase_order->get_po_main($type);
						
			$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
			$data['details_data'] = $this->md_purchase_order->get_po_details($data['main_data']['po_id']);
			$data['signatory']    = $this->md_project->all_employee();

			if($data['main_data']['status']=='APPROVED')
			{
				if(!$this->lib_auth->restriction('USER')){
				?>
					<script>
					alert('You are not allowed to Received Items');
					window.location = "<?php echo base_url().index_page() ?>/transaction_list/receiving_report/";
					</script>
				<?php
				}

				$this->load->view('procurement/transaction_list/rr_info',$data);
				
			}else
			{

				$rr_main = $this->md_received_purchase->get_po_received($type);					
				$this->load->view('procurement/transaction_list/rr_info_header',$data);	

				if($data['main_data']['status'] == "CLOSED" && $data['main_data']['cancel_remarks'] !=""){

					$cnt_rr = (count($rr_main) - 1);
					$data['rr_details'] = $this->md_received_purchase->get_rr_details($rr_main[$cnt_rr]['receipt_id']);
					$this->load->view('procurement/transaction_list/rr_info_rem',$data);

				}

				foreach($rr_main as $row){

					$data['rr_main']    = $row;
					$data['rr_details'] = $this->md_received_purchase->get_rr_details($row['receipt_id']);
					$this->load->view('procurement/transaction_list/cash_voucher_view',$data);

				}
			}
		}
		
	}

	public function cash_voucher_info($cash_voucher_id){

		$data['main']    = $this->md_voucher->get_cash_voucher_info($cash_voucher_id);
		
		$data['po_main'] = $this->md_purchase_order->get_main($data['main']['po_id']);
		
		$data['details'] = $this->md_voucher->get_cash_voucher_details($cash_voucher_id);
		$data['journal'] = $this->md_voucher->voucher_journal($cash_voucher_id);
		$data['bank']    = $this->md_voucher->get_bank();
		
		$this->load->view('accounting/cash_voucher/cash_voucher_info',$data);		
	}

	public function journal_voucher_info($cash_voucher_id){

		$data['main']    = $this->md_voucher->get_cash_voucher_info($cash_voucher_id);
		
		$data['po_main'] = $this->md_purchase_order->get_main($data['main']['po_id']);
		
		$data['details'] = $this->md_voucher->get_cash_voucher_details($cash_voucher_id);
		$data['journal'] = $this->md_voucher->voucher_journal($cash_voucher_id);
		$data['bank']    = $this->md_voucher->get_bank();
		
		$this->load->view('accounting/cash_voucher/journal_voucher_info',$data);		
	}


	public function edit_voucher($journal_id){

		$data['journal_id'] = $journal_id;
		
		$po = $this->md_voucher->get_po_journal_main($journal_id);

		$data['subsidiary'] = $this->md_voucher->get_subsidiary($journal_id);
		$data['po_main']    = $this->md_purchase_order->getBypo_id($po['po_id']);
		
		$data['rr_main']    = $this->md_received_purchase->get_po_received($data['po_main']['po_number']);
		$data['item_list']  = $this->md_voucher->get_voucher_item($po['po_id'],$journal_id);
		$data['voucher_info'] = $this->md_voucher->get_voucher_cumulative_po_id($po['po_id']);
				
		if($data['rr_main'][0]['paymentTerm'] == 'COD'){
			$data['type'] = 'ENTER PAYMENT';
		}else{
			$data['type'] = 'ENTER PAYABLE';
		}

		$cash_or_check = $this->md_voucher->cash_or_check($data['rr_main'][0]['receipt_id']);
		
		if($cash_or_check['check_no']==''){		
			$data['payment_type'] = "CASH";
		}else{			
			$data['payment_type'] = "CHECK";
		}

		$data['bank'] = $this->md_voucher->get_bank();		
		$this->load->view('accounting/disbursement_voucher/edit_voucher_info',$data);		
	}

	public function status4($type,$pr_id=""){

		$status = array(
				  array('status'=>'Active','label'=>'label-warning','event'=>'pending-event'),
				  array('status'=>'Approved','label'=>'label-success','event'=>'approved-event'),
				  array('status'=>'Rejected','label'=>'label-danger','event'=>'reject-event'),
			);
		$div ="";

		$div ='<div class="control-group">';
			foreach($status as $row){
				if(strtoupper($row['status']) == strtoupper($type))
				{
					$div .='<span class="control-item-group">';
						$div .='<span class="label '.$row['label'].'">'.$row['status'].'</span>';
					$div .='</span>';
				}else{
					if($pr_id !="")
					{
						
						$div .='<span class="control-item-group">';
							$div.='<a href="javascript:void(0)" class="'.$row['event'].' action-status">'.$row['status'].'</a>';
						$div .='</span>';
						

					}else{
						$div .='<span class="control-item-group">';
							$div.='<a href="javascript:void(0)" class="'.$row['event'].' action-status">'.$row['status'].'</a>';
						$div .='</span>';
					}
					

					
				}
			}
		$div .='</div>';		
		return $div;		
	}

}