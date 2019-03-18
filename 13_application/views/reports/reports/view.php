
	
	<section id="sidebar">
		
		<div class="row">
			<div class="col-md-12">
				<div class="content-title" style="margin-left:15px">
					<h3>Archive</h3>
				</div>
					
				<ul class="sidebar-ul">
						<?php echo $sidebar; ?>
				</ul>
			</div>
		</div>	
	</section>
		
	<section class="page-content-wrapper">

		<div id="content">		
			<div class="container">
				<div class="content-title">
					<h3>Report List</h3>
				</div>
				
				<?php /*if($this->extra->is_admin()): */?>
					<a href="<?php echo base_url().index_page(); ?>/reports/create" id="create" class="btn btn-primary pull-right btn-sm" style="margin-top:10px">Create Report</a>	
				<?php /*endif; */ ?>
				<!--TABLE-->
				<?php echo $table; ?>
							
			</div>
		</div>
				
	</section>
	
<script>
$(function(){
	var app = {
		init:function(){
			this.bindEvent();			
		},
		bindEvent:function(){

			$('body').on('click','.viewpdf',this.fancy);
			$('.sidebar-ul li').on('click',this.selected);
			$('#create').on('click',this.create);

		},
		get_data:function(date){

			$post = {
				'date':date,
			};

			$('.table-content').content_loader('true');	
			$.post('<?php echo base_url().index_page();?>/reports/get_data',$post,function(response){
				$('.table-content').html(response);
			});

		},
		fancy:function(){

			var id = $(this).closest('tr').find('td.id').text();
			$.fancybox.showLoading();

			$post = {
				id : id,
			};

			$.post('<?php echo base_url().index_page(); ?>/reports/view_pdf',$post,function(response){
				$.fancybox(response,{
					width     : 1200,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				});
			});

		},
		selected:function(){
			$('.sidebar-ul li').removeClass('selected-date');
			$(this).addClass('selected-date');
			var date = $(this).find('a').text();
			app.get_data(date);
		},
		create:function(){	
			$.fancybox.showLoading();
			$.post('<?php echo base_url().index_page();?>/reports/create',function(response){
				$.fancybox(response,{
					width     : 1000,
					height    : 550,
					fitToView : false,
					autoSize  : false,
				});
			});	
		}
		
	};		
		app.init();
	});
	
</script>











