
<div class="center-menu">
	<span class="page-title"></span>	
		<?php 
			echo $view;
		?>
</div>

<div class="right-menu">
	<?php echo $this->lib_transaction->transaction_info(); ?>
</div>

<script>
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


		var xxx = "";
		var updateContent = function(State){
		 		if(typeof(State) == 'undefined'){
		 			State = History.getState()		
		 		}
		  		var url = State.data.url;
		  		if(typeof(State.data.method) !='undefined')
		  		{	

		  			$get = {
			  			method : State.data.method,
			  			type   : State.data.type,
			  			value  : State.data.value,
		  			}
		  			
	  		        if(xxx && xxx.readystate != 4){
	  		            xxx.abort();
	  		        }

			  		$('.right-menu').html('<img src="asset/img/loading.gif" alt="Loading" /> Loading...');
			    	xxx = $.get('<?php echo base_url().index_page(); ?>/transaction_list/info',$get,function(response){
			        	$('.right-menu').html(response);
			        });

		  		}else{
		  			$('.right-menu').html('');
		  		}
		  		
		};


	



		var set_selected = function(){
			if( $('.item_contents').length > 0 )
			{				
				var State = History.getState();			
				$('.item_contents').each(function(i,val){
					if($(val).attr('data-value') == State.data.value)
					{
						$(val).addClass('active');
					}else{
						$(val).removeClass('active');
					}
				});
			}
		}


		$('body').on('click','.item_contents',function(){
			  var me = $(this);
		  	  var url    = me.attr('data-url');
		  	  var method = me.attr('data-method');
		      var type   = me.attr('data-type');
		      var value  = me.attr('data-value');
		      var uri    = me.attr('data-get');
		      var title  = me.attr('data-title');

		      if(typeof(title)== "undefined"){
		      	title = value;
		      }

		  	  History.pushState({ method:method,type:type,value:value },title, url);
		  	  $('.item_contents').removeClass('active');
		  	  me.addClass('active');
		  	  onResize();
		});


		$('body').on('click','.close-info',function(e){
				e.preventDefault();
		  	  var me = $(this);
		  	  var url = me.attr('href');
		  	  History.pushState(null,null,url);	  
		  	  set_selected();  
		  	  onResize();
		});


		$('body').on('click','.history-link',function(e){
			e.preventDefault();
		  	 var me = $(this);
		  	  var url    = me.attr('href');
		  	  var method = me.attr('data-method');
		      var type   = me.attr('data-type');
		      var value  = me.attr('data-value');
		      var uri    = me.attr('data-get');	
		      var title  = me.attr('data-title');

		      if(typeof(title)== "undefined"){
		      	title = value;
		      }		      
		  	 History.pushState({ method:method,type:type,value:value },title, url);
		  	 onResize();
		});		

		$('body').on('click','.history_back',function(e){
				e.preventDefault();
		  	History.back();
		  	onResize();
		});


		History.Adapter.bind(window, 'statechange', function(){			
		       updateContent(History.getState());	
		       onResize();	       
		});

		updateContent(History.getState());

		
	});



/*
		var xxx = "";
		var updateContent2 = function(State){
		 		if(typeof(State) == 'undefined'){
		 			State = History.getState()		
		 		}
		  		var url = State.data.url;
		  		if(typeof(State.data.method) !='undefined')
		  		{	

		  			$get = {
			  			method : State.data.method,
			  			type   : State.data.type,
			  			value  : State.data.value,
		  			}
		  			
	  		        if(xxx && xxx.readystate != 4){
	  		            xxx.abort();
	  		        }

			  		$('.right-menu').html('<img src="asset/img/loading.gif" alt="Loading" /> Loading...');
			    	xxx = $.get('<?php echo base_url().index_page(); ?>/transaction_list/info',$get,function(response){
			        	$('.right-menu').html(response);
			        });

		  		}else{
		  			$('.right-menu').html('');
		  		}		  	
		};*/


</script>