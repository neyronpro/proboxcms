		$(function() {

			// Вешаем обработчик на ссылки с нужным классом
			$('a.darkbox').click(function() {
				
				var link = $(this);
		
				if(!$('div.darkbox-frame').length) {
					
					// Если попап прежде не вызывался,
					// создаём его и цепляем к BODY
					darkbox = $('<div class="darkbox-frame"><div class="darkbox-shadow"></div><div class="darkbox-canvas"><div class="darkbox-button"></div></div></div>').appendTo('body');
				}

				// Клонируем попап,
				// прицепляем клон к BODY и показываем его
				var frame = darkbox.clone().appendTo('body').addClass('darkbox-frame-on');

				var shadow = frame.find('div.darkbox-shadow').animate({opacity:0.6},300);
				var canvas = frame.find('div.darkbox-canvas');
				var button = frame.find('div.darkbox-button');

				// Цепляем к попапу картинку и ждём её загрузки
				var image = $('<img src="'+ link.attr('href') +'" alt="'+ link.attr('title') +'">');

				image.appendTo(canvas);
				image.load(function(){

					var imageWidth = image.width();
					var imageHeight = image.height();
					var frameWidth = frame.width()-40;
					var frameHeight = frame.height()-40;

					// Вписываем картинку в размер окна,
					// если она шире, чем окно
					if(imageWidth > frameWidth) {

						imageWidth = frameWidth;
						image.width(imageWidth);					
						while(image.height() > frameHeight) {
							image.width(imageWidth);
							imageWidth--;
						}

						imageHeight = image.height();
					}

					// Вписываем картинку в размер окна,
					// если она выше, чем окно
					if(imageHeight > frameHeight) {

						imageHeight = frameHeight;
						image.height(imageHeight);						
						while(image.width() > frameWidth) {
							image.height(imageHeight);
							imageHeight--;
						}

						imageWidth = image.width();
					}

					// Анимируем загрузчик до размеров картинки
					// и одновременно смещаем к центру
					canvas.addClass('darkbox-canvas-load').animate({

						width:imageWidth,
						marginLeft:-imageWidth/2,
						height:imageHeight,
						marginTop:-imageHeight/2

					},500,function() {

						// После завершения анимации показываем кнопку и картинку
						canvas.addClass('darkbox-canvas-done');
						button.addClass('darkbox-button-on');
						button.addClass(navigator.platform.toLowerCase().indexOf('mac')+1?'darkbox-button-left':'darkbox-button-right');

						image.animate({opacity:1},500,function() {

							// Вешаем обработчики закрытия
							shadow.click(closer);
							button.click(closer);

						});
					});
				});

				// Функция закрытия попапа
				var closer = function() {
			
					canvas.remove();
					shadow.animate({opacity:0},300,function() {
						frame.remove();
					});
				}

				// Внимательно слушаем клавишу Esc
				$(document).keydown(function(e) {
					if(e.which==27) closer();
				});

				return false;
			});
		});