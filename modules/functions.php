<?

	
	function showXfields($id,$module,$content,$mysqli){ // устанавливает значение в переменную $Title

		$valfieldsInfo= $mysqli->query("SELECT `{$module}fieldsval`.`value`,`{$module}fieldsval`.`addsum`,`{$module}fieldsval`.`fieldname`,`{$module}fields`.`type`,`{$module}fields`.`name`,`{$module}fields`.`altname`  FROM `{$module}fieldsval`,`{$module}fields` WHERE `{$module}fieldsval`.`idshop` = '{$id}' AND `{$module}fields`.`id`=`{$module}fieldsval`.`idshopfields`;");
			if($mysqli->affected_rows!=0){
				$i=0;
				while($valfields=$valfieldsInfo->fetch_assoc()){
					
					$arrfield[$valfields['altname']]['type']= $valfields['type'];
					$arrfield[$valfields['altname']]['name']= $valfields['name'];
					
					if($module="shop"){
						if($valfields['type']=="checkbox"){
							$arrfield[$valfields['altname']]['values'][$valfields['value']].= $valfields['addsum'].=";".$valfields['fieldname'];
						}else{
						
							$arrfield[$valfields['altname']]['values'][$valfields['value']] = $valfields['addsum']; 
						
						}
					} 
					else{
						if($valfields['type']=="checkbox"){
							$arrfield[$valfields['altname']]['values'][$valfields['value']].= $valfields['fieldname'];
						}else{
						
							$arrfield[$valfields['altname']]['values']= $valfields['value'];
						
						}
					
					
					}
				}
				
				foreach($arrfield as $name => $key){
					
					if (strtolower($key['type'])=="select") {
						
						$content = str_replace("[fieldname={$name}]", $key['name'].":",$content);
						$select = "<select class=\"goodssub\" title=\"{$key['name']}\" name=\"{$name}\">";
						
						foreach($key as $finfo => $valfinfo){
						
							if(is_array($valfinfo)){
								$i=0;
								foreach($valfinfo as $atr => $valatr){
									if($valatr!=0){
										$sumatr ="(+{$valatr}р.)";
									} else {
										$sumatr ="";
									}
									if($i==0){
										$select .= "<option class=\"pprice{$valatr}\" value=\"{$atr}\" selected>{$atr}{$sumatr}</option>";
									}
									else{
										$select .= "<option class=\"pprice{$valatr}\" value=\"{$atr}\">{$atr}{$sumatr}</option>";
									}
									$i++;
								}
							
							}
						}
						$select .= "</select>";
						$content = str_replace("%field_{$name}%", $select."<br />", $content);
					}
					if (strtolower($key['type'])=="radio") {
						$content = str_replace("[fieldname={$name}]", $key['name'].":", $content);
						foreach($key as $finfo => $valfinfo){
						
							if(is_array($valfinfo)){
								$i=0;
								foreach($valfinfo as $atr => $valatr){
								
									if($valatr!=0){
										$sumatr ="(+{$valatr}р.)";
									} else {
										$sumatr ="";
									}
									if($i==0){
										$radio = "<input type=\"radio\" name=\"{$name}\" class=\"goodssub pprice{$valatr}\" title=\"{$key['name']}\" value=\"{$atr}\" checked>{$atr}{$sumatr}<br />";
									}
									else{
										$radio .= "<input type=\"radio\" name=\"{$name}\" class=\"goodssub pprice{$valatr}\" title=\"{$key['name']}\" value=\"{$atr}\" >{$atr}{$sumatr}<br />";
									}
									$i++;
								}
							
							}
						
						
						}
						
						
						$content = str_replace("%field_{$name}%", $radio."<br />", $content);
					}
					if (strtolower($key['type'])=="checkbox") {
						$content = str_replace("[fieldname={$name}]", $key['name'].":", $content);
						foreach($key as $finfo => $valfinfo){
						
							if(is_array($valfinfo)){
								
								foreach($valfinfo as $atr => $valatr){
										
										$checkSumName = explode(";", $valatr);
										if($checkSumName[0]!=0){
											$sumatr ="(+{$checkSumName[0]}р.)";
										} else {
											$sumatr ="";
										}
										$checkbox .= "<input type=\"checkbox\" name=\"{$checkSumName[1]}\" title=\"{$key['name']}\" class=\"goodssub pprice{$checkSumName[0]}\" value=\"{$atr}\" >{$atr}{$sumatr}<br />";
									
								}
							
							}
						
						
						}

						$content = str_replace("%field_{$name}%", $checkbox."<br />", $content);
					}
					if (strtolower($key['type'])=="text") {
						$content = str_replace("[fieldname={$name}]", $key['name'].":", $content);		
						foreach($key as $finfo => $valfinfo){
				
							$text = "<input type=\"text\" name=\"{$name}\" title=\"{$key['name']}\" class=\"goodssub\" value=\"\" ><br />";
						
						}
						
						
						$content = str_replace("%field_{$name}%", $text."<br />", $content);
					}
				}
			}
			//print_r($key);
				$content = preg_replace("/\[fieldname=(.+?)\]/is", "", $content);
				$content = preg_replace("/%field_(.+?)%/is", "", $content);
				return $content;
}
 

	
	//функции замены меток
	function group($matches){ 
	
		$res = explode(",", $matches[1]);
		if (in_array($_SESSION['group'], $res)) {
			return $matches[2];
		}
	
	}
	
	function notgroup($matches){
	
		$res = explode(",", $matches[1]);
		if (!in_array($_SESSION['group'], $res)) {
			return $matches[2];
		}
	
	}
	
	function aviable($matches){
	
		$res = explode("|", $matches[1]);
		
		foreach($res as $aviable){
			$locAviable = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			if($aviable == "home"){
				if($locAviable == $_SERVER['SERVER_NAME']."/"){
					return $matches[2];
				}
			
			}
			else{
				if(preg_match("/\/{$aviable}\//", $locAviable)){
					return $matches[2];
				}
			}
		}
		 
	
	}
	
	
	function notaviable($matches){
	
		$res = explode("|", $matches[1]);
		$notshowcont = 0;
		foreach($res as $noaviable){
			$locNoAviable = $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			if($noaviable == "home"){
				if($locNoAviable == $_SERVER['SERVER_NAME']."/"){
					
					$notshowcont = 1;
				
				}
			
			}
			elseif(preg_match("/\/{$noaviable}\//", $locNoAviable)){
				$notshowcont = 1;
			} 
			
			
		}
		 
		if($notshowcont == 0){
		
		return $matches[2];
		
		}
	}
?>
