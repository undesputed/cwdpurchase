<style>
	.border_bottom{
		border-bottom: 1px solid #333;
	}
	.margin-left{
		margin-left:5em;
	}
</style>


<div style="width:900px" >
	<div class="container">
		<h2 style="text-decoration:underline;text-align:center;"><?php echo $type ?></h2>
	<table style="margin-top:3em;width:100%">

		<tr>
			<td></td>
			<td style="width:450px"></td>
			<td></td>
			<td style="width:200px">NO. <div class="border_bottom pull-right" style="width:170px;height:20px"><?php echo $credit['dv_number']; ?></div> </td>
		</tr>
		
		<tr>
			<td>PAY TO:</td>
			<td class="border_bottom" style="width:450px"><?php echo $supplier['business_name']; ?></td>
			<td>DATE:</td>
			<td class="border_bottom" style="width:200px"><?php echo $date; ?></td>
		</tr>
		
		<tr>
			<td>ADDRESS:</td>
			<td class="border_bottom" style="width:450px"><?php echo $supplier['address']; ?></td>
			<td></td>
			<td style="width:200px"></td>
		</tr>
		
	</table>
	
	<table style="width:100%;margin-top:1em">
		<tr style="border-top:1px solid #333;">
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr style="border:1px solid #333;border-left:0px;border-right:0px">
			<td style="width:500px;text-align:center;">P A R T I C U L A R</td>
			<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px"></td>
			<td style="width:150px;text-align:center;">AMOUNT</td>
		</tr>
		<tbody>
			<tr>
				<td style="width:500px;border-bottom:1px solid #333;vertical-align:top;position:relative">				
				</td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;border-bottom:1px solid #333;"></td>
				<td style="width:150px;text-align:center;border-bottom:1px solid #333;"></td>
			</tr>
			<tr style="height:200px;vertical-align:top">				
				<td>
					PAYMENTS FOR :
					<div class="margin-left">
						SI <?php echo $rr_main['supplier_invoice']; ?> / PO <?php echo $main_data['reference_no']; ?>						
					</div>
				</td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;"></td>
				<td style="text-align:center"><?php echo $total; ?></td>
			</tr>
			<tr>				
				<td>
					<table style="width:100%">
						<tr>
							<td>AMOUNT: </td>
							<td><strong><?php echo $po_data['from_projectCodeName']; ?></strong></td>
							<td>TOTAL P </td>
						</tr>
					</table>			
				</td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;"></td>
				<td style="text-align:center"><strong><?php echo $total; ?></strong></td>
			</tr>
			<tr>
				<td style="width:500px;text-align:center;height:2px;border-bottom:1px solid #333;"></td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;border-bottom:1px solid #333;"></td>
				<td style="width:150px;text-align:center;border-bottom:1px solid #333;"></td>
			</tr>
			<tr>
				<td style="width:500px;text-align:center;height:2px;border-bottom:1px solid #333;"></td>
				<td style="border-left:1px solid #333;border-right:1px solid #333;width:4px;border-bottom:1px solid #333;"></td>
				<td style="width:150px;text-align:center;border-bottom:1px solid #333;"></td>
			</tr>
		</tbody>
	</table>
	<table style="margin-top:1em;width:100%;">
		<tr>
			<td>
					<table border=1 style="width:100%">
						<tr style="text-align:center">
							<td>PREPARED BY</td>
							<td>CHECKED BY</td>
							<td>APPROVED BY</td>
						</tr>
						<tr style="height:60px">
							<td><?php echo (isset($signatory['prepared_by'][0]['Person Name']))? $signatory['prepared_by'][0]['Person Name'] : ''; ?></td>
							<td><?php echo (isset($signatory['checked_by'][0]['Person Name']))? $signatory['checked_by'][0]['Person Name'] : '' ; ?></td>
							<td><?php echo (isset($signatory['approved_by'][0]['Person Name']))? $signatory['approved_by'][0]['Person Name'] : ''; ?></td>
						</tr>
					</table>				
			</td>
			<td>
				<table style="width:100%">
					<tr>
						<td>RECEIVED THE FULL PAYMENT AMOUNT DESCRIBED ABOVE BY:</td>
					</tr>
					<tr>
						<td style="border-bottom:1px solid #333;height:40px"></td>
					</tr>
					<tr>
						<table style="width:100%">
							<tr>
								<td>RES. CERT.#</td>
								<td class="border_bottom" style="width:140px"></td>
								<td>DATE: </td>
								<td class="border_bottom" style="width:100px"></td>
								<td>PLACE</td>
								<td class="border_bottom" style="width:100px"></td>
							</tr>
						</table>
					</tr>
				</table>
			</td>
		</tr>
	</table>

</div>
</div>