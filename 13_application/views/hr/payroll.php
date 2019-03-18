<style>
	.myTable thead th, tbody td{
		white-space: nowrap;
	}
	.data-scroll{
		overflow:auto;
	}
	.control-label{
		font-size:11px;
	}

</style>


<div class="header">
	<h2>Human Resource Management <small>Payroll</small></h2>	
</div>

<div class="container">
	<div class="row">
		<div class="col-md-2">			
			<div class="panel panel-default" style="margin-top:1em">		
			  <div class="panel-body">
			  		<span style="display:block;text-align:center">Payroll Period</span>
			  		<div class="form-group">
			  			<div class="control-label">From</div>
			  			<input name="" id="date_from" class="form-control input-sm">
			  		</div>
			  		<div class="form-group">
			  			<div class="control-label">To</div>
			  			<input name="" id="date_to" class="form-control input-sm">
			  		</div>
			  		<hr>
			  		<div class="form-group">
			  			<div class="control-label">Dept Name</div>
			  			<select name="" id="dept" class="form-control input-sm">
			  				<?php foreach($department as $row): ?>
								<option value="<?php echo $row['DeptCode_Dept']; ?>"><?php echo $row['Name_Dept']; ?></option>			  				
			  				<?php endforeach; ?>
			  			</select>
			  		</div>
			  		<button class="btn btn-primary pull-right" id="search">Generate <span class="fa"></span></button>
			  		<div class="clearfix"></div>
			  		<hr>

<!-- 			  		<div class="form-group">
	<div class="row">
		<div class="col-sm-6">
				  						<div class="checkbox">
											<input type="checkbox" name="checkbox" id="sss"><label for="sss">SSS</label>
										</div>
										<div class="checkbox">
											<input type="checkbox" name="checkbox" id="philhealth"><label for="philhealth">PhilHealth</label>
										</div>
										<div class="checkbox">
											<input type="checkbox" name="checkbox" id="hdmf"><label for="hdmf">HDMF</label>
										</div>
		</div>
		<div class="col-sm-6">
					<div class="checkbox">
											<input type="checkbox" name="checkbox" id="w_tax"><label for="w_tax">W/TAX</label>
										</div>
										<div class="checkbox">
											<input type="checkbox" name="checkbox" id="uniform"><label for="uniform">Uniform</label>
										</div>
										<div class="checkbox">
											<input type="checkbox" name="checkbox" id="meals"><label for="meals">Meals</label>
										</div>
		</div>
	</div>									
</div>

<hr> -->
			  		<div class="total-amount">
			  			<h3>P <span id="payroll-amt"> - </span></h3>
			  			<small>Payroll Amount</small>
			  		</div>

			  </div>	 
			</div>
		</div>		
		<div class="col-md-10">
			<div class="content-title">
				<h3>Employee List</h3>
			</div>	
			
			<div class="table-content" >
				
			</div>
			

		</div>
	</div>

</div>
	
<script>
$(function(){

	var app = {
		init:function(){
			$('#date_from').date_from();
			$('#date_to').date_to();
			this.bindEvents();
		},bindEvents:function(){
			$('#search').on('click',this.search);
		},
		search:function(){
			$('#search').find('span').addClass('fa-spin fa-spinner');
			$('#search').addClass('disabled');


			var check_list = new Array();
			$('input[name="checkbox"]:checked').each(function(i,val){
				check_list.push($(val).attr('id'));
			});

			$post = {
				date_from : $('#date_from').val(),
				date_to : $('#date_to').val(),
				dept_code  : $('#dept option:selected').val(),
				checkbox : check_list,
			};

			$('.table-content').content_loader('true');
			$.post('<?php echo base_url().index_page();?>/hr/get_payroll',$post,function(response){	

				$('.table-content').html(response.table);
				$('#payroll-amt').html(response.payroll_amt);
				$('.myTable').dataTable(datatable_option_scroll);
				$('.data-scroll').niceScroll();
				$('#search').find('span').removeClass('fa-spin fa-spinner');
				$('#search').removeClass('disabled');

			},'json');
		}
	};

	app.init();

});
	
</script>


