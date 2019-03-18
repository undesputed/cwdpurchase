<?php 
$approved = (isset($approved))? $approved : "";
$disabled['approved'] = ""; 
$disabled['li'] = "";
$edit_url = (isset($edit_url))? $edit_url : '';
$cum_status['value'] = 'Active';
$cum_status['icon'] = 'fa-eye';

$type = (isset($type)) ? $type: '' ;
if(isset($status)){
	if(isset($status) && strtoupper($status)=='ACTIVE'){
		$cum_status['value'] = 'Cancel';
		$cum_status['icon'] = 'fa-eye-slash';
		
	}else{
		$cum_status['value'] = 'Active';
		$cum_status['icon'] = 'fa-eye';
		$disabled['li'] = "disabled";
	}
}
?>

<div class="container saving">	
<?php if($approved == true):  $disabled['approved'] ='disabled';?>
	<div class="alert alert-success"><i class="fa fa-check"></i> <strong>Approved</strong></div>
<?php endif; ?>

<div class="after-save">
<?php if(!empty($disabled['li'])): ?>
	<div class="alert alert-info"><i class="fa fa-info-circle"></i> Status : <strong>Cancel</strong></div>
<?php endif; ?>
</div>

<input type="hidden" value="<?php echo $type; ?>" id="cum_type">
<input type="hidden" value="<?php echo $id; ?>" id="cum_id">
<input type="hidden" value="<?php echo $cum_status['value']; ?>" id="hdn_status">
<div class="panel panel-default">		
  <div class="panel-body">

  	<div class="row">
  		<div class="col-md-10">
  			<h5>Transaction Details</h5>
  		</div>
  		<div class="col-md-2">
					<div class="btn-group pull-right">
						  <button type="button" class="btn btn-default dropdown-toggle <?php echo $disabled['approved'] ?>" data-toggle="dropdown">
						    <i class="fa fa-gear"></i>
						  </button>
						  <ul class="dropdown-menu" role="menu">
						  	<!-- <li class="<?php echo $disabled['li']; ?>"><a href="javascript:void(0)" id="cum_approved"><i class="fa fa-check"></i> Approved</a></li>
						  	<li class="divider"></li> -->
						    <li class="<?php echo $disabled['li']; ?>"><a href="javascript:void(0)" id="cum_edit"><i class="fa fa-edit"></i> Modify</a></li>
						   <!--  <li><a href="javascript:void(0)" id="cum_status" ><i class="fa <?php echo $cum_status['icon'] ?>"></i> <?php echo $cum_status['value'] ?></a></li>					     -->
						  </ul>
					</div>
  		</div>
  	</div>
  </div>
  <?php echo $table; ?>	 
</div>

</div>

<script>
    var after_save = '<div class="alert alert-success"><i class="fa fa-check"></i> <strong>Approved</strong></div>';
    var after_status = function( args ){
    	return "<div class=\"alert alert-info\"><i class=\"fa fa-info-circle\"></i> Status : <strong>"+args+"</strong></div>";	
    }
    var edit_url = '<?php echo $edit_url; ?>'
	var app_cum = {
		init:function(){
			this.bindEvent();
		},bindEvent:function(){
			$('#cum_approved').on('click',this.approved);
			$('#cum_status').on('click',this.cum_status);
			$('#cum_edit').on('click',this.edit);
		},approved:function(){

			if($(this).parent().hasClass('disabled')===true){
				return false;
			}

			$.save({
				appendTo : '.saving',
				action   : 'show',
				loading  : 'Processing...',		
			});

			$post = {
				type : $('#cum_type').val(),
				id   : $('#cum_id').val(),
			}

			$.post('<?php echo base_url().index_page(); ?>/ajax/approved',$post,function(response){
				$.save({action:'success'});
				app.get_cumulative();
				$('.after-save').html(after_save);				
			}).error(function(){
				$.save({action : 'error'})
			});

		},cum_status:function(){

			if($(this).parent().hasClass('disabled')===true){
				return false;
			}

			$.save({
				appendTo : '.saving',
				action   : 'show',
				loading  : 'Processing...',				
			});
			$post = {
				type   : $('#cum_type').val(),
				id     : $('#cum_id').val(),
				status : $('#hdn_status').val(),
			}				
			$.post('<?php echo base_url().index_page(); ?>/ajax/changeStatus',$post,function(response){
				$.save({action:'success'});
				app.get_cumulative();					
				$('.after-save').html(after_status(response));
			}).error(function(){
				$.save({action : 'error'})
			});

		},edit : function(){
			if($(this).parent().hasClass('disabled')===true){
				return false;
			}

			$post = {
				po_id : $('#cum_id').val(),
			};
			$.save({
					appendTo : '.saving',
					action   : 'show',
					loading  : 'Processing...',				
			});

			$.post('<?php echo base_url().index_page();?>/'+edit_url,$post,function(response){

				$.save({action:'hide'});
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				});

			}).error(function(){
				$.save({action : 'error'});
			});			
		}
	};

$(function(){
	app_cum.init();
});

</script>