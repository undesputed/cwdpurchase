<style>
	#trial_table{
		width:100%;
	}
	.border-top{
		border-top:1px solid;
	}
	.border-bottom-double{
		border-style: double;
		border-left:0px;
		border-right:0px;
	}
	.text-right{
		text-align: right;
	}

    #wrapper{
		 display: table;
	}

	.footer {
  		  display: table-footer-group;
	}
	#header{
		display:table-footer-group;
	}
	/* 		
		#pageFooter:after {
		    counter-increment: page;
		    content: "Page " counter(page) " of " counter(pages);
		}
	*/

	@media print {
        div.footer {
            position: fixed;
            width:100%;
            bottom: 0px;
        }
		
        @page{
		  @bottom-right{
		    content: counter(page) " of " counter(pages);
		  }
		}
		
    }
	
</style>
<div id="wrapper" style="width:900px">
	
	<div  class="container">
		
		<h4 style="text-align:center"><?php echo $header['full_name'] ?></h4>

		<p style="text-align:center">BALANCE SHEET <br>
		<?php echo $from ?> to <?php echo $to ?></p>
		
		<style>
	
	.table thead > tr > th, .table tbody > tr > th, .table tfoot > tr > th, .table thead > tr > td, .table tbody > tr > td, .table tfoot > tr > td {
		border-top:none;
	}
	
	.space1{
		padding-left:5em !important;
	}
	.space2{
		padding-left:2em !important;
	}
	.border-top{
		border-top:1px solid !important;
	}
	.text-right{
		text-align: right;
	}
	.double-border{
		border-style:double;
		border-left:none;
		border-right:none;
	}
</style>


<?php 
	
	$array_liabilities = array('LIABILITIES','EQUITY');

?>
<table class="table">
<?php $liabilities_equity = 0; ?>
<?php foreach($data as $key=>$row): ?>	
	<tr>
		<td><strong><?php echo $key; ?></strong></td>
		<td></td>
		<td></td>
	</tr>

	<?php $_total = 0; ?>
	<?php foreach($row as $k=>$a): ?>

	<tr>
		<td class="space2"><strong><?php echo $k; ?></strong></td>
		<td></td>
		<td></td>
	</tr>
		<?php $total = 0; ?>
		<?php foreach($a as $a_row): ?>
		<?php		

			$total  = $total + $a_row['AMOUNT'];
			if($a_row['AMOUNT'] < 0){
				$display_amt = str_replace('-', '', $a_row['AMOUNT']);
				$display_amt = "( ".number_format($display_amt,2)." )";
				$a_row['DESCRIPTION'] = "Less : ". $a_row['DESCRIPTION'];
			}else{
				$display_amt = number_format($a_row['AMOUNT'],2);
			}
						
		?>
			<tr>
				<td class="space1"><?php echo $a_row['DESCRIPTION']; ?></td>
				<td class="text-right"><?php echo $display_amt; ?></td>
				<td></td>
			</tr>
		<?php endforeach; ?>	
		<tr>
			<td><strong>TOTAL <?php echo $k; ?></strong></td>
			<td class="border-top"></td>
			<td class="text-right"><?php echo number_format($total,2); ?></td>
		</tr>
		
		<?php $_total = $_total + $total; ?>
		<?php 
			  if(in_array($key, $array_liabilities)):
			  	$liabilities_equity = $liabilities_equity + $_total;
			  endif;
		 ?>

	<?php endforeach; ?>
	<?php if($key=='ASSETS'): ?>
	<tr>
		<td><strong>TOTAL <?php echo $key; ?></strong></td>
		<td></td>
		<td class="double-border text-right"><strong><?php echo number_format($_total,2); ?></strong></td>
	</tr>

	<?php elseif($key=='EQUITY'): ?>
		<tr>
		<td><strong>TOTAL LIABILITIES AND EQUITY</strong></td>
		<td></td>
		<td class="double-border text-right"><strong><?php echo number_format($liabilities_equity,2); ?></strong></td>
	</tr>	
	<?php endif; ?>

<?php endforeach; ?>
</table>
	
		
		<div class="row" style="margin-top:7em">
			<div class="col-xs-8"></div>
			<div class="col-xs-4">
				<span>Certified Correct:</span><br><br><br><br>
				
				<div style="margin-left:6em">
					PASTOR G. HOMECILLO, JR <br>
					<span style="margin-left:6em">Proprietor</span>
				</div>
				
			</div>
		</div>
		
		<div class="footer" style="display:block;margin-top:5em">
			<div id="date" style="float:left"></div>
			<div id="pageFooter" style="float:right"></div>
		</div>
		
	</div>

</div>

<script>
	$(function(){

		var newDate  = new Date();
		var datetime = "Date/Time Printed :       " + newDate.today() + "  " + newDate.timeNow();		

	});	
</script>

