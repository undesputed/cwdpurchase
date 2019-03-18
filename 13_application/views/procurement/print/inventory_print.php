
<style>
body{
  -webkit-print-color-adjust:exact;
  background: #fff;
}
.round{
	border-radius:10px;
	border:1px solid #999;	
	
}

.signatory{
	display:block;
	margin-top:1em;
	border-bottom:1px solid #999;
	margin-bottom:5px;
}

#item-table thead th{
	text-align: center;
}

#item-table{
	margin-top:1em;	
 	width:100%;
}
.border{
	border:1px solid #999;
}
.border th,.border td{
	border:1px solid #999;	
}

#item-table tbody tr td{
	border:1px solid #999;
}

.table-border{
	border:1px solid #999;
}
.bold{
	font-weight: bold;
}
.border-bottom{
	border-bottom:1px solid #999;
}

.po-label{
	width:35%;
	display:inline-block;
}
.center-text{
	text-align: center;
}
.right-text{
	text-align: right;
}
.padding{
	padding:3px;
}
.margin-top{
	margin-top:5px;
}
.dark{
	background:#333;
	color:#fff;
	font-weight: bold;
	font-size:17px;
}
.title{
	font-size:60px;
	font-family: "Broadway", arial, Serif;
	color:#333;
}

.po-color{
	color:#f00;
}
#wrapper{
	margin:0 auto;
	width: 900px;
}

.p-title{
	margin-left:5px;
}

.text-top{
	vertical-align: top;
}
.space{
	margin-top: 7px;
}
.size{
	width: 500px;
	margin: 10px auto;
}
.center{
	text-align: center;
}

</style>


<div id="wrapper" style="width:900px">
<div class="container">	
	<div class="row">
		<div class="col-xs-8">
			<h2 class="title"><img src="<?php echo base_url('images/dcd_logo.jpg');?>" width="200"></h2>
			<div style="display:block;height:60px;">
				<span style="display:block"><?php echo $header['address']; ?></span>
				<span style="display:block"><?php echo $header['contact']; ?></span>
				<span style="display:block"><?php echo $header['fax_no']; ?></span>
			</div>						
		</div>
	
		<div class="col-xs-4">

			<div style="display:block;height:60px;margin-top:1em">
				<span style="display:block" class="center-text"><?php echo $header['website']; ?></span>
				<span style="display:block" class="center-text"><?php echo $header['email']; ?></span>
			</div>

			<div class="round padding center-text margin-top bold dark">INVENTORY</div>	
		</div>
	</div>

	<div class="round" style="margin-top:2em;">
	<table style="width:100%" >
		<tr>
			<th>Project : </th>
			<td><?php echo " (".$project['project'].") ".$project['project_name']." - ".$project['project_location']; ?></td>
			<th>Date : </th>
			<td id="date"><?php echo date('Y-m-d'); ?></td>
		</tr>
	</table>
	</div>	
	
	<table id="item-table" style="margin-top:1em;">

		<thead>
		<tr class="border">		
			<th>Item No</th>								
			<th>Item Name</th>
			<th>Qty</th>
			<th>Unit</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($item as $key=>$row1): ?>	
			<tr>
				<th colspan="7">&nbsp;</th>
			</tr>
			<tr>
				<th colspan="7" style="font-size:14px"><?php echo $key; ?>	</th>
			</tr>
			<?php foreach($row1 as $row):?>			
			<tr>			
				<td class="center" style="font-size:12px"><?php echo $row['item_no']; ?></td>	
				<td style="font-size:12px"><?php echo $row['item_name']; ?></td>
				<td class="center" style="font-size:12px"><?php echo round($row['total_debit'],2) - round($row['total_credit'],2); ?></td>
				<td class="center" style="font-size:12px"><?php echo $row['unit_measure']; ?></td>
			</tr>
			<?php endforeach; ?>
		<?php endforeach; ?>
		</tbody>	
	</table>

	

</div>
</div>
		
<script>

</script>
	