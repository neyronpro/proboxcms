<?
	
	$tpl->set("%news%", "");
	$tpl->set("%shop%", "");
	
	if(@$_GET['module']=='news'){
		
///Настройки модуля/////			  
			
			$module = "news";  // ОБЯЗАТЕЛЬНОЕ НАЗВАНИЕ МОДУЛЯ!!!
		
			$metatitle = $settings->config['news']['metatitle']; //Метатитл модуля
			$moduleDescription = $settings->config['news']['description'] ;
			$moduleKeywords = $settings->config['news']['keywords'];
			
	////////Подключаем глобальный модуль контента////////////////	
			$pageCount = $settings->config['news']['pagecount']; //Кол-во на страницу.
			
			require_once "modules/gl_module_content.php";
		
	}
	
	elseif(@$_GET['module']=='shop'){
		
///Настройки модуля/////			
			$module = "shop";  // ОБЯЗАТЕЛЬНОЕ НАЗВАНИЕ МОДУЛЯ!!!
			$metatitle = $settings->config['shop']['metatitle']; //Метатитл модуля
			$moduleDescription = $settings->config['shop']['description'] ;
			$moduleKeywords = $settings->config['shop']['keywords'];
			
			
			$pageCount = $settings->config['shop']['pagecount']; //Кол-во на страницу.
			////////Подключаем глобальный модуль контента////////////////
			
			require_once "modules/gl_module_content.php";
		
	}
	
	
	elseif(@$_GET['module']=='reg'){						//модуль регистрации
		
			$metatitle = "Магазин"; //Метатитл модуля
			$moduleDescription = "Самый охуенный магазин";
			$moduleKeywords = "ма, га, зин";
			
			require_once "modules/reg.php";
		
	}
	elseif(@$_GET['module']=='profile'){						//модуль авторизации
		
			$metatitle = "Личный кабинет пользователя"; //Метатитл модуля
			$moduleDescription = "Личный кабинет пользователя";
			$moduleKeywords = "личный, кабинет";
			require_once "modules/profile.php";
		
	}	
	
	
	elseif(@$_GET['module']=='search'){						//модуль регистрации
		
			$metatitle = "Магазин"; //Метатитл модуля
			$moduleDescription = "Самый охуенный магазин";
			$moduleKeywords = "ма, га, зин";
			
			if(empty($_POST)){
			
				 $tpl->set_tpl("search", "search");
				$content = $tpl->modtpl;
			
			} 
			else{
				$search = htmlspecialchars($_POST['search']);
				$search = $mysqli->real_escape_string($search);
				
				
				if (strlen($search) < 3) {
					$info = '<p>Слишком короткий поисковый запрос.</p>';
				} 
				else if (strlen($search) > 128) {
					$info = '<p>Слишком длинный поисковый запрос.</p>';
				} 
				else { 
			
					$searchInfo=$mysqli->query("SELECT * FROM `shop` WHERE `title` LIKE '%{$search}%' OR `fullcont` LIKE '%{$search}%' ;");
					if($mysqli->affected_rows==0){
					
						$info = "По данному запросу новости не найдены";
						
					} 
					else {
					function ungame($matches){
								$one = strip_tags(substr($matches[1],-120));
								$two = "<font color=\"red\">{$matches[2]}</font>";
								$three = strip_tags(substr($matches[3],0,100));
								return $one.$two.$three;
								}
						while($searcher=$searchInfo->fetch_assoc()){				
							$stitle = preg_replace("/{$search}/is", "<font color=\"green\">{$search}</font>", $searcher['title']);
							$content .= "<a href=\"shop/{$searcher['id']}-{$searcher['altname']}.html\">{$stitle}</a><br /><br />";
							$intitle = stripos($searcher['fullcont'],$search);
							
							if($intitle!==false){
							
								
								$content .= preg_replace_callback("/(.*)({$search})(.*)/is", "ungame", $searcher['fullcont']);
								$content .= "<br/>";
							}
							
						}
						
						
						
					}
			
				}
			
			
			}
			
		
	}
	elseif(@$_GET['module']=='cart'){						//модуль авторизации
		
			$metatitle = "Магазин"; //Метатитл модуля
			$moduleDescription = "Самый охуенный магазин";
			$moduleKeywords = "ма, га, зин";
			$tpl->set_tpl("cart","cartlist");
		
			require_once "modules/module_cart.php";
			
		
	}
	elseif(@$_GET['module']=='page'){
		
				
				$module = "page";  // ОБЯЗАТЕЛЬНОЕ НАЗВАНИЕ МОДУЛЯ!!!
				$tpl->set_tpl($module,"page");
				
			if(@empty($_GET['id']) or empty($_GET['altname']) or !is_numeric($_GET['id'])){
				header('HTTP/1.1 404 Not Found');
				$info = "По данному запросу новости не найдены";
				
			}
			
			else {
				
				$id =$mysqli->real_escape_string((int)$_GET['id']);
				$altname =$mysqli->real_escape_string((htmlspecialchars(stripcslashes($_GET['altname']))));
				$contentInfo=$mysqli->query("SELECT * FROM `{$module}` WHERE `altname`='{$altname}' AND `id`='{$id}' LIMIT 1;");
				if($mysqli->affected_rows==0){ // если категории не существует
					
					header('HTTP/1.1 404 Not Found');
					
					$info = "По данному запросу новости не найдены";
					
				}
				else{ 
					$typeCont = "full";
					$classContent = new Content($mysqli,$contentInfo,$tpl->modtpl,$module,$typeCont);
					$conArray = $classContent->contentfunct(); //массив внутри которого $content и $metatitle
					$content=$conArray[0];
					$metatitle=$conArray[1];
					$moduleDescription = $conArray[2];
					$moduleKeywords = $conArray[3];
				}
			

					
			}
			
		
	}	
	else {
	header('HTTP/1.1 404 Not Found');
			
			$info = "По данному запросу новости не найдены";
	}
	
	
	
	
	
	$tpl->set("%content%",$content); 
	$tpl->set_tags($metatitle,$moduleDescription,$moduleKeywords);
	
	

?>
