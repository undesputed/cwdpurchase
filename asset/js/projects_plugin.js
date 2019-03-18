


(function($){

	function now(){
		var myDate = new Date();
		var prettyDate = myDate.getFullYear()+'-'+('0'+(myDate.getMonth()+1)).slice(-2)+ '-' + ('0'+(myDate.getDate())).slice(-2);			
		return prettyDate;
	};

	var range = {
		from : '',
		to   : ''
	}

	$.fn.get_projects= function(projects){
		/*=============PROPERTIES=========
		projects
		profit_center
		profit_center_data
		txtprojno
		call_back = function()
		pc_selectedvalue
		================================*/

		var defaults = $.extend({
			pc_id : '',
			project_id : '',
			main_office : false,
		},projects)


		var obj = $(this);
		var profit=new Object();
		var Proj_Code=new String;
		var main_office = '';
		if(projects!=null && projects.hasOwnProperty('projects')){
			var options=JSON.parse(projects.projects);
			if(options.length == 0)
				return false;
			$(obj).html('');
			for(var i=0,length=options.length;i<length;i+=1){
				$(obj).append("<option value='"+options[i]['title_id']+"'>"+options[i]['title_name']+"</option>");
			}
		}else{
			$.post('index.php/ajax/get_projects',{data:"1fadf5gasE"},function(data){
				var temp=JSON.parse(data);
				Proj_Code=temp['proj'];
				data=temp['projects'];
				if(data.length == 0)
					return false;
				$(obj).html('');
				var options=data;
				
				for(var i=0,length=options.length;i<length;i+=1){
					var selected = (options[i]['title_id']==temp['title_id']) ? "selected":"";
					$(obj).append("<option value='"+options[i]['title_id']+"' "+selected+">"+options[i]['title_name']+"</option>");
				}
				
				/*under observation*/
				// $(obj).trigger('change');
				/*end*/

				if(projects!=null && projects.hasOwnProperty('profit_center')){
					var profit_center = projects.profit_center;
					var options=null;

					if(projects.hasOwnProperty('profit_center_data')){
						options=JSON.parse(projects.profit_center_data);
						$(obj).trigger('change');
						if(typeof projects.call_back == 'function'){
							projects.call_back();
						}
					}
					else{
						$.post('index.php/ajax/get_profit_center',{data:"test"},function(data){
							if(data.length == 0)
								return false;
							options = data.profit_center;
							main_office = data.main_office;
							$(obj).trigger('change');
							if(typeof projects.call_back == 'function'){
								projects.call_back();
							}
						},'json');
					}

					$(obj).on('change',function(){
						if(options==null)
							return false;
						$(profit_center).html('');
						for(var i=0,length=options.length;i<length;i+=1){
							if(options[i]['title_id']==$(obj).val()){

								if(defaults.main_office == true){
									var selected = (options[i]['project_id'] == main_office) ? "selected='selected'" : "";
								}else
								{
									if(defaults.pc_id!=''){
										var selected = (options[i]['project_id'] == defaults.pc_id) ? "selected='selected" : "";
									}else{
										var selected = (options[i]['project_id'] == Proj_Code) ? "selected='selected" : "";
									}
								}
															
								$(profit_center).append("<option value='"+options[i]['project_id']+"' "+selected+" data-location='"+options[i]['location']+"' data-to='"+options[i]['project_location']+"' data-effectivity='"+options[i]['project_effectivity']+"'>"+options[i]['project_full']+"</option>");
							}
						}
						 $(profit_center).trigger('change');
					});

					if(projects.hasOwnProperty('txtprojno')){
						var txtprojno=projects.txtprojno;
						$(profit_center).on('change',function(){
							$(txtprojno).html('');
							for(var i=0,length=options.length;i<length;i+=1){
								if(options[i]['project_id']==$(profit_center).val())
									$(txtprojno).html("<option value='"+options[i]['project_id']+"'>"+options[i]['project_no']+"</option>");
							}
						});
					}
				}
			});
		}
	}
	$.fn.generate_option=function(settings){
		var options="";
		var data = (settings.hasOwnProperty('data')) ? settings.data : new Object();
		var val = (settings.hasOwnProperty('val')) ? settings.val : '';
		var text = (settings.hasOwnProperty('text')) ? settings.text : '';
		var selected = (settings.hasOwnProperty('selected')) ? settings.selected : '';
		for(var i=0,length=data.length; i<length;i+=1){
			var row = $.map(data[i],function(v){return v});
			var value = (data[i].hasOwnProperty(val)) ? data[i][val] : row[0];
			var display = (data[i].hasOwnProperty(text)) ? data[i][text] : row[1];
			var is_selected = (value==selected) ? 'selected':'';
			options+="<option value='"+value+"' "+is_selected+">"+display+"</option>";
		}
		$(this).html(options);
	}


	$.fn.date_from = function(arg){
		var option = $.extend({
			now : now(),
		},arg);

		range.from = $(this);
		$(this).datepicker({
			dateFormat:'yy-mm-dd',
			onClose: function( selectedDate ){
				range.to.datepicker( "option", "minDate", selectedDate );
			}
		});
		$(this).val(option.now);
		
	}
	
	$.fn.date_to = function(){
		range.to = $(this);
		$(this).datepicker({
			dateFormat:'yy-mm-dd',
			onClose: function( selectedDate ){
				range.from.datepicker( "option", "maxDate", selectedDate );
			}
		});
		$(this).val(now());
	}


	$.fn.date = function(type){
		$this = this;
		
		var a = (!$this.val())? now() : $this.val();
		var settings = $.extend({
			url  : '',
			dom  : '',
			div  : '',
			event: '',
			date : a,
			option : {
				dateFormat:'yy-mm-dd',
			}
		},type);

		var range;

		if(typeof(type)!='undefined'){
			var range = type;
		}else{
			var range = "default";
		}

		var app = {
			init:function(){				
				this.asign_value();
				this.bindEvents();					
			},asign_value:function(){

				$($this).datepicker(settings.option);

				$($this).val(settings.date);
				
			},bindEvents:function(){
				if(settings.event!=''){
					$this.on(settings.event,this.get_code);
					$this.trigger(settings.event);
				}				
			},get_code:function(){
				$post = {
					date: $(this).val(),
				};
				$.post('index.php/ajax/'+settings.url,$post,function(data){				
					settings.dom.val($.trim(data));
					if(settings.div !=''){
						if(typeof(settings.div)!='undefined' || typeof(settings.div)!='string'){						
							settings.div.html($.trim(data));	
						}						
					}
					
				});
			}

		}
	

		app.init();

	}


	$.fn.select = function(obj){
		$this = this;
		$this.html('');


		var settings = $.extend({
			selected  : '',			
		},obj);
		

		var $id;
		var app = {
			init:function(){
				$.each(obj.json,function(i,val){
					var selected = false;
					if(settings.selected !="" && (settings.selected == val[obj.attr.text] || settings.selected == val[obj.attr.value] )){
						selected = true
					}

					var option = $('<option>').val(val[obj.attr.value]).text(val[obj.attr.text]).prop('selected',selected);

					if(obj.hasOwnProperty('data')){
						var data_attr = new Array();
						$.each(obj.data,function(key,value){
							
							option.attr(key,val[value]);
						});
					}
					$this.append(option);					

				});

				if($this.prev().find(':input').length == 0){
					$id = $this.attr('id')+'_hdn';
					if($('#'+$id).length == 0){						
						$this.before($('<input type="hidden">').attr({ 'id' : $this.attr('id')+'_hdn','name' : $this.attr('id')+'_hdn' }));

					}										
					$('#'+$id).val($this.find(':selected').text());
				}	

				$this.on('change',function(){				
					$('#'+$id).val($(this).find(':selected').text());
				})			
			}
		}
		app.init();		
	}


	$.fn.content_loader = function(bool){

		$this = $(this);

		if(bool){
			$this.prepend('<div class="table-preload"><span class="table-loading"></div>');	
			$this.find('.table-preload').css({'height' : $this.height(),'width' : $this.width()});
		}else{
			$('.table-preload').remove();
		}

	}

	$.fn.my_alert = function(type){

		if(typeof(type)=="undefined" || type==""){
			type = "success";
		}
				
		var $this = $(this);
		var dom ;
		var button;
		var message = {
			'success' : {
						 msg  :'<strong>Successfully Save</strong>',
						 class : 'alert-success',
						},
			'update' : {
						 msg  :'<strong>Successfully Update</strong>',
						 class : 'alert-info',
						},						

		};

		button = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
		dom = $('<div></div>').addClass('alert alert-dismissable');
		dom.addClass(message[type].class);
		dom.append(button);
		dom.append(message[type].msg);
		
		$this.html(dom);

	}



    $.fn.clearForm = function() {
      return this.each(function() {
        var type = this.type, tag = this.tagName.toLowerCase();
        if (tag == 'form')
          return $(':input',this).clearForm();
        if (type == 'text' || type == 'password' || tag == 'textarea')
          this.value = '';
        else if (type == 'checkbox' || type == 'radio')
          this.checked = false;
        else if (tag == 'select')
          this.selectedIndex = -1;
      });
    };


    $.fn.editable_td = function(obj){

    	$this = this.selector;     	
    	var settings = $.extend({
    			insert : "input",
    			clone  : "",
    			callback : function(){},
    			addClass : "",
    			beforeback: function(){},
    			event  : "change blur",
    	},obj);

    	$('body').on('click',$this,function(){    		    			   

    		switch(settings.insert){

    			case "select":

		    				var td = this;
				    		var select; 
				    		select =  $('<select class="form-control input-sm">').append($(settings.clone).html()).focus().addClass(settings.addClass);    		
				    		$(this).html(select);
				    		select.focus();
				    		settings.beforeback();
				    		select.on(settings.event,function(){
				    			option = $(this);
				    			var value = $(this).find(':selected').text();
				    			var caret = '<b class="pull-right caret action" style="margin-top:5px;"></b>';
				    			$(td).html(value+caret);
				    			settings.callback(td,option);
				    		})
				    	
    			break;
    			case "input" :    			
    					var td = this;
    					var input = $('<input type="text" autofocus="autofocus" class="input-sm form-control">').addClass(settings.addClass);    					
    					$(this).html(input);
    					settings.beforeback();
    					input.focus();
    					input.on(settings.event,function(){
							$(td).html($(this).val());
							settings.callback(td);
    					})
    			break;

    		}
        

    	});

    }

    $.fn.ben_popover = function(obj){
    	$this = this.selector; 

    	$('body').on('click',$this,function(){
    		var position = $(this).offset();
    		
    		if($('.my_popover').length==0){
    			$('body').append('<div class="my_popover"><div class="panel panel-default"><div class="panel-heading">Details <button type="button" class="close" data-dismiss="my_popover" aria-hidden="true">&times;</button></div><div class="panel-body"></div></div></div>');
    		}
    		
    		var val = $(this).find('td:first').text();
    		$.get(obj.url,{'id':val},function(response){

					$('.my_popover .panel-body').html(response);
					var height = $('.my_popover').height();
					$('.my_popover').css({    		
		    				left : position.left,
		    				top  : position.top - height,    		
		    		});
    		});
    		
			$('.close').on('click',function(){					
				$(this).closest('.my_popover').remove();
			});

    	});

    }


    $.fn.custom_pop = function( args ){
    	$this = this.selector;

    	var settings = $.extend({

    	},args);

    	$('body').on('click',$this,function(){
    		var position = $(this).offset();
    		
    		if($('.my_popover').length==0){
    			$('body').append('<div class="my_popover"><div class="panel panel-default"><div class="panel-heading">Details <button type="button" class="close" data-dismiss="my_popover" aria-hidden="true">&times;</button></div><div class="panel-body"></div></div></div>');
    		}
			    		
				$('.my_popover .panel-body').html('+');
				var height = $('.my_popover').height();
				$('.my_popover').css({    		
	    				left : position.left + 50,
	    				top  : position.top + 20,    		
	    		});
    		    
				$('.close').on('click',function(){					
					$(this).closest('.my_popover').remove();
				});
				
    	});

    }


	
	 $.save = function ( options ){

		 var setting = $.extend({
		 		action   : 'show',
		 		reload   : 'false',
		 		appendTo : 'body',
		 		loading  : 'Saving...',
		 		success  : 'Successfully Save',
		 		delay    : '2000',
		 },options);

		 switch(setting.action){
		 	case "show":		 	
	            var div ='<div class="save-overlay"><div class="save-content"><div class="save-content-container"><span id="icon" class="loader"></span> <span class="save-text">'+setting.loading+'</span></div></div></div>';
	            $(div).appendTo(setting.appendTo);
		 	break;
		 	case "hide":
		 		$('.save-overlay').remove();
		 	break;
		 	case "success":		 		
		 		$('.save-overlay .save-text').html(setting.success);
		 		$('.save-overlay .loader').addClass('fa success');
		 		$('#icon').removeClass('loader');
		 		delay(function(){
		 			$('.save-overlay').remove();

		 			if(setting.reload == 'true'){

		 				location.reload(true);
		 			}
		 		},1000);
		 	break;

		 	case "error":
		 		$('.save-overlay .save-text').html('Failed to Save');
		 		$('.save-overlay .loader').addClass('fa error');
		 		$('#icon').removeClass('loader');
		 		delay(function(){
		 			$('.save-overlay').remove();
		 			if(setting.reload == 'true'){
		 				location.reload(true);
		 			}
		 		},1000);
		 	break;
		 	case "delay-hide":
		 		$('.save-overlay .save-text').html(setting.success);
		 		$('.save-overlay .loader').addClass('fa fa-warning');
		 		$('#icon').removeClass('loader');
		 		delay(function(){
		 			$('.save-overlay').remove();
		 		},setting.delay);		 		
		 	break;
		}
		
	}   


	$.signatory = function( obj ){
		var $settings = $.extend({
			type : '',
			prepared_by : '',
			recommended_by : '',
			approved_by : '',
			checked_by : '',
			received_by : '',
			noted_by    : '',
			callback: function(){},
			prepred_by_id     : '',
			recommended_by_id : '',
			approved_by_id    : '',
			checked_by_id     : '',
			received_by       : '',
			noted_by          : '',

		},obj);

			var app = {
				init:function(){}
			}
			
			$post = {
				prepared_by   : $settings.prepared_by,
				recommended_by: $settings.recommended_by,
				approved_by   : $settings.approved_by,
				checked_by    : $settings.checked_by,
				received_by   : $settings.received_by,
				noted_by      : $settings.noted_by,
				type          : $settings.type,				
			};	

			$.post('index.php/ajax/signatory3',$post,function(response){
										
					if($('#prepared_by').length>0){
						$('#prepared_by').html('');
						$.each(response.prepared_by,function(i,value){
							var selected = (value['pp_person_code']== $settings.prepred_by_id)? true : false ;
							$('#prepared_by').append($('<option>').text(value['Person Name']).val(value['emp_number']).prop('selected',selected));
						});
					}
										
					if($('#recommended_by').length>0){
						$('#recommended_by').html('');
						$('#recommended_by').append($('<option>').text(' -Select Person- ').val(' '));
						$.each(response.recommended_by,function(i,value){							
							var selected = (value['pp_person_code'] == $settings.recommended_by_id)? true : false ;
							$('#recommended_by').append($('<option>').text(value['Person Name']).val(value['emp_number']).prop('selected',selected));							
						});
					}
					
					if($('#approved_by').length>0){
						$('#approved_by').html('');
						$('#approved_by').append($('<option>').text(' -Select Person- ').val(' '));
						$.each(response.approved_by,function(i,value){
							var selected = (value['pp_person_code'] == $settings.approved_by_id)? true : false ;
							$('#approved_by').append($('<option>').text(value['Person Name']).val(value['emp_number']).prop('selected',selected));
						});
					}

					if($('#checked_by').length>0){
						$('#checked_by').html('');
						$('#checked_by').append($('<option>').text(' -Select Person- ').val(' '));
						$.each(response.checked_by,function(i,value){
							var selected = (value['pp_person_code'] == $settings.checked_by_id)? true : false ;
							$('#checked_by').append($('<option>').text(value['Person Name']).val(value['emp_number']).prop('selected',selected));
						});
					}

					if($('#received_by').length>0){
						$('#received_by').html('');
						$('#received_by').append($('<option>').text(' -Select Person- ').val(' '));
						$.each(response.received_by,function(i,value){
							var selected = (value['pp_person_code'] == $settings.received_by_id)? true : false ;
							$('#received_by').append($('<option>').text(value['Person Name']).val(value['emp_number']).prop('selected',selected));
						});
					}

					if($('#noted_by').length>0){
						$('#noted_by').html('');
						$('#noted_by').append($('<option>').text(' -Select Person- ').val(' '));
						$.each(response.noted_by,function(i,value){							
							var selected = (value['pp_person_code'] == $settings.noted_by_id)? true : false ;
							$('#noted_by').append($('<option>').text(value['Person Name']).val(value['emp_number']).prop('selected',selected));
						});
					}
										


			},'json');


	}





	/*ANDREI*/
		$.fn.generate_object=function(){
			if($(this).find('.dataTables_empty').length >= 1){
				return false;
			}
			var obj=new Object();
			$(this).children('td').each(function(){
				obj[$(this).attr('class').replace('sorting_1','').trim()]=$(this).text().trim();
			});
			return obj;
		}

			
		$.fn.generate_popover=function(data){
			var obj = $(this);
			$(this).popover('destroy');
			$(this).siblings().popover('destroy');
			$(this).popover({
							placement : 'bottom',
							title     : 'Details <span class="fa fa-times pull-right" id="custom_close_pop" style="margin-top:3px;color:red;cursor:pointer">&nbsp;</span>',
							content   : data,							
							html      :  true
			});
			$(this).popover('toggle');
			$('.popover').css("max-width",function() { return $(window).width(); }).css('text-decoration','none');
			$('.popover').on('click','table',function(e){e.stopPropagation()});
			$('body').on('click','.container',function(){ obj.popover('destroy'); });
			$('body').on('click','.popover',function(e){ e.stopPropagation(); });
			$('body').on('click','#custom_close_pop',function(){ obj.popover('destroy'); });

			return true;
		}

			
		$.fn.generate_preload=function(data){
			var btn = $(this);
			$(btn).attr('data-loading-text',"Loading...").attr('autocomplete','off');
			$(document).ajaxStart(function(){
				$(btn).button('loading')
			}).ajaxStop(function(){
				$(btn).button('reset');
			});
		}
	/*end andrei*/

	$.fn.required = function( obj ){

		var bool = false;		
		$(this).each(function(i,value){
				if($(value).val()==""){

					$(value).addClass('required-field').attr({'title':'This Field is Required'});
					$(value).stop(true,true).effect('highlight');
					
				 	$('html, body').animate({
				        scrollTop : $(value).offset().top
				  	}, 2000);

					$(value).on('blur',function(){
						var obj = $(this);
						if(obj.val()!=""){
							obj.removeClass('required-field');
							obj.removeAttr('title');							
						}
					});

					bool = true;

				}
		});

		if(bool){
			return true;				
		}else{
			return false;
		}

	};

}(jQuery));