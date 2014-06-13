<? 
Class Content extends Settings{
	public $db; 
	var $module;
	var $contentInfo;
	var $temp;
	var $typeCont;
	
	function __construct($db,$contentInfo,$temp,$module,$typeCont){
		$this->db = $db;
		$this->contentInfo = $contentInfo;
		$this->temp = $temp;
		$this->typeCont = $typeCont;
		$this->module = $module;
		parent::__construct($db);
	}
	function contentfunct(){
		if($this->typeCont=="short"){
			while($Cont=$this->contentInfo->fetch_assoc()){ 
			
				$content .= $this->fullcont($Cont);
						
			} //сортируем новости
			return $content;
		}
		elseif($this->typeCont=="full"){
			
			$Cont=$this->contentInfo->fetch_assoc();
			$this->db->query("UPDATE `{$this->module}` SET views = views + 1 WHERE `id`='{$Cont['id']}';"); 
			$metatitle = $Cont['title'];	//в полном содержимом модуля  метатитл равен заголовку статьи
			$moduleDescription = $Cont['description'];
			$moduleKeywords = $Cont['keywords'];
			$content = $this->fullcont($Cont);
			return array($content,$metatitle,$moduleDescription,$moduleKeywords);
		}
					
	}
		
		
		
	function fullcont($Cont){
		if($this->module=="shop"){
			$content = "<div class=\"goodsbox\">";
		}
		$content .= $this->temp;  //подгружаем шаблон превью контента (для каждого модуля свой)
		$content = str_replace("%title%", $Cont['title'], $content); //Заменяем в превью содержимом модуля заголовок
		$content = str_replace("%{$this->typeCont}{$this->module}%", $Cont[$this->typeCont.'cont'], $content); //Заменяем в превью содержимом модуля содержание
		$content = str_replace("%full{$this->module}link%", "/{$this->module}/{$Cont['id']}-{$Cont['altname']}.html", $content);	//Заменяем в превью содержимом модуля ссылку на полное содержание
		$content = str_replace("%date%", $Cont['date'], $content);
		$content = str_replace("%author%", $Cont['author'], $content);
		$content = str_replace("%views%", $Cont['views'], $content);
		if(!empty($Cont['previmg'])){$content = str_replace("%previmg%", $Cont['previmg'], $content);}else {$content = str_replace("%previmg%", "", $content);}
		
		if($this->module=="shop"){
			$content = str_replace("%cost%", $Cont['cost'], $content);
				
			$content = str_replace("%discost%", "<span class=\"goodsboxprice{$Cont['id']}\">{$Cont['discost']}</span>", $content);
			$content = str_replace("%goodscount%", "<input type=\"number\" class=\"goodsnumb{$Cont['id']}\" value=\"1\">", $content);
		
				 
			$content = showXfields($Cont['id'],$this->module,$content,$this->db);
			$content = preg_replace("/\[addcart_button\](.*?)\[\/addcart_button\]/is", "<input type=\"button\" class=\"addcartbutton\" id=\"{$Cont['id']}\" value=\"$1\">", $content);
			
			$content .= "</div>";
			
			
			if($this->typeCont=="full"){
				$temp = file_get_contents("template/{$this->config['skin']}/reviews/reviews.html", true);
				//// ОТЗЫВЫ /////////////////////////////////////////////////////////////////////////////////////
				if($this->config['reviews']['on']==1){
					/*
					if(!empty($_SESSION['userlogin'])){	
						$offInfo = $this->db->query("SELECT `allowreviews` FROM `shop` WHERE `id`='{$Cont['id']}' LIMIT 1;");
						$off=$offInfo->fetch_assoc();
						if($off['allowreviews']==1){
							$reviewsInfo = $this->db->query("SELECT `reviews`.*,`users`.`login`,`users`.`email`,`users`.`avatar` FROM `reviews`,`users` WHERE `reviews`.`goodsid`='{$Cont['id']}' AND `users`.`id`=`reviews`.`userid`;");
							if($this->db->affected_rows==0){
								$reviewscont = preg_replace("/(.*)\[no_reviews\](.*?)\[\/no_reviews\](.*)/is", "$2", $temp);
							
							}
							else{
								$i=1;
								while($reviews=$reviewsInfo->fetch_assoc()){ 
									
									$reviewscont .= preg_replace("/(.*)\[reviews_list\](.*?)\[\/reviews_list\](.*)/is", "$2", $temp);
									$reviewscont = str_replace("%id%", $i, $reviewscont);
									$reviewscont = str_replace("%username%", $reviews['login'], $reviewscont);
									$reviewscont = str_replace("%user_avatar%", $reviews['avatar'], $reviewscont);
									$reviewscont = str_replace("%rating%", $reviews['rating'], $reviewscont);
									$reviewscont = str_replace("%dignity%", $reviews['dignity'], $reviewscont);
									$reviewscont = str_replace("%luck%", $reviews['luck'], $reviewscont);
									$reviewscont = str_replace("%comment%", $reviews['comment'], $reviewscont);
									$i++; 
								}
								
							}
							
							
								$errorReviewsInfo = $this->db->query("SELECT * FROM `reviews` WHERE `goodsid`='{$Cont['id']}' AND (`userid`='{$_SESSION['userid']}' OR `ip`='{$_SERVER['REMOTE_ADDR']}');");
								if($this->db->affected_rows==0){
									
									
									
									if(empty($_POST['addreview'])){
										$addreviews = preg_replace("/(.*)\[add_reviews\](.*?)\[\/add_reviews\](.*)/is", "<form method=\"POST\" action=\"{$_SERVER['REQUEST_URI']}\">$2</form>", $temp);
										$addreviews = str_replace("%dignity%", "<textarea name=\"dignity\" ></textarea>", $addreviews);
										$addreviews = str_replace("%luck%", "<textarea name=\"luck\" ></textarea>", $addreviews);
										$addreviews = str_replace("%comment%", "<textarea name=\"comment\" ></textarea>", $addreviews);
										$addreviews = str_replace("%rating%", "<select name=\"rating\" ><option selected>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select>", $addreviews);
										$addreviews = str_replace("%submit%", "<input type=\"submit\" name=\"addreview\" >", $addreviews);
									} else {
									
										$this->db->query("INSERT INTO `reviews` (`id`, `userid`, `goodsid`, `comment`, `luck`, `dignity`, `rating`,`ip`) VALUES (NULL, '{$_SESSION['userid']}', '{$Cont['id']}', '{$_POST['comment']}', '{$_POST['luck']}', '{$_POST['dignity']}', '{$_POST['rating']}', '{$_SERVER['REMOTE_ADDR']}');");
										$addreviews = preg_replace("/(.*)\[reviews_successful\](.*?)\[\/reviews_successful\](.*)/is", "$2", $temp);		
									
									
									}
								} else {
								
									$addreviews = preg_replace("/(.*)\[reviews_error\](.*?)\[\/reviews_error\](.*)/is", "$2", $temp);	
								
								
								
								}
								$content = str_replace("%addreviews%", $addreviews, $content);
							
						} else {
						
							$reviewscont = preg_replace("/(.*)\[off_reviews\](.*?)\[\/off_reviews\](.*)/is", "$2", $temp);
						
						}
							$content = str_replace("%reviews%", $reviewscont, $content);
							$content = str_replace("%addreviews%", "", $content);
					} 
					else {
						$reviewscont = preg_replace("/(.*)\[reviews_error\](.*?)\[\/reviews_error\](.*)/is", "$2", $temp);
						$content = str_replace("%reviews%", $reviewscont, $content);
						$content = str_replace("%addreviews%", "", $content);
					
					
					}*/
						$content = str_replace("%reviews%", "<div class=\"showreviews\" id=\"cont{$Cont['id']}\"></div>", $content);
						$content = str_replace("%addreviews%", "<div class=\"addreviews\" id=\"cont{$Cont['id']}\"></div>", $content);
				}	$content = str_replace("%reviews%", "", $content);
					$content = str_replace("%addreviews%", "", $content);
				
			}
			
		}
		return $content;
	
	}
	
}


?>