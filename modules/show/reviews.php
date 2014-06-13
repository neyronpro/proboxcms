<? session_start();
if(!empty($_POST['showreviews']) or !empty($_POST['addreviews'])){

		header( 'Content-Type: text/html; charset=utf-8' );
		require_once "../config.php";
		require_once "../../classes/Settings.class.php";
		$mysqli= new mysqli($db_loc, $db_user, $db_pass, $db_name); 
		$mysqli->query("SET NAMES 'utf8'");
		$settings = new Settings($mysqli);
		$temp = file_get_contents($_SERVER['DOCUMENT_ROOT']."/template/{$settings->config['skin']}/reviews/reviews.html", true);
		$temp2 = file_get_contents($_SERVER['DOCUMENT_ROOT']."/template/{$settings->config['skin']}/reviews/addreviews.html", true);
	if($_POST['showreviews']=="show"){
		
		
				$offInfo = $mysqli->query("SELECT `allowreviews` FROM `shop` WHERE `id`='{$_POST['id']}' LIMIT 1;");
				$off=$offInfo->fetch_assoc();
				//$reviewscont = preg_replace("/(.*)\[reviews_head\](.*?)\[\/reviews_head\](.*)/is", "$2", $temp);
				if($off['allowreviews']==1){
					$reviewsInfo = $mysqli->query("SELECT `reviews`.*,`users`.`login`,`users`.`email`,`users`.`avatar` FROM `reviews`,`users` WHERE `reviews`.`goodsid`='{$_POST['id']}' AND `users`.`id`=`reviews`.`userid`;");
					
					if($mysqli->affected_rows==0){
						$reviewscont = preg_replace("/(.*)\[no_reviews\](.*?)\[\/no_reviews\](.*)/is", "$1$2$3", $temp);
						$reviewscont = preg_replace("/\[reviews_list\](.*?)\[\/reviews_list\]/is", "", $reviewscont);
						$reviewscont = preg_replace("/\[off_reviews\](.*?)\[\/off_reviews\]/is", "", $reviewscont);
						
					}
					else{
						$i=1;
						while($reviews=$reviewsInfo->fetch_assoc()){ 
							
							$reviewscont .= preg_replace("/(.*)\[reviews_list\](.*?)\[\/reviews_list\](.*)/is", "<span class=\"reviews{$i}\">$2</span>", $temp);
							$reviewscont = str_replace("%id%", $i, $reviewscont);
							$reviewscont = str_replace("%username%", $reviews['login'], $reviewscont);
							$reviewscont = str_replace("%user_avatar%", $reviews['avatar'], $reviewscont);
							$reviewscont = str_replace("%rating%", $reviews['rating'], $reviewscont);
							$reviewscont = str_replace("%dignity%", $reviews['dignity'], $reviewscont);
							$reviewscont = str_replace("%luck%", $reviews['luck'], $reviewscont);
							$reviewscont = str_replace("%comment%", $reviews['comment'], $reviewscont);
							$i++; 
						}
						
						$reviewscont = preg_replace("/(.*)\[reviews_list\](.*?)\[\/reviews_list\](.*)/is", "$1{$reviewscont}$3", $temp);
						$reviewscont = preg_replace("/\[no_reviews\](.*?)\[\/no_reviews\]/is", "", $reviewscont);
						$reviewscont = preg_replace("/\[off_reviews\](.*?)\[\/off_reviews\]/is", "", $reviewscont);
					}
					
					
					
				} else {
				
					$reviewscont .= preg_replace("/(.*)\[off_reviews\](.*?)\[\/off_reviews\](.*)/is", "$1$2$3", $temp);
					$reviewscont = preg_replace("/\[reviews_list\](.*?)\[\/reviews_list\]/is", "", $reviewscont);
					$reviewscont = preg_replace("/\[no_reviews\](.*?)\[\/no_reviews\]/is", "", $reviewscont);
				}
					
				echo $reviewscont;
			} 
						
			if($_POST['addreviews']=="add"){
				$offInfo = $mysqli->query("SELECT `allowreviews` FROM `shop` WHERE `id`='{$_POST['id']}' LIMIT 1;");
				$off=$offInfo->fetch_assoc();
			if($off['allowreviews']==1){
				if(!empty($_SESSION['userlogin'])){
						$errorReviewsInfo = $mysqli->query("SELECT * FROM `reviews` WHERE `goodsid`='{$_POST['id']}' AND (`userid`='{$_SESSION['userid']}' OR `ip`='{$_SERVER['REMOTE_ADDR']}');");
						if($mysqli->affected_rows==0){

							if(empty($_POST['addpostrev'])){
								$addreviews = preg_replace("/(.*)\[add_reviews\](.*?)\[\/add_reviews\](.*)/is", "$1<div class=\"ajaxaddrev\">$2</div>$3", $temp2);
								$addreviews = str_replace("%dignity%", "<textarea name=\"dignity\" ></textarea>", $addreviews);
								$addreviews = str_replace("%luck%", "<textarea name=\"luck\" ></textarea>", $addreviews);
								$addreviews = str_replace("%comment%", "<textarea name=\"comment\" ></textarea>", $addreviews);
								$addreviews = str_replace("%rating%", "<input type=\"hidden\" value=\"1\" id=\"ratingid\" name=\"rating\">", $addreviews);
								$addreviews = preg_replace("/\[submit\](.*?)\[\/submit\]/is", "<input type=\"button\" name=\"addreview\" class=\"addpostrev\" id=\"addpostrev{$_POST['id']}\" value=\"$1\">", $addreviews);
								$addreviews = preg_replace("/\[reply_reviews\](.*?)\[\/reply_reviews\]/is", "", $addreviews);
								$addreviews = preg_replace("/\[add_reviews_error\](.*?)\[\/add_reviews_error\]/is", "", $addreviews);	
								$addreviews = preg_replace("/\[add_reviews_successful\](.*?)\[\/add_reviews_successful\]/is", "", $addreviews);	
								
							} else {
							
								$mysqli->query("INSERT INTO `reviews` (`id`, `userid`, `goodsid`, `comment`, `luck`, `dignity`, `rating`,`ip`) VALUES (NULL, '{$_SESSION['userid']}', '{$_POST['id']}', '{$_POST['comment']}', '{$_POST['luck']}', '{$_POST['dignity']}', '{$_POST['rating']}', '{$_SERVER['REMOTE_ADDR']}');");
								$addreviews = preg_replace("/(.*)\[add_reviews_successful\](.*?)\[\/add_reviews_successful\](.*)/is", "$2", $temp2);		
								
							
							}
						} 
						else {
						
							$addreviews = preg_replace("/(.*)\[reply_reviews\](.*?)\[\/reply_reviews\](.*)/is", "$1$2$3", $temp2);
							$addreviews = preg_replace("/\[add_reviews_error\](.*?)\[\/add_reviews_error\]/is", "", $addreviews);	
							$addreviews = preg_replace("/\[add_reviews\](.*?)\[\/add_reviews\]/is", "", $addreviews);	
							$addreviews = preg_replace("/\[add_reviews_successful\](.*?)\[\/add_reviews_successful\]/is", "", $addreviews);	
						
						}
				}
				else {
				
					$addreviews = preg_replace("/(.*)\[add_reviews_error\](.*?)\[\/add_reviews_error\](.*)/is", "$1$2$3", $temp2);	
					$addreviews = preg_replace("/\[add_reviews\](.*?)\[\/add_reviews\]/is", "", $addreviews);	
					$addreviews = preg_replace("/\[reply_reviews\](.*?)\[\/reply_reviews\]/is", "", $addreviews);	
					$addreviews = preg_replace("/\[add_reviews_successful\](.*?)\[\/add_reviews_successful\]/is", "", $addreviews);		
				}
			}
				echo $addreviews;
				
			
			}
						
						
		//$reviewscont .= preg_replace("/(.*)\[reviews_bottom\](.*?)\[\/reviews_bottom\](.*)/is", "$2", $temp);
		
		
	}

?>