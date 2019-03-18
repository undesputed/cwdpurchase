<div class="content-page">
 <div class="content">

<div class="header">
	<h2>Progress Billing Entry</h2>
</div>

<div class="panel panel-default">		
  <div class="panel-body">
  		<div class="row">
			<div class="col-md-4">
					<div class="form-group">
			  			<div class="control-label">Projects</div>
			  			<select name="" id="profit_center" style="width:100%">			  				
			  				<?php foreach($project as $row): ?>
								<option value="<?php echo $row['project_id']; ?>"><?php echo $row['project_full_name'] ?></option>
			  				<?php endforeach; ?>
			  			</select>
			  		</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
			  			<div class="control-label">Project Type</div>
			  			<select name="" id="profit_center" style="width:100%">			  				
			  				<?php foreach($project_category as $row): ?>
								<option value="<?php echo $row['id']; ?>"><?php echo $row['project_name'] ?></option>
			  				<?php endforeach; ?>
			  			</select>
			  	</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
			  			<div class="control-label">Contract Price </div>
			  				<input type="text" class="form-control" id="contract_price">
			  			</select>
			  	</div>
			</div>
		</div>
  </div>
  <div class="table-responsive">
  <table class="table" id="tbl-billing">
  		<thead>
  			<tr>
  				<th>Billing No</th>
  				<th>%</th>
  				<th>Accomplishment</th>
  				<th>Recoupment</th>
  				<th>10% Retention</th>
          <th>Construction Income</th>
          <th>VAT output Tax</th>
  				<th>Gross</th>
  				<th>EWT</th>
  				<th>NET</th>
  				<th>Date</th>
  				<th>Collection</th>
  				<th></th>
  			</tr>
  		</thead>
  		<tbody>
  			<tr>
  				<td class="editable billing_no"></td>
  				<td class="percent"></td>
  				<td class="editable-accomplishment"></td>
  				<td class="recoupment" data-recoupment="0.15"></td>
  				<td class="retention"></td>
  				<td class="construction_income"></td>
          <td class="vat_output"></td>
          <td class="gross"></td>
  				<td class="ewt"></td>
  				<td class="net"></td>
  				<td class="date"></td>
  				<td class="collection editable"></td>
  				<td><span class="close">&times;</span></td>
  			</tr>
  		</tbody>
  		<tfoot>
  			<tr>
  				<td><a href="javascript:void(0)" id="add_row">add row</a></td>
  				<td></td>
  				<td></td>
  				<td></td>
  				<td></td>
  				<td></td>
          <td></td>
          <td></td>
  				<td></td>
  				<td></td>
  				<td></td>
  				<td></td>
  				<td></td>
  			</tr>
        <tr>
          <td>Total:</td>
          <td class="total_percentage"></td>
          <td class="total_accomplishment"></td>
          <td class="total_recoupment"></td>
          <td class="total_retention"></td>
          <td class="total_const_income"></td>
          <td class="total_vatoutput"></td>
          <td class="total_gross"></td>
          <td class="total_ewt"></td>
          <td class="total_net"></td>
          <td class=""></td>
          <td class="total_collection"></td>
          <td class=""></td>
        </tr>
        <tr>
          <td>Balance:</td>
          <td class="balance_billing"></td>
          <td class="balance_accomplishment"></td>
          <td class="balance_recoupment"></td>
          <td class="balance_retention"></td>
          <td class="balance_construction_income"></td>
          <td class="balance_vatoutput"></td>
          <td class="balance_gross"></td>
          <td class="balance_ewt"></td>
          <td class="balance_net"></td>
          <td class=""></td>
          <td class="balance_collection"></td>
          <td></td>
        </tr>  
  		</tfoot>
  </table>	 
  </div>
</div>

<div class="panel panel-default">		
  <div class="table-responsive">
  <table class="table">
  		<tbody>
  			
  		
  		</tbody>
  </table>
</div>
</div>
</div>
</div>


<script>
	
	$(function(){
		var app = {
			init:function(){
				this.bindEvent();
			},bindEvent:function(){
				$('#add_row').on('click',this.add_row);
				$('body').on('click','.close',this.close);
                
       $( '.date' ).editable(
            function( value, settings ) {
              $(this).html( value );
            },
            {
              type: 'datepicker',
              datepicker:{
                  changeMonth: true,
                  changeYear: true
              }
            }
        );
        $('.recoupment').editable(function(value,settings){
            var amount = value.split('*');
            console.log(amount);
            var value = parseFloat(amount[0]) * parseFloat(amount[1]);
            $(this).attr('data-recoupment',amount[1]);
              value = Math.round(value * 100) / 100;   
            return value;
           },{
             placeholder : '-',
             data : function(value,settings){
              var recoupment = $(this).attr('data-recoupment');
              var accomplishment = $(this).closest('tr').find('.editable-accomplishment').html();
                  accomplishment = accomplishment.replace(/,/g,'');

                return accomplishment + " * "+recoupment;
             },
             callback:function(){  
               gross(this);          
            }
        });

        $('.editable-accomplishment').editable(function(value,settings){
            value              = value.replace(/,/g,'');
            var contract_price = $('#contract_price').val().replace(/,/g,'');  
            var percent        = parseFloat(value) / parseFloat(contract_price);
            percent            = Math.round(percent * 100) / 100;            
            $(this).closest('tr').find('.percent').html(percent+'%');


            var recoupment     = value * 0.15;
                recoupment     = Math.round(recoupment * 100) / 100; 
            $(this).closest('tr').find('.recoupment').html(comma(recoupment));


            var retention      = value * 0.1;
                retention      = Math.round(retention * 100) / 100; 
            $(this).closest('tr').find('.retention').html(comma(retention));

            return comma(value);
          },{
             placeholder : '-',
             callback:function(){
              gross(this);
           }
        });
				$('.editable').editable(function(value,settings){      
						return value;
					},{
				     placeholder : '-',
				     callback:function(){
						
					 }
				});
			},add_row:function(){
				var tr = "<tr>"
	  			     +'<td class="editable billing_no"></td>'
                +'<td class="percent"></td>'
                +'<td class="editable-accomplishment"></td>'
                +'<td class="recoupment" data-recoupment="0.15"></td>'
                +'<td class="retention"></td>'
                +'<td class="construction_income"></td>'
                +'<td class="vat_output"></td>'
                +'<td class="gross"></td>'
                +'<td class="ewt"></td>'
                +'<td class="net"></td>'
                +'<td class="date"></td>'
                +'<td class="collection editable"></td>'
                +'<td><span class="close">&times;</span></td>'
		  			    + "</tr>";
				$('#tbl-billing tbody').append(tr);

          $( '.date' ).editable(
              function( value, settings ) {
                $(this).html( value );
              },
              {
                type: 'datepicker',
                datepicker:{
                    changeMonth: true,
                    changeYear: true
                }
              }
          );

        $('.recoupment').editable(function(value,settings){
            var amount = value.split('*');
            console.log(amount);
            var value = parseFloat(amount[0]) * parseFloat(amount[1]);
            $(this).attr('data-recoupment',amount[1]);
              value = Math.round(value * 100) / 100;   
            return value;
           },{
             placeholder : '-',
             data : function(value,settings){
              var recoupment = $(this).attr('data-recoupment');
              var accomplishment = $(this).closest('tr').find('.editable-accomplishment').html();
                  accomplishment = accomplishment.replace(/,/g,'');

                return accomplishment + " * "+recoupment;
             },
             callback:function(){  
               gross(this);          
            }
        });

        $('.editable-accomplishment').editable(function(value,settings){
            value              = value.replace(/,/g,'');
            var contract_price = $('#contract_price').val().replace(/,/g,'');  
            var percent        = parseFloat(value) / parseFloat(contract_price);
            percent            = Math.round(percent * 100) / 100;            
            $(this).closest('tr').find('.percent').html(percent+'%');


            var recoupment     = value * 0.15;
                recoupment     = Math.round(recoupment * 100) / 100; 
            $(this).closest('tr').find('.recoupment').html(comma(recoupment));


            var retention      = value * 0.1;
                retention      = Math.round(retention * 100) / 100; 
            $(this).closest('tr').find('.retention').html(comma(retention));

            return comma(value);
          },{
             placeholder : '-',
             callback:function(){
              gross(this);
           }
        });
        $('.editable').editable(function(value,settings){      
            return value;
          },{
             placeholder : '-',
             callback:function(){
            
           }
        });

			},close:function(){
				$(this).closest('tr').remove();
			}
		}
		app.init();


    var gross = function(dom){      
      var recoupment     = $(dom).closest('tr').find('.recoupment').html().replace(/,/g,'');
      var retention      = $(dom).closest('tr').find('.retention').html().replace(/,/g,'');
      var accomplishment = $(dom).closest('tr').find('.editable-accomplishment').html().replace(/,/g,'');

      var gross = parseFloat(accomplishment) - (parseFloat(recoupment) + parseFloat(retention));

      var construction_income = gross/1.12;
      var vat_output_tax = construction_income * 1.12;
      var ewt            = gross/1.12*0.02;
      var net            = gross - ewt;

      gross                  = Math.round(gross * 100) / 100; 
      construction_income  = Math.round(construction_income * 100) / 100; 
      vat_output_tax       = Math.round(vat_output_tax * 100) / 100; 
      ewt                  = Math.round(ewt * 100) / 100; 
      net                  = Math.round(net * 100) / 100; 
      
      $(dom).closest('tr').find('.vat_output').html(comma(vat_output_tax));
      $(dom).closest('tr').find('.construction_income').html(comma(construction_income));
      $(dom).closest('tr').find('.gross').html(comma(gross));
      $(dom).closest('tr').find('.ewt').html(comma(ewt));
      $(dom).closest('tr').find('.net').html(comma(net));      
      total();
    }

    var total = function(){
      var total = {
        percent        : 0,
        accomplishment : 0,
        recoupment     : 0,
        retention      : 0,
        construction_income : 0,
        vat_output     : 0,
        gross          : 0,
        ewt            : 0,
        collection     : 0,
      };

      $('#tbl-billing tbody tr').each(function(i,val){
         total.percent        += parseFloat($(val).find('.percent').html().replace(/%/g,''));
         total.accomplishment += parseFloat($(val).find('.editable-accomplishment').html().replace(/,/g,''));
         total.recoupment     += parseFloat($(val).find('.recoupment').html().replace(/,/g,''));
         total.retention      += parseFloat($(val).find('.retention').html().replace(/,/g,''));
         total.construction_income += parseFloat($(val).find('.construction_income').html().replace(/,/g,''));
         total.vat_output     += parseFloat($(val).find('.vat_output').html().replace(/,/g,''));
         total.gross          += parseFloat($(val).find('.gross').html().replace(/,/g,''));
         total.ewt            += parseFloat($(val).find('.ewt').html().replace(/,/g,''));
         total.collection     += parseFloat($(val).find('.collection').html().replace(/,/g,''));
      });

      $('.total_percentage').html(total.percent+'%');    
      $('.total_accomplishment').html(comma(total.accomplishment));
      $('.total_recoupment').html(comma(total.recoupment));
      $('.total_retention').html(comma(total.retention));
      $('.total_const_income').html(comma(total.construction_income));
      $('.total_vatoutput').html(comma(total.vat_output));
      $('.total_gross').html(comma(total.gross));
      $('.total_ewt').html(comma(total.ewt));
      $('.total_net').html(comma(total.net));
      $('.total_collection').html(comma(total.collection));

      var balance = {
        percent        : 0,
        accomplishment : 0,
        recoupment     : 0,
        retention      : 0,
        construction_income : 0,
        vat_output     : 0,
        gross          : 0,
        ewt            : 0,
        collection     : 0,
      }

      balance.percent = parseFloat(100) - total.percent;
      $('.balance_billing').html(balance.percent+'%');      
      $('.balance_accomplishment').html(balance.accomplishment);
      $('.balance_recoupment').html(balance.recoupment);
      $('.balance_retention').html(balance.retention);
      $('.balance_construction_income').html(balance.construction_income);
      $('.balance_vatoutput').html(total.vat_output);
      $('.balance_gross').html(total.gross);
      $('.balance_ewt').html(total.ewt);
      $('.balance_net').html(total.net);
      $('.balance_collection').html(total.collection);
      
    };

	});

</script>