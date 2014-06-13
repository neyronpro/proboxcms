<?
											//
		if (empty($_GET['page']) or $_GET['page']==1 or $_GET['page']==-0 ){					//
			$p = 0;		
			$cpage=1;	
						// если странца не задана, то будем на 1й
		}																// зачатки пагинации
		else{		
			if(!is_numeric($_GET['page'])){
				$p = 0;		
				$cpage=1;
				header('HTTP/1.1 404 Not Found');}
			else {
				$p = (abs($_GET['page'])-1)*$pageCount;	
				$cpage=abs($_GET['page']);//
			}
		}																//
		//if($module == "shop"){									//
			if(empty($_POST['dateorder'])){
				$sort = "`date` DESC";
			
			}
		//}

if(@empty($_GET['id']) and empty($_GET['altname'])){	//если не задан id
		
	$typeCont = "short";
	$tpl->set_tpl($module,"short");
	if(@!isset($_GET['catname'])){ 					//Если категория не выбрана
		
							
		$contentInfo=$mysqli->query("SELECT * FROM `{$module}` ORDER BY {$sort} LIMIT {$p}, {$pageCount};");
		
		if($mysqli->affected_rows==0){ // если категории не существует
		
			$info = "По данному запросу материалы не найдены";
		
		} 
		else {
			
			$pagInfo=$mysqli->query("SELECT * FROM `{$module}`;"); //инфо для пагинации
			if($mysqli->affected_rows > $pageCount){
				$itemscount = $mysqli->affected_rows;
				$pagination = 1;
			}
			
			$classContent = new Content($mysqli,$contentInfo,$tpl->modtpl,$module,$typeCont);
			$content = $classContent->contentfunct();
			
			
		}
	}   
	else { //если категория выбрана
		$catname = $mysqli->real_escape_string(htmlspecialchars($_GET['catname']));
		$catInfo=$mysqli->query("SELECT * FROM `cat{$module}` WHERE `translate`='{$catname}';");	//цепляем категорию
		if($mysqli->affected_rows==0){ // если категории не существует
		
			header('HTTP/1.1 404 Not Found');
		
			$info = "По данному запросу материалы не найдены";
		
		} else {
		$catSelect=$catInfo->fetch_assoc();
		$metatitle = $metatitle." - Категория: ".$catSelect['name']; 	//метатеги
		$moduleDescription = $catSelect['description'];
		$moduleKeywords = $catSelect['keywords'];
		$podcatInfo=$mysqli->query("SELECT `id`,`parrent_id` FROM `cat{$module}`;"); //цепляем подкатегории
		$sq="";
		while($podcat[]=$podcatInfo->fetch_assoc()){}  //перебираем массив с категориями
	
			foreach($podcat as $key => $value){	//шаманим, получая подкатегории 2 и 3 уровней
		
				if($value['parrent_id'] == $catSelect['id']){ 
		
					
					$sq .= "OR `catid`='{$value['id']}' ";
					$valid=$value['id'];
					foreach($podcat as $key2 => $value2){
						if($value2['parrent_id'] == $valid){
		
							$sq .= "OR `catid`='{$value2['id']}' ";
					}
						}
		
				}
		
		
			}
			
				$contentInfo=$mysqli->query("SELECT * FROM `{$module}` WHERE `catid`='{$catSelect['id']}' {$sq} ORDER BY {$sort} LIMIT {$p}, {$pageCount} ;"); //цепляем новости по выбранным категориям
				if($mysqli->affected_rows==0){ // если новостей нет
		
					header('HTTP/1.1 404 Not Found');
		
						$info = "По данному запросу материалы не найдены";
		
				} else {
					$pagInfo=$mysqli->query("SELECT * FROM `{$module}`  WHERE `catid`='{$catSelect['id']}' {$sq};"); //инфо для пагинации
					if($mysqli->affected_rows > $pageCount){
						$itemscount = $mysqli->affected_rows;
						$pagination = 1;
					}
					
					
					$classContent = new Content($mysqli,$contentInfo,$tpl->modtpl,$module,$typeCont);
					$content = $classContent->contentfunct();
					
					
				
				}
		}}
		
		
} 
else {	//если задан id
		if(@empty($_GET['id']) or empty($_GET['altname']) or !is_numeric($_GET['id'])){
		
			header('HTTP/1.1 404 Not Found');
			
			$info = "По данному запросу материалы не найдены";
		} 
		
		else {
			$id =$mysqli->real_escape_string((int)$_GET['id']);
			$altname =$mysqli->real_escape_string((htmlspecialchars(stripcslashes($_GET['altname']))));
			$contentInfo=$mysqli->query("SELECT * FROM `{$module}` WHERE `altname`='{$altname}' AND `id`='{$id}' LIMIT 1;");
			if($mysqli->affected_rows==0){ // если категории не существует
				
				header('HTTP/1.1 404 Not Found');
				
				$info = "По данному запросу материалы не найдены";
				
			} 
			else{
				
				$typeCont = "full";
				$tpl->set_tpl($module,"full");
				
				$classContent = new Content($mysqli,$contentInfo,$tpl->modtpl,$module,$typeCont);
				$conArray = $classContent->contentfunct();
				
				$content=$conArray[0];
				$metatitle=$conArray[1];
				$moduleDescription = $conArray[2];
				$moduleKeywords = $conArray[3];
			}
		}
		
}
			
?>
