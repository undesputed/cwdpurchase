<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Income_statement extends CI_Controller {

	public function __construct(){
		parent :: __construct();	
		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_income_statement');	
		$this->load->model('md_project');
	}

	public function index(){

		$this->lib_auth->title = "Income Statement";		
		$this->lib_auth->build = "accounting/income_statement/index";
		$data['project'] = $this->md_project->get_profit_center();
		$data['project_category'] = $this->md_project->get_project_category();

		$this->lib_auth->render($data);

	}

	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);

		$data = array();		
		$post = $this->input->post();

		/*	
		if($post['month'] ==0 ){
			$data['from'] = $post['year']."-01-01";
			$data['to']   = $post['year']."-12-31";
		}else{
			$data['from'] = date('Y-m-01',strtotime($post['year']."-".$post['month']."-01"));
			$data['to']   = date('Y-m-t',strtotime($post['year']."-".$post['month']."-01"));
		}
		*/

		$data['from'] = $post['date_from'];
		$data['to']   = $post['date_to'];
		
		$data['pay_item'] = '';
		$data['project']  = $post['location'];
		$data['check']    = $this->session->userdata('Proj_Main');
		$data['year']     = '';
	
		$result = $this->md_income_statement->get_cumulative($data['from'],$data['to'],$data['project'],$data['check'],$data['pay_item']);		
		

		$table  = null;
		$prev_total_income = 0;
		$current_total_income = 0;
		$prev_total_expenses = 0;
		$current_total_expenses = 0;
		$prev_total_revenue = 0;
		$current_total_revenue = 0;
		$include_income = false;
		$include_expense = false;		


		$counter = 0;
		$cnt     = 0 ;
		$dup_td  = "";

		if($result){
			$table .="<table class='table myTable table-striped'>";
			$table .= "<thead>
						<tr>
							<td>Income / Expense</td>";

					foreach($result[5] as $row){
						$table.="<td>{$row['DATE']}</td>";
						$counter ++;
						$dup_td .="<td></td>";
					}

			$table .="
						</tr>
						</thead>
				<tbody>";
				$table .= "<tr><td><strong style='color:red;'>INCOME</strong></td>{$dup_td}</tr>";
				foreach($result[0] as $resZero){
					if($resZero['short_description'] == "INCOME"){
						$include_income = false;
						foreach($result[2] as $resTwo){
							if($resZero['id'] == $resTwo['id']){

								if($include_income == false){
									$table .= "<tr><td style='padding-left:7em;'>".$resZero['full_description']."</td>{$dup_td}</tr>";
									$include_income = true;
								}

								$table .="<tr>";
								$cnt = 0;								
								foreach($resTwo as $key=>$row){
									if (ctype_digit($key)){
										$table .= "<td>".number_format($row, 2, '.', ',')."</td>";
										$array[$cnt] =@ $array[$cnt] + $row;										
										$cnt++;
									}else{
										if($key == 'account_description_com'){
												$table .= "<tr><td style='padding-left:10em;'>".$row."</td>";
										}
									}
								}								
								$table .="</tr>";
							}
						}
					}
				}

				$table .= "<tr><td style='padding-left:5em;'><strong>Total Income</strong></td> ";
				for ($i=0; $i < $counter ; $i++){ 
					$total = (isset($array[$i]))? $array[$i] : 0;
					$table .="<td><strong>".number_format($total, 2, '.', ',')."</strong></td>";
				}														
				$table.="</tr>";
				$table.="<tr></tr>";
				
				$table .= "<tr><td></td>{$dup_td}</tr>";
				$table .= "<tr><td>COST OF REVENUES</td>{$dup_td}</tr>";

				foreach($result[4] as $resFour){
					$cnt = 0;
					foreach($resFour as $key=>$row){
								if (ctype_digit($key)){

										$table .= "<td>".number_format($row, 2, '.', ',')."</td>";
										$array1[$cnt] =@ $array1[$cnt] + $row;
										$cnt++;

									}else{
										if($key == 'account_description_com'){
												$table .= "<tr><td style='padding-left:10em;'>".$row."</td>";
										}
								}
					}							
				}

				$table .= "<tr><td style='padding-left:5em;'><strong>Total Cost of Revenues</strong></td> ";
				for ($i=0; $i < $counter ; $i++) { 
					$total = (isset($array1[$i]))? $array1[$i] : 0;
					$table .="<td><strong>".number_format($total, 2, '.', ',')."</strong></td>";
				}														
				$table.="</tr>";
				$table.="<tr></tr>";

				$table .= "<tr><td><strong style='color:red;'>EXPENSES</strong></td>{$dup_td}</tr>";
				foreach($result[1] as $resOne){
					if($resOne['short_description'] == "EXPENSES"){
						foreach($result[3] as $resThree){
							if($resOne['id'] == $resThree['id']){
								if($include_expense == false){
									$table .= "<tr><td style='padding-left:7em;'>".$resOne['full_description']."</td>{$dup_td}</tr>";
									$include_expense = true;
								}

								$table .="<tr>";
								$cnt    = 0;

								foreach($resThree as $key=>$row){									
									if (ctype_digit($key)){
										$table .= "<td>".number_format($row, 2, '.', ',')."</td>";
										$array2[$cnt] =@ $array2[$cnt] + $row;
										$cnt++;
									}else{
										if($key == 'account_description_com'){
										$table .= "<tr><td style='padding-left:10em;'>".$row."</td>";
										}
									}
								}

								$table .="</tr>";	

							}
						}
					}
				}

				$table .= "<tr><td style='padding-left:5em;'><strong>Total Expense</strong></td>";
							
				for ($i=0; $i < $cnt ; $i++){
					$total  = (isset($array2[$i]))? $array2[$i] : 0;
					$table .="<td><strong>".number_format($total, 2, '.', ',')."</strong></td>";
					$net_income[$i] =@ ($array[$i] - $array1[$i]) - $total;
				}

				$table.="</tr>";
				$table.="<tr></tr>";
				
				$table .="<tr>";
				$table .="<td><strong>Net Profit</strong></td>";
				for ($i=0; $i < $cnt; $i++)
				 {
					$table.="<td><strong>".number_format($net_income[$i], 2, '.', ',')."</strong></td>";
				 }
				$table.="</tr></table>";			
				$table.="</table>";
			echo $table;
		}
		else
			echo false;



	}




}