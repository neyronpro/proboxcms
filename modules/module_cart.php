<?
if(!empty($_SESSION['products'])){
			
				if(preg_match("/(.*)\[goods_cart\](.*?)\[\/goods_cart\](.*)/is", $tpl->modtpl)){
					
					
					
					
					
					
					
					$goodsInfo=$mysqli->query("SELECT * FROM `shop`;");
					while($goods[]=$goodsInfo->fetch_assoc()){}
				
					foreach ($_SESSION['products'] as $key=>$value) {
					
						foreach($goods as $byaka => $chief){	
		
							if($chief['id'] == $key){
							
								$countcart = $_SESSION['products'][$key]['count'];
								$goodsCost = $chief['discost']*$countcart;
								$cart .= preg_replace("/(.*)\[goods_cart\](.*?)\[\/goods_cart\](.*)/is", "$2", $tpl->modtpl);
								$cart = str_replace("%titlecart%", $chief['title'], $cart);
								$cart = str_replace("%previmg%", $chief['previmg'], $cart);
								$cart = str_replace("%costcart%", $goodsCost, $cart);
								$cart = str_replace("%countcart%", $countcart, $cart);
								$cart = str_replace("%atr_delete%", "class=\"delgoods\" id=\"{$chief['id']}\"", $cart);
								$cart = str_replace("%atr_goods%", "id=\"gtr{$chief['id']}\"", $cart);
							
							}
					
	
						}
					
					
					}  
					
					
					$content = preg_replace("/\[goods_cart\](.*?)\[\/goods_cart\]/is", $cart, $tpl->modtpl);
					$content = preg_replace("/\[cart_form\](.*?)\[\/cart_form\]/is", "<form action=\"/modules/show/order.php\" method=\"POST\">$1</form>", $content);
					$content = str_replace("%fio%", "<input type=\"text\" name=\"fio\" value=\"\" placeholder=\"Василий Пупкин\">", $content);
					$content = str_replace("%phone%", "<input type=\"text\" name=\"phone\" value=\"\" placeholder=\"+79233509989\">", $content);
					if(!empty($_SESSION['email'])){
						$content = str_replace("%email%", "<input type=\"text\" name=\"email\" value=\"{$_SESSION['email']}\">", $content);
					}
					else {
						$content = str_replace("%email%", "<input type=\"text\" name=\"email\" value=\"\" placeholder=\"myname@mail.ru\">", $content);
					}
					
					$content = str_replace("%deliv%", "<textarea type=\"text\" name=\"deliv\" value=\"\"></textarea>", $content);
					$content = str_replace("%comment%", "<textarea type=\"text\" name=\"comment\" value=\"\"></textarea>", $content);
					
					
					
				} 
				else {
				 $info = "Отсутствует метка [goods_cart][/goods_cart] в шаблоне";
				
				}
				//$content = str_replace("%goods%", " <table><tr><td>Наименование товара</td><td class=\"tdnumb\">Кол-во</td><td class=\"tdnumb\">На сумму</td><td class=\"tdrico\">&nbsp;</td><td class=\"tdrico\">&nbsp;</td></tr>", $listTemp);
				/*$goodsInfo=$mysqli->query("SELECT * FROM `shop`;");
				
				while($goods[]=$goodsInfo->fetch_assoc()){}
				
				foreach ($_SESSION['products'] as $key=>$value) {
					
					foreach($goods as $byaka => $chief){	
		
						if($chief['id'] == $key){
							
							$countcart = $_SESSION['products'][$key]['count'];
							$goodsCost = $chief['discost']*$countcart;
					$content .= str_replace("%goods%", "<tr id=\"gtr{$chief['id']}\"><td>{$chief['title']}</td><td>{$countcart}</td><td class=\"tdnumb\">{$goodsCost}</td><td><a href=\"{$chief['previmg']}\" target=\"_blank\" alt=\"Посмотреть изображение товара\"><img alt=\"Посмотреть изображение товара\" src=\"template/img/eye.png\"></a></td><td><img class=\"delgoods\" id=\"{$chief['id']}\" alt=\"Удалить товар из корзины\" src=\"template/img/delico.png\" style=\"cursor:pointer;\"></td></tr>", $listTemp);
						}
					
	
					}
					
					
				}  
					
					$content .= "<form action=\"/modules/order.php\" method=\"POST\">";
					$content .= str_replace("%fio%", "<input type=\"text\" name=\"fio\" value=\"\">", $formTemp);
					$content = str_replace("%phone%", "<input type=\"text\" name=\"phone\" value=\"\">", $content);
					$content = str_replace("%email%", "<input type=\"text\" name=\"email\" value=\"\">", $content);
					$content = str_replace("%deliv%", "<textarea type=\"text\" name=\"deliv\" value=\"\"></textarea>", $content);
					$content = str_replace("%comment%", "<textarea type=\"text\" name=\"comment\" value=\"\"></textarea>", $content);
					$content .="</form>"*/
			}
else{

	$info = "Корзина пуста";

}
?>
