	<style type="text/css">
	#c1List table tbody tr:hover{
		text-decoration: underline;
		cursor: pointer;
	}
</style>
<div class="header">
	<h2>Transaction Inventory Entry</h2>	
</div>

	<div class="container">

			<div class="form-horizontal" style="display:none;">
				<div class="row">
					<div class="control-group">
						<label class="control-label">Project: </label>
						<div class="controls">
							<select class="col-md-12" name="cmbMainProject_cumulative">
								
							</select>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="control-group">
						<label class="control-label">Profit Center: </label>
						<div class="controls">
							<select class="col-md-2" name="txtprojno" readonly>		
							</select>
							<select class="col-md-10" name="cmbprojectlocation">		
							</select>
						</div>
					</div>
				</div>
			</div>

			<div class="content-title">
				<h3>Item Type:</h3>
			</div>

			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">		
			
							<select name="cmbItemType" class="form-control col-md-3 input-sm"><?php echo $cmbItemType; ?></select>
				

						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="oTable-result" id="c1List">

								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row" style="margin-top:1em;display:none;" id="DOMS_here">
				<div class="col-md-12">
					<form id="myForm">

						<div class="row" style="display:none;">
							<!-- ninja fields -->
							<select name="cmbaccount"><?php echo $cmbaccount; ?></select>
							<select name="cmbdivision"></select>
							<input name="txtItemNo">
							<input name="txtitemcode">
							<input name="nudRecievedQty">
							<input name="txtMeasurement">
							<input name="txtpropertyno">
							<input name="txtreceipt">
						</div>

						<div class="row">
							<div class="col-md-12">
								<fieldset>
									<legend>Transaction</legend>
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6" id="DOMS_here_left"></div>
											<div class="col-md-6" id="DOMS_here_right"></div>
										</div>
									</div>
									
								</fieldset>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<input type='submit' class="btn btn-primary" value="Save">
							</div>
						</div>

					</form>
				</div>
			</div>

	</div>


<script type="text/javascript">
(function($){
	var cmbdivision=new Object();

	var app = {
		init:function(){
			app.bindEvents();
			cmbdivision = eval(<?php echo $cmbdivision; ?>);
			app.cmbItemType();
			app.cmbdivision();
			$('[name="cmbItemType"]').chosen();
			$('[type="submit"]').generate_preload();
		},cmbdivision:function(){
			var options=new Array();
			for(var i=0,length=cmbdivision.length; i<length; i+=1){
				if(cmbdivision[i]['title_id']==$('[name="cmbMainProject_cumulative"]').val()){
					options.push(cmbdivision[i]);
				}
			}
			$('[name="cmbdivision"]').generate_option({data : options,
														text : "division_name",
														val : "division_code"});
		},bindEvents:function(){
			$('[name="cmbItemType"]').on('change',this.cmbItemType);
			$('body').on('click','#c1List tbody tr',this.tr_click);
			$('#myForm').bind('submit',this.myForm);
			$('body').on('change','[name="dtpdateofpurchase"]',this.dtpdateofpurchase);
		},dtpdateofpurchase:function(){
			$.post('<?php echo base_url().index_page()?>/setup/inventory_entry/dtpdateofpurchase',{dtpdateofpurchase:$('[name="dtpdateofpurchase"]').val()},function(data){
				$('[name="txtpropertyno"]').val(data.txtpropertyno);
				$('[name="txtreceipt"]').val(data.txtreceipt);
			},'json');
		},myForm:function(e){
			e.preventDefault();
			var form = $('#myForm').serialize();
			var obj=JSON.stringify({
					"cmbprojectlocation" 		:  $('[name="cmbprojectlocation"]').val(),
					"cmbMainProject_cumulative" :  $('[name="cmbMainProject_cumulative"]').val(),
					"cmbItemType_SelectedIndex" :  $('[name="cmbItemType"] option:selected').index(),
					"cmbequipmenttype_text"		:  $('[name="cmbequipmenttype"] option:selected').text(),
					"cmbfueltype_text"			:  $('[name="cmbfueltype"] option:selected').text()
					});
			$.post('<?php echo base_url().index_page()?>/setup/inventory_entry/btnSave_Click',{form:form,obj:obj},function(data){
				if(data=='true'){
					if(confirm("Successfully Saved."))
						location.reload(true);
				}else{
					alert('Error Saving..');
				}
			});
		},tr_click:function(){
			var obj = $(this).generate_object();

			if($('[name="cmbModel"]').length > 0){
				$.post('<?php echo base_url().index_page()?>/setup/inventory_entry/cmbModel',{Item_No:obj['Item_No.']},function(data){
					$('[name="cmbModel"]').html(data);
				});
			}

			var data = $.map(obj,function(v){ return v;});
	        $('[name="txtItemNo"]').val(data[0]);/*ninja*/
	        $('[name="txtequipmentname"]').val(data[1]);
	        $('[name="nudRecievedQty"]').val(data[2]);/*ninja*/
	        $('[name="txtunitcost"]').val(data[3]);
	        $('[name="txtMeasurement"]').val(data[4]);/*ninja*/	
	        $.post('<?php echo base_url().index_page()?>/setup/inventory_entry/txtitemcode',{data:data[0]},function(data){
	        	$('[name="txtitemcode"]').val(data);/*ninja*/
	        });
	        

			$.fancybox({
					  // fancybox API options
					  href: "#DOMS_here",
					  fitToView: false,									  
					  width: 1200,
					  height: 550,
					  autoSize: false
					}); 
			return true;
		},cmbItemType:function(){
			$('#c1List').content_loader('true');
			$.post('<?php echo base_url().index_page()?>/setup/inventory_entry/c1display',{cmbItemType:$('[name="cmbItemType"]').val()},function(data){
 				$('#c1List').html(data.table);
				$('#c1List table').dataTable(datatable_option);
			},'json');

			var hide_DOM=new Array();
	        if ($('[name="cmbItemType"] option:selected').text() == "FUEL SUPPLY" || $('[name="cmbItemType"] option:selected').text() == "SUPPLIES,MATERIALS,SPAREPARTS AND LUBRICANTS"){
	            hide_DOM.push('txtbrand');
	            hide_DOM.push('txtmadein');
	            hide_DOM.push('txtengineno');
	            hide_DOM.push('txtchassisno');
	            hide_DOM.push('txtlife');
	            hide_DOM.push('txtproghrs');
	            hide_DOM.push('txttruckfactor');
	            hide_DOM.push('txtyear');
	            hide_DOM.push('cmbModel');
	            hide_DOM.push('txtSMR');
	     	}else if($('[name="cmbItemType"] option:selected').text() == "TIRE SUPPLY"){
	            hide_DOM.push('txtqty');
	            hide_DOM.push('txtengineno');
	            hide_DOM.push('txtchassisno');
	            hide_DOM.push('cmbModel');
	            hide_DOM.push('txtSMR');
	            hide_DOM.push('cmbequipmenttype');
	            hide_DOM.push('cmbfueltype');
	        }else{
	            hide_DOM.push('txtqty');
	        }
	        
	        if($('[name="cmbItemType"] option:selected').text() == "SUPPLIES,MATERIALS,SPAREPARTS AND LUBRICANTS"){
	            hide_DOM.push('cmbfueltype');
	            hide_DOM.push('cmbequipmenttype');
	            hide_DOM.push('txtfulltank');
	        }

			cmbItemType_SelectedIndexChanged(hide_DOM);
			return true;
		}
	};

	$(document).ready(function(){
		var options={profit_center:$('[name="cmbprojectlocation"]'),
					txtprojno:$('[name="txtprojno"]'),
					call_back:function(){app.init()}};
		$('[name="cmbMainProject_cumulative"]').get_projects(options);
	});


	function cmbItemType_SelectedIndexChanged(obj){
		var DOMS=new Object();
		var place_switcher =0;
		$('#DOMS_here_left, #DOMS_here_right').html('');
		DOMS=eval(<?php echo $DOMS;?>);

		for(var i=0,length=DOMS.length; i<length; i+=1){
			var temp = Object.keys(DOMS[i]);
			if(obj.indexOf(temp[0]) !== -1)
				continue;

			var data=new Array();
			data = $.map(DOMS[i],function(v){return v;});
			var _id = (place_switcher%2==0) ? "DOMS_here_left" : "DOMS_here_right";
			$('#'+_id+'').append(data[0]);
			place_switcher++;
		}

		$('[name="dtpdateofpurchase"]').date();
		$('[name="cmbSupplier"]').html("<?php echo $cmbSupplier; ?>");
		$('[name="cmbequipmenttype"]').html("<?php echo $cmbequipmenttype; ?>");
		$('[name="cmbdriver"]').html("<?php echo $cmbdriver; ?>");
		$('[name="cmbfueltype"]').html("<?php echo $cmbfueltype; ?>");
		app.dtpdateofpurchase();
		$('#DOMS_here input[type="text"]').prop('required',true);

		return true;
	}

}(jQuery));
</script>
