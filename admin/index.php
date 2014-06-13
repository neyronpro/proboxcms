<? session_start();
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
require_once "../modules/config.php";
$mysqli= new mysqli($db_loc, $db_user, $db_pass, $db_name);  
$mysqli->query("SET NAMES 'utf8'");
require_once "probox/functions.php";

 function my_autoloader($class) {
    include '../classes/' . $class . '.class.php';
	 
}

spl_autoload_register('my_autoloader');

$settings = new Settings($mysqli);
$userinfo = new Userinfo();
$userinfo->info($settings->config['skin'],$mysqli);



if(empty($_SESSION['group']) or ($_SESSION['adminpanel']!=1)){

$temp = file_get_contents('template/login.html', true);
$template = str_replace("%info%", "", $temp);
if(!empty($_POST['submit'])){
	$code=$_SESSION['code'];	
	$p_code=$_POST['captcha_code'];//
	if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} } //заносим введенный пользователем логин в переменную $reglogin, если он пустой, то уничтожаем переменную
	if (isset($_POST['pass'])) { $pass=$_POST['pass']; if ($pass =='') { unset($pass);} }
	if (empty($login) or empty($pass)){ // если не ввел логин или пароль
		
		$template = $temp; 
		$template = str_replace("%info%", "Заполните все поля", $template);
	}
	elseif(empty($_POST['captcha_code'])){
			$template = $temp;
			$template = str_replace("%info%", "Введите символы с картинки!", $template);
		
		
		}elseif($p_code!=$code){
		
			$template = $temp;
			$template = str_replace("%info%", "Каптча введена неправильно!", $template);
		
		}else {
	
		$login = $mysqli->real_escape_string(htmlspecialchars($login));												//
		$pass = $mysqli->real_escape_string(htmlspecialchars($pass));												// очищаем от херни
		$login = trim($login);															// и шифруем пароль
		$pass = trim($pass);															//
		$pass = md5(sha1($pass));	
		
		$result= $mysqli->query("SELECT * FROM `users` WHERE `login`='{$login}';");
		$row = $result->fetch_array();
		if(empty($row['id']) or ($row['pass']!=$pass) or ($row['group']!=5)) {				// если логина в базе нет
			$template = $temp;
			$template = str_replace("%info%", "Логин или пароль не верный!", $template);
		
		}else{
			$_SESSION['userlogin'] = $login;
			$_SESSION['email'] = $row['email'];
			if(!empty($row['avatar'])){$_SESSION['avatar'] = $row['avatar'];}else {$_SESSION['avatar']="/template/".$config['skin']."/proboximg/noavatar.png";} //грузим аватар
			$_SESSION['group'] = $row['group'];
			header("Location: {$_SERVER['HTTP_REFERER']}");
		}
	
		}
	}
}
else{









	$template = file_get_contents('template/index.html', true);





	if(@!isset($_GET['do'])){
		require_once "home.php";
		
		
	} 
	else {
		require_once "do.php";
	}
}



$template = str_replace("%userlogin%", $_SESSION['userlogin'], $template);
$template = str_replace("%groupname%", $_SESSION['groupname'], $template);
echo $template;



?>














