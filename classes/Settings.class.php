<? class Settings{
	public $config;
	public $db;
		function __construct($mysqli){
		$this->db = $mysqli;
		$configInfo= $this->db->query("SELECT * FROM `settings`;");
		while($configSelect[]=$configInfo->fetch_assoc()){}
		foreach($configSelect as $k => $c){	//шаманим, получая категории 1го уровня
				
					$this->config[$c['module']]['pagecount'] = $c['pagecount'];
					$this->config[$c['module']]['homecount'] = $c['homecount'];
					$this->config[$c['module']]['metatitle'] = $c['metatitle'];
					$this->config[$c['module']]['description'] = $c['description'];
					$this->config[$c['module']]['keywords'] = $c['keywords'];
					$this->config[$c['module']]['on'] = $c['on'];
					if($c['module']=="skin"){
						$this->config['skin'] = $c['skin'];
					
					}
		}
	}
}
?>