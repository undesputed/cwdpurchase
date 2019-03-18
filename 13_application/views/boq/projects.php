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
						<th>
							<select name="" id="main-category" style="width:70%;font-size:13px;text-align:left">
					  			<?php foreach($project_category as $row): ?>
						  			<option value="<?php echo $row['id']; ?>"><?php echo $row['project_name']; ?></option>					  			
					  			<?php endforeach; ?>
					  		</select>
						</th>
						<th>BOQ</th>
						<th>ACTUAL</th>
						<th>REMAINING</th>
						<th>DISCREPANCY</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>

	</div>
</div>

<script>
$(function(){
	var xhr = "";
	var app = {
		init:function(){
			this.bindEvents();
		},bindEvents:function(){
			$('#main-category').on('change',function(){
				var me  = $(this);					
				me.after('<span style="margin-left:5px"><i class="fa fa-spinner fa-spin"></i></span>');
				$post = {
					type_id : $('#main-category option:selected').val(),
					type_name : $('#main-category option:selected').text(),
				}

				if(xhr && xhr.readystate != 4){
	            	xhr.abort();
	        	}

				xhr = $.post('<?php echo base_url().index_page();?>/boq/get_project',$post,function(response){
					$('.table-boq-report tbody').html(response);
					$("#main-category + span").remove();
				}).error(function(x,e){
					console.log(x.status);
					$("#main-category + span").remove();
				});
				
			});

			$('#main-category').trigger('change');

			$('body').on('click','.boq-project',this.project_details);

			$('body').on('click','.item-history',this.item_history);
					
		},project_details:function(){
			var me = $(this);
			var id = me.attr('data-project-id');
	        if(xhr && xhr.readystate != 4){
	            xhr.abort();
	        }

	        $.fancybox.showLoading();
			xhr = $.post('<?php echo base_url().index_page();?>/boq/report2/'+id,function(response){

				$.fancybox(response,{
					width     : "100%",
					height    : 530,
					fitToView : false,
					autoSize  : false,
				});
			});

		},item_history:function(){
			var me = $(this);
			var img = "<img src='<?php echo base_url()?>/asset/img/loading.gif'> Loading...";
			$( "#dialog" ).html(img);
			$( "#dialog" ).dialog({modal : true,width:900});
			$post = {
				item_no    : me.attr('data-itemno'),
				project_id : $('#project_id').val(),
			};
			$.post('<?php echo base_url().index_page();?>/boq/item_history',$post,function(response){				
				$("#dialog").dialog({ title: response.title });
				$("#dialog").html(response.table);
			},'json');

		}
	}

	app.init();

});


</script>