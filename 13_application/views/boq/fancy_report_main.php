
<div class="container">		
		<div class="header">
			<h2><?php echo $project_site['project_name'].', '.$project_site['project_location']; ?> <small>BOQ Monitoring</small></h2>
		</div>
		
		<input type="hidden" id="project_id" value="<?php echo $project_id; ?>">
		
		<div class="panel panel-default">		
		  <div class="panel-body">
			<div class="row">
				<div class="col-xs-3">
			  		<select name="" id="main-category1" >
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

<script>
	$(function(){
		var app = {
			
			init:function(){
				this.bindEvent();
			},bindEvent:function(){				
				$('#main-category1').on('change',this.change_content);
				$('#main-category1').trigger('change');
			},change_content:function(){				
				var me  = $(this);					
				me.after('<span style="margin-left:5px"><i class="fa fa-spinner fa-spin"></i></span>');

				$post = {
					type       : $('#main-category1 option:selected').val(),
					project_id : $('#project_id').val(),
					type_name  : $('#main-category1 option:selected').text(),
				}

				$.post('<?php echo base_url().index_page();?>/boq/get_report_details',$post,function(response){					
					$('.content-group').html(response);					
					$("#main-category1 + span").remove();
				});
			}

		}

		app.init();
	});
</script>