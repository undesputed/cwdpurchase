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
	
	.pointer {
		cursor: pointer;
	}
</style>

<div class="content-page">
	<div class="content">
		
		<div class="header">
			<h2>BOQ View</h2>
		</div>
		<div class="row" style="margin: 10px 0px;">
			<div class="col-md-8" style="padding-left: 0px;">
				<form id="frm-boq" action="" method="post">
					<div class="col-md-6" style="padding: 10px 0 0;">
						<select name="project" class="col-md-12">
							<option value=""> - Select Project - </option>
							<?php foreach ( $projects AS $project ) { ?>
									<option value="<?php echo $project->project_id; ?>" <?php if($this->session->userdata('Proj_Code') == $project->project_id){ ?>selected="selected"<?php } ?>><?php echo $project->project_name; ?> - <?php echo $project->project_location; ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-6" style="padding: 10px 0 0 10px;">
						<select name="project_type" class="col-md-12">
							<option value=""> - Select Project Type - </option>
							<?php foreach ( $project_categories AS $project_type ) { ?>
									<option value="<?php echo $project_type->id; ?>"><?php echo $project_type->project_name; ?></option>
							<?php } ?>
						</select>
					</div>
				</form>
			</div>
			<div class="col-md-4">
				<button id="search_boq_project" class="btn btn-primary">Search BOQ Projects</button>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" id="tbl-boq-main">
				<!-- AJAX Request Result -->
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		var base_url = '<?php echo base_url(); ?>index.php/';
		
		$(document).ready(function(){ 
			ajax_show_boq_table();
			click_process_search_boq_project();
		});
		
		function ajax_show_boq_table() {
			$.post(base_url+'boq/ajax_show_boq_view', $('#frm-boq').serialize(), function(res){ 
				$('#tbl-boq-main').html(res);
			});
		}
		
		function click_process_search_boq_project() { 
			$('body').on('click', '#search_boq_project', function() { 
				ajax_show_boq_table();
			});
		}
	});
</script>