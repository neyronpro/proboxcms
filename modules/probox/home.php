<?
	$tpl->set_tags($settings->config['home']['metatitle'],$settings->config['home']['description'],$settings->config['home']['keywords']);
	$tpl->set("%content%","");
///////////	 
	
	if ($tpl->search("%news%")) { //ищем метку модуля "news"
		
		$module = "news";
		$typeCont = "short";
		
		$pageCount = $settings->config['news']['homecount'];
		$tpl->set_tpl($module,"short");
		
		$contentInfo=$mysqli->query("SELECT * FROM `{$module}` ORDER BY `date` DESC LIMIT {$pageCount};");
		$classContent = new Content($mysqli,$contentInfo,$tpl->modtpl,$module,$typeCont);
		$content = $classContent->contentfunct();
		
		$tpl->set("%shop%",$content);
	}
	
	if ($tpl->search("%shop%")) { //ищем метку модуля "news"
		
		$module = "shop";
		$typeCont = "short";
		

		$pageCount = $settings->config['shop']['homecount'];
		$tpl->set_tpl($module,"short");
		
		$contentInfo=$mysqli->query("SELECT * FROM `{$module}` ORDER BY `date` DESC LIMIT {$pageCount};");
		$classContent = new Content($mysqli,$contentInfo,$tpl->modtpl,$module,$typeCont);
		$content = $classContent->contentfunct();
		
		$tpl->set("%shop%",$content);
	}
?>
