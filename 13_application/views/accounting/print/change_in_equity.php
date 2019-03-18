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
		<p style="text-align:center">STATEMENT OF CHANGES IN EQUITY<br>
			<?php echo $from ?> to <?php echo $to ?>
		</p>
		<?php echo $table; ?>		
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

