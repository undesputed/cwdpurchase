<style>
	.t1{
		display:block;
		font-size:11px;
	}
	.table-boq-report thead th{
		text-align: center;
		font-size:20px;
	}
	.table-boq-report tbody td{
		vertical-align: middle !important;
		text-align: center;
	}
	.number{
		font-size:15px;
	}

	.table-boq-report td{
		border : 1px solid #ccc;
	}
</style>

<div class="content-page">
	<div class="content">
		
		<div class="header">
			<h2>BOQ PROJECTS</h2>
		</div>
		<div class="row">
			<div class="col-md-12">
				<span class="control-item-group">
					<!-- <a href="<?php echo base_url().index_page(); ?>/print/project/" target="_blank" class="action-status cancel-event">Print</a> -->
				</span>	
			</div>
		</div>
			<table class="table table-boq-report">
				<thead>
					<tr>
						<th style="text-align:left;">
							<select name="" id="project" style="width:100%;font-size:13px;text-align:left">
					  			<?php foreach($projects as $row): ?>
						  			<option value="<?php echo $row->project_id; ?>"><?php echo $row->project_name.' - '.$row->project_location; ?></option>					  			
					  			<?php endforeach; ?>
					  		</select>
						</th>
						<th style="text-align:left;">
							<select name="" id="category" style="width:100%;font-size:13px;text-align:left">
					  			<?php foreach($project_category as $row): ?>
						  			<option value="<?php echo $row['id']; ?>"><?php echo $row['project_name']; ?></option>					  			
					  			<?php endforeach; ?>
					  		</select>
						</th>
						<th>
							<button id="search_boq_project" class="btn btn-primary">Search BOQ Projects</button>
						</th>
						<th>
							<button id="export_to_excel" class="btn btn-primary" style="display:none;">Export to Excel</button>
						</th>
					</tr>
				</thead>
			</table>

			<div id="boq_project_content">

			</div>

	</div>
</div>

<script>
$(document).ready(function(){
	var xhr = "";

	$('#search_boq_project').on('click',function(){
		$post = {
			project : $('#project option:selected').val(),
			category : $('#category option:selected').val()
		}

		if(xhr && xhr.readystate != 4){
        	xhr.abort();
    	}

    	$.fancybox.showLoading();
    	xhr = $.post('<?php echo base_url().index_page();?>/boq/report_new',$post,function(response){
    			if($.trim(response) != ''){
    				$.fancybox.hideLoading();
    				$('#export_to_excel').removeAttr('style');
    				$('#boq_project_content').html(response);
    			}
    			/*$.fancybox(response,{
					width     : "100%",
					height    : 530,
					fitToView : false,
					autoSize  : false,
				});*/
    		
    	});
		
	});

	$('body').on('click','.boq_details',function(){
		$post = {
			boq_id : $(this).attr('data-id')
		}

		if(xhr && xhr.readystate != 4){
        	xhr.abort();
    	}

    	$.fancybox.showLoading();
    	xhr = $.post('<?php echo base_url().index_page();?>/boq/boqdetails',$post,function(response){
    		$.fancybox(response,{
				width     : "80%",
				height    : 530,
				fitToView : false,
				autoSize  : false,
			});
    	});
	});

	$('#export_to_excel').on('click',function(){
		$post = {
			project : $('#project option:selected').val(),
			category : $('#category option:selected').val()
		}

		document.location.href = "<?php echo base_url().index_page();?>/boq/run_export?project="+$post.project+"&category="+$post.category;

	});
});
</script>