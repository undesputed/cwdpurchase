<style>
#imagePreview {
    width: 180px;
    height: 180px;
    background-position: center center;
    background-size: cover;
    -webkit-box-shadow: 0 0 1px 1px rgba(0, 0, 0, .3);
    display: inline-block;    
}
</style>
<?php 		
		$hide = true;
		if(empty($result['path'])){
			$result['path'] = '';
			$hide = false;
		} 

?>
<div class="container">
<h4>Add Img Signatory</h4>
<div id="imagePreview" style="background-image:url('<?php echo $result['path']; ?>')"></div>
<form name="form" action="" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="emp_id" value="<?php echo $id; ?>">
	<small><i class="text-danger">*upload .Png file</i></small>
	<input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input">	
</form>

<div class="btn-group">
	<?php if($hide): ?>
	<button class="btn btn-danger"  id="remove_upload" style="margin-top:5px">Remove</button>
	<?php endif; ?>
	<button class="btn btn-primary" id="run_upload"    style="margin-top:5px">Upload Signature</button>
</div>

<script>
$(function(){
	var xhr = "";
	var upload = {
		init:function(){
			this.bindEvent();
		},bindEvent:function(){			
			  $("#fileToUpload").on("change",this.imagePreview);
			  $('#run_upload').on("click",this.run_upload);
			  $('#remove_upload').on("click",this.remove_upload);
		},imagePreview:function(){
				var files = !!this.files ? this.files : [];
		        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
		 
		        if (/^image/.test( files[0].type)){ // only image file
		            var reader = new FileReader(); // instance of the FileReader
		            reader.readAsDataURL(files[0]); // read the local file		 
		            reader.onloadend = function(){ // set image data as background of div
		                $("#imagePreview").css("background-image", "url("+this.result+")");
		            }
		        }
		},run_upload:function(){
			var me = $(this);
			me.html('Uploading...');
			me.addClass('disabled');
			$.ajaxFileUpload
				(
					{
						url:'<?php echo base_url().index_page(); ?>/setup/employee_setup/upload_img/<?php echo $id; ?>',
						secureuri:true,
						fileElementId:'fileToUpload',
						dataType: 'json',
						data:{name:'logan', id:'id'},
						success: function (data, status)
						{							
							if(typeof(data.error) != 'undefined')
							{
								if(data.error != '')
								{
									alert(data.error);
									me.removeClass('disabled');
									me.html('Upload Signature');
								}else
								{
									alert(data.msg);
									me.removeClass('disabled');
									me.html('Upload Signature');
									$.fancybox.close();			

									app_sub_classification.get_classification_setup();

								}
							}
						},
						error: function (data, status, e)
						{
							alert(e);
						}
					}
				)
				return false;
		},remove_upload :function(){
			var bool = confirm("Are you sure?");			
			if(!bool){
				return false;
			}

			var me = $(this);
			me.html('Removing...');
			me.addClass('disabled');

			$post = {
				id : '<?php echo $id; ?>'
			}

	        if(xhr && xhr.readystate != 4){
	            xhr.abort();
	        }
	        
			xhr = $.post('<?php echo base_url().index_page();?>/setup/employee_setup/remove_signatory',$post,function(response){
					alert('Successfully Remove');					
					app_sub_classification.get_classification_setup();
					$.fancybox.close();
			}).error(function(e){
				alert(e);
				me.removeClass('disabled');
				me.html('Remove');
			});
			
		}		
	}

	upload.init();
  
}); 
</script>
</div>