<?
Class Content {
	public $db; 
	public $module;
	public $cat;
	public $catSelect;
	
	function __construct($mysqli,$module){
		$this->db = $mysqli;
		$this->module = $module;
		
	}
	
 function categorylist(){
 if($this->module!="page"){
						$catInfo = $this->db->query("SELECT `id`,`parrent_id`,`name`,`translate` FROM `cat{$this->module}`;"); //цепляем категории
						$cat="";
						while($this->catSelect[]=$catInfo->fetch_assoc()){}
						foreach($this->catSelect as $byaka => $chief){	//шаманим, получая категории 1го уровня
						
							if($chief['parrent_id'] == "0"){
								$cat .="<ul>";
								$cat .= "<li><p class=\"catactive\" id=\"pic{$chief['id']}\">{$chief['name']}</p></li>"; //выврлим имя с линком
								$validmenu = $chief['id'];
								foreach($this->catSelect as $byaka2 => $chief2){	//шаманим, получая подкатегории 1го уровня

									if($chief2['parrent_id'] == $validmenu){
										$cat .= "<li><p  class=\"catactive\" id=\"pic{$chief2['id']}\">{$chief2['name']}</p>";	//выврлим имя с линком
										$validmenu2 = $chief2['id'];
										$cat .="<ul>";
											foreach($this->catSelect as $byaka3 => $chief3){	//шаманим, получая подкатегории 2го уровня
												
												if($chief3['parrent_id'] == $validmenu2){
												
												$cat .= "<li><p class=\"catnoactive\" id=\"pic{$chief3['id']}\">{$chief3['name']}</p></li>";	//выврлим имя с линком
												
												}
											
											}
										$cat .="</ul>";
										$cat .= "</li>";
									
									}
									
								}	$cat .="</ul>";
							}
							
					
						}
						$cat = str_replace("<ul></ul>","", $cat);
	$this->cat = $cat;
	}
 }
  function addcategory(){
 
	 $temp = file_get_contents('template/category/addcategory.html', true); // шаблоны модуля
	 if($_GET['go']!="editcat"){
		$content = str_replace("%link%", "/admin/index.php?do={$this->module}&link=category&go=add", $temp);
		$content = str_replace("%action%", "Добавление", $content);
		$content = str_replace("%name%", "", $content);
		$content = str_replace("%altname%", "", $content);
		$content = str_replace("%description%", "", $content);
		$content = str_replace("%keywords%", "", $content);
		$content = str_replace("%parid%", "0", $content);
		$content = str_replace("%id%", "0", $content);
		$content = str_replace("%button%", "Добавить", $content);
		$content = str_replace("%butname%", "add", $content);
		$content = preg_replace("/\[catlist\](.*)\[\/catlist\]/is", "$1", $content);
		
		$cateditlisttemp = file_get_contents('template/category/cateditlist.html', true); // шаблоны модуля

		foreach($this->catSelect as $byaka => $chief){	//шаманим, получая категории 1го уровня
		
			if($chief['parrent_id'] == "0"){
					 $cateditlist .= str_replace("%id%", "<div class=\"catlistbox cslvl1 glbox\"><p class=\"csboxid\">id:".$chief['id']."</p>", $cateditlisttemp);
					  $cateditlist = str_replace("%edit%", "<p class=\"cseditbut\"><input type=\"image\" src=\"template/images/edit_action.png\" name=\"idedcat\" value=\"{$chief['id']},{$chief['parrent_id']}\"></p></div>", $cateditlist);
					 $cateditlist = str_replace("%name%", "<p class=\"csboxname\"><a href=\"/shop/{$chief['translate']}/\" target=\"_blank\" title=\"{$chief['name']}\">".$chief['name']."</a></p>", $cateditlist);
					 $cateditlist = str_replace("%delete%", "<p class=\"csdelbut\"><input type=\"image\" src=\"template/images/delete.png\" name=\"iddelcat\" value=\"{$chief['id']}\"></p>", $cateditlist);
				$validmenu = $chief['id'];
				foreach($this->catSelect as $byaka2 => $chief2){	//шаманим, получая подкатегории 1го уровня

					if($chief2['parrent_id'] == $validmenu){
						$cateditlist .= str_replace("%id%", "<div class=\"catlistbox cslvl2 glbox\"><p class=\"csboxid\">id:".$chief2['id']."</p>", $cateditlisttemp);
						$cateditlist = str_replace("%delete%", "<p class=\"csdelbut\"><input type=\"image\" src=\"template/images/delete.png\" name=\"iddelcat\" value=\"{$chief2['id']}\"></p>", $cateditlist);
						$cateditlist = str_replace("%name%", "<p class=\"csboxname\"><a href=\"/shop/{$chief2['translate']}/\" target=\"_blank\" title=\"{$chief2['name']}\">".$chief2['name']."</a></p>", $cateditlist);
						$cateditlist = str_replace("%edit%", "<p class=\"cseditbut\"><input type=\"image\" name=\"idedcat\"  src=\"template/images/edit_action.png\" value=\"{$chief2['id']},{$chief2['parrent_id']}\"></p></div>", $cateditlist);
						$validmenu2 = $chief2['id'];
						
							foreach($this->catSelect as $byaka3 => $chief3){	//шаманим, получая подкатегории 2го уровня
								
								if($chief3['parrent_id'] == $validmenu2){
								 
									$cateditlist .= str_replace("%id%", "<div class=\"catlistbox cslvl3 glbox\"><p class=\"csboxid\">id:".$chief3['id']."</p>", $cateditlisttemp); 
									$cateditlist = str_replace("%delete%", "<p class=\"csdelbut\"><input type=\"image\" src=\"template/images/delete.png\" name=\"iddelcat\" value=\"{$chief3['id']}\"></p>", $cateditlist);
									$cateditlist = str_replace("%edit%", "<p class=\"cseditbut\"><input type=\"image\"  src=\"template/images/edit_action.png\" name=\"idedcat\" value=\"{$chief3['id']},{$chief3['parrent_id']}\"></p></div>", $cateditlist);
									$cateditlist = str_replace("%name%", "<p class=\"csboxname\"><a href=\"/shop/{$chief3['translate']}/\" target=\"_blank\" title=\"{$chief3['name']}\">".$chief3['name']."</a></p>", $cateditlist);
								}
							
							}
					
					}
					
				}	
			}
			
	
		}
		$content = str_replace("%cateditlist%", $cateditlist, $content);
	 
	 }else {
		if(!empty($_POST['idedcat'])){
			
			$content = str_replace("%link%", "/admin/index.php?do={$this->module}&link=category&go=add", $temp);
			$content = str_replace("%action%", "Редактирование", $content);
			$category = explode(",", $_POST['idedcat']);
			$content = str_replace("%parid%", $category[1], $content);
			$content = str_replace("%id%", $category[0], $content);
			$thisCat = $this->db->query("SELECT * FROM `cat{$this->module}` WHERE `id`='{$category[0]}';");
			$catInfo=$thisCat->fetch_assoc();
			$content = str_replace("%name%", $catInfo['name'], $content);
			$content = str_replace("%altname%", $catInfo['translate'], $content);
			$content = str_replace("%description%", $catInfo['description'], $content);
			$content = str_replace("%keywords%", $catInfo['keywords'], $content);
			$content = str_replace("%button%", "Редактировать", $content);
			$content = str_replace("%butname%", "edit", $content);
			$content = preg_replace("/\[catlist\](.*)\[\/catlist\]/is", "", $content);
		}
	 
	 }
	 $content = str_replace("%mod%", $this->module, $content);
	 $content = str_replace("%cat%", $this->cat, $content);
	 

	 
	 
	 
	 
	 return $content;
 }
	function addcontent(){
	
		$temp = file_get_contents("template/{$this->module}mod/addcontent.html", true); // шаблоны модуля
		$content = str_replace("%link%", "/admin/index.php?do={$this->module}&link=add", $temp);
		$content = str_replace("%cat%", $this->cat, $content);
		
		if(@empty($_GET['id'])){
			$contInfo=$this->db ->query("SELECT * FROM `{$this->module}` ORDER BY `id` DESC LIMIT 1;");
			$cont=$contInfo->fetch_assoc();
			$id = $cont['id'] + 1;
			$content = str_replace("%name%", "", $content);
			$content = str_replace("%altname%", "", $content);
			$content = str_replace("%description%", "", $content);
			$content = str_replace("%keywords%", "", $content);
			$content = str_replace("%but_action%", "add", $content);
			$content = str_replace("%butval%", "Добавить", $content);
			$content = str_replace("%fullcont%", "", $content);
			$content = str_replace("%id%", $id, $content);
			if($this->module!="page"){
			
				$content = str_replace("%catid%", "0", $content);
				$content = str_replace("%shortcont%", "", $content);
				$content = str_replace("%previmg%", "<tr><td>Загрузить изображение</td><td><input type=\"FILE\" name=\"imgupload\"></td></tr>", $content);
			
			}
			if($this->module=="shop"){
				$content = str_replace("%addname%", "Добавление товара", $content);
				$content = str_replace("%cost%", "", $content);
				$content = str_replace("%discost%", "", $content);
				
			
			}
			if($this->module=="news"){
				$content = str_replace("%addname%", "Добавление новости", $content);
			}
			
			if($this->module=="page"){
				$content = str_replace("%addname%", "Добавление страницы", $content);
			}
		}
		else{
			if(is_numeric($_GET['id'])){
				$contInfo=$this->db ->query("SELECT * FROM `{$this->module}` WHERE `id`='{$_GET['id']}';");
				$cont=$contInfo->fetch_assoc();
				$content = str_replace("%name%", $cont['title'], $content);
				$content = str_replace("%altname%", $cont['altname'], $content);
				$content = str_replace("%description%", $cont['description'], $content);
				$content = str_replace("%keywords%", $cont['keywords'], $content);
				$content = str_replace("%but_action%", "edit", $content);
				$content = str_replace("%butval%", "Редактировать", $content);
				$content = str_replace("%fullcont%", $cont['fullcont'], $content);
				$content = str_replace("%id%", $cont['id'], $content);
				if($this->module!="page"){
				
					$content = str_replace("%catid%", $cont['catid'], $content);
					$content = str_replace("%shortcont%", $cont['shortcont'], $content);
					if(!empty($cont['previmg'])){$content = str_replace("%previmg%", "<tr><td>Изображение:</td><td><input type=\"hidden\" name=\"previmg\" value=\"{$cont['previmg']}\"><img src=\"../{$cont['previmg']}\" style=\"max-width: 200px;\" ></td></tr><tr><td>Заменить изображение</td><td><input type=\"FILE\" name=\"imgupload\"></td></tr>", $content);} else {$content = str_replace("%previmg%", "<tr><td>Загрузить изображение</td><td><input type=\"FILE\" name=\"imgupload\"></td></tr>", $content); }
				
				}
				if($this->module=="shop"){
					$content = str_replace("%addname%", "Редактирование товара", $content);
					$content = str_replace("%cost%", $cont['cost'], $content);
					$content = str_replace("%discost%", $cont['discost'], $content);
					
				
				}
				if($this->module=="news"){
				$content = str_replace("%addname%", "Редактирование новости", $content);
				}
			
				if($this->module=="page"){
					$content = str_replace("%addname%", "Редактирование страницы", $content);
				}
			}else{
				$content = "fuck you hacker";
			}
		
		}
		$content = str_replace("%author%", $_SESSION['userlogin'], $content);
		return $content;
	}
	
	
	
	function contentlist(){
		
		$temp = file_get_contents("template/{$this->module}mod/contlist.html", true); 
		$content = preg_replace("/(.*)\[head_contlist\](.*?)\[\/head_contlist\](.*)/is", "$2", $temp);
		$contInfo=$this->db ->query("SELECT * FROM `{$this->module}` ORDER BY `date` DESC;");
		while($cont=$contInfo->fetch_assoc()){
	
			$content .= preg_replace("/(.*)\[body_contlist\](.*?)\[\/body_contlist\](.*)/is", "$2", $temp);
			$date = substr($cont['date'], 0, 10);
			$date = str_replace("-", ".", $date);
			$content = str_replace("%date%", $date, $content);
			$content = str_replace("%name%", "<a href=\"index.php?do={$this->module}&link=add&id={$cont['id']}\" title=\"Редактировать\">{$cont['title']}</a>", $content);
			if($this->module=="shop"){
				
				
					$content = str_replace("%listname%", "товаров", $content);
			}elseif($this->module=="news"){
				
					
					$content = str_replace("%listname%", "новостей", $content);
			}elseif($this->module=="page"){
				
				
					$content = str_replace("%listname%", "страниц", $content);
			}
			
			
			
			
			
			
			if($this->module!="page"){
				if($this->module=="shop"){
				
					$content = str_replace("%discost%", $cont['discost'], $content);
					
				}
				if($cont['catid']!=0){
					$catInfo=$this->db ->query("SELECT * FROM `cat{$this->module}` WHERE `id`='{$cont['catid']}';");
					$catname=$catInfo->fetch_assoc();
					$content = str_replace("%category%", $catname['name'], $content);
				}else {
					$content = str_replace("%category%", "---", $content);
				}
			}
			$content = str_replace("%author%", $cont['author'], $content);
			$content = str_replace("%views%", $cont['views'], $content);
			
		}
		
		
		$content .= preg_replace("/(.*)\[bottom_contlist\](.*?)\[\/bottom_contlist\](.*)/is", "$2", $temp);
		return $content;
	}
	
	
	
	
	
	
}





function clearinput($string){

	$string = htmlspecialchars($string);
	if(get_magic_quotes_gpc()==1){
		$string=stripslashes(trim($string));
	}
	else{
		$string=trim($string);
	}
	return $string;
}
?>
