<? session_start();
   
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

//two
//Подключаемся к базе
require_once "./modules/config.php";

$mysqli= new mysqli($db_loc, $db_user, $db_pass, $db_name); 
$mysqli->query("SET NAMES 'utf8'");
$location = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
//конфиг модулей

 function my_autoloader($class) {
    include 'classes/' . $class . '.class.php';
	 
}

spl_autoload_register('my_autoloader');

$settings = new Settings($mysqli);
$userinfo = new Userinfo();
$userinfo->info($settings->config['skin'],$mysqli);
$tpl = new Template($settings->config['skin']);;


	
//Формиируем основной шаблон
$template .= file_get_contents("template/".$settings->config['skin']."/index.html", true);
//$content = file_get_contents('./content.html', true);

///  	Глобальные модули контента
require_once "modules/functions.php";		  ///  короткий контент

/////////////////////////////////////////////////////




if(@!isset($_GET['module'])){
	require_once "./modules/probox/home.php";
	
} 
else {
	require_once "./modules/probox/modules.php";
}




//Подключение Суперглобальных модулей



if($tpl->search("%newsmenu%")){
//меню категорий
$newsMenuInfo=$mysqli->query("SELECT `id`,`parrent_id`,`name`,`translate` FROM `catnews`;"); //цепляем категории
		$newsMenu="";
		while($newsMenuSelect[]=$newsMenuInfo->fetch_assoc()){}
		foreach($newsMenuSelect as $byaka => $chief){	//шаманим, получая категории 1го уровня
		
			if($chief['parrent_id'] == "0"){
				$newsMenu .="<ul>";
				$newsMenu .= "<li><a href=\"/news/{$chief['translate']}/\"><span>{$chief['name']}</span></a></li>"; //выврлим имя с линком
				$validmenu = $chief['id'];
				foreach($newsMenuSelect as $byaka2 => $chief2){	//шаманим, получая подкатегории 1го уровня

					if($chief2['parrent_id'] == $validmenu){
						$newsMenu .= "<li><a href=\"/news/{$chief2['translate']}/\"><span>{$chief2['name']}</span></a>";	//выврлим имя с линком
						$validmenu2 = $chief2['id'];
						$newsMenu .="<ul>";
							foreach($newsMenuSelect as $byaka3 => $chief3){	//шаманим, получая подкатегории 2го уровня
								
								if($chief3['parrent_id'] == $validmenu2){
								
								$newsMenu .= "<li><a href=\"/news/{$chief3['translate']}/\"><span>{$chief3['name']}</span></a></li>";	//выврлим имя с линком
								
								}
							
							}
						$newsMenu .="</ul>";
						$newsMenu .= "</li>";
					
					}
					
				}	$newsMenu .="</ul>";
			}
			
	
		}
		
$newsMenu = str_replace("<ul></ul>","", $newsMenu); //заменяем метку модуля "news"
$tpl->set("%newsmenu%", $shopMenu); //заменяем метку модуля "shop"
}

if($tpl->search("%shopmenu%")){
$shopMenuInfo=$mysqli->query("SELECT `id`,`parrent_id`,`name`,`translate` FROM `catshop`;");
		$shopMenu="";
		while($shopMenuSelect[]=$shopMenuInfo->fetch_assoc()){}
		foreach($shopMenuSelect as $byaka => $chief){	
		

			if($chief['parrent_id'] == "0"){
				$shopMenu .="<ul>";
				$shopMenu .= "<li><a href=\"/shop/{$chief['translate']}/\"><span>{$chief['name']}</span></a></li>";
				$validmenu = $chief['id'];
				foreach($shopMenuSelect as $byaka2 => $chief2){

					if($chief2['parrent_id'] == $validmenu){
						$shopMenu .= "<li><span class=\"sub-cat-icon\"></span><a href=\"/shop/{$chief2['translate']}/\"><span>{$chief2['name']}</span></a>";
						$validmenu2 = $chief2['id'];
						$shopMenu .="<ul>";
							foreach($shopMenuSelect as $byaka3 => $chief3){
								
								if($chief3['parrent_id'] == $validmenu2){
								
								$shopMenu .= "<li><span class=\"sub-cat-icon\"></span><a href=\"/shop/{$chief3['translate']}/\"><span>{$chief3['name']}</span></a></li>";
								
								}
							
							}
						$shopMenu .="</ul>";
						$shopMenu .= "</li>";
					
					}
					
				}	$shopMenu .="</ul>";
			}
			
	
		}
		
$shopMenu = str_replace("<ul></ul>","", $shopMenu); //заменяем метку модуля "shop"
$tpl->set("%shopmenu%", $shopMenu); //заменяем метку модуля "shop"
}
///////////пагинация

if($pagination == 1){
	$tpl->set_tpl("pagination","pagination");
	$pagedisprange=3; // соклько страниц до и после текущей выводить
	$pagescount=ceil($itemscount/$pageCount); // кол-во страниц
	$stpage=$cpage-$pagedisprange; // определим начиная с какого номера будем выводить страницы
	$loacationForPagination = $location;
	if(preg_match("/\/([0-9]+)\//i", $loacationForPagination)||!preg_match("/\=/i", $loacationForPagination)){
		$loacationForPagination = preg_replace("/\/([0-9]+)\//i", "", $loacationForPagination);
		
		$op=1;
	}else {
			$loacationForPagination = preg_replace("/(.)page=(.*)/i", "", $loacationForPagination);
			$op=2;
	}
	if ($stpage<1) { $stpage=1; } // если наше "начало" вылазит на отрицательные номера, то стави м в 1
	$endpage=$cpage+$pagedisprange; // аналогично с номером, по который будем выводить
	if ($endpage>$pagescount) { $endpage=$pagescount; } // если больше чем страниц, то последняя выводимая страницы - самая последняя наша
	if ($cpage>1) {
		// first
		if($op==1){
			$tpl->modtpl = preg_replace("/\[prev_link\](.*)\[\/prev_link\]/is", "<a href=\"{$loacationForPagination}/".($cpage-1)."/\" title=\"Назад\">$1</a>", $tpl->modtpl); 
		}
		elseif($op==2){
			$tpl->modtpl = preg_replace("/\[prev_link\](.*)\[\/prev_link\]/is", "<a href=\"{$loacationForPagination}&page=".($cpage-1)."\" title=\"Назад\">$1</a>", $tpl->modtpl); 
		}
		
	}
	else {
		$tpl->modtpl = preg_replace("/\[prev_link\](.*)\[\/prev_link\]/is", "", $tpl->modtpl); 
	
	}
	
	for ($i=$stpage;$i<=$endpage;$i++) { 
		if ($i==$cpage) {
			if($i<$endpage){
				$tpl->modtpl = str_replace("%numbpages%",$i."%numbpages%", $tpl->modtpl); 
			}
			elseif($i==$endpage){
				$tpl->modtpl = str_replace("%numbpages%",$i, $tpl->modtpl); 
			}
		}
		else { 
			if($i<$endpage){
				if($op==1){
					$tpl->modtpl = str_replace("%numbpages%","<a href=\"{$loacationForPagination}/".$i."/\">".$i."</a>%numbpages%", $tpl->modtpl);
				}
				elseif($op==2){
					$tpl->modtpl = str_replace("%numbpages%","<a href=\"{$loacationForPagination}&amp;page=".$i."\">".$i."</a>%numbpages%", $tpl->modtpl);
				}
			}
			elseif($i==$endpage){
				if($op==1){
					$tpl->modtpl = str_replace("%numbpages%","<a href=\"{$loacationForPagination}/".$i."/\">".$i."</a>", $tpl->modtpl);
				}
				elseif($op==2){
					$tpl->modtpl = str_replace("%numbpages%","<a href=\"{$loacationForPagination}&amp;page=".$i."\">".$i."</a>", $tpl->modtpl);
				}
			
			
			}
		
		}
	}
	//if ($endpage<$pagescount) $tpl->modtpl .= "... "; // если начало конец вывода не последняя страница, то напечатаем три точки
	if ($cpage<$pagescount) {
		if($op==1){
			$tpl->modtpl = preg_replace("/\[next_link\](.*)\[\/next_link\]/is", "<a href=\"{$loacationForPagination}/".($cpage+1)."/\" title=\"Вперед\">$1</a>", $tpl->modtpl);
		
		}
		elseif($op==2){
			$tpl->modtpl = preg_replace("/\[next_link\](.*)\[\/next_link\]/is", "<a href=\"{$loacationForPagination}&amp;page=".($cpage+1)."\" title=\"Вперед\">$1</a>", $tpl->modtpl); 
		}
		// last
		//$tpl->modtpl .= "<a href=\"{$loacationForPagination}&page=".$pagescount."\">Last</a> ";
	}
	else{
		$tpl->modtpl = preg_replace("/\[next_link\](.*)\[\/next_link\]/is", "", $tpl->modtpl); 
	}
	
	$tpl->modtpl = preg_replace("/\/\/([0-9]+)\//is", "/$1/", $tpl->modtpl);
	$tpl->set("%pagination%",$tpl->modtpl); //заменяем метку модуля "pagination"
} 
else {
	$tpl->set("%pagination%",""); //заменяем метку модуля "pagination"
}



//Миникорзина
$tpl->set_tpl("cart","minicart");
$tpl->set("%minicart%",$tpl->modtpl);
$tpl->set("%minicart%",$tpl->modtpl);
$tpl->set("%cartboxtotal%", "<span class=\"cartboxtotal\">0</span>");
$tpl->set("%cartboxsum%", "<span class=\"cartboxsum\">0</span>");
 


require_once "modules/miniprofile.php";









	$tpl->tpl = preg_replace_callback("/\[group=(.+?)\](.*?)\[\/group\]/is", "group", $tpl->tpl);
	$tpl->tpl = preg_replace_callback("/\[not-group=(.+?)\](.*?)\[\/group\]/is", "notgroup", $tpl->tpl);
	$tpl->tpl = preg_replace_callback("/\[aviable=(.+?)\](.*?)\[\/aviable\]/is", "aviable", $tpl->tpl);
	$tpl->tpl = preg_replace_callback("/\[not-aviable=(.+?)\](.*?)\[\/not-aviable\]/is", "notaviable", $tpl->tpl);
	
	
	

if(!empty($info)){
	
	$infoTemp = file_get_contents("template/".$settings->config['skin']."/info.html", true);
	$infoCont = str_replace("%info_message%", $info, $infoTemp);
	$tpl->set("%info%", $infoCont);
	if(!empty($infoTitle)){
	
		$tpl->set("%info_title%", $infoTitle);
	
	} else {
		$tpl->set("%info_title%", "");
	
	}
} else {

	$tpl->set("%info%", "");
	$tpl->set("%info_title%", "");
}

echo $tpl->tpl; 

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);



?>














