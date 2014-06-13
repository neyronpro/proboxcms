<?
class Template{
	public $tpl;
	public $modtpl;
	var $skin;
	function __construct($skin){
	
		$this->skin = $skin;
		$this->tpl = file_get_contents("template/{$this->skin}/index.html", true);
	
	
	
	}
	function set_tags($metatitle,$description,$keywords){
	
		$this->tpl = str_replace("%metatitle%", "<title>{$metatitle}</title>", $this->tpl);
		$this->tpl = str_replace("%description%", "<meta name=\"description\" content=\"{$keywords}\">", $this->tpl);
		$this->tpl = str_replace("%keywords%", "<meta name=\"keywords\" content=\"{$description}\"><base href=\"http://{$_SERVER['SERVER_NAME']}/\">", $this->tpl);

	
	}
	function set($patern,$string){
	
		$this->tpl = str_replace($patern, $string, $this->tpl);
	 
	}
	function set_block($patern,$string){
	
		$this->tpl = str_replace($patern, $string, $this->tpl);
	 
	}
	
	function set_tpl($module,$file){
	
		$this->modtpl = file_get_contents("template/{$this->skin}/{$module}/{$file}.html", true);
	 
	}
	
	function search($string){
		$search = stripos($this->tpl,$string);
		if ($search === false) {
			return false;
		}
		if ($search !== false) {
			return true;
		}
	
	}
	
	function search_block($string){
		if(preg_match($string, $this-tpl)){
			return true;
		} 
		else {
		
			return false;
		
		}
	
	}
	
}




?>