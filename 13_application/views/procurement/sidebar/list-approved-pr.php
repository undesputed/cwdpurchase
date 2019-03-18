<?php 
	
	$date = array();
	$data = array();
	foreach($result as $row){
		$d = $this->extra->format_date($row['purchaseDate']);
		$data[$d][] = $row;
	}

?>

<div class="panel panel-default">
  <div class="panel-heading">List of Approved Purchase Request</div>		
  <div class="panel-body">
  		<ul class="sidebar">
			<?php foreach($data as $key=>$row): ?>
			<li>
				<span><?php echo $key; ?></span>
				<ul>
				<?php foreach($row as $row1): ?>
					<li>
						<a href="<?php echo base_url().index_page(); ?>/transaction/purchase_request/<?php echo $row1['pr_id'] ?>"><?php echo $row1['purchaseNo'] ?></a>

						<?php if($row1['status'] == 'APPROVED'): ?>
							<?php if($row1['uid']==0): ?>								
								<div class="pull-right"><a href="<?php echo base_url().index_page(); ?>/transaction/canvass/<?php echo $row1['pr_id'] ?>">Create Canvass</a></div>
							<?php else: ?>
								<span class="label label-success pull-right">Already PO</span>
							<?php endif; ?>
						<?php else: ?>
								<span class="pull-right"><?php echo $this->extra->label($row1['status']); ?></span>
						<?php endif; ?>
					</li>							
				<?php endforeach;?>
				</ul>
			</li>
			<?php endforeach; ?>
  		</ul>
  </div>
</div>

<script>
	$(function(){
		var sidebar_pr = {
			init:function(){
				this.bindEvent();
			},
			bindEvent:function(){
				$('.sidebar-closing').on('click',this.closing);
			},
			closing:function(){

				var bool = confirm('Are you sure to Close this PR');
				if(!bool){
					return false;
				}

				$post = {
					pr_id : $(this).attr('data-id'),
				};
				
				$.post('<?php echo base_url().index_page();?>/procurement/purchase_request/closing',$post,function(response){

				});

			}
		}

		sidebar_pr.init();
		
	});
</script>