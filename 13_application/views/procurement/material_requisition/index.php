<div class="header">
	<h2>Material Requisition</h2>	
</div>

<input type="hidden" id="hdn_date">
<div class="container">

<div class="row">
	<div class="col-md-2">
		
	</div>
	<div class="col-md-10">
		<div class="row">
			<div class="col-md-3">
				<div class="content-title">
					<h3>Request List</h3>
				</div>
			</div>
			<div class="col-md-6">
				<div style="margin-top:2em;">
					<div class="radio-inline">
						<input type="radio" name="filter" value="date"> <input type="text" class="date">
					</div>
					<div class="radio-inline">
						<input type="radio" name="filter" value="today" checked id="today"><label for="today">Today</label>
					</div>
					<div class="radio-inline">
						<input type="radio" name="filter" value="all" id="all"><label for="all">All</label>
					</div>
				</div>
			</div>
		</div>

		<section id="request-content">
			
		</section>

	</div>
	
</div>

</div>

<script>
	
	var view_details = function(id){
			$post = {
				id  : id
			}
			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/procurement/material_requisition/get_request_details',$post,function(response){
				$.fancybox(response,{
					width     : 1000,
					height    : 550,
					fitToView : true,
					autoSize  : false,
				})
			});
	}

	$(function(){
		

		var app = {
			init:function(){
				$('.date').date();
				$('#hdn_date').date();
				this.bindEvents();
				this.get_request_list();
			},
			bindEvents:function(){
				$('input[name="filter"]').on('change',this.get_request_list)
				$('.date').on('change',function(){
					if($('input[name="filter"]:checked').val()=='date'){
						app.get_request_list();	
					}
					
				});
			},
			get_request_list:function(){
				var date = $('.date').val();
				var type = "";

				switch($('input[name="filter"]:checked').val()){
					case"today":
						date = $('#hdn_date').val();
						type = 'daily';
					break;

					case"date":
						type = 'daily';
					break;
					case"all":
						date = '-';
						type = 'all';
					break;
				}

				$post = {
					date : date,
					type : type
				}
				$('#request-content').content_loader('true');
				$.post('<?php echo base_url().index_page();?>/procurement/material_requisition/get_material_requisition',$post,function(response){					
					$('#request-content').html(response);
					$('.myTable').dataTable(datatable_option);
				});

			}

		}

		app.init();
	});


</script>