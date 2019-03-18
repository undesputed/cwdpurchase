
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



<div id="wrapper">
<div class="container">	
	<div class="row">
		<div>
			<h2 class="center-text title"><?php echo $header['title']; ?></h2>
			<div class="round padding  dark size"><span class="center-text" style="border-bottom:1px solid #fff;display:block"><?php echo $header['sub_title'] ?></span></div>
			<div class="round padding center-text margin-top bold size">INVENTORY</div>			
		</div>	
	</div>

	<table style="width:100%">
		<tr>
			<th>Project : </th>
			<td><?php echo " (".$project['project'].") ".$project['project_name']." - ".$project['project_location']; ?></td>
			<th>Date : </th>
			<td id="date"><?php echo date('Y-m-d'); ?></td>
		</tr>
	</table>




	<?php foreach($item as $key=>$row1): ?>	
	
	<table id="item-table">

		<thead>
		<tr class="border">
			<th colspan="6" style="font-size:18px"><?php echo $key; ?>	</th>
		</tr>
		<tr class="border">		
			<th>No</th>								
			<th>Item Name</th>
			<th>Received</th>
			<th>Withdrawal</th>
			<th>Transfer</th>
			<th>Stock</th>			
			<th>Unit</th>
		</tr>
		</thead>
		<tbody>
			<?php $cnt = 0; ?>
			<?php foreach($row1 as $row): $cnt++;?>			
			<tr>			
				<td class="center"><?php echo $cnt; ?></td>	
				<td><?php echo $row['item_name']; ?></td>
				<td class="center"><?php echo $row['receive_qty']; ?></td>
				<td class="center"><?php echo $row['withdraw_qty']; ?></td>
				<td class="center"><?php echo $row['transfer_qty']; ?></td>
				<td class="center"><?php echo $row['current_qty']; ?></td>
				<td class="center"><?php echo $row['unit_measure']; ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>	
	</table>

	<?php endforeach; ?>

</div>
</div>
		
<script>

</script>
	