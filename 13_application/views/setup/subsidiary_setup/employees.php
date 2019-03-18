
<div class="container">
	
	<table class="table">
		<tbody>
			<tr>
				<td style="width:130px">FirstName:</td>
				<td><input type="text" class="form-control required uppercase" id="firstname"></td>
			</tr>
			<tr>
				<td>MiddleName:</td>
				<td><input type="text" class="form-control uppercase" id="middlename"></td>
			</tr>
			<tr>
				<td>LastName:</td>
				<td><input type="text" class="form-control uppercase required" id="lastname"></td>
			</tr>
			<tr>
				<td>Position:</td>
				<td><input type="text" class="form-control uppercase" id="position"></td>
			</tr>
			<tr>
				<td>Gender:</td>
				<td>
					<select name="" id="gender" class="form-control">
						<option value="MALE">MALE</option>
						<option value="FEMALE">FEMALE</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<button class="btn btn-primary" id="save">Save</button>
				</td>
			</tr>
		</tbody>
	</table>

</div>

<script>
	$(function(){

		var app = {
			init:function(){
				this.bindEvent();
			},
			bindEvent:function(){
				$('#save').on('click',this.save);
			},save:function(){
				if($('.required').required()){
					return false;
				}

				var bool = confirm('Are you sure?');
				if(!bool){
					return false;
				}

				$post = {						
						FirstName_Empl  : $('#firstname').val(),
						MiddleName_Empl : $('#middlename').val(),
						LastName_Empl   : $('#lastname').val(),
						Position_Empl   : $('#position').val(),
						gender          : $('#gender option:selected').val(),
						type            : $('#type option:selected').val(),
					
				};

				$.post('<?php echo base_url().index_page();?>/setup/subsidiary_setup/save_type',$post,function(response){
					switch($.trim(response)){
						case "1":
							alert('Successfully Added');
							location.reload('true');
						break;
					}
				});

			}
		}

		app.init();
	});
</script>