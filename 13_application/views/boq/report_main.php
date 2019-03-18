<div class="content-page">
	<div class="content">		
		<div class="header">
			<h2>BOQ Monitoring</h2>
		</div>		
		<input type="hidden" id="project_id" value="<?php echo $project_id; ?>">		
		<div class="panel panel-default">		
		  <div class="panel-body">
			<div class="row">
				<div class="col-xs-3">
			  		<select name="" id="main-category" >
			  			<?php foreach($project_category as $row): ?>
				  			<option value="<?php echo $row['id']; ?>"><?php echo $row['project_name']; ?></option>					  			
			  			<?php endforeach; ?>
			  		</select>
				</div>				
			</div>
		  </div>	 
		</div>
		
		<div class="content-group">
			
		</div>
					
	</div>
</div>

<script>
	$(function(){
		var xhr = "";
		var app = {
			init:function(){
				this.bindEvent();
			},bindEvent:function(){				
				$('#main-category').on('change',this.change_content);
				$('#main-category').trigger('change');
				$('body').on('click','.item-history',this.item_history);
			},change_content:function(){				
				var me  = $(this);					
				me.after('<span style="margin-left:5px"><i class="fa fa-spinner fa-spin"></i></span>');

				$post = {
					type       : $('#main-category option:selected').val(),
					project_id : $('#project_id').val(),
					type_name  : $('#main-category option:selected').text(),
				}

		        if(xhr && xhr.readystate != 4){
		            xhr.abort();
		        }
		        
				xhr = $.post('<?php echo base_url().index_page();?>/boq/get_report_details',$post,function(response){
					$('.content-group').html(response);
					$("#main-category + span").remove();
				});
			},item_history:function(){
				var me = $(this);
				var img = "<img src='<?php echo base_url()?>/asset/img/loading.gif'> Loading...";
				$( "#dialog" ).html(img);
				$( "#dialog" ).dialog({modal : true,width:500});
				$post = {
					item_no    : me.attr('data-itemno'),
					project_id : $('#project_id').val(),
				};
				$.post('<?php echo base_url().index_page();?>/boq/item_history',$post,function(response){				
					$("#dialog").dialog({ title: response.title });
					$("#dialog").html(response.table);
				},'json').error(function(x,e){
					alert("Error Code: "+ x.status);
				});

			}

		}

		app.init();
	});
</script>