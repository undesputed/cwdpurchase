
<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Disbursement Voucher</h2>
</div>

<div class="container">

	<div style="margin-top:5px">
		<ul class="nav nav-tabs" role="tablist">
		    <li ><a href="<?php echo base_url().index_page(); ?>/accounting/voucher">Main</a></li>
		    <li class="active"><a href="<?php echo base_url().index_page(); ?>/accounting/voucher/cumulative">Cumulative Data</a></li>
	  	</ul>
  	</div>

		<div class="content-title">
			<h3>Cumulative Data</h3>	
		</div>
		
		<div class="panel panel-default">		
		  <div class="panel-body">
		  	 <table class="table">
		  	 	<thead>
		  	 		<tr>
		  	 			<th>CV NO.</th>
		  	 			<th>CV DATE</th>
		  	 			<th>PO NO.</th>
		  	 			<th>SI NO.</th>
		  	 			<th>SUPPLIER</th>
		  	 			<th>REMARKS</th>
		  	 			<th>AMOUNT</th>
		  	 		</tr>
		  	 	</thead>
		  	 	<tbody>
		  	 		<?php if(count($cumulative) > 0): ?>
		  	 		<?php foreach($cumulative as $row): ?>
						<tr>
							<td><?php echo $row['CV NO.']; ?></td>
							<td><?php echo $row['CV DATE']; ?></td>
							<td><?php echo $row['PO N0.']; ?></td>
							<td><?php echo $row['SI N0.']; ?></td>
							<td><?php echo $row['SUPPLIER']; ?></td>
							<td><?php echo $row['REMARKS']; ?></td>
							<td><?php echo $row['AMOUNT']; ?></td>
						</tr>
		  	 		<?php endforeach; ?>
		  	 		<?php else: ?>
					
		  	 		<?php endif; ?>
		  	 	</tbody>		  	 	
		  	 </table>		  		
		  </div>	 
		</div>		
		
</div>


</div>
</div>
<script>

	var application ={
		init:function(){
			this.bindEvent();
			$('#date').date();
		},bindEvent:function(){
			$('#btn_po_list').on('click',this.open_po_list);
		},open_po_list:function(){

			$( "#dialog" ).dialog({
				modal :  true, 
				title : 'Received PO List',
				width :  '700px'
			});

			$('#dialog').html('Loading...');

			$.post('<?php echo base_url().index_page();?>/accounting/get_po_list',function(response){
				$('#dialog').html(response);
			}).error(function(){
				$('#dialog').html('ERROR!, FAILED TO LOAD DATA');
			});

		}
	};

	$(function(){		
		application.init();
	});
</script>