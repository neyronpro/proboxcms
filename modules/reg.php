<?

			if (empty($_SESSION['userlogin']) ){
				$temp = file_get_contents('./template/'.$config['skin'].'/reg.html', true);
				if(count($_POST)==0){
				
						$content = $temp;
				}
				else {
					if (isset($_POST['reglogin'])) { $reglogin = $_POST['reglogin']; if ($reglogin == '') { unset($reglogin);} } //заносим введенный пользователем логин в переменную $reglogin, если он пустой, то уничтожаем переменную
					if (isset($_POST['regpass'])) { $regpass=$_POST['regpass']; if ($regpass =='') { unset($regpass);} }
					if (isset($_POST['regmail'])) { $regmail=$_POST['regmail']; if ($regmail =='') { unset($regpass);} }
					
					if (empty($reglogin) or empty($regpass) or empty($regmail)){
						$info = "<span style=\"color:red;\">Заполните все поля!</span><br>";
						$content = $temp;
					} 
					else {
						$reglogin = htmlspecialchars($reglogin);
						$regpass = htmlspecialchars($regpass);
						$regmail = htmlspecialchars($regmail);
						$reglogin = trim($reglogin);
						$regpass = trim($regpass);
						$regpass = md5(sha1($regpass));
						$regmail = trim($regmail);
						$result=$mysqli->query("SELECT COUNT(*) FROM `users` WHERE `login` = '{$reglogin}' OR `email`='{$regmail}' LIMIT 1;");
						$row = $result->fetch_row();

						if($row[0]!=0)
						{
							$info = "Пользователь или E-mail уже существуют";
							$content = $temp;				
						} else {
						$mysqli->query("INSERT INTO `users` (login,pass,email) VALUES('{$reglogin}','{$regpass}','{$regmail}');");
							$info = "Поздравляем! Можете <a href=\"?module=profile\">Авторизироваться</a>";
						
						
						}
					}
				}
				
			} 
			else {
				$info = "Вы уже зарегистрированы";
			}

?>
