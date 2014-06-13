<?php
require_once "config.php";
$mysqli= new mysqli($db_loc, $db_user, $db_pass, $db_name); 
$mysqli->query("SET NAMES 'utf8'");
function translitIt($str) 
{
    $tr = array(
        " "=>"-",":"=>"","_"=>"","_"=>"","="=>"","C"=>"c","A"=>"a","B"=>"b","V"=>"v","G"=>"g",
        "D"=>"d","E"=>"e","J"=>"j","Z"=>"z","I"=>"i",
        "Y"=>"y","K"=>"k","L"=>"l","M"=>"m","N"=>"n",
        "O"=>"o","P"=>"p","R"=>"r","S"=>"s","T"=>"t",
        "U"=>"u","F"=>"f","H"=>"h","TS"=>"ts","CH"=>"ch",
        "SH"=>"sh","SCH"=>"sch","YI"=>"yi",
        "YU"=>"yu","YA"=>"ya","А"=>"a","Б"=>"b",
		"В"=>"v","Г"=>"g",
        "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
        "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
        "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
        "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
        "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
        "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
    );
    return strtr($str,$tr);
}

$cityInfo=$mysqli->query("SELECT * FROM `page`;");
while($cityResult=$cityInfo->fetch_assoc()){

$str = $cityResult['title'];


$str2 = translitIt($str);
$mysqli->query("UPDATE  `page` SET  `altname` =  '{$str2}' WHERE `title` ='{$cityResult['title']}'; "); 
} 


?>