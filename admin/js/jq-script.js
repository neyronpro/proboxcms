$(document).ready(function() {
 
  $('.formtest').on('click',function(){
   $('form[name=addcat]').attr('action','test.php');
  });
 
  $(window).on('load', function(){
   
   /*Дооформляем дерево категорий, если находимся на странице со списком категорий*/
   $('.cslvl2, .cslvl3').append('<p class="cslist-arr">&nbsp;</p>');
   /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
   /*Подгружаем название текущей категории*/
    var idactive = $('input[name="catselect"]').val();
        var namecatactive = $('#pic'+idactive).text();
        $('.csbnamecatbut').text(namecatactive); 
   /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/ 
   
   $('form[name=content] .catnoactive').attr('class', 'catactive').css({'text-decoration':'none','color':'#111','background':'none'});
   
  });
 
  //Навешиваем на зпрещенные категории title с соответствующей информацией
  $('.catnoactive').attr('title','Данную категорию нельзя использовать как родительскую');
  
  /*Раскрываем список доступных категорий*/
  $('.csbnamecatbut').on('click',function(){
  
    $('.catlist').slideDown(300); //Открываем список
    
    /*Подсвечиваем текущую категорию*/
    var idactive = $('input[name="catselect"]').val();
        $('#pic'+idactive).css({'background':'#0067d0', 'color':'#fff'}); 
    /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/   
  });
  /*=====================================*/
  
  /*Подсветка строки-кнопки в списке при наведении*/
  $('.catactive, form[name=content] .catnoactive').hover(
    function() {
      $(this).css({'background':'#0067d0', 'color':'#fff'});
    }, function() {
      $(this).css({'background':'#fff', 'color':'#333'});
    }
  );
  /*==============================================*/
  
  /*Выбираем нужную категорию и передаем ее в форму*/
  $('.catactive, form[name=content] .catnoactive').on('click',function(){
  
   //Получаем name и id выбранной категории, передаем данные в нужные поля формы
    var nameparent = $(this).text();
    var idparent = $(this).attr('id').slice(3);
        $('input[name=catselect]').val(idparent);
        $('.csbnamecatbut').text(nameparent);
   
   //Визуализируем событие выбора категории     
    $('.catactive').css({'background':'#fff', 'color':'#333'});//Сбрасываем у всех строк списка отличительное оформление
    $(this).css({'background':'#0067d0', 'color':'#fff'});//Маркируем выбранную строку
    $('.catlist').slideUp(300); //Прячем список
  
  });
  /*===============================================*/
  
  /*В случае если ни одна категория небыла выбрана, прячем список, как только с него будет убран курсор мыши*/
  $('.catlist').hover(
    function() {
      /*ничего не происходит*/
    }, function() {
      $(this).slideUp(300);
    }
  );
  /*========================================================================================================*/
  

  

});