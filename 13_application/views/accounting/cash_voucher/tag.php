
<section>
	<h4>Transaction List</h4>
	<select name="" id="tag_transaction" class="form-control" style="width:190px" multiple="">
					<option value="">-NONE-</option>
						<?php foreach($po_list as $row): ?>
					<option value="" data-po_id="<?php echo $row['po_id']; ?>" data-rr_id="<?php echo $row['receipt_id']; ?>"><?php echo "PO ".$row['reference_no']." - DR/SI ".$row['supplier_invoice']; ?></option>
				<?php endforeach; ?>					
	</select>	

	<button class='btn btn-primary' id="tag_item">Tag</button>

</section>

<script>




	$(function(){

		jQuery.fn.extend({
	        selectedAsJSON: function(){
	            var result = [];
	            $('option:selected', this).each(function(){
	                result.push($(this).data());
	            })
	            return result;
	        }
	    });

		var xhr2 = "";
		$('#tag_transaction').chosen({
			placeholder_text_single: "Select PO",
		});	
		
		$('#tag_item').on('click',function(){			
			$post = {				
				voucher_id : '<?php echo $voucher_id;?>',
				 details   : $('#tag_transaction').selectedAsJSON(),
			}


			if($post.rr_id ==""){
				alert('Please select PO No.');
				return false;
			}

	        if(xhr2 && xhr.readystate != 4){
	            xhr2.abort();
	        }
	        	        
	     	xhr2 = $.post('<?php echo site_url('accounting/do_tag')?>',$post,function(response){
	        	if($.trim(response == '1')){
	        		alert('Successfully Tag');
	        		$.fancybox.close();	   
	        		updateContent();     		
	        	}
	        });


		});
			

	});
</script>