<? 
if($_POST['module']=="shop" or $_POST['module']=="news"){
require_once "../../modules/config.php";
$mysqli= new mysqli($db_loc, $db_user, $db_pass, $db_name);  
$mysqli->query("SET NAMES 'utf8'");
$catSelectInfo=$mysqli->query("SELECT * FROM `cat{$_POST['module']}`;");

$i=0;
//$catJson[] = "{\"data\":";
while($catSelect=$catSelectInfo->fetch_assoc()){

	$catJson[] = $catSelect;
	$i++;
}


//echo "{\"data\":";
echo json_encode($catJson);
//echo "}";
}
?>














