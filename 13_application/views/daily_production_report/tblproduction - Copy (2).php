								 
<?php 
	$cnt = 1;
	$counter = 0;
	$remove = array('RUNNING TODAY','RUNNING MONTHLY','RUNNING ANNUAL','TODAY','MONTH','YEAR');
	$color_grade = array('LG_HF'=>'lg_hf','LG_MF'=>'lg_mf','LG'=>'lg','MG_HF'=>'mg_hf','MG_LF'=>'mg_lf','HG'=>'hg');

	$title = array();

	foreach($data[0] as $key=>$row)
	{
		if($cnt>9){
			
			$_key = str_replace($remove,'', $key);

			if(!in_array($_key, $title)){
				$title[] = $_key;
			}			
			$counter ++;
		}
		$cnt++;		
	}
	$loop1 = ($counter / 3);


 ?>

								  <table class="table table-condensed table-report" >
										<thead>

										<tr class="cf-1">
											<td rowspan="2" style="vertical-align:middle">Material</td>			
											<td rowspan="2" style="vertical-align:middle;display:none">GRADE</td>			
											<td colspan="3">IN-HOUSE DT</td>
											<td colspan="3">IN-HOUSE ADT</td>

											<?php for ($i=0; $i < $loop1; $i++){ ?>
												<td colspan="3"> <?php echo $title[$i];?> </td>
											<?php }; ?>
											
											<!-- 
											<td colspan="3">UMRC</td>
											<td colspan="3">S AND T</td>
											<td colspan="3">SKAFF</td>
											<td colspan="3">PBA</td>
											<td colspan="3">TOTAL</td> -->
										</tr>

										<tr class="cf-2">
												<td>Today</td>
												<td>Running Monthly</td>
												<td>Running Annual</td>
												<td>Today</td>
												<td>Running Monthly</td>
												<td>Running Annual</td>
											<?php for ($i=0; $i < $loop1; $i++){ ?>
												<td>Today</td>
												<td>Running Monthly</td>
												<td>Running Annual</td>
											<?php }; ?>
											
											<!---->	
										</tr>
										</thead>
										<tbody>	
										<?php 
										$total_daily = 0;
										$total_monthly = 0;
										$total_yearly = 0;

										
											foreach ($data as $key => $value) :
											
											/*	
											$total_daily = $row['DT TODAY'] + $row['ADT TODAY'] + $row['UMRC RUNNING TODAY'] + $row['S AND T RUNNING TODAY'] + $row['SKAFF RUNNING TODAY'] + $row['PBA RUNNING TODAY'];
											$total_monthly = $row['DT RUNNING MONTHLY'] + $row['ADT RUNNING MONTHLY'] + $row['UMRC RUNNING MONTHLY'] + $row['S AND T RUNNING MONTHLY'] + $row['SKAFF RUNNING MONTHLY'] + $row['PBA RUNNING MONTHLY'];
											$total_yearly = $row['DT RUNNING ANNUAL'] + $row['ADT RUNNING ANNUAL'] + $row['UMRC RUNNING ANNUAL'] + $row['S AND T RUNNING ANNUAL'] + $row['SKAFF RUNNING ANNUAL'] + $row['PBA RUNNING ANNUAL'];
											*/
											
											$color =@ $color_grade[$value['GRADE']];

											?>
											
											<tr>
												<?php 
													$cnt = 0 ;
													foreach($value as $row): 
													if($cnt!=0):

													if($cnt == 1):	
												?>
													<td class="<?php echo $color; ?>"><?php echo (is_numeric($row)? $this->extra->comma($row): $row); ?></td>
													<?php $color = ''; ?>													
												<?php endif; ?>
												<?php if($cnt == 2): ?>
													<td style="display:none"></td>
												<?php elseif($cnt > 2): ?>
													<td><?php echo (is_numeric($row)? $this->extra->comma($row): $row); ?></td>
												<?php endif; ?>
												
												<?php endif; $cnt++;?>
												
												<?php endforeach; ?>

											</tr>												
											<?php endforeach; ?>		
											
									</tbody>
								</table>

