<?  session_start();
	if(!empty($_POST['order'])){ //если нажали оформить заказ
		
		$to="mail@teplovod-komplekt.ru"; // e-mail куда уйдет письмо
		//очистим от ненужной херни
		if(!empty($_POST['fio'])){
		$fio = stripcslashes(trim(htmlspecialchars($_POST['fio'])));
		}
		if(!empty($_POST['phone'])){
		$phone = stripcslashes(trim(htmlspecialchars($_POST['phone'])));
		}
		if(!empty($_POST['email'])){
		$email = stripcslashes(trim(htmlspecialchars($_POST['email'])));
		}
		if(!empty($_POST['deliv'])){
		$deliv = stripcslashes(trim(htmlspecialchars($_POST['deliv'])));
		}
		if(!empty($_POST['comment'])){
		$comment = stripcslashes(trim(htmlspecialchars($_POST['comment'])));
		}
		
		//формируем сообщение
		$message ="<html><head></head><body>";
		if(!empty($_POST['fio'])){
		$message .="Ф.И.О:&nbsp;".$fio;
		}
		if(!empty($_POST['phone'])){
		$message .="<br />Телефон:&nbsp;".$phone;
		}
		if(!empty($_POST['email'])){
		$message .="<br />Емейл:&nbsp;".$email;
		$to .=", ".$email;
		}
		if(!empty($_POST['deliv'])){
		$message .="<br />Адрес доставки:&nbsp;".$deliv;
		}
		if(!empty($_POST['comment'])){
		$message .="<br />Комментарий к заказу:&nbsp;".$comment;
		}
		if(!empty($_SESSION['userlogin'])){
		$login = $_SESSION['userlogin'];
		$message .="<br />Логин заказчина на сайте:&nbsp;".$login;
		}
		else {
		
		$login = "Гость";
		$message .="<br />Логин заказчина на сайте:&nbsp;".$login;
		}
		require_once "config.php";
		$mysqli= new mysqli($db_loc, $db_user, $db_pass, $db_name);  
		$mysqli->query("SET NAMES 'utf8'");
		$orderInfo=$mysqli->query("SELECT * FROM `orders` ORDER BY `id` DESC LIMIT 1;");
		if($mysqli->affected_rows==0){
			$ordID = "1";
		}
		else{
			$order=$orderInfo->fetch_assoc();
			$ordID = $order['id']+1;
		}
		$mysqli->query("INSERT INTO `orders` (`id`, `user`, `date`, `fio`, `email`, `phone`, `comment`, `adress`, `fullcost`) VALUES ('{$ordID}', '{$login}', '', '{$fio}', '{$email}', '{$phone}', '{$comment}', '{$deliv}', '{$_SESSION['cart_cost']}');");
		
		$goodsInfo=$mysqli->query("SELECT * FROM `shop`;");
				while($goods[]=$goodsInfo->fetch_assoc()){}
				
				
				
				$message .= "<br />Заказ:<br /><table border=\"1\"><tr><td>Название товара:</td><td align=\"center\">Кол-во:</td><td align=\"center\">Стоимость:</td></tr>";
				foreach ($_SESSION['products'] as $key=>$value) {
					
					foreach($goods as $byaka => $chief){	
		
						if($chief['id'] == $key){
							
							$countcart = $_SESSION['products'][$key]['count'];
							$goodsCost = $chief['discost']*$countcart;
							
					$message .= "<tr><td>{$chief['title']}</td><td align=\"center\">{$countcart}</td><td align=\"center\">{$goodsCost}</td></tr>";
					$mysqli->query("INSERT INTO `ordergoods` (`id`, `orderid`, `name`, `count`, `cost`, `article`) VALUES (NULL, '{$ordID}', '{$chief['title']}', '{$countcart}', '{$chief['discost']}', '{$chief['article']}');");
						}
					
	
					}
		
		}
		
		$message .= "</table></body></html>";
		
	

  $subject = "Заказ с сайта {$_SERVER['SERVER_NAME']}";

//  mail ($to, $subject, $message, $headers);
 

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset="utf-8"' . "\r\n";
	$headers .= 'From: mail@teplovod-komplekt.ru'. "\r\n";
	mail($to, $subject, $message, $headers);
	unset($_SESSION['products']);
	header("Location: http://teplovod-komplekt.ru/page/8-ok.html");
	}
?>
