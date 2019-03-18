<?php defined('BASEPATH') OR exit('No direct script access allowed');

class do_notification extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('procurement/md_purchase_request');
		$this->load->model('procurement/md_transaction_history');
		$this->load->model('procurement/md_purchase_order');
		$this->load->model('procurement/md_canvass_sheet');
		$this->load->model('procurement/md_project');

		$this->load->model('procurement/md_event_logs');	
		$this->load->model('procurement/md_item_request');		
	}

	public function _remap($method,$arg = array()){

		if (method_exists($this, $method))
	    {
	        $this->$method($arg);
	    }
	    else
	    {
	        $this->default_method();
	    }

	}

	public function default_method(){		

	}

	public function purchase_request($arg){

		if($arg[0]==''){
			redirect(base_url(),'refresh');
		}
		
		$data['segment'] = $arg;
		$data['main'] = $this->md_purchase_request->get_jo_main($arg[0]);
		$data['details'] = $this->md_purchase_request->get_jo_details($arg[0]);
		$data['transaction'] = $this->md_transaction_history->get_transaction_main_ref_id($arg[0]);

		$data['origin'] = $this->md_project->get_main($data['transaction']['from_projectCode'],$data['transaction']['from_projectMain']);

		$this->lib_auth->title = $data['main']['purchaseNo']." : Purchase Request";
		$this->lib_auth->build = "procurement/notification/purchase_request";		
		$this->lib_auth->render($data);
		
	}

	public function job_order($arg){

		if($arg[0]==''){
			redirect(base_url(),'refresh');
		}
		
		$data['segment'] = $arg;
		$data['main'] = $this->md_purchase_request->get_jo_main($arg[0]);
		$data['details'] = $this->md_purchase_request->get_jo_details($arg[0]);

		$data['origin'] = $this->md_project->get_main($data['transaction']['from_projectCode'],$data['transaction']['from_projectMain']);

		$this->lib_auth->title = $data['main']['job_order_no']." : Job Order";
		$this->lib_auth->build = "procurement/notification/job_order";		
		$this->lib_auth->render($data);
		
	}


	public function purchase_order($arg){
		if($arg[0]==''){
			redirect(base_url(),'refresh');
		}

		if(isset($arg[1])){
			switch($arg[1])
			{
				case "view":

					$data['po_main']    = $this->md_purchase_order->get_main_join($arg[0]);
					$data['po_details'] = $this->md_purchase_order->get_item_details($arg[0]);
					$data['supplier']   = $this->md_purchase_order->get_supplier_po($data['po_main']['supplierType'],$data['po_main']['supplierID']);

				    $data['approvedBy'] = $this->md_purchase_order->signatory($data['po_main']['approvedBy']);
					$data['preparedBy'] = $this->md_purchase_order->signatory($data['po_main']['preparedBy']);
					$data['recommendedBy'] = $this->md_purchase_order->signatory($data['po_main']['recommendedBy']);
								
					$this->lib_auth->title = "Purchase Order";
					$this->lib_auth->build = "procurement/notification/purchase_order_view";
					$this->lib_auth->render($data);

				break;

				case "cv":

					$arg['can_id']      = $arg[0];
					$arg['supplier_id'] = $arg[2];					
					$canvass_main = $this->md_canvass_sheet->get_canvassMain_id($arg['can_id']);					
					$data['pr_main'] = $this->md_purchase_request->get_pr_main($canvass_main['pr_id']);
					$canvass_details = $this->md_canvass_sheet->get_canvassDetails($arg['can_id']);					
					$data['canvass_item_details'] = $this->md_canvass_sheet->get_canvassDetails_supplier($arg);
										
					$details = $data['canvass_item_details'][0];					
					$supplier_info = $this->lib_transaction->supplier($details['supplier_id'],$details['supplierType']);

					$supplier_info[0]['type'] = $details['supplierType'];					
					$data['supplier_info'] = $supplier_info[0];
					

					$details = array();
					
					foreach($canvass_details as $row){
						$details[$row['supplierType']][$row['Supplier']][] = $row;
					}
					
					$data['canvass_supplier'] = $details;
					$this->lib_auth->title = "Purchase Order";
					$this->lib_auth->build = "procurement/notification/purchase_order_canvass";
					$this->lib_auth->render($data);


				break;

			}	
		}else{	

				$canvass_details = $this->md_canvass_sheet->get_canvassDetails($arg[0]);
				foreach($canvass_details as $row){
					$details[$row['supplierType']][$row['Supplier']][] = $row;
				}

				$data['canvass_supplier'] = $details;
				$this->lib_auth->title = "Purchase Order";
				$this->lib_auth->build = "procurement/notification/purchase_order_canvass_menu";
				$this->lib_auth->render($data);
				
				
		}

		/*
		if(isset($arg[1]) && $arg[1] == 'view'){
			
			$data['po_main']    = $this->md_purchase_order->get_main_join($arg[0]);
			$data['po_details'] = $this->md_purchase_order->get_item_details($arg[0]);
			$data['supplier']   = $this->md_purchase_order->get_supplier_po($data['po_main']['supplierType'],$data['po_main']['supplierID']);

		    $data['approvedBy'] = $this->md_purchase_order->signatory($data['po_main']['approvedBy']);
			$data['preparedBy'] = $this->md_purchase_order->signatory($data['po_main']['preparedBy']);
			$data['recommendedBy'] = $this->md_purchase_order->signatory($data['po_main']['recommendedBy']);
						
			$this->lib_auth->title = "Purchase Order";
			$this->lib_auth->build = "procurement/notification/purchase_order_view";
			$this->lib_auth->render($data);
			
		}else{

			$data['pr_main'] = $this->md_purchase_request->get_pr_main($arg[0]);
			$data['pr_details'] = $this->md_purchase_request->get_pr_details($arg[0]);
						
			$canvass_details = $this->md_canvass_sheet->get_canvassDetails($arg[0]);


			$details = array();
			foreach($canvass_details as $row){
				$details[$row['supplierType']][$row['Supplier']][] = $row;
			}
			
			$data['canvass_supplier'] = $details;
*/
			/*
			$this->lib_auth->title = "Purchase Order";
			$this->lib_auth->build = "procurement/notification/purchase_order";
			$this->lib_auth->render($data);
			*/
/*
			$this->lib_auth->title = "Purchase Order";
			$this->lib_auth->build = "procurement/notification/purchase_order_canvass";
			$this->lib_auth->render($data);*/

		//}

	}

	public function create_canvass($arg = array()){
		if(empty($arg)){
			redirect(base_url());
		}

		$data['pr_main']    = $this->md_purchase_request->get_purchaseNo($arg[0]);
		$data['pr_details'] = $this->md_purchase_request->get_pr_details($data['pr_main']['pr_id']);		
		$data['view']       = $this->load->view('procurement/notification/create_canvass',$data,true);

		$this->lib_auth->title = "Create Canvass : ".$arg[0];
		$this->lib_auth->build = "procurement/transaction_list/sidebar_2";
		
		$this->lib_auth->render($data);

	}

	public function create_po($arg = array()){
		if(empty($arg)){
			redirect(base_url());
		}

		$data['canvass_main']    = $this->md_canvass_sheet->get_canvassMain_no($arg[0]);

		$params['can_id'] = $data['canvass_main']['can_id'];
		$params['supplier_id'] = '';
		$data['supplier'] = array();
		$data['canvass_details'] = array();
				
		$data['supplier_info'] = array('business_name'=>' - select a Supplier -',
									   'address'=>'-',
									   'contact_no'=>'-',
									   'supplierType'=>'BUSINESS',
									   'supplier_id'=>'');

		if(!empty($arg[1])){
			$params['supplier_id']   = $arg[1];
			$data['canvass_details'] = $this->md_canvass_sheet->get_canvass_details3($params);
			$data['supplier_info']   = $this->md_purchase_order->get_supplier_po($data['canvass_details'][0]['supplierType'],$params['supplier_id']);
		}
		
		$data['supplier']   = $this->md_canvass_sheet->get_supplier_canvass($data['canvass_main']['can_id']);			
		$data['pr_main']    = $this->md_purchase_request->get_pr_main($data['canvass_main']['pr_id']);
		$data['pr_details'] = $this->md_purchase_request->get_pr_details($data['canvass_main']['pr_id']);

		$pm['pr_id'] = $data['canvass_main']['pr_id'];
		$pm['can_id'] = $data['canvass_main']['can_id'];
		$pm['supplier_id'] = $params['supplier_id'];
		$data['po_main'] = $this->md_purchase_order->get_po_main_canvass($pm);

		$data['reference_no'] = $this->md_purchase_order->get_max_reference_no();
				
		if(count($data['po_main']) > 0)
		{	
			$data['po_details'] = $this->md_purchase_order->get_po_details($data['po_main']['po_id']);
			$data['view'] = $this->load->view('procurement/notification/view_po',$data,true);
		}else if(!empty($arg[1]))
		{
			$data['view'] = $this->load->view('procurement/notification/create_po',$data,true);
		}else{
			$data['view'] = $this->load->view('procurement/notification/create_po_step1',$data,true);
		}
	
		/*
		$data['pr_main']    = $this->md_purchase_request->get_purchaseNo($arg[0]);
		$data['pr_details'] = $this->md_purchase_request->get_pr_details($data['pr_main']['pr_id']);
		*/
					
		$this->lib_auth->title = "Create P.O : ".$arg[0];
		$this->lib_auth->build = "procurement/transaction_list/sidebar_2";		
		$this->lib_auth->render($data);

	}

	public function get_po_items(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$arg = $this->input->post();
		
		$data['canvass_details'] = $this->md_canvass_sheet->get_canvass_details3($arg);		
		$this->load->view('procurement/notification/po_items',$data);
		
	}
	

	public function ap_canvass_item(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$this->md_canvass_sheet->update_canvass_details_status($arg);		
	}

	public function ap_canvass_supplier(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo $this->md_canvass_sheet->update_canvass_details_supplier($arg);

	}
		
	public function ap_canvass_main(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$id = $this->input->post('can_id');
		$update = array(
			'approval'=>$this->input->post('approval')
		);		

		$this->md_canvass_sheet->update_canvass_main($update,$id);

		$event['type']    = 'Canvass Sheet';
		$event['transaction_no'] = $this->input->post('can_no') ;
		$event['transaction_id'] = $id;
		$event['remarks'] = '';
		$event['action']  = 'APPROVED';

		echo $this->md_event_logs->create($event);


		
	}

	public function check_canvass(){
		$id = $this->input->post('can_id');

		$count_items = $this->md_canvass_sheet->count_items($id);
		$count_approved_items = $this->md_canvass_sheet->count_approved_items($id);

		$temp = "";
		if($count_items == $count_approved_items){
			$temp = 'TRUE';
		}else{
			$temp = 'FALSE';
		}

		echo $temp;
	}



	public function canvass_supplier($arg = array()){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();

		$data['supplier']['Affiliate'] = $this->md_project->get_supplier_affiliate();
		$data['supplier']['Business']  = $this->md_project->get_supplier_business();

		$data['pr_main']    = $this->md_purchase_request->get_pr_main($arg['pr_id']);
		$data['pr_details'] = $this->md_purchase_request->get_pr_details($arg['pr_id']);

		$this->load->view('procurement/notification/canvass_supplier',$data);

	}

	public function lookup($arg){
		$arg = array(
				'item_no' => $this->input->post('item_no')
			);

		$data['result'] = $this->md_purchase_request->get_lookup($arg);

		$this->load->view('procurement/notification/lookup_view',$data);		
	}

	public function canvass($arg = array()){
		if(empty($arg)){
			redirect(base_url());
		}

		$data['pr_main'] = $this->md_purchase_request->get_pr_main($arg[0]);
		$data['pr_details'] = $this->md_purchase_request->get_pr_details($arg[0]);

		$data['afilliate'] = $this->md_project->get_supplier_affiliate();
		$data['business'] = $this->md_project->get_supplier_business();

		$this->lib_auth->title = "Create Canvass";
		$this->lib_auth->build = "procurement/notification/canvass";
		$this->lib_auth->render($data);

	}

	public function do_approve(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$post = $this->input->post();
		$output = array();

		switch($post['type']){
			case"pr":
				
				$arg['pr_id']   = $post['pr_id'];
				$arg['id']      = $post['id'];
				$arg['status']  = 'APPROVED';
				$arg['remarks'] = '';
				$arg['bool'] = 'TRUE';
				$this->md_transaction_history->update_status($arg);
				$update = $post['data'];
				$this->md_purchase_request->update_details($update,$arg['pr_id']);
				$output['msg'] = 'Successfully Approved!';
				$this->session->set_flashdata(array('message'=>'Successfully Approved P.R ','type'=>'alert-success'));
				
			break;
			case"":

			break;
		}

		echo json_encode($output);

	}


	public function do_decline($arg = ''){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$post = $this->input->post();
		$output = array();

		switch($post['type']){
			case"pr":
				
				$arg['pr_id']   = $post['pr_id'];
				$arg['id']      = $post['id'];
				$arg['status']  = 'CANCELLED';
				$arg['remarks'] = $post['remarks'];
				$this->md_purchase_request->do_cancel($arg);
				$this->md_transaction_history->update_status($arg);
				$output['msg'] = 'Successfully Cancelled';

			break;
			case"po":

				$data = $this->md_transaction_history->get_po_ref_id($post['pr_id']);
				$arg['id'] = $data['id'];
				$arg['po_id']   = $post['po_id'];			
				$arg['status']  = 'CANCELLED';
				$arg['remarks'] = $post['remarks'];	

				$this->md_purchase_order->do_cancel($arg);
				$this->md_transaction_history->update_status($arg);
				$output['msg'] = 'Successfully Cancelled';

			break;
		}

		echo json_encode($output);

	}

	public function do_closing(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$post = $this->input->post();
		$output = array();

		switch($post['type']){
			case"pr":

				$arg['pr_id']   = $post['pr_id'];
				$arg['id']      = $post['id'];
				$arg['status']  = 'CLOSED';
				$arg['remarks'] = $post['remarks'];				
				$this->md_purchase_request->do_cancel($arg);
				$this->md_transaction_history->update_status($arg);
				$output['msg']  = 'Successfully Closed';

			break;

			case"":

			break;
		}

		echo json_encode($output);

	}

	public function create($arg){
		if(!isset($arg[0]))
		{
			redirect(base_url(),'refresh');
		}

		switch($arg[0]){
			case"pr":
				$this->_createPR();
			break;
			case "jo":
				$this->_createJO();
			break;
			case "rr":
				$this->_createRR();
			break;
			case "dt":
				$this->_createDT();
			break;
			case "rx":
				$this->_createRX();
			break;
		}

	}

	public function _createPR(){

		if($this->lib_auth->restriction('USER')){

			$this->lib_auth->title = "New Purchase Requisition";
			$this->lib_auth->build = "procurement/purchase_request/create_v2";
			$this->lib_auth->render();
		}else{
			?>
			<script>
				alert('You Are not allowed to PR');
				window.location = "<?php echo base_url().index_page();?>";
			</script>
			<?php

		}

	}

	public function _createJO(){

		if($this->lib_auth->restriction('USER')){

			$this->lib_auth->title = "New Job Order";
			$this->lib_auth->build = "procurement/job_order/create_v2";
			$this->lib_auth->render();
		}else{
			?>
			<script>
				alert('You Are not allowed to JO');
				window.location = "<?php echo base_url().index_page();?>";
			</script>
			<?php

		}

	}

	public function _createRR(){

		if($this->lib_auth->restriction('USER')){

			$this->lib_auth->title = "New Direct Receiving";
			$this->lib_auth->build = "procurement/direct_receiving/create_v2";
			$this->lib_auth->render();
		}else{
			?>
			<script>
				alert('You Are not allowed to RR');
				window.location = "<?php echo base_url().index_page();?>";
			</script>
			<?php

		}

	}

	public function _createDT(){

		if($this->lib_auth->restriction('USER')){

			$this->lib_auth->title = "New Direct Transfer";
			$this->lib_auth->build = "procurement/direct_transfer/create_v2";
			$this->lib_auth->render();
		}else{
			?>
			<script>
				alert('You Are not allowed to Direct Transfer');
				window.location = "<?php echo base_url().index_page();?>";
			</script>
			<?php

		}

	}

	public function _createRX(){

		if($this->lib_auth->restriction('USER')){

			$this->lib_auth->title = "New Return";
			$this->lib_auth->build = "procurement/return/create_v2";
			$this->lib_auth->render();
		}else{
			?>
			<script>
				alert('You Are not allowed to Return');
				window.location = "<?php echo base_url().index_page();?>";
			</script>
			<?php

		}

	}


	public function canvass_approval($arg = array()){

		if(empty($arg)){
			redirect(base_url());
		}

		if(isset($_POST['can_id'])){
			$arg['approval'] = $this->input->post('prepared_by');
			$arg['can_id']   = $this->input->post('can_id');
			if($this->md_canvass_sheet->approveCanvass($arg)){
				redirect(current_url());
			}

		}

		$data['main_list'] = $this->md_canvass_sheet->get_canvassMain_id($arg[0]);
		$data['item_list'] = $this->md_canvass_sheet->canvass_display_item($arg[0]);
		$canvass_details   = $this->md_canvass_sheet->get_canvassDetails($arg[0]);

		$details = array();
		foreach($canvass_details as $row){
			$details[$row['supplierType']][$row['Supplier']][] = $row;
		}
		
		$data['canvass_supplier'] = $details;
		
		$this->lib_auth->title = "Canvass Supplier Approval";
		$this->lib_auth->build = "procurement/notification/canvass_approval";
		$this->lib_auth->render($data);

	}


	public function change_status(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();

		switch($arg['type']){
			case "PR":				
				$data['pr_status'] = 'CANCELLED';
				if($arg['status']=='PENDING' or $arg['status'] =='APPROVED'){
					$data['pr_status'] = 'ACTIVE';	
				}
				$data['id']        = $arg['id'];
				$data['transaction_status'] = $arg['status'];

				if(!empty($arg['details'])){
					$this->md_purchase_request->update_pr_qty($arg['details'],$data['id']);
				}
			
				/*
				type
				transaction_no
				remarks
				action
				*/
				$this->md_purchase_request->changestatus($data);

				$event['type']    = 'Purchase Request';
				$event['transaction_no'] = $arg['transaction_no'];
				$event['transaction_id'] = $arg['id'];
				$event['remarks'] = $arg['remarks'];
				$event['action']  = $arg['status'];

				echo $this->md_event_logs->create($event);

				

			break;
			case "PO":
				
				/*
				if($arg['status']=='PENDING' or $arg['status'] =='APPROVED'){
					$data['po_status'] = 'Active';	
				}else{
					$data['po_status'] = $arg['status'];
				}
				*/
				
				$data['po_status'] = $arg['status'];
				$data['id']        = $arg['id'];
				$data['transaction_status'] = $arg['status'];				
				$this->md_purchase_order->changestatus($data);
				
				$event['type']    = 'Purchase Order';
				$event['transaction_no'] = $arg['transaction_no'];
				$event['transaction_id'] = $arg['id'];
				$event['remarks'] = $arg['remarks'];
				$event['action']  = $arg['status'];

				echo $this->md_event_logs->create($event);
				
			break;

			case 'transfer':

				$data['request_status'] = $arg['status'];
				$data['id']        = $arg['id'];
				$data['transaction_status'] = $arg['status'];				
				$this->md_item_transfer->changestatus($data);

				$event['type']    = 'Item Transfer';
				$event['transaction_no'] = $arg['transaction_no'];
				$event['transaction_id'] = $arg['id'];
				$event['remarks'] = $arg['remarks'];
				$event['action']  = $arg['status'];

				$this->md_item_transfer->compute_itemtransfer($arg['id']);				
				echo $this->md_event_logs->create($event); 

			break;
			case 'item_request':

				$data['request_status'] = $arg['status'];
				$data['id']        = $arg['id'];
				$data['transaction_status'] = $arg['status'];				
				$this->md_item_request->status_change($data);
				$this->md_item_request->save_withdraw($data);

				$event['type']    = 'Item Request';
				$event['transaction_no'] = $arg['transaction_no'];
				$event['transaction_id'] = $arg['id'];
				$event['remarks'] = $arg['remarks'];
				$event['action']  = $arg['status'];
				
				echo $this->md_event_logs->create($event); 

			break;

			case "PO_CANCEL":				
				echo $this->md_purchase_order->cancel_po($arg);
			break;

			case "CANVASS_CANCEL":
				if($this->md_canvass_sheet->cancel_canvass($arg)){
					$event['type']    = 'Canvass Sheet';
					$event['transaction_no'] = $this->input->post('can_no') ;
					$event['transaction_id'] = $arg['id'];
					$event['remarks'] = '';
					$event['action']  = 'CANCEL';

					echo $this->md_event_logs->create($event);
				}			
			break;

			case "RR": 
				
				echo json_encode($this->md_received_purchase->cancel_rr($arg));
				
			break;

			case "JO":				
				/*$data['jo_status'] = 'CANCELLED';
				if($arg['status']=='PENDING' or $arg['status'] =='APPROVED'){
					$data['jo_status'] = 'ACTIVE';	
				}*/
				$data['jo_status'] = $arg['status'];
				$data['id']        = $arg['id'];
				$data['transaction_status'] = $arg['status'];
			
				/*
				type
				transaction_no
				remarks
				action
				*/
				$this->md_purchase_request->jostatus($data);

				$event['type']    = 'Job Order';
				$event['transaction_no'] = $arg['transaction_no'];
				$event['transaction_id'] = $arg['id'];
				$event['remarks'] = $arg['remarks'];
				$event['action']  = $arg['status'];

				echo $this->md_event_logs->create($event);

				

			break;

		}



	}

	public function canvass_edit(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$arg = $this->input->post();

		$data['main_data']       = $this->md_canvass_sheet->get_canvassMain_no($arg['can_no']);
		$data['pr_main']         = $this->md_purchase_request->get_purchaseNo($data['main_data']['purchaseNo']);
		$data['details_data']    = $this->md_purchase_request->get_pr_details($data['main_data']['pr_id']);
		$data['canvass_details'] = $this->md_canvass_sheet->get_canvassDetails_2($data['main_data']['can_id']);

		$data['supplier_list']['Affiliate'] = $this->md_project->get_supplier_affiliate();
		$data['supplier_list']['Business']  = $this->md_project->get_supplier_business();
		
		$this->load->view('procurement/transaction_list/canvass_info_edit',$data);

	}


	public function search_item(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['item'] = $this->md_canvass_sheet->search_item();
		$this->load->view('procurement/notification/canvass_search_item',$data);
		
	}

	public function supplier_item(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$arg    = $this->input->post();
		$data['item_name'] = $arg['item_name'];
		$array  = array();		
		$c_date = array();
		$result = $this->md_canvass_sheet->supplier_item($arg);
		
		foreach($result as $row){
			$array[$row['c_number']][] = array(
				'supplier'=>$row['supplier'],
				'unit_cost'=>$row['unit_cost'],
				'sup_qty'=>$row['sup_qty'],
				);
			$c_date[$row['c_number']] = $this->extra->format_date($row['c_date']);
		}

		$data['supplier'] = $array;
		$data['c_date']   = $c_date;
		$this->load->view('procurement/notification/canvass_supplier_item',$data);

	}

	public function user_status(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$arg = $this->input->post();
		$this->md_purchase_order->po_status($arg['po_id'],$arg['data']);

	}


	public function po_summary(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['from'] = '';
		$data['to'] = '';
		$data['proj'] = '';

		$data['project'] = $this->md_purchase_order->get_projects();
		/*$data['po_summary'] = $this->md_purchase_order->get_all_po_summary($arg);*/
		$data['po_summary'] = '';

		$this->load->view('procurement/notification/po_summary',$data);
	}

	public function po_summary_override(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = array(
				'datefrom' => $this->input->post('datefrom'),
				'dateto' => $this->input->post('dateto'),
				'project' => $this->input->post('project')
			);

		$data['from'] = $this->input->post('datefrom');
		$data['to'] = $this->input->post('dateto');
		$data['proj'] = $this->input->post('project');

		$data['project'] = $this->md_purchase_order->get_projects();
		$data['po_summary'] = $this->md_purchase_order->get_all_po_summary_filter($arg);

		$this->load->view('procurement/notification/po_summary',$data);
	}





}