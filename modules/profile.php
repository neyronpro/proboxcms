<?  

if (empty($_SESSION['userlogin']) ){ // если не авторизирован
		$tpl->set_tpl("profile","login");
				if(count($_POST)==0){ 
				
					$content = $tpl->modtpl;
				}
				else { // если пытается авторизоваться
					if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } //заносим введенный пользователем логин в переменную $reglogin, если он пустой, то уничтожаем переменную
					if (isset($_POST['pass'])) { $pass=$_POST['pass']; if ($pass =='') { unset($pass);} }
					
						
					if (empty($login) or empty($pass)){ // если не ввел логин или пароль
						$info = "<span style=\"color:red;\">Заполните все поля!</span><br>";
						$content = $tpl->modtpl;
					}
				
					else { // если ввел и логин и пароль
						$login = htmlspecialchars($login);												//
						$pass = htmlspecialchars($pass);												// очищаем от херни
						$login = trim($login);															// и шифруем пароль
						$pass = trim($pass);															//
						$pass = md5(sha1($pass));														//
						$result= $mysqli->query("SELECT * FROM `users` WHERE `login`='{$login}';");
						$row = $result->fetch_array();
						
						if(empty($row['id']) or ($row['pass']!=$pass)) {				// если логина в базе нет
							$info = "Логин или пароль верный!";
							$content = $tpl->modtpl;
						}
						
						else { // если успешно авторизовался
							
							
							$_SESSION['userlogin'] = $login;
							$_SESSION['email'] = $row['email'];
							if(!empty($row['avatar'])){$_SESSION['avatar'] = $row['avatar'];}else {$_SESSION['avatar']="/template/".$settings->config['skin']."/proboximg/noavatar.png";} //грузим аватар
							$info = "Логин или пароль верный!";
							$_SESSION['group'] = $row['group'];
							header("Location: {$_SERVER['HTTP_REFERER']}");
						}
					}
				}
				
				
			} 
			else {		// если уже авторизован
				
				if(@$_GET['logout']=="1"){ //Если послал запрос на выход
					$_SESSION = array(); 
					unset($_SESSION);
 					session_destroy();
					header("Location: {$_SERVER['HTTP_REFERER']}");
				} 
				elseif(empty($_GET['page'])){ //Если не указана страница - выводим страницу профиля
					$content = "Уже авторизирован";
				}
				elseif(@$_GET['page']=="orders"){
					$tpl->set_tpl("profile","orders");
					
					if(empty($_POST['showorder'])){
						
						$ordersInfo=$mysqli->query("SELECT * FROM `orders` WHERE `user`='{$_SESSION['userlogin']}';");
						if($mysqli->affected_rows==0){
						
							$info = "Вы не делали заказов";
						} else {
								
								$content = "<form action=\"/profile/orders/\" method=\"POST\">"; 
								$content .= preg_replace("/(.*)\[orders_head\](.*?)\[\/orders_head\](.*)/is", "$2", $tpl->modtpl);
								while($orders=$ordersInfo->fetch_assoc()){
									
									$content .= preg_replace("/(.*)\[orders_list\](.*?)\[\/orders_list\](.*)/is", "$2", $tpl->modtpl);
									$content = str_replace("%numb_order%", $orders['id'], $content); 
									$content = str_replace("%fnm%", $orders['fio'], $content); 
									$content = str_replace("%contacts%", $orders['email']."<br />".$orders['phone'], $content);
									$comment = str_replace(" ", "<br />", $orders['comment']);
									$adress = str_replace(" ", "<br />", $orders['adress']);
									$comment = preg_replace("/\n/", "<br />", $comment);
									$adress = preg_replace("/\n/", "<br />", $adress);
								
									$content = str_replace("%adress%", $adress, $content); 
									$content = str_replace("%comment%", $comment, $content); 
									$content = str_replace("%fullcost%", $orders['fullcost'], $content); 
									$content = str_replace("%show%", "<input type=\"image\" src=\"template/Default/img/eye.png\" name=\"showorder\" value=\"{$orders['id']}\">", $content); 
								}
								$content .= preg_replace("/(.*)\[orders_bottom\](.*?)\[\/orders_bottom\](.*)/is", "$2", $tpl->modtpl);
								$content .= "</form>"; 
							
							
						}
					}
					else {
						$orderid =(int)$_POST['showorder'];
						$ordersInfo=$mysqli->query("SELECT * FROM `ordergoods` WHERE `orderid`='{$orderid}';");
						$content = preg_replace("/(.*)\[goodslist_head\](.*?)\[\/goodslist_head\](.*)/is", "$2", $tpl->modtpl);
						$content = str_replace("%numb_order%", $orderid, $content); 
						while($orders=$ordersInfo->fetch_assoc()){
								$content .= preg_replace("/(.*)\[goodslist_body\](.*?)\[\/goodslist_body\](.*)/is", "$2", $tpl->modtpl);
								$content = str_replace("%goodsname%", $orders['name'], $content); 
								$content = str_replace("%count%", $orders['count'], $content);
								$content = str_replace("%cost%", $orders['cost'], $content);
								$content = str_replace("%article%", $orders['article'], $content);
								
								
								
						}
						$content .= preg_replace("/(.*)\[goodslist_bottom\](.*?)\[\/goodslist_bottom\](.*)/is", "$2", $tpl->modtpl);
					}
				}
				else {
					header('HTTP/1.1 404 Not Found');
			
					$info = "Нет такой страницы";
				}
			}
?>
			
			
