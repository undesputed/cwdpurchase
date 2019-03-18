<div id="wrapper" style="width:900px">
<div class="container">
	<div style="height:30px;"></div>

	<table id="item-table">
		<tbody>
			<tr class="border">
				<th colspan="7" rowspan="3">
					<span class="center-text bold" style="display:block;"><?php echo $header['title']; ?></span>
					<span class="center-text" style="display:block;"><?php echo $header['address']; ?></span>
					<span class="center-text bold" style="font-size:18px;display:block;">STOCK CARD</span>
				</th>
			</tr>
			<tr>
			</tr>
			<tr>
			</tr>
			<tr class="border">
				<td colspan="2">
					<span style="display:block;margin-top:-15px;"><strong>Items:</strong> <font style="text-decoration:underline;"><?php echo $item['group_description'];?></font>_________</span>
				</td>
				<td colspan="3">
					<span style="display:block;margin-top:-15px;"><strong>Description:</strong> <font style="text-decoration:underline;"><?php echo $item['description'];?></font>_________</span>
				</td>
				<td colspan="2">
					<span style="display:block;border-bottom:1px solid #999;"><strong>Stock No.:</strong> <font style="text-decordation:underline;"><?php echo $item['group_detail_id'];?></font></span>
					<span style="display:block;"><strong>Reorder Point:</strong> ___________</span>
				</td>
			</tr>
			<tr class="border">
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th class="center-text">Receipt</th>
				<th colspan="2" class="center-text">ISSUANCE</th>
				<th class="center-text">Balance</th>
				<th class="center-text">&nbsp;</th>
			</tr>
			<tr class="border">
				<th class="center-text">DATE</th>
				<th class="center-text">REFERENCE</th>
				<th class="center-text">QTY</th>
				<th class="center-text">QTY</th>
				<th class="center-text">OFFICE</th>
				<th class="center-text">&nbsp;</th>
				<th class="center-text">NO. OF DAYS TO CONSUME</th>
			</tr>
			<?php
				if(count($item_list) > 0){
					$balance = 0;
					foreach($item_list as $row){
						if($row['type'] == 'RECEIVING'){
                          	$balance = $balance + $row['debit'];
                        }elseif($row['type'] == 'WITHDRAW'){
                          	$balance = $balance - $row['credit'];
                        }elseif($row['type'] == 'TRANSFER'){
                          	$balance = $balance - $row['credit'];
                        }elseif($row['type'] == 'WITHDRAW'){
                          	$balance = $balance - $row['credit'];
                        }elseif($row['type'] == 'RETURN'){
                        	$balance = $balance + $row['debit'];
                        }elseif($row['type'] == 'BEGINNING'){
                        	$balance = $balance + $row['debit'];
                        }
			?>
			<tr class="border">
				<td class="center-text"><?php echo date('m/d/Y',strtotime($row['trans_date']));?></td>
				<td class="center-text"><?php echo $row['reference_no'];?></td>
				<td class="center-text"><?php echo $row['debit'];?></td>
				<td class="center-text"><?php echo $row['credit'];?></td>
				<td class="center-text"><?php echo $row['office'];?></td>
				<td class="center-text"><?php echo $balance;?></td>
				<td>&nbsp;</td>
			</tr>
			<?php
					}
				}
			?>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
		</tbody>
	</table>
	
	<div class="print_ft" style="margin-top:10px;">
		<div class="row">
			<div class="col-md-4"><span class="bold">FM-WHS-06</span></div>
			<div class="col-md-4" style="text-align:center;"><span class="bold">00</span></div>
			<div class="col-md-4" style="text-align:right;"><span class="bold">8/20/2016</span></div>
		</div>
	</div>

</div>
</div>
		
<script>

</script>
	