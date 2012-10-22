<?php


class Router {
	
	public static $inst; # Holds the string instance
	public $folder;
	public $page;
	
	/**
	 * App Constructor. Should only be called by inst() function
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return void
	 */
	private function __construct() {
		
	}
	
	/**
	 * Creates a new instance (or returns a created one) of App
	 * @author Scott Dover <sdover102@me.com>
	 * @param none
	 * @return object
	 */
	public static function inst() {
		if ( ! self::$inst ) self::$inst = new self;
		return self::$inst;
	}

	public static function interpret_paths() {
		$obj = self::inst();
		$fields = array('QUERY_STRING','REQUEST_URI','REDIRECT_URL');
		foreach ($fields as $t_field) {
			if (isset($_SERVER[$t_field])) { $field = $t_field; break; }
		}
		
		$url = substr($_SERVER[$field],1);

		$url_parts = explode('/', $url);
		
		if (count($url_parts) == 0 || !$url) {
			$obj->folder = 'default';
			$obj->page = NULL;
		} else {
			if ($url_parts[0] == 'page') $obj->folder = 'default';
			else $obj->folder = array_shift($url_parts);

			$page = array_shift($url_parts);
			$obj->page = array_shift($url_parts);			
			if ($page != 'page') 
				$obj->page = $page;
		}

		new Page($obj->folder, $obj->page);
		#$obj->load_page();
	}
}