$(document).ready(function() {


 $('.autoboxbut').on('click',function(){
  $('.miniprofile-autobox').slideDown('normal');
 });  
 
 $('#otzivstart').on('click', function(){
  
  $('#otzform')[0].reset();
  $('.otzivi').fadeIn(150);
  $('.otzivi').delay(4000).fadeOut(300);
  
 }); 




});