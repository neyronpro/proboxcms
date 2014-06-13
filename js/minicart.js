$(document).ready(function() {

	$(window).on('load', function(){
	
		 $.ajax({
		
			url:"/ajax/cartjob.php",
			type: "POST",
			dataType:"json",
			success: function(result){
				
				
					
			  $('.cartboxtotal').html(result.total);
			  $('.cartboxsum').html(result.sum);
				}
	
	
	
		});
		
		if($('div').is('.showreviews')){
			var showrevid =	$('div.showreviews').attr('id').substr(4);
		//	alert(showrevid);
		
			var params = {
						 showreviews: 'show',
						id: showrevid
					};
			
			 $.ajax({
				
					
					url:"/modules/show/reviews.php",
					type: "POST",
					data: params,
					success: function(result){
						
						$('div.showreviews').html(result);
					/*
						$.ajax({
				
						url:"/modules/show/reviews.php",
						type: "POST",
						dataType:"html",
						success: function(result){
							
							
							$('span.showreviews').replaceWith(result);
						}
			
			
			
						});*/
						
						
						
						
					}
			
			
			
			});
	
		}	
		if($('div').is('.addreviews')){
			var addrevid =	$('div.addreviews').attr('id').substr(4);
		//	alert(showrevid);
		
			var params = {
						 addreviews: 'add',
						id: addrevid
					};
			
			 $.ajax({
				
					
					url:"/modules/show/reviews.php",
					type: "POST",
					data: params,
					success: function(result){
						
						$('div.addreviews').html(result);
					  $('div#vis-rating-hover').html('<p onclick="ratstar(\'1\');" onmouseover="ratstarOver(\'1\');" onmouseout="ratstarOut(\'0\');">&nbsp;</p><p onclick="ratstar(\'2\');" onmouseover="ratstarOver(\'2\');" onmouseout="ratstarOut(\'0\');">&nbsp;</p><p onclick="ratstar(\'3\');" onmouseover="ratstarOver(\'3\');" onmouseout="ratstarOut(\'0\');">&nbsp;</p><p onclick="ratstar(\'4\');" onmouseover="ratstarOver(\'4\');" onmouseout="ratstarOut(\'0\');">&nbsp;</p><p onclick="ratstar(\'5\');" onmouseover="ratstarOver(\'5\');" onmouseout="ratstarOut(\'0\');">&nbsp;</p>');
	
					}
			
			
			
			});
			
	
		}			
		
	});       
	
	$('body').on('click', '.addpostrev', function(){
			
			var addrevid = $(this).attr('id').substr(10);
			var dignity = $('[name = dignity]').val();
			var comment = $('[name = comment]').val();
			var luck = $('[name = luck]').val();
			var rating = $('[name = rating]').val();
			var params = {
						 addreviews: 'add',
						addpostrev: 'bla',
						dignity: dignity,
						comment: comment,
						luck: luck,
						rating: rating,
						id: addrevid
					};
			
			 $.ajax({
				
					
					url:"/modules/show/reviews.php",
					type: "POST",
					data: params,
					success: function(result2){
						//alert('yyy');
						$('div.ajaxaddrev').replaceWith(result2);
						var showrevid =	$('div.showreviews').attr('id').substr(4);
								var params = {
									 showreviews: 'show',
									id: showrevid
								};
						
						 $.ajax({
							
								
								url:"/modules/show/reviews.php",
								type: "POST",
								data: params,
								success: function(result){
									
									$('div.showreviews').html(result);
								
									
									
									
								}
						
						
						
						});
									
						
						
						
					}
			
			
			
			});
		
		}
		
		);
		

  $('.addcartbutton').click(function(){
	idgoods = $(this).attr('id');
	pricegoods = $('.goodsboxprice' + idgoods).text();
	goodscount = $('.goodsnumb' + idgoods).val();
	
   var params = {
	 addcart: '1',
     idcart: idgoods,
     discost: pricegoods,
     count: goodscount
    };
	
  	 $.ajax({
		
  	 	url:"/ajax/cartjob.php",
  		type: "POST",
  		data: params,
      success: function(){
				$.ajax({
		
		    	url:"/ajax/cartjob.php",
			    type: "POST",
			    dataType:"json",
			    success: function(result){
				
				    $('.blackbg').fadeIn(150);
            $('.blackbg').delay(1000).fadeOut(300);
					
			      $('.cartboxtotal').html(result.total);
			      $('.cartboxsum').html(result.sum);
			   	}
	
	
	
		    });
		
  			}	
  	 });
		
		   
   }
  );
  
  
  $('.delgoods').click(function(){
	idgoods = $(this).attr('id');
	$('#gtr'+idgoods).remove();
    var params = {
	 delgoods: '1',
     goodid: idgoods
    };

  	 $.ajax({
		
  	 	url:"/ajax/cartjob.php",
  		type: "POST",
  		data: params,
      success: function(){
				$.ajax({
		
			   url:"/ajax/cartjob.php",
			   type: "POST",
			   dataType:"json",
			   success: function(result){
				
				
					
			     $('.cartboxtotal').html(result.total);
			     $('.cartboxsum').html(result.sum);
				 }
	
	
	
		   });
		
  		}	
  	 });
		
		
	 
   }
  );

  
  $('.cat_tape ul li').attr('class','sablist');
  $('.sub-cat-icon').html('&raquo;&nbsp;');
  $('.cat_tape ul li:first-child').attr('class','firstlist');
  //Разворот под категорий списка категорий
  /*$('.cat_tape ul').hover(function(){
   $(this).children('.sablist').slideDown('normal')},
   function(){
   $(this).children('.sablist').slideUp('normal');
  });*/
  
  $('p.comratbut').on('click',
    function(){
     var id = $(this).attr('id').substr(8);
     alert(id);
    }
  );
  

});

function ratstar(id){
  document.getElementById('vis-rating-panel').className = 'addrating' + id;
  document.getElementById('ratingid').value = id;
};
function ratstarOver(id){
  document.getElementById('vis-rating-hover').className = 'addrating' + id;
};
function ratstarOut(id){
  document.getElementById('vis-rating-hover').className = 'addrating' + id;
};