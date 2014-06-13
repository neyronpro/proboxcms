<?  
	if (empty($_SESSION['userlogin']) ){ //если юзер не залогинен
		$tpl->set_tpl("miniprofile","login");
		$tpl->set("%miniprofile%", $tpl->modtpl);
		

	} 
	else { //если залогинен
		$tpl->set_tpl("miniprofile","miniprofile");
		$tpl->set("%miniprofile%", $tpl->modtpl);
		$tpl->set("%name%", $_SESSION['userlogin']);
		$tpl->set("%avatar%","<img alt=\"Аватар\" title=\"Аватар\" width=\"100px\" height=\"100px\" src=\"{$_SESSION['avatar']}\">");
		
	}
?>
