<?

	if (preg_match("/%content%/", $template)) { //ищем метку модуля "news"
		
		$content = file_get_contents('template/home.html', true);
		
		
		$template = str_replace("%content%",$content, $template); //заменяем метку модуля "news"
	}
	
	
?>
