<div class="header">
	<h2>Stock Receiving</h2>
</div>

<div class="container">
	
	<div class="row">
		<div class="col-md-3">
			<div class="content-title">
				<h3>Project Information</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">
			  		<div class="form-group">
			  			<div class="control-label">Project</div>
			  			<select name="" id="project" class="form-control input-sm"></select>
			  		</div>

			  		<div class="form-group">
			  			<div class="control-label">Profit Center</div>
			  			<select name="" id="profit_center" class="form-control input-sm"></select>
			  		</div>

			  		<div class="form-group">
			  			<div class="control-label">Address</div>
			  			<input type='text' name="" id="location" class="form-control input-sm">
			  		</div>
			  		<input type='text' name="" id="to" class="form-control input-sm" style="display:none">
			  </div>	 
			</div>

			<div class="panel panel-default">		
			  <div class="panel-body">
			  		<div class="form-group">
			  			<div class="control-label">Item Type</div>
			  			<select name="" id="itemType" class="form-control input-sm">
			  				<?php foreach($itemType as $row): ?>
			  					<option value="<?php echo $row['id'] ?>"><?php echo $row['type'] ?></option>
			  				<?php endforeach; ?>
			  			</select>
			  		</div>
			  </div>	 
			</div>
		</div>

		<div class="col-md-9">
			<div class="content-title">
				<h3>Item List</h3>
			</div>
			<div class="panel panel-default">		
			  <div class="panel-body">	
			  		
			  </div>	
			  <div class="item-content">
				  <table class="table-itemlist table table-striped">
				  	<thead>
				  		<tr>
				  			<th>Item Description</th>
				  			<th>Quantity</th>
				  			<th>Unit Cost </th>			  			
				  			<th>Unit Measure</th>
				  		</tr>
				  	</thead>
				  	<tbody>
				  		
				  	</tbody>
				  </table>
			  </div> 
			</div>
		</div>
	</div>	
</div>

<script type="text/javascript">
	
	var app = {
		init:function(){
			
			var option = {
				profit_center : $('#profit_center')
			}			
			$('#project').get_projects(option);			
			
			this.bindEvents();
			this.get_item();
	
		},bindEvents:function(){			

			$('#profit_center').on('change',this.location);
			$('#itemType').on('change',this.get_item);
			$('body').on('click','.receive',this.receive);

		},location:function(){
			
			$('#location').val($('#profit_center option:selected').data('location'));
			$('#to').val($('#profit_center option:selected').data('to'));

		},get_item:function(){
			$('.item-content').content_loader('true');
			$post = {
				itemType : $('#itemType option:selected').val(),
				location : $('#profit_center option:selected').val(),
			}

			$.post('<?php echo base_url().index_page();?>/procurement/stock_receiving/get_item',$post,function(response){
				$('.item-content').html(response);
				$('.myTable').dataTable(datatable_option);
			});

		},receive:function(){
			var tr = $(this).closest('tr');
			$.fancybox.showLoading();

			$post = {
				id       : tr.find('td.item_no').text(),
				itemType : $('#itemType option:selected').val(),
				location : $('#profit_center option:selected').val(),
				project  : $('#project option:selected').val(),
			};

			$.post('<?php echo base_url().index_page();?>/procurement/stock_receiving/assign_item',$post,function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				})
			});
			
		}
	}

$(function(){
	app.init();
});
</script>