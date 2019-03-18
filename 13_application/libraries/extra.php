<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Extra{	
	public function __get($var)	{
		return get_instance()->$var;	
	}		
	
	function generate_pr_code($month = null,$year = null) {		
		$this->md_manage->get_max_pr($month,$year);	
	}	
	
	public function get_po_code($_date) {			
		$date = explode('-',$_date); 
		$month = $date[1];			
		$year = $date[0];						
		$data = $this->md_project->get_max_po($month,$year);						
		
		if (empty($data[0]['max'])){				
			return "PO-".$year."-".$month.'-'.$this->str_pad('1');			
		}
		else {				
			$date = explode('-',$data[0]['max']);				
			return "PO-".$year."-".$month.'-'.$this->str_pad(($date[2]+1));			
		}	
	}	
	
	public function get_pr_code($_date) {						
		$date = explode('-',$_date);			
		$month = $date[1];			
		$year = $date[0];						
		$data = $this->md_project->get_max_pr($month,$year);					
		
		if ( empty($data[0]['max'])) {				
			return  "PR-".$year."-".$month.'-'.$this->str_pad('1').'';			
		} 
		else {				
			$date = explode('-',$data[0]['max']);								
			return  "PR-".$year."-".$month.'-'.$this->str_pad(($date[2]+1)).'';			
		}				
	}	
	
	public function get_journal_code($date,$type_name,$project_id,$type) {			
		$date  = explode('-',$date);			
		$month = $date[1];			
		$year  = $date[0];			
		$data  = $this->md_project->get_journalEntry($month,$year,$type_name,$project_id);						
		
		if ( empty($data[0]['max'])) {				
			return $type.'-'.$year.'-'.$month."-".$this->str_pad(1);			
		}
		else {				
			return $type.'-'.$year.'-'.$month."-".$this->str_pad($data[0]['max']);			
		}	
	}	
	
	public function get_max_rr($_date) {						
		$date = explode('-',$_date);			
		$month = $date[1];			
		$year = $date[0];			
		$data = $this->md_project->get_max_rr($month,$year);					
		
		if ( empty($data[0]['max'])) {				
			return  "RR-".$year."-".$month.'-'.$this->str_pad('1');			
		}
		else {				
			$date = explode('-',$data[0]['max']);				
			return  "RR-".$year."-".$month.'-'.$this->str_pad(($date[2]+1));			
		}				
	}		
	
	private function str_pad($num) {		 
		return str_pad($num, 3, '0', STR_PAD_LEFT);	
	}			
	
	function setup_category($id = null) {					
	
	}		
	
	public function is_admin() {		
		if ($this->session->userdata('type_user')==strtoupper("admin")) {			
			return true;		
		} 
		else {			
			return false;		
		}	
	}		
	
	public function user() {		
		return strtoupper($this->session->userdata('username'));	
	}		
	
	function get_title($id = null) {				
		$id = (empty($id)) ? $this->session->userdata('Proj_Main') : $id ;		
		$title = $this->session->userdata('project');		
		
		echo "<strong id='main-title'>".strtoupper($this->session->userdata('LABEL_SUB_NAME'))."</strong>";		
		echo "<span>(".$title[0]->project.") ".$title[0]->project_location."</span>";			
	}	
	
	function format_date($val) {		
		return date('F d, Y',strtotime($val));	
	}	
	
	public function milisecond($date) {		
		return (strtotime($date)*1000);	
	}		
	
	function alert() {	?>					
	
		<?php if((bool) $this->session->flashdata('message')):?>
			<div class="alert <?php echo $this->session->flashdata('type')?>">
				<button type="button" class="close" data-dismiss="alert">&times;</button>						
				<span><?php echo $this->session->flashdata('message')?></span>			
			</div>				
		<?php endif;?>
	<?php		
	}			
	
	public function generate_table($options=array()) {			
		/*=============== Revised version as of Jan 9,2014===========*/		
		$result = (isset($options['result'])) ? $options['result'] :null;		
		$hide = (isset($options['hide'])) ? $options['hide'] :array();		
		$heading  = (isset($options['heading'])) ? $options['heading'] : array();		
		$bool  = (isset($options['bool'])) ? $options['bool'] : false;		
		$custom_width = (isset($options['custom_width'])) ? $options['custom_width'] :array();		
		$table_class = (isset($options['table_class']))? $options['table_class'] : array('table', 'table-items', 'myTable', 'table-striped');		
		/*===========================================================*/				
		
		if (is_object($result)) {			
			$array = $result->result_array();			
			$array_header = $result->list_fields();				
		}
		else {			
			$array = $result['result_array'];			
			$array_header = $result['list_fields'];		
		}		
		
		if (empty($array_header)) {			
			$array_header = array();			
		}
		
		$tbl_prop = "";		
		foreach ($table_class as $row) {			
			$tbl_prop .= " ".$row;		
		}		
		
		$table=null;		
		$table .= "<div class='table-responsive'>";		
		$table .= "<table class='".$tbl_prop."' width='100%'>";			
		$table .= "<thead>";				
		$table .= "<tr>";				
		
		foreach ($array_header as $key) {						
			$temp;						
			if (is_array($heading) and count($heading)>0) {							
				$temp = (isset($heading[$key]))? $heading[$key] : $key; 						
			}
			else {							
				$temp = $key;						
			}						
			
			if ($bool) {							
				$display  = null;							
				$display2 = "style='display:none;";						
			} 
			else {							
				$display2 = null;							
				$display  = "style='display:none;";						
			}							
			
			if (is_array($custom_width) and count($custom_width)>0) {							
				$display2 .= (isset($custom_width[$key]))? "style='width:".$custom_width[$key]."'" : ""; 						
			}					
			
			$table .= (in_array($key,$hide))? "<th class='".str_replace(" ", "_", $temp)."' ".$display." '>".$temp."</th>" : "<th class='".str_replace(" ", "_", $temp)."' ".$display2." '>".$temp."</th>";
		}				
		$table .= "</tr>";			
		$table .= "</thead>";			
		$table .= "<tbody>";				
		
		if (count($array) >0) {					
			foreach($array as $arr){						
				$table .= "<tr>";						
				foreach($arr as $key => $pair){							
					$arr[$key] = (empty($arr[$key]))? "-" : $arr[$key];							
					$table .= (in_array($key,$hide))? "<td class='".str_replace(" ", "_", $key)."' $display '>".$arr[$key]."</td>" : "<td class='".str_replace(" ", "_", $key)."' $display2 '>".$arr[$key]."</td>";			
				}						
				$table .= "</tr>";						
			}				
		}
		else {					
			$table .="<tr class='empty_result'>";					
			$table .="<td colspan='".count($array_header)."'>Empty Result</td>";					
			$table .="</tr>";				
		}			
		$table .= "</tbody>";		
		$table .= "</table>";		
		$table .= "</div>";		
		
		return $table;	
	}	
	
	public function generate_options($settings=array()){
		$options="";		
		$data= (isset($settings['data'])) ? $settings['data']: array();		
		$val = (isset($settings['val'])) ? $settings['val']: "";		
		$text = (isset($settings['text'])) ? $settings['text']: "";		
		$selected = (isset($settings['selected'])) ? $settings['selected']: "";		
		
		foreach ($data as $row) {			
			$temp=array_values($row);			
			$value=(isset($row[$val])) ? $row[$val] : $temp[0];			
			$display=(isset($row[$text])) ?$row[$text]:$temp[1];			
			$is_selected = ($selected==$value) ? "selected":"";			
			$options .= "<option value='".$value."' ".$is_selected.">".$display."</option>";		
		}		
		
		return $options;	
	}	
	
	public function label2($type) {		
		switch(strtoupper($type)){			
			case "TRUE":				
					return "<span class='label label-success'><span class='fa fa-check'></span></span>";			
				break;			
			case "FALSE":				
					return "<span class='label label-danger'><span class='fa fa-times'></span></span>";			
				break;		
		}	
	}	
	
	public function label_journal($type){		
		switch(strtoupper($type)){			
			case "ACTIVE":					
					return "<span class='label label-warning'>Unposted</span>";			
				break;			
			case "POSTED":					
					return "<span class='label label-success'>Posted</span>";			
				break;			
			case "CANCELLED":					
					return "<span class='label label-danger'>Cancelled</span>";			
				break;		
		}	
	}	
	
	public function for_po_status($status_code = ""){		
		switch($status_code){			
			case "0":				
					return "<span class='label label-warning'>Pending</span>";			
				break;			
			case "1":				
					return "<span class='label label-success'>Complete PO</span>";			
				break;			
			case "2":				
					return "<span class='label label-info'>Remaining</span>";			
				break;			
			default:				
				return "<span class='label label-warning'>Pending</span>";			
			break;		
		}			
	}	
	
	public function label3($type){		
		switch(strtoupper($type)){			
			case "TRUE":				
					return "<span class='label label-success'>Approved</span>";			
				break;			
			case "FALSE":				
					return "<span class='label label-warning'>For Approval</span>";			
				break;		
		}	
	}	
	
	public function label5($type){				
		switch(strtoupper($type)){			
			case "APPROVED":				
					return "<span class='label label-warning label2'>WAITING</span>";			
				break;			
			case "PARTIAL":				
					return "<span class='label label-info label2 '>PARTIAL</span>";			
				break;			
			case "COMPLETE":				
					return "<span class='label label-success label2'>COMPLETE</span>";			
				break;			
			case "CANCELLED":				
					return "<span class='label label-danger label2'>CANCELLED</span>";			
				break;			
			case "CLOSED":				
					return "<span class='label label-danger label2'>CLOSED</span>";			
				break;		
		}	
	}	
	
	public function label6($type){		
		switch(strtoupper($type)){			
			case "ACTIVE":				
					return "<span class='label label-warning'>FOR APPROVAL</span>";			
				break;			
			case "UNPAID":				
					return "<span class='label label-warning'>UNPAID</span>";			
				break;			
			case "RECEIVED":			
			case "RECEIVE":				
					return "<span class='label label-success'>APPROVED</span>";			
				break;			
			case "CANCELLED":				
					return "<span class='label label-danger'>CANCELLED</span>";			
				break;			
			case "APPROVED":				
					return "<span class='label label-success'>APPROVED</span>";			
				break;			
			case "PARTIAL":				
					return "<span class='label label-success'>APPROVED</span>";			
				break;			
			case "COMPLETE":				
					return "<span class='label label-success'>APPROVED</span>";			
				break;		
		}	
	}	
	
	public function label4($type){		
		switch(strtoupper($type)){			
			case "ACTIVE":				
					return "<span class='label label-warning'>FOR APPROVAL</span>";			
				break;			
			case "UNPAID":				
					return "<span class='label label-warning'>UNPAID</span>";			
				break;			
			case "RECEIVED":			
			case "RECEIVE":				
					return "<span class='label label-info'>RECEIVED</span>";			
				break;			
			case "CANCELLED":				
					return "<span class='label label-danger'>CANCELLED</span>";			
				break;			
			case "APPROVED":				
					return "<span class='label label-success'>APPROVED</span>";			
				break;			
			case "PARTIAL":				
					return "<span class='label label-info'>PARTIAL</span>";			
				break;			
			case "COMPLETE":				
					return "<span class='label label-success'>COMPLETE</span>";			
				break;		
		}	
	}	
	
	public function c_label($type){		
		switch(strtoupper($type)){			
			case "APPROVED":				
					return "<span class='label label-success'>APPROVED</span>";			
				break;					
			case "PENDING":				
					return "<span class='label label-warning'>PENDING</span>";			
				break;						
			case "REJECTED":				
					return "<span class='label label-danger'>REJECTED</span>";			
				break;			
			case "CANCEL":			
			case "CANCELLED":				
					return "<span class='label label-danger'>CANCELLED</span>";			
				break;			
			case "EDIT":				
					return "<span class='label label-info'>UPDATED</span>";			
				break;			
			case "ACTIVE":				
					return "<span class='label label-success'>ACTIVE</span>";			
				break;			
			case "ADD":				
					return "<span class='label label-success'>CREATED</span>";			
				break;		
		}	
	}	
	
	public function label( $type ){		
		$type = strtoupper($type);		
		
		switch ( $type ) {			
			case 'FALSE':				
					return "<span class='label label-warning'>P</span>";				
				break;			
			case 'TRUE' :				
					return "<span class='label label-success'><i class='fa fa-check'></i></span>";				
				break;			
			case "ACTIVE" :				
					return "<span class='label label-warning'>Active</span>";			
				break;
			case "REJECTED" :				
					return "<span class='label label-danger'>REJECTED</span>";			
				break;			
			case "RECEIVE":			
			case "RECEIVED":				
					return "<span class='label label-info'>Received</span>";			
				break;			
			case "CANCEL" : 				
					return "<span class='label label-danger'>Cancelled</span>";			
				break;			
			case "CLOSED" : 				
					return "<span class='label label-danger'>closed</span>";			
				break;			
			case "CANCELLED" : 				
					return "<span class='label label-danger'>Cancelled</span>";			
				break;			
			case "APPROVED" : 				
					return "<span class='label label-success'>Approved</span>";			
				break;			
			case "REQUESTED" :				
					return "<span class='label label-warning'>Requested</span>";				
				break;			
			case "AVAILABLE" :				
					return "<span class='label label-success'>Available</span>";				
				break;				
			case "UNDER REPAIR" :				
					return "<span class='label label-danger'>Under Repair</span>";				
				break;			
			case "UNPOSTED" :				
					return "<span class='label label-warning'>UNPOSTED</span>";				
				break;				
			case "POSTED" :				
					return "<span class='label label-success'>POSTED</span>";				
				break;				
			case "ABSENT" :				
					return "<span class='label label-danger'>ABSENT</span>";				
				break;				
			case "RESTDAY" :				
					return "<span class='label label-success'>RESTDAY</span>";				
				break;							
			case "NOT SERVED" :				
					return "<span class='label label-danger'>NOT SERVED</span>";				
				break;			
			case "PENDING" :				
					return "<span class='label label-warning'>PENDING</span>";				
				break;			
			case "RETURNED" :				
					return "<span class='label label-success'>RETURNED</span>";			
				break;							
			default :				
					return "<span class='label label-warning'>".$type."</span>";			
				break;							
		}	
	}	
	
	public function project_type_label($type){		
		switch(strtoupper($type)){			
			case "PLUMBING":				
					return "<span class='label label-success' title='PLUMBING'>PLMB</span>";			
				break;					
			case "FIRE PROTECTION":				
					return "<span class='label label-danger' title='FIRE PROTECTION'>FP</span>";			
				break;			
			case "MECHANICAL":				
					return "<span class='label label-warning' title='MECHANICAL'>MECH</span>";			
				break;			
			case "ELECTRICAL":				
					return "<span class='label label-info' title='ELECTRICAL'>ELEC</span>";			
				break;			
			case "OTHERS":				
					return "<span class='label label-default' title='OTHERS'>OTHRS</span>";			
				break;	
		}	
	}	
	
	public function project_type_label2($type){		
		switch(strtoupper($type)){			
			case "1":				
					return "<span class='label label-success' title='PLUMBING'>Plumbing</span>";			
				break;					
			case "2":				
					return "<span class='label label-danger' title='FIRE PROTECTION'>Fire Pro.</span>";			
				break;			
			case "3":				
					return "<span class='label label-warning' title='ELECTRICAL'>Electrical</span>";			
				break;			
			case "4":				
					return "<span class='label label-info' title='MECHANICAL'>Mechanical</span>";			
				break;			
			default :				
					return "";			
				break;		
		}	
	}	
	
	public function number_format($value){		
		if(is_numeric($value)){			
			return number_format($value,2,'.',',');		
		}
		else {			
			return '';		
		}	
	}		
	
	public function comma($value){		
		if(is_numeric($value)){			
			return number_format($value,0,'.',',');		
		}
		else {			
			return '';		
		}	
	}			
	
	public function if_null($value) {		
		return (empty($value))? '-' : $value ;	
	}	
	
	function createDateRangeArray($strDateFrom,$strDateTo)	{	   
		/* takes two dates formatted as YYYY-MM-DD and creates an	    
		inclusive array of the dates between the from and to dates.	    
		could test validity of dates here but I'm already doing	    
		that in the main script*/	    
		
		$aryRange=array();	    
		$iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     
		substr($strDateFrom,8,2),substr($strDateFrom,0,4));	    
		$iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     
		substr($strDateTo,8,2),substr($strDateTo,0,4));	    
		
		if ($iDateTo>=$iDateFrom) {	        
			array_push($aryRange,date('Y-m-d',$iDateFrom)); 	        
			
			while ($iDateFrom<$iDateTo) {	            
				$iDateFrom+=86400; 	            
				array_push($aryRange,date('Y-m-d',$iDateFrom));	        
			}	    
		}	    
		
		return $aryRange;	
	}	
	
	function get_digital_signature($emp_id) {		
		$this->load->model('setup/md_employee_setup');		
		$result = $this->md_employee_setup->get_digital_signature($emp_id);		
		
		if(!empty($result)){			
			return base_url().$result['path'];					
		}
		else{			
			return "";		
		}	
	}	
	
	function convert_number_to_words($number){   	    
		$hyphen      = '-';	    
		$conjunction = ' and ';	    
		$separator   = ', ';	    
		$negative    = 'negative ';	    
		$decimal     = ' point ';	    
		$dictionary  = array(
							0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five', 
							6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten', 
							11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen', 
							16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty', 
							30 => 'thirty', 40 => 'fourty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy', 
							80 => 'eighty', 90 => 'ninety', 100 => 'hundred', 1000 => 'thousand', 1000000 => 'million', 
							1000000000 => 'billion', 1000000000000 => 'trillion', 1000000000000000 => 'quadrillion', 1000000000000000000 => 'quintillion' 
						);	   	    
		
		if (!is_numeric($number)) {	        
			return false;	    
		}	   	    
		
		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {	        	        
			trigger_error( 'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,  E_USER_WARNING ); 
			return false;	    
		}	    
		
		if ($number < 0) {	        
			return $negative . $this->convert_number_to_words(abs($number));	    
		}	   	    
		
		$string = $fraction = null;
		if (strpos($number, '.') !== false) {	        
			list($number, $fraction) = explode('.', $number);	    
		}	   	    
		
		switch (true) {	        
			case $number < 21:	            
					$string = $dictionary[$number];	            
				break;	        
			case $number < 100:	            
					$tens   = ((int) ($number / 10)) * 10;	            
					$units  = $number % 10;	            
					$string = $dictionary[$tens];	            
					
					if ($units) {	                
						$string .= $hyphen . $dictionary[$units];	            
					}	            
				break;	        
			case $number < 1000:	            
					$hundreds  = $number / 100;	            
					$remainder = $number % 100;	            
					$string = $dictionary[$hundreds] . ' ' . $dictionary[100];	            
					
					if ($remainder) {	                
						$string .= $conjunction . $this->convert_number_to_words($remainder);	            
					}	            
				break;	        
			default:	            
					$baseUnit = pow(1000, floor(log($number, 1000)));	            
					$numBaseUnits = (int) ($number / $baseUnit);	            
					$remainder = $number % $baseUnit;	            
					$string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];	            
					
					if ($remainder) {	                
						$string .= $remainder < 100 ? $conjunction : $separator;	                
						$string .= $this->convert_number_to_words($remainder);	            
					}	            
				break;	    
		}	   	    
		
		if (null !== $fraction && is_numeric($fraction)) {	        
			$string .= $decimal;	        
			$words = array();	        
			
			foreach (str_split((string) $fraction) as $number) {	            
				$words[] = $dictionary[$number];	        
			}	        
			
			$string .= implode(' ', $words);	    
		}	   	    
		
		return $string;	
	}	
	
	public function signatory4($params){		
		$arg['form'] = $params['type'];		
		
		if(isset($params['prepared_by'])){					
			if($params['prepared_by'] =='web_signatory'){				
				$arg['signatory'] = 'prepared_by';				
				$data['prepared_by']  = $this->md_project->get_websignatory($arg);				
			}
			else{				
				$data['prepared_by']  = $this->md_project->signatory1($params['prepared_by']);					
			}					
		}		
		
		if(!empty($params['recommended_by'])){			
			if(isset($params['recommended_by'])){				
				$arg['signatory'] = 'recommended_by';				
				$data['recommended_by'] = $this->md_project->get_websignatory($arg);			
			}		
		}		
		
		if(!empty($params['approved_by'])){			
			if(isset($params['approved_by'])){				
				$arg['signatory'] = 'approved_by';				
				$data['approved_by'] = $this->md_project->get_websignatory($arg);			
			}		
		}		
		
		if(!empty($params['requested_by'])){			
			$arg['signatory'] = 'requested_by';			
			$data['requested_by'] =  $this->md_project->get_websignatory($arg);		
		}		
		
		if(!empty($params['issued_by'])){			
			$arg['signatory'] = 'issued_by';			
			$data['issued_by'] = $this->md_project->get_websignatory($arg);		
		}		
		
		if(!empty($params['noted_by'])){			
			$arg['signatory'] = 'noted_by';			
			$data['noted_by'] = $this->md_project->get_websignatory($arg);	
		}		
		
		if(!empty($params['received_by'])){			
			$arg['signatory'] = 'received_by';			
			$data['received_by'] = $this->md_project->get_websignatory($arg);		
		}		
		
		if(!empty($params['checked_by'])){			
			$arg['signatory'] = 'checked_by';			
			$data['checked_by'] = $this->md_project->get_websignatory($arg);		
		}											
		
		return $data;	
	}
}