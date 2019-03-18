<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {

	public function __construct(){
		parent :: __construct();	

		$this->load->model('procurement/md_transaction_history');	
	}



	public function transaction(){
		/*if(!$this->input->is_ajax_request()){
			exit(0);
		}*/
		$result = $this->md_transaction_history->get_notification();	
	
		$div = "";		
		$div .='<ul class="dropdown-menu">';
		$class = '';
		$cnt = '';
		$open = '';
		$date  = array();
		if(count($result) > 0)
		{

			$open = "open";			
			foreach($result as $row)
			{
				$class = '';
				if($row['is_open'] =='FALSE'){
					$class = 'bold';
					$cnt++;
				}

				$span_class = '';
				switch(strtoupper($row['status'])){

					case "PENDING":
					$span_class = "label-warning";
					break;

					case "APPROVED":
					$span_class = "label-success";
					break;

					case "CANCELLED":
					$span_class = "label-info";
					break;

					case "CLOSED":
					$span_class = "label-danger";
					break;

				}

				$d = date('F d,Y',strtotime($row['date_created']));
				if(in_array($d, $date)){
					$date[] = $d;				
				}else{
					$date[] = $d;
					$div.="<li class='dropdown-header'>".$d."</li>";					
				}
				$segment3 = '';
				if(strtoupper($row['type'])=='PURCHASE REQUEST')
				{
					if($row['notify']=='creator')
					{
						$segment3 =  'incoming/'.$row['purchaseNo'];
					}else
					{
						$segment3 =  'outgoing/'.$row['purchaseNo'];
					}
				}


				$div.='<li><a href="'.base_url().index_page().'/transaction_list/'.$this->to_url($row['type']).'/'.$segment3.'" class="'.$class.'"><span class="label '.$span_class.'">'.$row['status'].'</span> <strong>'.$row['type'].'</strong> :  <div> '.$row['title_projectMain'].' <div><small>'.$row['title_projectCode'].'</small></div> </div></a></li>';

			}

		}else
		{
			$div.='<li><span class="no-notification text-muted">No Notification</span></li>';
		}		
		$div .="</ul>";
		

		if($cnt==0){
			$open = '';
		}

		$output = array(
			'cnt'=>$cnt,
			'content'=>$div,
			'class'=>$open,

		);

		echo json_encode($output);

	}

	public function to_url($string){
		$string = strtolower(str_replace(' ','_', $string));
		return $string;
	}



}