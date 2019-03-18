
<div class="center-menu">
	<span class="page-title"></span>	
		<?php 
			echo $view;
		?>		
</div>

<div class="right-menu">
	<?php /*echo $this->lib_transaction->transaction_info();*/ ?>
</div>

<script>
	var xxx = "";

	var reload_center = function(path){
		if($('.close-info').length>0){
			var url_close = $('.close-info').attr('href');
			History.pushState(null,null,url_close);
		}

		if(typeof(path) !='undefined'){
			var url = path;
		}else{
			var url = $('.filter-item.active').attr('data-url');
		}

		if($('.search-textbox').val() !=""){
			var query_string = $('.search-textbox').val();			  
		  	url += "&search="+query_string;
		}

		
		if(jscroll_xhr && jscroll_xhr.readystate != 4){
			$('.appended-loading').remove();
            jscroll_xhr.abort();
        }
		
		$('.item_contents').remove();	
		$('.head_date').remove();
		$('a.jscroll-next').remove();
		$('.center-menu').append('<div class="appended-loading"><img src="asset/img/loading.gif" alt="Loading" /> Loading...</div>');
     
		jscroll_xhr = $.get(url,function(response){
			$('.center-menu').find('.appended-loading').remove();
			$('.jscroll-loading').remove();

			if($('.jscroll-inner').length > 0){
				$('.jscroll-inner').append(response);
			}else{
				$('.center-menu').append(response);
			}

			$('.center-menu').unbind('.jscroll')
                    .removeData('jscroll')
                    .find('.jscroll-inner').children().unwrap()
                    .filter('.jscroll-added').children().unwrap();
			
			if($('a.jscroll-next:last').length > 0){
				$('.center-menu').jscroll({
					loadingHtml: '<img src="asset/img/loading.gif" alt="Loading" /> Loading...',					
					padding: 20,
					nextSelector: 'a.jscroll-next:last',
					callback:function(){
						set_selected();
					}
				});
			}
			$('.jscroll-next').remove();
			set_selected();
		});
	}

	var set_selected = function(){
		if( $('.item_contents').length > 0 )
		{

			var State = History.getState();			
			
			$('.item_contents').each(function(i,val){
				if($(val).attr('data-value') == State.data.value || $(val).attr('data-value') == State.data.value2)
				{
					$(val).addClass('active');
				}else{
					$(val).removeClass('active');
				}
			});
		}
	}

	var updateContent = function(State){

				var bool = false;var form = "";

		 		if(typeof(State) == 'undefined'){
		 			State = History.getState();		
		 			console.log(State);			
		 		}
		 		
		 		if(State.data.pop == "true"){
		 			bool = true;
		 		}

		 		if(State.data.form == 'dialog'){
		 			form = State.data.form;
		 		}

		  		var url = State.data.url;

		  		var content = "";
  		        if($('.fancybox-inner').length > 0){
  		        	content = $('.fancybox-inner');
  		        }else{
  		        	content = $('.right-menu');
  		        }

		  		if(typeof(State.data.method) !='undefined')
		  		{
		  			$get = {
			  			method : State.data.method,
			  			type   : State.data.type,
			  			value  : State.data.value,
		  			}
		  			
	  		        if(xxx && xxx.readystate != 4){
	  		            xxx.abort();
	  		            $('.right-menu').html('');
	  		        }
					if(bool && form ==""){

					}if(form=='dialog'){
						$('#dialog').html('<img src="asset/img/loading.gif" alt="Loading" /> Loading...');
					}else{
						content.html('<img src="asset/img/loading.gif" alt="Loading" /> Loading...');	
					}

			    	xxx = $.get('<?php echo base_url().index_page(); ?>/transaction_list/info',$get,function(response){
			    		if(bool && form == ""){
			    			$.fancybox(response,{
				    			width     : 1200,
				    			height    : 650,
				    			fitToView : true,
				    			autoSize  : false,
				    			afterClose : function(){			    
					             $('.pop_up_content').removeClass('active');
					             History.pushState(null,null,url);
					           }
				    		});
			    		}if(form=='dialog'){
			    			$('#dialog').html(response);
			    		}else{
			    			content.html(response);	
			    		}
			        	
			        });
		  		}else{
		  			content.html('');
		  		}
		  		
	 };

	 var updateStatus = function(status,label_type){

	 	if(typeof label_type == "undefined"){
	 		var label_type = "label";
	 	}
	 	
	 	if( $('.item_contents').length > 0 )
		{
			var State = History.getState();			
			$('.item_contents').each(function(i,val){
				if($(val).attr('data-value') == State.data.value || $(val).attr('data-value') == State.data.value2)
				{										
					$(val).find('.item_content_status').html(window[label_type](status));
				}
			});
		}

		if($('.pop_up_content').length > 0){
			$('.pop_up_content').each(function(i,val){
				if($(val).hasClass('active'))
				{
					$(val).find('.item_content_status').html(window[label_type](status));
				}
			});
		}

	 }



	$(function(){


		var title1  = $('#sidebar-menu').find('.subdrop.active').find('span:first').html();
		var title2  = $('#sidebar-menu').find('.subdrop.active').closest('li').find('ul').find('.active').find('span:first').html();
		var title3  = $('#sidebar-menu').find('.active').find('span:first').html();
		var title4  = $('#sidebar-menu').find('.subdrop.active').closest('li').find('ul').find('.active').html();
		if(typeof(title1) != 'undefined' && typeof(title2) != 'undefined')
		{
			$('.page-title').html(title1 +" > "+title2);	
		}else if(typeof(title3) !='undefined' && typeof(title1) == 'undefined')
		{		
			$('.page-title').html(title3);	
		}else{
			$('.page-title').html(title1 +" > "+title4);
		}
		
		reload_center();
		
		var app_activity = {
		  init:function(){
		    this.bindEvent();
		  },bindEvent:function(){
		      $('.ajax-link').on('click',this.ajax_link);
		      $('body').on('click','.close-info',this.close_info);
		      $('body').on('click','.item_contents',this.item_content);
		      $('body').on('click','.pop_up_content',this.pop_up_content);
			  $('body').on('click','.history-link',this.history_link);
			  $('body').on('click','.history-back',this.history_back);
		      $('.filter-item').on('click',this.filter_item);
		      $('.search-textbox').on('keypress',this.search_query);
		  },ajax_link:function(e){
		      location.hash = this.id; // get the clicked link id 
		      e.preventDefault(); // cancel navigation
		  },close_info:function(e){
		  	  e.preventDefault();
		  	  var me = $(this);
		  	  var url = me.attr('href');
		  	  History.pushState(null,null,url);	  
		  	  set_selected();  
		  	  onResize();
		  },item_content:function(){
		  	  var me = $(this);
		  	  var url    = me.attr('data-url');
		  	  var method = me.attr('data-method');
		      var type   = me.attr('data-type');
		      var value  = me.attr('data-value');
		      var title  = me.attr('data-title');
		      if(typeof(title)== "undefined"){
		      	title = value;
		      }

		      var pop = "";
		      if($(this).hasClass('pop_up_content')){
		      	pop = "true";
		      }	

		      var uri    = me.attr('data-get');
		  	  History.pushState({ method:method,type:type,value:value,pop:pop},title, url);
		  	  $('.item_contents').removeClass('active');
		  	  me.addClass('active');
		  	  onResize();
		  	  //location.hash = url;		  	  
		  },filter_item:function(){
		  	$('.filter-item.active').removeClass('active');
		  	$(this).addClass('active');
		  	if($('.search-textbox').length>0){
				$('.search-textbox').val(' ');
			}
		  	reload_center();
		  	onResize();
		  },search_query:function(e){
		  	if(e.which == 13){
		  		var query_string = $.trim($(this).val());
		  		var url = $('.filter-item.active').attr('data-url');		  	
		  		url += "&search="+query_string;
		  		reload_center(url);
		  	}
		  	onResize();
		  },history_link:function(e){
		  	e.preventDefault();
		  	  var me     = $(this);
		  	  var url    = me.attr('href');
		  	  var method = me.attr('data-method');
		      var type   = me.attr('data-type');
		      var value  = me.attr('data-value');
		      var title  = me.attr('data-title');
		      var value2 = me.attr('data-value2');
		      if(typeof(title)== "undefined"){
		      	title = value;
		      }

		      var form = me.attr('data-form');

		      var uri = me.attr('data-get');
		  	 History.pushState({ method:method,type:type,value:value,value2:value2,form:form},title, url);
		  	 onResize();
		  },history_back:function(e){
		  	e.preventDefault();
		  	History.back();
		  	onResize();
		  },pop_up_content:function(e){
		  	  var me = $(this);
		  	  var url    = me.attr('data-url');
		  	  var method = me.attr('data-method');
		      var type   = me.attr('data-type');
		      var value  = me.attr('data-value');
		      var uri    = me.attr('data-get');
		      me.addClass('active');

	  			$get = {
		  			method : method,
		  			type   : type,
		  			value  : value,
	  			}	  			
		        if(xxx && xxx.readystate != 4){
		            xxx.abort();
		        }
		        $.fancybox.showLoading();
		  		$('.right-menu').html('');
		    	xxx = $.get('<?php echo base_url().index_page(); ?>/transaction_list/info',$get,function(response){
		    		$.fancybox(response,{
		    			width     : 1200,
		    			height    : 650,
		    			fitToView : true,
		    			autoSize  : false,
		    			afterClose : function(){			    
			             $('.pop_up_content').removeClass('active');
			            
			           }
		    		})
		        });

		        onResize();
		  	  /*  
			  	  History.pushState({ method:method,type:type,value:value },value, url);
			  	  $('.item_contents').removeClass('active');
			  	  me.addClass('active');
		  	  */
							  	
		  }
		};
		app_activity.init();


		History.Adapter.bind(window, 'statechange', function(){
		       updateContent(History.getState());
		       onResize();
		});

		updateContent(History.getState());

	});
</script>