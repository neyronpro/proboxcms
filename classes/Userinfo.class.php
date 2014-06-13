<?
class Userinfo {
	static function info($skin,$mysqli){
		
		if(!empty($_SESSION['userlogin'])){
			$loginInfo= $mysqli->query("SELECT * FROM `users` WHERE `login`='{$_SESSION['userlogin']}';");
			$login = $loginInfo->fetch_assoc();
			$_SESSION['userlogin'] = $login['login'];
			$_SESSION['email'] = $login['email'];
			$_SESSION['avatar'] = $login['avatar'];
			$_SESSION['group'] = $login['group'];
			$_SESSION['userid'] = $login['id'];
			
		}
		else {
			$_SESSION['group'] = 0;
			
		}

		$groupInfo=$mysqli->query("SELECT * FROM `groups` WHERE `id`='{$_SESSION['group']}';");
		while($group = $groupInfo->fetch_assoc()){
		
			$_SESSION['adminpanel']=$group['adminpanel'];
			$_SESSION['groupname']=$group['name'];
		}
	}
}	
?>