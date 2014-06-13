<?
	
	
	if(@$_GET['do']=='news' or $_GET['do']=='shop' or $_GET['do']=='page'){
		if (preg_match("/%content%/", $template)) { //ищем глобальную метку
			
			$module = $_GET['do'];
			
			$classContent = new Content($mysqli,$module);
			$classContent->categorylist();
			if(@!isset($_GET['link'])){
				$content = file_get_contents("template/{$module}mod/{$module}.html", true); // шаблоны модуля
			} 
			elseif(@$_GET['link']=='category'){
				if($module!="page"){
					if(@!isset($_GET['go'])){
						
						$content = $classContent->addcategory();
					}
					elseif(@$_GET['go']=='add'){
						if(count($_POST)!=0){
							$catselect = $mysqli->real_escape_string(clearinput($_POST['catselect']));
							$name = $mysqli->real_escape_string(clearinput($_POST['name']));
							$altname = $mysqli->real_escape_string(strtolower(clearinput($_POST['altname'])));
							$description = $mysqli->real_escape_string(clearinput($_POST['description']));
							$keywords = $mysqli->real_escape_string(clearinput($_POST['keywords']));
						}
						if(empty($_POST['name']) or empty($_POST['altname'])){
							$content = "Не заполнены обязательные поля!";
						}
						elseif(!empty($_POST['add'])){
							$mysqli->query("SELECT * FROM `cat{$module}` WHERE `translate`='{$altname};"); //цепляем категории
							if($mysqli->affected_rows == 0){
								$mysqli->query("INSERT INTO `cat{$module}` (`id`, `parrent_id`, `name`, `translate`, `description`, `keywords`) VALUES (NULL, '{$catselect}', '{$name}', '{$altname}', '{$description}', '{$keywords}');");
								$content = "Категория добавлена";
							} else { 
							
								$content = "Название на англиском(альтернативное имя) уже используется";
							}	
						}
						elseif(!empty($_POST['edit'])){
							$mysqli->query("SELECT * FROM `cat{$module}` WHERE `translate`='{$altname}' AND `id`!='{$_POST['id']}';"); //цепляем категории
							if($mysqli->affected_rows == 0){
								$mysqli->query("UPDATE `cat{$module}` SET `parrent_id`='{$catselect}', `name`='{$name}', `translate`='{$altname}', `description`='{$description}', `keywords`='{$keywords}' WHERE `id`='{$_POST['id']}';");
								$content = "Категория обновлена";
							} else {
							
								$content = "Название на англиском(альтернативное имя) уже используется";
							}
						}
						else{
							$content = "Неправильный запрос!";
						
						}
						
			
					}
					elseif(@$_GET['go']=='editcat'){
						if(!empty($_POST['idedcat'])){
							$content = $classContent->addcategory();
						}
						elseif(!empty($_POST['iddelcat'])){
							 $mysqli->query("DELETE FROM cat{$module} WHERE `id`='{$_POST['iddelcat']}'");
							 $mysqli->query("UPDATE `cat{$module}` SET `parrent_id`='0' WHERE `parrent_id`='{$_POST['iddelcat']}';");
							 $mysqli->query("UPDATE `{$module}` SET `catid`='0' WHERE `catid`='{$_POST['iddelcat']}';");
								$content = "Категория удалена";
						} 
						else {
						
							$content = "Неправильный запрос"; 
						
						}
					}
				} else{
					$content = "у этого модуля нет категорий";
				}
			}
			elseif(@$_GET['link']=="list"){
			
				$content = $classContent->contentlist();
			
			
			}
			elseif(@$_GET['link']=='add'){
				if(empty($_POST['add']) and empty($_POST['edit'])){
					
					
					
					$content = $classContent->addcontent();
					
				}
				else{
					if(empty($_POST['name']) or empty($_POST['altname']) or empty($_POST['author'])){
							$content = "Не заполнены обязательные поля!";
					}
					else{
						$name = $mysqli->real_escape_string(clearinput($_POST['name']));
						$author = $mysqli->real_escape_string(clearinput($_POST['author']));
						$altname = $mysqli->real_escape_string(clearinput($_POST['altname']));
						$fullcont = $_POST['fullcont'];
						$description = $mysqli->real_escape_string(clearinput($_POST['description']));
						$keywords = $mysqli->real_escape_string(clearinput($_POST['keywords']));
						if($module!="page"){
							if(!empty($_POST['add'])){
								$shortcont = ",'".$_POST['shortcont']."'";
								$short =",`shortcont`";
															
								$categcont = ", '{$_POST['catselect']}'";
								$categ = ", `catid`";
							}elseif(!empty($_POST['edit'])){
								$shortcont = "='{$_POST['shortcont']}'";
								$short =",`shortcont`";
								$previmgcont = "='{$_POST['previmg']}'";
								$previmg = ", `previmg`";
								$categcont = "='{$_POST['catselect']}'";
								$categ = ", `catid`";
							
							}
							
							
							
							 if (!empty($_FILES['imgupload']['name'])){
								if(!preg_match('/(.+)\.((JPG)|(jpg)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG))$/is',$_FILES['imgupload']['name'])){
									$sucfile = 0;
								}
								else{
								
									$path_directory = "../uploads/{$module}/";
								
									$format = preg_replace("/(.+)\.((JPG)|(jpg)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG))$/is","$2",$_FILES['imgupload']['name']);
								
											
										$filename = $_POST['id'].".".$format;
										$source = $_FILES['imgupload']['tmp_name'];	
										$target = $path_directory . $filename;
										move_uploaded_file($source, $target);
										if(!empty($_POST['add'])){
											$previmg = ", `previmg`";
											$previmgcont = ", 'uploads/{$module}/{$filename}'";
										}
										if(!empty($_POST['edit'])){
											$previmgcont = "='uploads/{$module}/{$filename}'";
										}
								}		
								
							}
						}
						
						
						
						if($module == "shop"){
							if(!empty($_POST['add'])){
								$costcont = ", '{$_POST['cost']}'";
								$cost = ", `cost`";
								$discostcont = ", '{$_POST['discost']}'";
								$discost = ", `discost`";
							
							}else{
								$costcont = "='{$_POST['cost']}'";
								$cost = ", `cost`";
								$discostcont = "='{$_POST['discost']}'";
								$discost = ", `discost`";
							}
						}
						$date = date("Y-m-d H:i:s");
						
						if(@!empty($_POST['add']) and !isset($sucfile)){
							$mysqli->query("INSERT INTO `{$module}` (`id`, `title`, `altname`, `fullcont`, `date`, `author`, `description`, `keywords` {$categ} {$cost} {$discost} {$short} {$previmg}) VALUES (NULL, '{$name}', '{$altname}', '{$fullcont}', '{$date}', '{$author}', '{$description}', '{$keywords}' {$categcont} {$costcont} {$discostcont} {$shortcont} {$previmgcont});");
							$content = "Контент добавлен";
						}
						elseif(@!empty($_POST['edit']) and !isset($sucfile)){
							$mysqli->query("UPDATE `{$module}` SET `title`='{$name}', `altname`='{$altname}', `author`='{$author}', `fullcont`='{$fullcont}', `date`='{$date}', `description`='{$description}', `keywords`='{$keywords}' {$categ}{$categcont} {$cost}{$costcont} {$discost}{$discostcont} {$short}{$shortcont} {$previmg}{$previmgcont} WHERE `id`='{$_POST['id']}';");
							echo $filename;
							$content = "Содержание обновлено";
						
						
						}
						if(isset($sucfile)){
							$content = "К загрузке допускаются только изображения";
						
						
						}
						
					}
				}
			}
			
			
		}
	}
	/*
	elseif(@$_GET['do']=='page'){
		if (preg_match("/%content%/", $template)) { //ищем глобальную метку
			
			$module = $_GET['do'];
			
			if(@!isset($_GET['link'])){
				$content = file_get_contents("template/{$module}mod/{$module}.html", true); // шаблоны модуля
			}elseif(@$_GET['link']=='add'){
				if(count($_POST)==0){
					$classContent = new Content($mysqli,$module); //заменяем метку модуля "news"
					$content = $classContent->addcontent();
					
				}
				elseif(@!empty($_POST['add'])){
					$name = clearinput($_POST['name']);
					$altname = clearinput($_POST['altname']);
					$fullcont = clearinput($_POST['fullcont']);
					$description = clearinput($_POST['description']);
					$keywords = clearinput($_POST['keywords']);
					$date = date("Y-m-d H:i:s");
					$mysqli->query("INSERT INTO `{$module}` (`id`, `title`, `altname`, `fullcont`, `date`, `description`, `keywords`) VALUES (NULL, '{$name}', '{$altname}', '{$fullcont}', '{$date}','{$description}', '{$keywords}');");
					$content = "Контент добавлен";
			
				}
			}
	
		}
		
	} */
	$template = str_replace("%content%", $content, $template); //Заменяем контент
	
	

?>
